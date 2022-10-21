<?php

    session_start();
    $x = strlen($_SESSION['user']);
    date_default_timezone_set('Asia/Manila');

    if ($x > 0 ) {
        include('sqlconn.php');
        $lcuser  = $_SESSION['user'] ;
        $lcusername = $_SESSION['username'] ;
        $branch =  $_SESSION['branch_code'] ;
        $xbranch =  $_SESSION['branch_code'] ;
        $glbranchname = $_SESSION['branch_name'];
        $dept_code = $_SESSION['dept_code'] ;
        $lcdeptname = $_SESSION['dept_name'];
        $division_code = $_SESSION['divcode'];
        $lcdivname = $_SESSION['divname'];
        $lcaccrights = $_SESSION['type'];
        $buyersId = @$_SESSION['buyersId'];
        $height = $xbranch == 'S399' ? 460 : 460;
        $lcbuyerid = @$_SESSION['lcbuyerid'];
        date_default_timezone_set('Asia/Manila');
    }
    else {
        header('Location: login.php');
        header("HTTP/1.0 403 Access denied");
        die("Un-Authorized Access");
    }

    // $branch = htmlspecialchars(strip_tags($_GET['branch']), ENT_QUOTES);
    $marker = 0;

    $lQry = " select * from everlyl_conspo.consolidatepo.dbo.sitegroup order by main_site ";
    $lRs  = mssql_query($lQry);
    $mainSitesList   = array();
    $uniqueMainSites = array();
    $subSitesList    = array();

    while($cpRs = mssql_fetch_object($lRs))
    {
        $mainSitesList[trim($cpRs->sub_site)] = array(
            'code' => trim($cpRs->main_site),
            'name' => trim($cpRs->main_name),
        );
        $subSitesList[trim($cpRs->main_site)][] = trim($cpRs->sub_site);
        $uniqueMainSites[] = trim($cpRs->main_site);
    }

    if ((int) $lcaccrights === 5 OR (int) $lcaccrights === 4 )
    {
        if ($xbranch === 'S399') {
            if ($_SESSION['dept_code'] === 'ACT') {
                $seluser = "select distinct branch_code,branch_name from ref_branch where isactive = 1
                    order by branch_code";
            } elseif ($_SESSION['dept_code'] === 'EDP'){
                $seluser = "select distinct branch_code,branch_name from ref_branch where isactive = 1
                    and branch_code in ('S801','S802','S803', 'S301') order by branch_code";
            } elseif ($_SESSION['dept_code'] === 'PUR') {
                $seluser = "select distinct branch_code,branch_name from ref_branch where isactive = 1
                    and branch_code not in ('S801','S802','S803', 'S301') order by branch_code";
            } else {
                // this query will handle the branch handle by the supervisor
                $seluser = "select distinct branch_code,branch_name from ref_branch where isactive = 1
                    and branch_code not in ('S801','S802','S803', 'S301') :admin
                    order by branch_code";

                if(strpos($lcuser, 'admin') === false AND trim($_SESSION['dept_code']) !== 'MKT')
                {
                    $toReplace = "and branch_code in ( select distinct branch_code from ref_supervisor where supervisor='".trim($lcuser)."' )";
                    $seluser = str_replace(':admin', $toReplace, $seluser);
                }
                else
                    $seluser = str_replace(':admin', '', $seluser);
            }
        } else {
            $seluser = "select distinct a.branch_code,a.branch_name from ref_branch a left join ref_supervisor b on
            a.branch_code = b.branch_code where b.supervisor = '{$lcuser}' and b.isactive = 1
            and a.branch_code not in ('S801','S802','S803', 'S301') order by a.branch_code";
        }
        $marker = 1;
    }
    elseif((int) $lcaccrights === 3)
    {
        if ($_SESSION['dept_code'] === 'ACT') {
            $seluser = "select distinct branch_code,branch_name from ref_branch where isactive = 1
                order by branch_code";
        } elseif ($_SESSION['dept_code'] === 'EDP'){
            $seluser = "select distinct branch_code,branch_name from ref_branch where isactive = 1
                and branch_code in ('S801','S802','S803', 'S301') order by branch_code";
        } else {
            $seluser = "select distinct branch_code,branch_name from ref_branch where isactive = 1
                and branch_code not in ('S801','S802','S803', 'S301') order by branch_code";
        }
        $marker = 1;
    }
    elseif((int) $lcaccrights === 2 and $xbranch === 'S399' and $_SESSION['dept_code'] <> 'OPS')
    {
        if ($_SESSION['dept_code'] === 'ACT') {
            $seluser = "select distinct branch_code,branch_name from ref_branch where isactive = 1
                order by branch_code";
        } elseif (trim($_SESSION['dept_code']) === 'EDP'){
            $seluser = "select distinct branch_code,branch_name from ref_branch where isactive = 1
                    and branch_code in ('S801','S802','S803') order by branch_code";
        } else {
            $seluser = "select distinct branch_code,branch_name from ref_branch where isactive = 1
                and branch_code not in ('S801','S802','S803', 'S301') order by branch_code";
        }
        $marker = 1;
    }
    else
    {
        $marker = 1;
        $select = '<select name="cmbbranch" id="cmdbranchid" style="width: 100% !important;">';
        $select .= '<option value="'.$branch.'" selected="selected">'.$glbranchname.'</option>';
        $select .= '</select>';
        $selbranch = $branch;
    }

    if($marker AND trim($seluser) !== '')
    {
        $rsuser = mssql_query($seluser);
        $select = '<select name="cmbbranch" id="cmdbranchid" style="width: 100% !important;">';
        $selectOBranch = "<select name='cmbobranch' id='cmdobranchid' style='width: 100% !important; display: none;'>";
        // $select .= '<option value="%" selected="selected">All Branches</option>';
        $mSites = array_unique($uniqueMainSites);

        $mOData = array();
        $mData = array();
        while($b_row = mssql_fetch_array($rsuser))
        {
            $selected = "";
            if($i === 0)
                $selbranch = trim($b_row['branch_code']);

            if (trim($b_row['branch_code']) === trim($branch)) {
                $selected = 'selected="selected"';
                $selbranch = $branch;
            }

            $select .= "<option value='" . trim($b_row['branch_code']) . "' " . $selected . ">" . trim($b_row['branch_name']) . "</option>";

            foreach($mSites as $v)
            {
                if($v === trim($b_row['branch_code']))
                {
                    $selectOBranch .= "<option value='" . trim($b_row['branch_code']) . "' " . $selected . ">" . trim($b_row['branch_name']) . "</option>";
                }
            }
        }

        //foreach($mSites as $v)
        //{

        //    $select .= "<optgroup label='Ordering Branch &mdash; ".$v."'>";
        //    $select .= "<option value='OB-{$v}' $obSelected>Ordering Branch &mdash; $v</option>";
        //    $select .= "<optgroup label='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Receiving Branches'>";

            //foreach($mData as $m)
            //{
            //    $selected = "";
            //    if($i === 0)
            //        $selbranch = trim($m['branch_code']);

            //    if (trim($m['branch_code']) === trim($branch)) {
            //        $selected = 'selected="selected"';
            //        $selbranch = $branch;
            //    }

            //    if($mainSitesList[trim($m['branch_code'])]['code'] == $v)
            //        $select .= "<option value='" . trim($m['branch_code']) . "' " . $selected . ">" . trim($m['branch_name']) . "</option>";
            //}

            //$select .= '</optgroup>';
            //$select .= '</optgroup>';

        //}

        $select .= '</select>';
        $selectOBranch .= '</select>';
    }

    // get all approved entries
    $isReprint = htmlspecialchars(strip_tags($_GET['ispr']), ENT_QUOTES);
    if( ! $isReprint)
    {
        $lcbuyeridx = trim($lcbuyerid);
        $buyers = ('' !== trim($lcbuyerid) AND !is_null($lcbuyerid)) ? ' and deduction_master.buyerid=\'{$lcbuyeridx}\' ': '';

        $qry = " select deduction_master.*, convert(char(8), deduction_master.dm_date, 112) as fdm_date, convert(char(8), deduction_master.encoded_date, 112) as fenc_date
            , convert(char(8), deduction_master.review_date, 112) as freview_date
            from deduction_master
            where deduction_master.paymentid=2 and deduction_master.branch_code like '".$selbranch."%' and deduction_master.vposted='1'
            and ltrim(rtrim(deduction_master.dm_no_acctg)) = ''
            and ltrim(rtrim(deduction_master.dept_code)) = '{$dept_code}' $buyers
            and convert(char(8), deduction_master.review_date, 112) between '20121203' and convert(char(8), getdate(), 112)  ";

        $deptExc = array('ACT', 'EDP');

        if((int) $lcaccrights === 4 AND !in_array($dept_code, $deptExc) AND stripos($lcuser, 'admin') === false)
        {
            $qry .= " and deduction_master.encoded_by in ( select distinct user_name from ref_supervisor where supervisor='{$lcuser}') ";
        }
        else
            $qry .= " and deduction_master.review_by in ( select supervisor from ref_supervisor where user_name='{$lcuser}') ";

        
        $rs = mssql_query($qry);

    }
    else
    {
        // get default set of batch
        $currentDate = date('Ymd');

        $bqry = " select distinct dspd.batch, dspd.deduction_slip_prints_id as dspid,
            dsp.print_by from deduction_slip_print_details dspd left join deduction_slip_prints dsp
            on dspd.deduction_slip_prints_id=dsp.id where dsp.branch='{$selbranch}' and dsp.printed_date='{$currentDate}' ";

        if((int) $lcaccrights !== 4)
            $bqry .= " and dsp.print_by='{$lcuser}' ";
        else
            $bqry .= " and dsp.print_by in ( select user_name from ref_supervisor where supervisor='{$lcuser}' ) ";
            //$bqry .= " and deduction_master.review_by in ( select supervisor from ref_supervisor where user_name='{$lcuser}') ";

        $brst = mssql_query($bqry);
        $bselm = '<select name="cmbbatch" id="cmbbatchid" class="span1" style="width: 120px;">';
        $bseld = '';

        while($bsel = mssql_fetch_object($brst))
        {
            if((int) $lcaccrights !== 4)
                $bseld .= '<option value="'.$bsel->batch.'-'.$bsel->dspid.'">'.$bsel->batch.'</option>';
            else
                $bseld .= '<option value="'.$bsel->batch.'-'.$bsel->dspid.'">'.$bsel->batch.' - '.$bsel->print_by.'</option>';
        }
        if(trim($bseld) === '')
            $bseld = '<option value="0">no-batch-found</option>';

        $bselect = "{$bselm}{$bseld}</select>";
    }


    // $data = array();

    // while($rst = mssql_fetch_object($rs))
    // {
        // $data['content'][] = array(
        //     'ctrlno'     => $rst->dm_no,
        //     'branch_code' => $rst->branch_code,
        //     'division_code' => $rst->division_code,
        //     'dept_code'  => $rst->dept_code,
        //     'disparea'   => $rst->disparea,
        //     'buyerid'    => $rst->buyerid,
        //     'vendorcode' => $rst->vendorcode,
        //     'suppliername' => $rst->SupplierName,
        //     'dm_date'    => date('Y-m-d', strtotime($rst->fdm_date)),
        //     'period'     => $rst->period,
        //     'amount'     => $rst->amount,
        //     'remarks'    => $rst->remarks,
        //     'remarks1'   => $rst->remarks1,
        //     'vposted'    => $rst->vposted,
        //     'eposted'    => $rst->eposted,
        //     'contractno' => $rst->contractno,
        //     'ap_od'      => $rst->ap_od,
        //     'deducted'   => $rst->deducted,
        //     'dm_no_acctg' => trim($rst->dm_no_acctg),
        //     'isforreview' => $rst->isForReview,
        //     'isposted'   => $rst->isPosted,
        //     'printed'    => $rst->isDMprinted,
        // );
    // }

    ob_start();
    include 'deduction_slip_view_on.php';
    $buffer = ob_get_contents();
    ob_get_clean();

    echo $buffer;

    die();
