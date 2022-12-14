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
  $dept_code = $_SESSION['dept_code'];
  $lcdeptname = $_SESSION['dept_name'];
  $division_code = $_SESSION['divcode'];
  $lcdivname = $_SESSION['divname'];
  $lcaccrights = $_SESSION['type'];
  $height = $xbranch == 'S399' ? 460 : 460;
  date_default_timezone_set('Asia/Manila');

  $_query = "select password from ref_users where user_name='{$lcuser}' ";
  $_rs    = mssql_query($_query);
  $_row   = mssql_fetch_array($_rs);
  $_pass  = $_row['password'];

  // decrypt
  $_query = " execute decrypt_pass '{$_pass}'";
  $_rs    = mssql_query($_query);
  $_row  = mssql_fetch_array($_rs);
  $_pass = $_row['pass'];

  $now_date = date('m-d-Y');
  $title = "Deduction_".$now_date;

  $file_type = "vnd.ms-excel";
  $file_ending = "xls";
  $app = "application/";

  include_once 'function_v2.php';
  include_once 'include_deduction_get.php';

  $dmNumbers = array();

  function cleanEntry($row)
  {
    global $dmNumbers,
      $sep;

    for($j=0; $j<17;$j++)
    {

      $rdata = trim(preg_replace('/(\$|\#)+/', '', @$row[$j]));

      if($j === 16)
        $rdata = @$row[$j];

      if($j === 2 and trim($row[1]) !== '')
      {
        // $sql = ' select aptno, aptref, convert(char(8), PostedDate, 112) as fposteddate from
        //   arms.dbo.accountspayable where aptno in (\''.trim($row[1]).'\')
        //   and isnull(aptref,\'0\') <> \'0\'';

        // $rst = mssql_query($sql);
        // $rdata = @mssql_result($rst, 0, 'aptref');
        $dmNumbers[] = trim($row[1]);
        $rdata = ':'.trim($row[1]).':';
      }
      /*var_dump($rdata);*/
      if(!isset($rdata) OR is_null($rdata))
        $schema_insert .= "NULL".$sep;
      elseif (trim($rdata) !== "")
        $schema_insert .= $rdata.$sep;
      else
        $schema_insert .= "".$sep;
    }
    
    return $schema_insert;
  }

  function fetchAptRef($data)
  {
    global $dmNumbers;
    $aptList = array();

    $sql = ' select aptno, aptref, convert(char(8), PostedDate, 112) as fposteddate from
      arms.dbo.accountspayable where aptno in (\''.implode('\',\'', $dmNumbers).'\') 
      and isnull(aptref,\'0\') <> \'0\'';

    $rst = mssql_query($sql);
    while($rApt = mssql_fetch_object($rst))
    {
      $aptList[] = trim($rApt->aptno);
      $data = str_replace(':'.trim($rApt->aptno).':', $rApt->aptref, $data);  
    }
    
    $notExistAptNo = array_diff($dmNumbers, $aptList);
    
    $datas = clean_data($data,$notExistAptNo);
    
    return $data;

  }

  function clean_data($arr_param1,$arr_param2)
  {
    foreach($arr_param2 as $n)
    {
      $arr_param1 = str_replace(':'.trim($n).':', '', $arr_param1);
    }

    return $arr_param1;
  }

  header("Content-Type: $app$file_type");
  header("Content-Disposition: attachment; filename=$title.$file_ending");
  header("Pragma: no-cache");
  header("Expires: 0");

  /*    FORMATTING FOR EXCEL DOCUMENTS ('.xls')   */
  //create title with timestamp:
  if ($Use_Title == 1){
    echo("$title\n");
  }
  //define separator (defines columns in excel & tabs in word)
  $sep = "\t"; //tabbed character
  //start of printing column names as names of MSSQL fields
  echo "CONTROL#\t";
  echo "DM #\t";
  echo "CV #\t";
  echo "BRANCH\t";
  echo "DEPARTMENT\t";
  echo "DIVISION\t";
  echo "VENDOR_CODE\t";
  echo "VENDOR NAME\t";
  echo "CATEGORY\t";
  echo "SUB CATEGORY\t";
  echo "DM DATE\t";
  echo "AMOUNT\t";
  echo "STATUS\t";
  echo "BUYER CODE\t";
  echo "PAYMENT TYPE\t";
  echo "PERIOD\t";
  echo "REMARKS\t";

  print("\n");
  //end of printing column names

  //start while loop to get data
  $content = '';
  while($row = mssql_fetch_row($r_select))
  {

    $schema_insert = "";

    $schema_insert = cleanEntry($row);

    $schema_insert = str_replace($sep."$", "", htmlspecialchars_decode($schema_insert));
    $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
    $schema_insert .= "\t\n";
    $content .= $schema_insert;

  }

  $contented = fetchAptRef($content);

  print(trim($contented));
  print "\n";

//  unlink($fileName);

  exit();
}
