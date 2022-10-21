<?php

    session_start();
    $x = strlen($_SESSION['user']);
    date_default_timezone_set('Asia/Manila');

    if ($x > 0 ) {
        include('sqlconn.php');

        $d = mssql_select_db('arms', $s);

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
    $dataids = json_decode($_POST['dataid']);


    if(! empty($dataids))
    {
        /*$query = ' select APTNO,APTREF, convert(char(8), aptdue, 112) as fposteddate from accountspayable where aptno in (\''.implode('\', \'', $dataids).'\') and isnull(aptref,\'0\') <> \'0\' ';*/



        $query = ' select distinct APTNO,APTREF,convert(char(8), aptdue, 112) as fposteddate from accountspayable where aptno in (\''.implode('\', \'', $dataids).'\') and isnull(aptref,\'0\') <> \'0\'   union all select distinct dmno as APTNO,substring(voucher_or_no,4,10) as APTREF, convert(char(8), voucher_or_date, 112) as fposteddate from dm_deduction.dbo.debitmemo_otherdeduct where dmno in (\''.implode('\', \'', $dataids).'\') and isnull(dmno,\'0\') <> \'0\' ';

        $rst = mssql_query($query);
        $vouchers = array();

        while($rs = mssql_fetch_object($rst))
        {
            $vouchers[trim($rs->APTNO)] = array(
                'id' => $rs->APTREF,
                'date' => date('Y-m-d', strtotime($rs->fposteddate)),
            );
        }
        
    }
    else
        $vouchers = array();

    header('Content-Type: application/json');
    echo json_encode($vouchers);


