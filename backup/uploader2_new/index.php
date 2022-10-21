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
        header('Location: ../login.php');
        header("HTTP/1.0 403 Access denied");
        die("Un-Authorized Access");
    }

    if(isset($_COOKIE['fileErrors']))
    {
        $fileErrors = (@$_COOKIE['fileErrors']) ? array($_COOKIE['fileErrors']): array();
        unset($_COOKIE['fileErrors']);
    }

    if($_POST['cmbsubmit'])
    {
        $file = $_FILES['uplname'];
        $remarks = htmlspecialchars(strip_tags($_POST['taremarks']), ENT_QUOTES);

        // Allowed extension name
        $allowedExt = array('csv', 'xls', 'xlsx');
        $allowedType = array(
                'text/x-comma-separated-values',
                'text/comma-separated-values',
                'application/octet-stream',
                'application/vnd.ms-excel',
                'application/x-csv',
                'text/x-csv',
                'text/csv',
                'application/csv',
                'application/excel',
                'application/vnd.msexcel',
                'application/excel',
                'application/vnd.ms-excel',
                'application/msexcel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/zip');

        $fileErrors = array();

        $fileExt = strtolower(end(explode('.', $file['name'])));

        // Check if the file allowed to be upload
        if( ! in_array($fileExt, $allowedExt) OR ! in_array($file['type'], $allowedType))
            $fileErrors['extension'] = 'Not allowed extensions or file types!';
        else
        {
            include 'process_xls.php';
            die();
        }
    }

    $freezeTime = new DateTime();
    $freezeTime->setTimezone(new DateTimeZone('asia/manila'));
    $freezeTime->modify('-4 Month');

    $sql = " select convert(char(8), upload_date, 112) as upload_date from deductions_upload where status_id <> 5
        and cast(convert(char(8), upload_date, 112) as int) >= cast('".$freezeTime->format('Ymd')."' as int)
        group by convert(char(8), upload_date, 112) order by upload_date desc";

    $recordSet = mssql_query($sql);

    $groupDate = array();
    $listing = array();
    while($rs = mssql_fetch_object($recordSet))
    {
        $groupDate[$rs->upload_date][] = $rs->upload_date;
        $listing[] = $rs->upload_date;
    }

    // details
    $sql = " select *, convert(char(8), upload_date, 112) as upldate, convert(char(8), upload_date, 114) as upltime
        from deductions_upload where status_id <> 5
        and convert(char(8), upload_date, 112) in ( '".implode('\', \'', $listing)."' )";

    $recSet = mssql_query($sql);

    while($r = mssql_fetch_object($recSet))
    {
        $groupDate[$r->upldate]['details'][] = $r;
    }

    ob_start();
    include 'index.view.php';
    $output = ob_get_contents();
    ob_end_clean();

    header('Content-Type: text/html;');
    // render the view
    echo $output;
    die();
