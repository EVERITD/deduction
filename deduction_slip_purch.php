<?php  
    
    session_start();
    $x = strlen(@$_SESSION['user']);
    date_default_timezone_set('Asia/Manila');

   
    if ($x > 0 ) {
        include('sqlconn.php');
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
        $buyersId = @$_SESSION['buyersId'];
        $height = $xbranch == 'S399' ? 460 : 460;
        $lcbuyerid = @$_SESSION['lcbuyerid'];
       // 
        date_default_timezone_set('Asia/Manila');
    }
    else {
        header('Location: login.php');
        header("HTTP/1.0 403 Access denied");
        die("Un-Authorized Access");
    }
//===========branch_code query=============//
 $sqlbr = "select distinct * from ref_branch where branch_code not in ('S801','S802','S803', 'S301' ,'S309', 'S316' ,'S317','S312','S319')";
 $querybr =  mssql_query($sqlbr);
 //echo $querybr;
//===========buyer_code query=============//
 $sqler = 'select distinct * from ref_buyer';
 $queryer = mssql_query($sqler);
 //echo $queryer;
 //==========+category_code query=============//
 $sqlry = 'select distinct * from ref_category';
 $queryry = mssql_query($sqlry);
 //echo $queryry;
 
//===============Vender=====================//
 //$sqlvn = "select  distinct CASE WHEN vendorcode = '' THEN APTID ELSE vendorcode END AS vendorcode,SupplierName from arms.dbo.supplier";
$sqlvn = "get_supplier" ; //execute get_supplier,//to call strode procedured
$queryvn = mssql_query($sqlvn);

/*$row = mssql_fetch_array($queryvn);
var_dump($row);*/
//=========================================//




 //=============================






 //===============array for multiselect site============//
    $sites = ""; //"'%'"
  if(!empty($_POST['opsite'])) {
    foreach($_POST['opsite'] as $site ) {
        $site_q[] = '\''.$site.'\'';
        $sites =  implode(',',$site_q);
    }}
    if ($sites == '') //($sites == "'%'")
    {
        //$sit = "a.branch_code like $sites";
          $sit = '';
    }
    else
    {

       $sit = "  a.branch_code in ($sites) and";
    }
//=====================================================//
//===========array for multiselect buyer==============//
$buyers = ""; //"'%'"
 if(!empty($_POST['opbuyer'])) 
 {
    foreach($_POST['opbuyer'] as $buyer) 
    {
         $buyer_q[] = '\''.$buyer.'\'';
         $buyers = implode(',',$buyer_q);
    }
 }
     if ($buyers == '') //($buyers == "'%'")
     {
       // $buy = "a.buyerid LIKE $buyers";
          $buy = '';
     }
     else   
     {
        $buy = " a.buyerid  in ($buyers) AND ";
     }   
 //==================================================//
 //=======array for multiselect category=============//
   $category = ""; //"'%'"
 if(!empty($_POST['opcategory'])) {
    foreach($_POST['opcategory'] as $cat) {
        $category_q[] = '\''.$cat.'\'';
        $category = implode(',',$category_q);
    }}
    
    if ($category == '' ) //($category == "'%'" )
    {
        //$cats = "c.category_code like $category";
        $cat = '';
    }
    else
    {
        $cats = " c.category_code in ($category) and ";
    }
//===============================================//


//===============Accessright:===================//
    if ($lcaccrights == '4')
    {
    $sec = "encoded_by in (select user_name from ref_supervisor where supervisor = '$lcuser')";
    }else{
    $sec = "encoded_by = '$lcuser'";
    }
//===========================================//
 //===========vendorcode====================// 
      
 
   $vendor = ""; //"'%'"
 if(!empty($_POST['opvendor'])) {
    foreach($_POST['opvendor'] as $var) {
        $vendor_q[] = '\''.$var.'\'';
        $vendor = implode(',',$vendor_q);
    }}
    
   if ($vendor =='')
   {
     $ven = '';
   }
    else 
   {   
 $ven =  "a.vendorcode  in($vendor) and";
   }
    
//==========================================//

//========search query for date============//
  $frm = $_POST['from']; 
  $xpload = explode('/', $frm);
//print_r($xpload);
//print_r($xpload2);
//Array ( [0] => 04 [1] => 01 [2] => 2014 )
//yyyy - MM - dd 
  $from = "$xpload[2]$xpload[0]$xpload[1]";
  $t = $_POST['to'];
  $xpload2 = explode('/', $t);
  $to = "$xpload2[2]$xpload2[0]$xpload2[1]";
if ($from == '' and $to == '') {
    $xx = '';
} else {
    $xx = "convert(char(8),review_date,112) between '$from' and '$to' and";
}
// echo $xx;
//=========//

//=====Query for branch====//
//echo  $lcaccrights;

//=========dm_print or reprint===========//

if ($count == '')
{
  $isReprint = '0';
}
else
{
  $isReprint = '1';
// $isReprint = $count == '' ? '1' : '0';
}

/*$isReprint = $HTTP_POST_VARS['rpprt'];
*///$isReprint; 

if ($isReprint == '0')
{
  $printed = 'e.is_printed = 0';
  $select = 'a.dm_no not in (select dm_ctrl_no from deduction_slip_print_details) and';
  
}
else
{
  
  $printed = 'e.is_printed = 1';
  $select = 'a.dm_no in (select dm_ctrl_no from deduction_slip_print_details) and';

}

$close_branch = "d.branch_code not in ('S801','S802','S803', 'S301' ,'S309', 'S316' ,'S317','S312','S319') and";

//======================================//

if($dept_code == 'PUR' or $dept_code == 'MKT'):

    if ($frm == "" and $t == "" and  $category == "" and $vendor =="" and $buyers=="" and  $sites =="" )
    {

    //no display
    }
    else
    {
      if (isset($_POST['sub']))
      {  

          $sqlsearch = " select  a.dm_no, d.branch_code, a.vendorcode, a.SupplierName, b.buyer_code,ltrim(rtrim(a.department))+ ' - ' +ltrim(rtrim(f.deptname)) as department,c.category_name, ";
          $sqlsearch.= "a.period, a.remarks1, a.amount ";
          $sqlsearch.= "from deduction_master a ";
          $sqlsearch.= "Left join ref_buyer b on a.buyerid = b.buyerid ";
          $sqlsearch.= "Left join ref_category c on c.category_code = a.category_code ";
          $sqlsearch.= "Left join ref_branch d on a.branch_code = d.branch_code ";
          $sqlsearch.= "left join deduction_slip_print_details e on a.dm_no = e.dm_ctrl_no and $printed ";
          $sqlsearch.= "left join ref_prod_dept f on a.department = f.deptcode ";
          $sqlsearch.= " where a.dept_code in ('$dept_code') and a.paymentid = 2  and $ven  $sit $buy $cats  $xx $select $close_branch $sec order by a.dm_no,a.branch_code,a.encoded_date  desc ";

          //echo $sqlsearch;
          $querysr = mssql_query($sqlsearch);
          if($_SERVER["REMOTE_HOST"] =="192.168.17.128") {
            echo $sqlsearch;
          }
      }
    }
    
else:
echo '<script type="text/javascript">alert("For Pruchising and Marketing Use Only");</script>';
endif;  
if(isset ($_POST['prt']))
{
 $check =  $_POST['chk'];

 if(isset($check))
 {

  $ctrlno =  implode('-',$check);
 
 }
 else 
 {
 $ctrlno = "";
 }

if($ctrlno == "") 
{

  // nothings happen.
}
else
{
  $check1 =  $_POST['chk'];

  $check1 = implode("','",$check1);

  $sql = "select branch_code from deduction_master where dm_no in ('".$check1."')";
  $branch = mssql_query($sql);  
  while($branch2 = mssql_fetch_assoc($branch))
  {
     $site[] = $branch2['branch_code']; 
     $spaces = array_map('trim',$site);

     $cmbbranch = implode('-',$spaces); 
     
    // trim($cmbbranch);
    
  } 
}


  if($ctrlno == '' and $cmbbranch == '')
  {
  echo '<script type="text/javascript">alert("No Deposit Slip to Print");</script>';
  }
  else
  {
  header('Location:dm_print_purch.php?ctrlno='.$ctrlno."&branch=".$cmbbranch."&need=yes&prt=yes");
  }
}


if($dept_code == 'PUR' or $dept_code == 'MKT')
{
    if(isset($_POST['rprt']))
    {
     
    header('Location:deductionslip_reprint.php?ispr=yes');

    }
}
else{
   echo '<script type="text/javascript">alert("For Pruchising and Marketing Use Only");</script>';
}


include "deduction_slip_purch_view.php";
