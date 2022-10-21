<?php

$errorCount = 0;
$errorMessages = array();

/**
 * [validateEntries description]
 * @param  [type] $data [description]
 * @return [type]       [description]
 */
function validateEntries($data)
{
  global $lcuser, $branch, $lcaccrights, $branches;
  global $errorCount, $errorMessages;
  // check if the user is allowed to upload for a
  // particular branch empty the errors array
  $errorCount = 0;
  $errorMessages = array();

  if (
    !isset($branches['admin'])
    and $branches['admin'] !== 'all'
    and (int) $lcaccrights === 4
    and (!in_array(strtoupper($branch), $branches)
      or !in_array(strtoupper($data['A']), $branches))
  ) {
    $errorCount++;
    $errorMessages[$errorCount] = "User " . $lcuser .
      " are not allowed to upload for branch " . $branch . ".";
  }


  if (trim($data['A']) === '') {
    $errorCount++;
    $errorMessages[$errorCount] = "Empty branch.";
  }

  validateFields($data['C'], 'department');     // department
  validateFields($data['B'], 'division');       // division
  validateFields($data['D'], 'category');       // category
  validateFields($data['E'], 'subcategory');    // subcategory
  /*  validateFields($data['H'], 'item dept');    // subcategory*/
  validateFields($data['J'], 'supplier code');  // vcode
  var_dump($data['J']);
  // amount

  if (trim($data['L']) === '' or (int) $data['K'] === 0) {
    $errorCount++;
    $errorMessages[$errorCount] = "Invalid value for amount field.";
  }
  // payment
  if (trim($data['M']) === '' or (int) $data['L'] === 0) {
    $errorCount++;
    $errorMessages[$errorCount] = "Invalid value for paymentid field.";
  }
  if ($_SERVER["REMOTE_HOST"] == "192.168.17.128") {
    echo json_encode($errorMessages);
  }


  // return $errorCount; /// uncomment on deployement
}

function validateFields($field, $name)
{
  global $errorCount, $errorMessages;

  if (trim($field) === '') {
    $errorCount++;
    $errorMessages[$errorCount] = "Empty fields " . $name . ".";
    return true;
  }

  // check for the existant of a particular vendorcode
  if (strtolower($name) === 'supplier code') {
    $lcquery = ' select suppliername from arms.dbo.supplier where
        ltrim(rtrim(vendorcode))=\'' . trim($field) . '\' OR ltrim(rtrim(aptid))=\'' . trim($field) . '\' ';
    $rows = mssql_num_rows(mssql_query($lcquery));
    if ($rows < 1) {
      $errorCount++;
      $errorMessages[$errorCount] = 'Vendor code ' . $field . ' doesn\'t exists.';
    }
  }


  /*if (strtolower($name) === 'item dept')
    {
      $lcquery = ' select deptname from ref_prod_dept where
        ltrim(rtrim(deptcode))=\''.trim($field).'\'';
      $rows = mssql_num_rows(mssql_query($lcquery));
      if($rows < 1)
      {
        $errorCount++;
        $errorMessages[$errorCount] = 'Item Department '.$field.' doesn\'t exists.';
      }
    }*/
  return true;
}


$branches = array();

if ((int) $lcaccrights === 4 and strpos($lcuser, 'admin') === false) {
  $sql = " select distinct branch_code from ref_supervisor where
      supervisor = '" . $lcuser . "'";

  $recordSet = mssql_query($sql);
  $branches = array();
  while ($rs = mssql_fetch_object($recordSet)) {
    if (trim($rs->branch_code) === 'S399')
      $branches['admin'] = 'all';
    else
      $branches[] = trim($rs->branch_code);
  }
}


if (strpos($lcuser, 'admin'))
  $branches['admin'] = 'all';

// filename check

if (strpos($file['name'], '_') != false)
  $fileErrors['filename_format'] = "Invalid filename format.
          Please follow the standard file naming convention!";
else {

  $file_branch = explode('_', $file['name']);

  // get the allowed branch for each user.
  // if((int) $lcaccrights === 4 AND strpos($lcuser, 'admin') === false
  // AND ! in_array(strtoupper($file_branch[0]), $branches))
  //     $fileErrors['branch_no_access'] = "You don't have access to this
  //       branch.";
  // elseif((int) $lcaccrights !== 4 AND $branch !== $file_branch[0])
  //   $fileErrors['branch_no_access'] = "You don't have access to this
  //     branch.";

  // if(@$branches['admin'] === 'all')
  //   unset($fileErrors['branch_no_access']);

  if (
    !isset($fileErrors['branch_no_access'])
    or @$fileErrors['branch_no_access'] === ''
    or !@$fileErrors['branch_no_access']
  ) {
    // save the file...
    // we dont have to bother checking the files here because
    // the uploader overrides the files.

    move_uploaded_file($file['tmp_name'], 'uploads/' . $file['name']);

    $isCsv = (strtolower(end(explode('.', $file['name']))) === 'csv') ? 1 : 0;

    // content checking, must traverse throught the files content
    require_once 'excel/PHPExcel.php';

    if (!$isCsv) {
      $objPHPExcel = PHPExcel_IOFactory::load('uploads/' . $file['name']);

      $sheet = $objPHPExcel->getActiveSheet('Sheet1')
        ->toArray(null, true, true, true);
    } else {
      $objReader = PHPExcel_IOFactory::createReader('CSV');
      $objPHPExcel = $objReader->load('uploads/' . $file['name']);

      $sheet = $objPHPExcel->getActiveSheet('Sheet1')
        ->toArray(null, true, true, true);
    }
    // $spreadSheet = new Spreadsheet_Excel_Reader();
    // $spreadSheet->read('uploads/'.$file['name']);
  }
}

if (!file_exists('uploads/' . $file['name'])) {
  setcookie('fileErrors', trim($fileErrors['branch_no_access']), time() + 30);
  header('Location: index.php', 'refresh');
}

ob_start();
include 'process_xls_view.php';
$output = ob_get_contents();
ob_end_clean();

header('Content-Type: text/html;');
echo $output;
