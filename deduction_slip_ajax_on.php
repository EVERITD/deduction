<?php

    session_start();
    $x = strlen($_SESSION['user']);
    date_default_timezone_set('Asia/Manila');

    if ($x > 0 ) {
        include('sqlconn_local.php');
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
        $height = $xbranch == 'S399' ? 460 : 460;
        date_default_timezone_set('Asia/Manila');
    }
    else {
        header("HTTP/1.0 403 Access denied");
        die("Un-Authorized Access");
    }

    $sdate  = htmlspecialchars(strip_tags($_POST['sdate']), ENT_QUOTES);
    $branch = htmlspecialchars(strip_tags($_POST['cmbbranch']), ENT_QUOTES);
    $batch  = explode('-', htmlspecialchars(strip_tags($_POST['cmbbatch']), ENT_QUOTES));
    $isprn  = htmlspecialchars(strip_tags($_POST['txttranstype']), ENT_QUOTES);
    $vendorcat  = htmlspecialchars(strip_tags($_POST['vndrcat']), ENT_QUOTES);
    $isOBranch  = htmlspecialchars(strip_tags($_POST['chkconspo']), ENT_QUOTES); 

    if($vendorcat=='outright' and  (int) $lcaccrights === 4)
    {
       $vncat=" and substring(vendorcode,1,3) = '131' ";
    }
    elseif($vendorcat=='consignor' and  (int) $lcaccrights === 4)
    {
       $vncat=" and substring(vendorcode,1,3) <> '131'";
    }
    else
    {
        $vncat=""; 
    }



    $lQry       = " select distinct main_site from everlyl_conspo.consolidatepo.dbo.sitegroup where ltrim(rtrim(sub_site))='{$branch}'";
    $lRs        = mssql_query($lQry);
    $lNumRows   = mssql_num_rows($lRs);
    $mainISite  = $branch;
    $isMainSite = false;

    if($lNumRows > 0)
    {
        while($lRows = mssql_fetch_object($lRs))
        {
            $mainISite = trim($lRows->main_site);
            $isMainSite = true;
        }
    }

    $isOBranch = true;
    $isConsPo  = false;
    
    $dept4newrep = array('PUR', 'MKT');

    if (!in_array($dept_code,$dept4newrep)) { 
        $consPo .= " :link deduction_master.dm_no :not in (
            select dm_no from conspo_pivot where main_site :branch
          ) ";

        if($branch === $mainISite AND $isMainSite)
        {
          $consPo = str_replace(':not', '', $consPo);
          $consPo = str_replace(':link', ' OR ', $consPo);
          $isConsPo = true;
        }
        else
        {
          $consPo = str_replace(':not', ' not ', $consPo);
          $consPo = str_replace(':link', ' AND ', $consPo);
        }

        $consPo = str_replace(':branch', "like '{$mainISite}%'", $consPo);
    } else {
        $consPo = '';
    }

	// original query by raffy
    // $qry = " select deduction_master.*, convert(char(8), deduction_master.dm_date, 112) as fdm_date, convert(char(8), deduction_master.encoded_date, 112) as fenc_date
    //     , convert(char(8), deduction_master.review_date, 112) as freview_date :type
    //    from deduction_master :join where deduction_master.paymentid=2
    //    and deduction_master.vposted in ('1', '2') AND (deduction_master.branch_code like '".$branch."%' :conspo )";
	
	$qry = " select deduction_master.dm_no,case when deduction_master.branch_code =  '".$branch."' then '----' else deduction_master.branch_code end as branch_code,
		deduction_master.division_code,deduction_master.dept_code,deduction_master.category_code,deduction_master.subcat_code,deduction_master.disparea,
		deduction_master.buyerid,deduction_master.vendorcode,deduction_master.suppliername,deduction_master.dm_date,deduction_master.period,deduction_master.amount,
		deduction_master.paymentid,deduction_master.remarks,deduction_master.remarks1,
		deduction_master.encoded_by,deduction_master.encoded_date,deduction_master.edit_by,deduction_master.edit_date,deduction_master.review_by,
		deduction_master.review_date,deduction_master.vposted,deduction_master.extracted_by,deduction_master.eposted,deduction_master.contractno,ap_od,
		deduction_master.deducted,deduction_master.cancel_by,deduction_master.cancel_date,deduction_master.cancel_remarks,deduction_master.dm_no_acctg,
		deduction_master.isforreview,deduction_master.isposted,deduction_master.isdmprinted,
		convert(char(8), deduction_master.dm_date, 112) as fdm_date, convert(char(8), deduction_master.encoded_date, 112) as fenc_date,
        convert(char(8), deduction_master.review_date, 112) as freview_date,case when lbr_number is null then '' else lbr_number end as LBR_Number :type 
        from deduction_master :join where deduction_master.paymentid=2
        and deduction_master.vposted in ('1', '2') AND (deduction_master.branch_code like '".$branch."%' :conspo ) $vncat";

    $qry = str_replace(':conspo', ( ! empty($isOBranch)) ? $consPo: '', $qry);

    $deptExc = array('ACT', 'EDP', 'MKT');

    if((int) $lcaccrights === 4 AND !in_array($dept_code, $deptExc) AND stripos($lcuser, 'admin') === false AND ! $isConsPo)
    {
        // if the user is pur give them full access to branch
        $qry .= " and deduction_master.encoded_by in ( select distinct user_name from ref_supervisor where supervisor='{$lcuser}' ) ";
    }
    else
    {
        $qry .= " and deduction_master.dept_code='".$dept_code."' ";
        // $qry .= " and deduction_master.review_by in ( select supervisor from ref_supervisor where user_name='{$lcuser}') ";
    }

    if(!$isprn)
        $qry .= " and deduction_master.vposted <> 2 and convert(char(8), deduction_master.review_date, 112)
            between '20121203' and convert(char(8), getdate(), 112) ";

    if($sdate)
    {
        // to be used on union
        $mqry = $qry . " and ltrim(rtrim(dm_no_acctg)) = '' ";
        $mqry = str_replace(':type', ', \'\' as \'type\'', $mqry);
        $mqry = str_replace(':join', '', $mqry);

        // $qry = str_replace(':join', ' left join print_logs on', $qry);
        $qry .= " and ltrim(rtrim(deduction_master.dm_no)) in ( select deduction_slip_print_details.dm_ctrl_no from deduction_slip_prints
            left join deduction_slip_print_details on deduction_slip_prints.id=deduction_slip_print_details.deduction_slip_prints_id
            where deduction_slip_prints.printed_date=cast('".date('Ymd', strtotime($sdate))."' as int)
                and deduction_slip_print_details.batch='{$batch[0]}'
                and deduction_slip_print_details.deduction_slip_prints_id='{$batch[1]}'
            ) and ltrim(rtrim(deduction_master.dm_no_acctg)) <> ''";
    }
    else
    {  /* if($lcuser != 'lyndon.yumul'):
        
        else:
        $qry .= " and review_by = 'lyndon.yumul' ";
        $qry = str_replace(':type', ', \'\' as \'type\'', $qry);    
        endif;*/
        $qry .= " and ltrim(rtrim(dm_no_acctg)) = '' ";
        $qry = str_replace(':type', ', \'\' as \'type\'', $qry);
    }

    if((int) $lcaccrights !== 4)
    {
        // $qry .= " and deduction_master.encoded_by='{$lcuser}' ";
    }

    if($sdate)
    {
        $qry = str_replace(':type', ', \'1\' as \'type\'', $qry);
        // $qry = str_replace(':join', '', $qry.' union '.$mqry);

        $qry = str_replace(':join', '', $qry);
    }


    $qry = str_replace(':join', '', $qry). ' order by deduction_master.dm_no_acctg ';
    //echo $qry;
   //die();
    $rs = mssql_query($qry);

    $data = array();

    while($rst = mssql_fetch_object($rs))
    {
        $data['content'][] = array(
            'ctrlno'      => $rst->dm_no,
            'branch_code' => $rst->branch_code,
            'division_code' => $rst->division_code,
            'dept_code'  => $rst->dept_code,
            'disparea'   => $rst->disparea,
            'buyerid'    => $rst->buyerid,
            'vendorcode' => $rst->vendorcode,
            'suppliername' => $rst->suppliername,
            'dm_date'    => date('Y-m-d', strtotime($rst->fdm_date)),
            'period'     => $rst->period,
            'amount'     => $rst->amount,
            'remarks'    => $rst->remarks,
            'remarks1'   => $rst->remarks1,
            'vposted'    => ($rst->vposted == 2) ? 0: 1,
            'eposted'    => $rst->eposted,
            'contractno' => $rst->contractno,
            'ap_od'      => $rst->ap_od,
            'deducted'   => $rst->deducted,
            'dm_no_acctg' => trim($rst->dm_no_acctg),
            'isforreview' => $rst->isForReview,
            'isposted'   => $rst->isPosted,
            'printed'    => $rst->isDMprinted,
            'fdmdate'    => $rst->fdm_date,
            'isprint'    => trim($rst->type),
            'encoded'    => date('Y-m-d', strtotime($rst->fenc_date)),
            'reviewed'   => date('Y-m-d', strtotime($rst->freview_date)),
            'encoded_by'    => $rst->encoded_by,
            'reviewed_by'   => $rst->review_by,
            'lbrno'   => (strlen(trim($rst->LBR_Number)) > 0 ? " (LBR# ".$rst->LBR_Number.")" : ""),
        );
    }

    $data['status'] = (count($data['content'])) ? 1: 0;

    header('Content-Type: application/json');
    echo json_encode($data);
    die();
