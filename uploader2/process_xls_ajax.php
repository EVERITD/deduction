<?php

/**
 * This page is accessible via ajax calls
 * @author ever-itd
 * @co-author raffy.hegina
 */

session_start();
$x = strlen($_SESSION['user']);
date_default_timezone_set('Asia/Manila');

if ($x > 0) {
    include('../sqlconn.php');
    $lcuser  = $_SESSION['user'];
    $lcusername = $_SESSION['username'];
    $branch =  $_SESSION['branch_code'];
    $xbranch =  $_SESSION['branch_code'];
    $glbranchname = $_SESSION['branch_name'];
    $dept_code = $_SESSION['dept_code'];
    $lcdeptname = $_SESSION['dept_name'];
    $division_code = $_SESSION['divcode'];
    $lcdivname = $_SESSION['divname'];
    $lcaccrights = $_SESSION['type'];
    $height = $xbranch == 'S399' ? 460 : 460;
    date_default_timezone_set('Asia/Manila');
} else {
    header("HTTP/1.0 403 Access denied");
    die("Un-Authorized Access");
}

// load the excel class
require_once 'excel/PHPExcel.php';
include 'functions.inc.php';

$file = htmlspecialchars_decode(htmlspecialchars(strip_tags($_POST['file']), ENT_QUOTES));
$remarks = htmlspecialchars(strip_tags($_POST['rm']), ENT_QUOTES);

$isCsv = (strtolower(end(explode('.', $file))) === 'csv') ? 1 : 0;

if (!$isCsv) {
    $objPHPExcel = PHPExcel_IOFactory::load('uploads/' . $file);

    $sheet = $objPHPExcel->getActiveSheet('Sheet1')->toArray(null, true, true, true);
} else {
    $objReader = PHPExcel_IOFactory::createReader('CSV');
    $objPHPExcel = $objReader->load('uploads/' . $file);

    $sheet = $objPHPExcel->getActiveSheet('Sheet1')->toArray(null, true, true, true);
}


$data = array();
$dateHash = date('YmdhisA') . '_' . $file;

if (count($sheet) > 0) {
    $data['status'] = 'success';

    // overwrite the branch incase its a masangkay
    if (trim(strtoupper($branch)) === 'S306')
        $branch = 'S399';

    // save the main files
    // $qry = " insert into deductions_upload
    //         ( filename, ref_division_id, ref_branch_id, ref_department_id, upload_by, upload_date,
    //         status_id, remarks, log_file )
    //         values(
    //             '" . $dateHash . "', '{$division_code}', '{$branch}', '{$dept_code}', '{$lcuser}', getdate(),
    //             3, '{$remarks}', '-none-'
    //         ); select * from deductions_upload where ltrim(rtrim(filename)) = '{$dateHash}';
    //     ";

    // $result = mssql_query($qry);
    // while ($rs = mssql_fetch_object($result)) {
    $id = $rs->id;
    $counter = 0;
    foreach ($sheet as $row) {

        // Assign deduction number;
        // $row = array_diff($row, array(''));
        // if($counter !== 0 and !empty($row))

        if ($counter !== 0 and ($row['A']
            or $row['B']
            or $row['C']
            or $row['D']
            or $row['E']
            or $row['F']
            or $row['G']
            or $row['H']
            or $row['I']
            or $row['J']
            or $row['K']
            or $row['L']
        )) {


            if (trim(strtoupper(trim($row['A']))) === 'S306')
                $row['A'] = 'S399';
            $conso_br = funcGetConsoPO(trim($row['A']), trim($row['I']));
            $dm_no = funcGetNewDm(trim($row['A']));
            $suppliername = funcGetSupplierName(trim($row['I']));
            $subcat = funcGetSubCat(trim($row['E']));
            if (trim(strtoupper($row['A'])) === 'S306')
                $row['A'] = 'S399';

            $qry = " insert into deduction_master(
                    dm_no, 
                    branch_code, 
                    division_code, 
                    dept_code, 
                    category_code, 
                    subcat_code, 
                    promo, 
                    vendorcode, 
                    suppliername, 
                    dm_date,
                    period, 
                    amount, 
                    paymentid, 
                    remarks, 
                    remarks1, 
                    encoded_by, 
                    encoded_date, 
                    eposted, 
                    buyerid, 
                    isforreview, 
                    isposted, 
                    isdmprinted,
                    department)
                    values ( 
                        '{$dm_no}', 
                        '{$row['A']}', 
                        '{$row['B']}', 
                        '{$row['C']}',
                        '{$row['D']}', 
                        '{$row['E']}',
                        '{$row['F']}', 
                        '{$row['J']}',  
                        '" . trim(str_replace('\'', '\'+char(39)+\'', $suppliername)) . "',
                        getdate(), 
                        '{$row['K']}', 
                        '{$row['L']}', 
                        '{$row['M']}',
                        '{$subcat['shortcode']}', 
                        '{$subcat['longcode']}-{$row['N']}', 
                        '{$lcuser}',
                        getdate(), 
                        '{$id}', 
                        '{$row['H']}',
                        1,
                        1,
                        1,
                        '{$row['I']}')";
            var_dump($qry);
            die();

            // @mssql_query($qry);
            //    die();

            if (strlen(trim($conso_br)) > 2) {
                // $qry_conso = "delete from conspo_pivot where dm_no='$dm_no'; insert into conspo_pivot (dm_no,main_site,is_active,update_at,created_at) values ('{$dm_no}','{$conso_br}','1',getdate(),getdate())";
                // $rs_conso = mssql_query($qry_conso);
            }
        }
        $counter++;
    }
    // }
} else
    $data['status'] = 'failed';

unlink('uploads/' . $file);

header('Content-Type: application/json;');
echo json_encode($data);
die();
