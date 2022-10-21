<?php

    session_start();
    $x = strlen(@$_SESSION['user']);
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

    $branch = htmlspecialchars(strip_tags($_POST['branch']), ENT_QUOTES);
    $date = htmlspecialchars(strip_tags($_POST['odate']), ENT_QUOTES);
    $isCons = htmlspecialchars(strip_tags($_POST['isconspo']), ENT_QUOTES);
    $date = date('Ymd', strtotime($date));

    if( ! empty($isCons))
    {
      $consPo .= " dspd.dm_ctrl_no in (
        select distinct dm_no from deduction_master where vendorcode in (
        select distinct a.vendor_code from everlyl_conspo.consolidatepo.dbo.sitegroup_vendors a
        left join everlyl_conspo.consolidatepo.dbo.sitegroup b on a.main_site=b.main_site
        where b.main_site :branch
        ) ) and ";

      $consPo = str_replace(':branch', "like '{$branch}%'", $consPo);

      $isConsPo = true;
    }

    // get default set of batch
    // $bqry = " select distinct dspd.batch from deduction_slip_print_details dspd left join deduction_slip_prints dsp
    //         on dspd.deduction_slip_prints_id=dsp.id where dsp.branch='{$branch}' and dsp.printed_date='{$date}'";
    $bqry = " select distinct dspd.batch, dspd.deduction_slip_prints_id as dspid,
        dsp.print_by from deduction_slip_print_details dspd left join deduction_slip_prints dsp
        on dspd.deduction_slip_prints_id=dsp.id where :conspo :branch and dsp.printed_date='{$date}' ";

    if((int) $lcaccrights !== 4)
        $bqry .= " and dsp.print_by in ( select user_name from ref_users where dept_code='{$dept_code}' ) ";
    else
        $bqry .= " and dsp.print_by in (
            select a.user_name from ref_supervisor a, ref_users b where
            a.supervisor='{$lcuser}' and a.user_name=b.user_name
            and ltrim(rtrim(b.dept_code))='{$dept_code}' ) ";

    echo $bqry = str_replace(':conspo', ($isCons) ? $consPo: '', $bqry);

    $brst = mssql_query($bqry);
    $bselect = array();
    $bcnt = 0;
    while($bsel = mssql_fetch_object($brst))
    {
        $bselect[] = array(
            'id' => $bsel->batch,
            'mainid' => $bsel->dspid,
            'printby' => $bsel->print_by
        );
    }

    header('Content-Type: application/json;');
    echo json_encode(array('content' => $bselect));
    die();
