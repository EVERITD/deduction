<?php

    /**
     * This page handles the generation of Deduction Number
     * @author ever-itd
     * @co-author raffy.hegina
     */

    session_start();
    $x = strlen($_SESSION['user']);
    date_default_timezone_set('Asia/Manila');

    if ($x > 0 ) {
        include('../sqlconn.php');
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

    $oid = htmlspecialchars(strip_tags($_GET['oid']), ENT_QUOTES);

    $mqry = " select * from deductions_upload where id='{$oid}' ";
    $mrst = mssql_query($mqry);

    $file = array();
    while($rs = mssql_fetch_object($mrst))
    {
        $file = array(
            'name' => $rs->filename,
            'upload_date' => $rs->upload_date,
            'upload_by' => $rs->upload_by,
            'remarks' => $rs->remarks,
        );
    }

    $qry = " select * from deduction_master where eposted='{$oid}' ";
    $dmDetails = mssql_query($qry);

    $viewOnly = 1;

    ob_start();
    include 'process_xls_view.php';
    $output = ob_get_contents();
    ob_end_clean();

    header('Content-Type: text/html;');
    echo $output;
