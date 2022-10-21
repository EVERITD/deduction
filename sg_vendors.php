<?php
  // get the login details
  session_start();
  if(isset($_SESSION) AND !isset($_SESSION['username'])) header('Location: login.php');

  $lcuser        = $_SESSION['user'] ;
  $lcusername    = $_SESSION['username'] ;
  $branch        = $_SESSION['branch_code'] ;
  $xbranch       = $_SESSION['branch_code'] ;
  $glbranchname  = $_SESSION['branch_name'];
  $dept_code     = $_SESSION['dept_code'];
  $lcdeptname    = $_SESSION['dept_name'];
  $division_code = $_SESSION['divcode'];
  $lcdivname     = $_SESSION['divname'];
  $lcaccrights   = $_SESSION['type'];
  $height        = $xbranch == 'S399' ? 460 : 460;
  $lcbuyerid     = @$_SESSION['lcbuyerid'];

  error_reporting(-1);

  if(isset($_GET['act']) AND 'maintenance' === htmlspecialchars(strip_tags($_GET['act']), ENT_QUOTES))
  {
    // show encoding form
    $eForm = true;
  } else
    header('Location: deductionmain.php');

  $conn = @mssql_connect('192.168.16.68', 'sa', 'masterkey');
  $d = @mssql_select_db('consolidatepo', $conn)
    or die("Couldn't open database consolidatepo");

  $siteGroups = array();
  $listings = array();

  $postData = (count($_POST) > 0) ? $_POST: null;

  if(!is_null($postData))
  {
    $pMainSite = htmlspecialchars(strip_tags($postData['cmbMainSite']), ENT_QUOTES);
    $pVendor   = htmlspecialchars(strip_tags($postData['txtVendors']), ENT_QUOTES);

    // check the postdata then return error if meron
    $lQry = " insert into sitegroup_vendors(main_site, vendor_code, is_active, created_by, created_at, updated_by, updated_at) "
      . " values('$pMainSite', '$pVendor', 1, 'admin', getdate(), 'admin', getdate()) ";

    mssql_query($lQry);
    header('Location: sg_vendors.php?act=maintenance');
  }

  $lQry = " select distinct main_site from sitegroup ";
  $lRs  = mssql_query($lQry);

  while($rs = mssql_fetch_object($lRs))
    $siteGroups[] = $rs->main_site;

  $lQry = " select a.*, b.main_name, b.sub_name, c.suppliername
    from sitegroup_vendors a
    inner join sitegroup b on a.main_site=b.sub_site
    left join eversql_arms.arms.dbo.supplier c on a.vendor_code=c.VendorCode
    order by a.main_site ";
  $lVendors = mssql_query($lQry);

  while($rsVendors = mssql_fetch_object($lVendors))
    $listings[trim($rsVendors->main_site)][] = $rsVendors;

  ob_start();
  include '_layout.html';
  $buffer = ob_get_contents();
  ob_get_clean();

  echo $buffer;

  die();
