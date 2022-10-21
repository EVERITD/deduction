<?php

    /**
     * This page handles the generation of Deduction Number
     * @oids seperated by pipe (|)
     * @author ever-itd
     * @co-author raffy.hegina
     */

    session_start();
    $x = strlen($_SESSION['user']);
    date_default_timezone_set('Asia/Manila');

    if ($x > 0 ) {
        include('../sqlconn_local.php');
        include 'funcGenerateDsNumber.php';
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

    $oid = htmlspecialchars(strip_tags($_POST['oids']), ENT_QUOTES);

    $oids = explode('|', $oid);

    $where = implode('\', \'', $oids);

    // we will just query the deduction with no deposit_slip_number
    $query = "select deduction_master.dm_no, deduction_master.dm_no_acctg, deduction_master.branch_code,
        ref_branch.branch_prefix from deduction_master
        left join ref_branch on deduction_master.branch_code=ref_branch.branch_code
        where deduction_master.dm_no in ('".$where."') and deduction_master.dm_no_acctg=' '";
    $rst = mssql_query($query);
    $data = array();

    $rowCount = mssql_num_rows($rst);
    // generate temp data

    while($rs = mssql_fetch_object($rst))
    {
        // this must be a function, to be able to used in dm_print.php
        $fmaxId = funcGenerateDsNumber($rs->branch_code, $rs->branch_prefix);

        $qry = "update deduction_master set dm_no_acctg='".$fmaxId."' where dm_no='".$rs->dm_no."'";
        $rs2 = mssql_query($qry);

        $data[] = array(
            'id' => trim($rs->dm_no),
            'dm_no_acctg' => $fmaxId,
        );
    }

    header('Content-Type: application/json');
    echo json_encode($data);
    die();

