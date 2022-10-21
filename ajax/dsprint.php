<?php

    session_start();
    $x = strlen($_SESSION['user']);
    date_default_timezone_set('Asia/Manila');

    if ($x > 0 ) {
        include('../sqlconn_local.php');
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

    $pageCover = json_decode(htmlspecialchars(strip_tags($_POST['pcover']), ENT_QUOTES));
    $ctrlno = json_decode(htmlspecialchars(strip_tags($_POST['oids']), ENT_QUOTES));
    $ctrlno = explode('-', $ctrlno);
    $ctrlno = array_map('trim', $ctrlno);       // trim the array
    $ctrlno = array_filter($ctrlno);            // remove empty array, value = "" OR ''
    $where = implode('\', \'', $ctrlno);

    if($pageCover)
    {
        $sql = " select top 1 deduction_slip_prints_id from deduction_slip_print_details where ltrim(rtrim(dm_ctrl_no)) in ('{$where}') ";
        $rst = mssql_query($sql);
        $dspId = mssql_result($rst, 0, 'deduction_slip_prints_id');

        $sql2 = " select * from deduction_slip_prints where id='{$dspId}' ";
        $rst2 = mssql_query($sql2);

        while($dsp = mssql_fetch_object($rst2))
        {
            if($dsp->is_printed)
            {
                $qry = " update deduction_slip_prints set reprint_at=getdate() where id='{$dsp->id}' ";
                mssql_query($qry);
            }
            else
            {
                $qry = " update deduction_slip_prints set is_printed=1 where id='{$dsp->id}' ";
                mssql_query($qry);

            }
        }

    }
    else
    {
        $sql = " select * from deduction_slip_print_details where ltrim(rtrim(dm_ctrl_no)) in ('{$where}') ";
        $rst = mssql_query($sql);

        while($dspd = mssql_fetch_object($rst))
        {
            if($dspd->is_printed)
            {
                $qry = " update deduction_slip_print_details set reprint_at=getdate() where dm_ctrl_no = '{$dspd->dm_ctrl_no}' ";
                mssql_query($qry);
            }
            else
            {
                $qry = " update deduction_slip_print_details set is_printed=1 where dm_ctrl_no = '{$dspd->dm_ctrl_no}' ";
                mssql_query($qry);
            }
        }
    }

    $data = array(
        'status' => 'success',
        'status_code' => 200,
    );

    header('Content-Type: application/json;');
    echo json_encode($data);
    die();
