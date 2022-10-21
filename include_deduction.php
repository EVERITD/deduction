<?php
if ($_GET['s'] != '') {
  $s = $_GET['s'];
  $s = '';
} else {
  $s = '';
}

if ($_POST['branch'] != '') {
  $branch = $_POST['branch'];
} else {
  $branch = '%';
}

if ($_POST['euser'] != '') {
  $euser = trim($_POST['euser']);
} else {
  $euser = '%';
}

if ($_GET['dept_code'] != '') {
  $dept_code = trim($_GET['dept_code']);
} else {
  if ($_POST['dept_code'] != '') {
    $dept_code = trim($_POST['dept_code']) ;
  }else {
    $dept_code = "%" ;
  }
}

if ($_POST['division_code'] != '') {
  $division_code = trim($_POST['division_code']) ;
}else {
  $division_code = "%" ;
}

if ($_POST[category_code] != '') {
  $category_code = trim($_POST['category_code']) ;
  $noavail = '';
}else {
  $category_code = "%" ;
  $noavail = 'No Subcategory Available';
}

if ($_POST['subcat_code'] != '') {
  $subcat_code = trim($_POST['subcat_code']) ;
}else {
  $subcat_code = "%" ;
}

if(in_array(@$_POST['cmbstat'], array('0','1')) AND isset($_POST['cmbstat']))
{
  $selStat = $_POST['cmbstat'];
  $selstats[$selStat] = 'selected="selected"';

  if($selstats !== '%')
    $printedIsApprove = ' and deducted='.$selStat.' ';

}

if (strlen($_POST['txtDate'])==0 or strlen($_POST['txtDate2'])==0) {
  //$txtDate = date("m/d/Y");
  //$txtDate2 = date("m/d/Y");
  $txtDate = '';
  $txtDate2 = '';
  //$qrydm_date = "";
}else{
  $txtDate = $_POST['txtDate'];
  $txtDate2 = $_POST['txtDate2'];
}

if ($_POST[vendor] != '') {
  $vendor = trim($_POST[vendor]) ;
  $cntlen = count_len($vendor);
  $cnt = (int)strlen($vendor) - (int)$cntlen;
  $cntx = $cnt - 2;   //removing space and -
  $vcode = substr($vendor,$cnt,$cntlen);
  $qry_vcode = " and vendorcode like '{$vcode}' ";

  include("sqlconn.php");
}else {
  $vendor = "" ;
  $vcode = '';
  $qry_vcode = "";
}

if ($_POST['fileid'] != '') {
  $fileid = trim($_POST['fileid']);
  $qryfile = " and g.id like '{$fileid}'";
} else {
  $fileid = '';
  $qryfile = '';
}

if (strlen($_POST['cmdsearch'])!= 0) {
  $cmdsearch = trim($_POST['cmdsearch']);
} else {
  $cmdsearch = '';
}

if (strlen($_POST['cmdnew']) != 0) {
   $cmdnew = trim($_POST['cmdnew']) ;
}else {
  $cmdnew = "" ;
}

if (strlen($_POST['cmdpost']) != 0) {
   $cmdpost = trim($_POST['cmdpost']) ;
}else {
  $cmdpost = "" ;
}

if (strlen($_POST['cmdcancel']) != 0) {
  $cmdcancel = trim($_POST['cmdcancel']) ;
}else {
  $cmdcancel = "" ;
}

if (strlen($_POST['cmdextract']) != 0) {
   $cmdextract = trim($_POST['cmdextract']) ;
}else {
  $cmdextract = "" ;
}

if (strlen($_POST['cmdreport']) != 0) {
   $cmdreport = trim($_POST['cmdreport']) ;
}else {
  $cmdreport = "" ;
}

if (strlen($_POST['cmdlogout']) != 0) {
   $cmdlogout = trim($_POST['cmdlogout']) ;
}else {
  $cmdlogout = "" ;
}

if ($_POST['markall'] != '') {
  $markall = $_POST['markall'] ;
  $chk  = $_POST['markall'] ;
}

if ($_GET['chk'] != '') {
  $chk = $_GET['chk'];
} else {
  if ($_POST['chk'] != '') {
    $chk = $_POST['chk'] ;
  }
}

if ($_GET['cancelrem'] != '') {
  $cancelrem = $_GET['cancelrem'];
} else {
  if ($_POST['cancelrem'] != '') {
    $cancelrem = $_POST['cancelrem'];
  } else {
    $cancelrem = '';
  }
}

if ($_POST['lststatus'] != '') {
  $lststatus = trim($_POST['lststatus']);
}else{
  $lststatus = "%";
}

/**
 * Ordering Branch
 */
$branchesListing = '';
if(0 === strripos($branch, 'OB'))
{
  $cleanBranch = str_replace('OB-', '', $branch);
  $branchesListing = '\''.implode('\', \'', $subSitesList[$cleanBranch]).'\'';
}

$branchesListing = (empty($branchesListing)) ? '\''.$branch.'\'': $branchesListing;
$branchCondition = ($branch === '%') ? 'like': 'in';

$isConsPo = false;
if(isset($_POST['cmbconspo']))
{
  $consPo = '('.($qry_vcode !== '') ? $qry_vcode: '';
  $consPo .= " and vendorcode in (
    select distinct a.vendor_code from everlyl_conspo.consolidatepo.dbo.sitegroup_vendors a
    left join everlyl_conspo.consolidatepo.dbo.sitegroup b on a.main_site=b.main_site
    where b.sub_site :branch :vcode
    ) ";

  $consPo = str_replace(':branch', ($branchCondition === 'like') ? 'like'.$branchesListing:
  'in ('.$branchesListing.')', $consPo);

  $consPo = str_replace(':vcode', ($qry_vcode !== '') ? " and a.vendor_code='{$vcode}' ": '', $consPo);

  $isConsPo = true;
}

/* --------------------------------------------------------- */

switch($lststatus) {
  case "%": //for all status
    $qrystat = "and vposted like '%' ";
    $dateshow = 'Date Encoded';
    $qrydate = " convert(char(12),a.dm_date,101) as dm_date, ";
    $qrydmno = " a.dm_no_acctg as dmno, ";
    if (strlen($_POST['txtDate'])>0 or strlen($_POST['txtDate2'])>0) {
      $qrydm_date = " and convert(char(12),dm_date,112) between convert(char(8), cast('{$txtDate}' as datetime), 112) and convert(char(8), cast('{$txtDate2}' as datetime), 112)";
    }
    break;
  case " ": //for all status
    $qrystat = "and vposted like '%' ";
    $dateshow = 'Date Encoded';
    $qrydate = " convert(char(12),a.dm_date,101) as dm_date, ";
    $qrydmno = " a.dm_no_acctg as dmno, ";
    if (strlen($_POST['txtDate'])>0 or strlen($_POST['txtDate2'])>0) {
      // $qrydm_date = " and convert(char(12),dm_date,101) between '{$txtDate}' and '{$txtDate2}'";
      $qrydm_date = " and convert(char(12),dm_date,112) between convert(char(8), cast('{$txtDate}' as datetime), 112) and convert(char(8), cast('{$txtDate2}' as datetime), 112)";
    }
    break;
  case "0": //for unposted
    $qrystat = " and vposted = 0 ";
    $dateshow = 'Date Encoded';
    $qrydate = " convert(char(12),a.dm_date,101) as dm_date, ";
    $qrydmno = " a.dm_no_acctg as dmno, ";
    if (strlen($_POST['txtDate'])>0 or strlen($_POST['txtDate2'])>0) {
      // $qrydm_date = " and convert(char(12),dm_date,101) between '{$txtDate}' and '{$txtDate2}'";
      $qrydm_date = " and convert(char(12),dm_date,112) between convert(char(8), cast('{$txtDate}' as datetime), 112) and convert(char(8), cast('{$txtDate2}' as datetime), 112)";
    }
    break;
  case "1": //for approved
    $qrystat = " and vposted = 1 ";
    $dateshow = 'Approved Date';
    $qrydate = " convert(char(12),a.review_date,101) as dm_date, ";
    $qrydmno = " a.dm_no_acctg as dmno, ";
    if (strlen($_POST['txtDate'])>0 or strlen($_POST['txtDate2'])>0) {
      // $qrydm_date = " and convert(char(12),review_date,101) between '{$txtDate}' and '{$txtDate2}'";
      $qrydm_date = " and convert(char(12),dm_date,112) between convert(char(8), cast('{$txtDate}' as datetime), 112) and convert(char(8), cast('{$txtDate2}' as datetime), 112)";
    }
    break;
  case "2": //for cancelled
    $qrystat = " and vposted = 2 ";
    $dateshow = 'Cancelled Date';
    $qrydate = " convert(char(12),a.cancel_date,101) as dm_date, ";
    $qrydmno = " a.dm_no_acctg as dmno, ";
    if (strlen($_POST['txtDate'])>0 or strlen($_POST['txtDate2'])>0) {
      // $qrydm_date = " and convert(char(12),cancel_date,101) between '{$txtDate}' and '{$txtDate2}'";
      $qrydm_date = " and convert(char(12),dm_date,112) between convert(char(8), cast('{$txtDate}' as datetime), 112) and convert(char(8), cast('{$txtDate2}' as datetime), 112)";
    }
    break;
  case "3": //for extracted
    $qrystat = " and vposted = 3 ";
    $dateshow = 'Extracted Date';
    $qrydate = " convert(char(12),a.extracted_date,101) as dm_date, ";
    $qrydmno = " a.dm_no_acctg as dmno, ";
    if (strlen($_POST['txtDate'])>0 or strlen($_POST['txtDate2'])>0) {
      // $qrydm_date = " and convert(char(12),extracted_date,101) between '{$txtDate}' and '{$txtDate2}'";
      $qrydm_date = " and convert(char(12),dm_date,112) between convert(char(8), cast('{$txtDate}' as datetime), 112) and convert(char(8), cast('{$txtDate2}' as datetime), 112)";
    }
    break;
  case "4": //for printed
    $qrystat = " and vposted not in (0, 2, 3) ";
    $qrydate = " convert(char(12),a.dm_date,101) as dm_date, dsp.printed_date, dspd.batch, ";
    $qrydmno = " a.dm_no_acctg as dmno, ";
    $qryDsp = " select dm_ctrl_no from deduction_slip_print_details dspd left join deduction_slip_prints dsp on dspd.deduction_slip_prints_id=dsp.id
      where dsp.branch :branch :date ";
    // like '{$branch}'
    $qryDsp = str_replace(':branch', ($branchCondition === 'like') ? 'like'.$branchesListing:
    'in ('.$branchesListing.')', $qryDsp);

    if(@$_POST['txtDate'] or @$_POST['txtDate2'])
      $qryDsp = str_replace(':date', " and dsp.printed_date between
        cast(convert(char(8), cast('{$txtDate}' as datetime), 112) as bigint)
        and cast(convert(char(8), cast('{$txtDate2}' as datetime), 112) as bigint) ", $qryDsp);
    else
      $qryDsp = str_replace(':date', '', $qryDsp);

    $rstDsp = mssql_query($qryDsp);
    $rstCtrlNo = array();
    while($rsDsp = mssql_fetch_object($rstDsp))
    {
      $rstCtrlNo[] = $rsDsp->dm_ctrl_no;
    }
    break;
}

$lcbuyeridx = trim($lcbuyerid);
$buyers = ('' !== trim($lcbuyerid) AND !is_null($lcbuyerid)) ? ' and a.buyerid=\''.$lcbuyeridx.'\' ': '';

switch($lcaccrights) {
  case 1 :  //admin
    $cmddisabled  = '';           //encode new deduction button
    $cmddisabled1 = '';           //cancel button
    $cmddisabled2 = '';           //review button
    $cmddisabled3 = '';           //extract button
    break;
  case 2 :  //encoder
    $cmddisabled  = '';
    $cmddisabled1 = '';
    $cmddisabled2 = 'disabled="disabled"';
    $cmddisabled3 = 'disabled="disabled"';
    if (trim($_SESSION['dept_code']) == 'MKT') {
      if ($euser == '%') {
        $auser = "and encoded_by in (select user_name from ref_users where dept_code in ('PUR','MKT'))";
      } else {
        $auser = "and encoded_by like '{$euser}'";
      }
    } else {
      $auser = "and encoded_by like '{$lcuser}' $buyers ";
    }
    break;
  case 3 :  //reviewer
    $cmddisabled  = 'disabled="disabled"';
    $cmddisabled1 = 'disabled="disabled"';
    $cmddisabled2 = 'disabled="disabled"';
    $cmddisabled3 = 'disabled="disabled"';
    if ($euser == '%') {
      $auser = "and encoded_by like '{$euser}' ";
    } else {
      $auser = "and encoded_by = '{$euser}'";
    }
    break;
  case 4 :  //supervisor
    $cmddisabled  = '';           //encode new deduction button
    $cmddisabled1 = '';           //cancel button
    $cmddisabled2 = '';           //review button
    $cmddisabled3 = 'disabled="disabled"';
    if ($euser == '%') {
      $auser = "and encoded_by in (select user_name from ref_supervisor where supervisor = '{$lcuser}') ";
    } else {
      $auser = "and encoded_by like '{$euser}'";
    }
    break;
  case 5 :  //audit user (view all branch, all user but can't edit or cancel)
    $cmddisabled = 'disabled="disabled"';
    $cmddisabled1 = 'disabled="disabled"';
    $cmddisabled2 = 'disabled="disabled"';
    $cmddisabled3 = '';
    //$auser = "and encoded_by like '{$euser}'";
    if ($euser == '%') {
      $auser = "and encoded_by like '%' ";
    } else {
      $auser = "and encoded_by like '{$euser}'";
    }
    $qrystat = " and vposted = 1 ";
    break;

}

// ops and branch s399 are not allowed to print ds
$cmdPrintDs = ($_SESSION['dept_code'] === 'OPS' AND $xbranch !== 'S399' AND (int) $lcaccrights !== 4) ? ' disabled="disabled" ': '';
$cmddisabledx = $cmddisabled2;

if($lcdeptname === 'Marketing')
  $cmddisabledx = '';

//period=<?php echo $namemonth&d=<?php echo $d&

if ($_GET['s'] == 1) {}
else
{
  $pageNumber = htmlspecialchars(strip_tags($_POST['txthiddenpageno']), ENT_QUOTES);

  $select = " a.dm_no,a.branch_code,c.dept_code,d.division_code,e.category_name,f.subcat_name,
    ltrim(rtrim(a.vendorcode))+' '+ltrim(rtrim(a.suppliername)) as suppliername,{$qrydate}{$qrydmno}
    a.amount,a.remarks,isnull(a.cancel_remarks,'') as cancel_remarks,a.encoded_by,
	case when a.dm_no_acctg = '' or a.dm_no_acctg is null or a.vposted = 2 then a.vposted else 4 end as vposted,isDMprinted,isPosted,
    isnull(h.buyer_code,'') as buyer_code,isnull(i.paymentdesc,'') as paymentdesc,a.period,convert(char(8), a.review_date, 112) as review_date,
    dsp.printed_date, dspd.batch, convert(char(8), cancel_date, 112) as cancelleddate, a.remarks1, a.paymentid, conspo.main_site,a.lbr_number ";

  $where = " a.branch_code :branch and a.dept_code like '{$dept_code}' and
    a.division_code like '{$division_code}' and
    a.category_code like '{$category_code}' and a.subcat_code like '{$subcat_code}'
    {$qrydm_date} {$auser} {$qrystat} {$qryfile} :vcode and
    a.division_code not in ('BO') :deducted ";

  $where = str_replace(':vcode', ($isConsPo) ? $consPo: $qry_vcode, $where);

  $where = str_replace(':branch', ($branchCondition === 'like') ? 'like'.$branchesListing:
    'in ('.$branchesListing.')', $where);

  $mselqry = "select :page :select
    from deduction_master a left join ref_branch b on a.branch_code = b.branch_code
    left join ref_department c on a.dept_code = c.dept_code
    left join ref_division d on a.division_code = d.division_code
    left join ref_category e on a.category_code = e.category_code
    left join ref_subcategory f on a.subcat_code = f.subcat_code
    left join deductions_upload g on a.eposted = g.id
    left join ref_buyer h on a.buyerid = h.buyerid
    left join ref_payment i on a.paymentid = i.paymentid
    left join conspo_pivot conspo on a.dm_no = conspo.dm_no
    :join
    where :brace :where :endbrace :printed :order ";

  $order = " order by a.encoded_date, a.dm_no desc ";

  $finalSelQry = str_replace(':page', '', $mselqry);
  $finalSelQry = str_replace(':select', $select, $finalSelQry);
  $finalSelQry = str_replace(':where', $where, $finalSelQry);
  $finalSelQry = str_replace(':order', $order, $finalSelQry);

  $mainQry = str_replace(':page', '', $mselqry);
  $mainQry = str_replace(':select', ' count(a.dm_no) as tcount ', $mainQry);
  $mainQry = str_replace(':where', $where, $mainQry);

  if($selstats !== '%')
  {
    $finalSelQry = str_replace(':deducted', $printedIsApprove, $finalSelQry);
    $mainQry = str_replace(':deducted', $printedIsApprove, $mainQry);
  }

  $finalSelQry = str_replace(':deducted', '', $finalSelQry);
  $mainQry     = str_replace(':deducted', '', $mainQry);

  $mainQry = str_replace(':order', '', $mainQry);
  $selqry  = str_replace(':join', " left join deduction_slip_print_details dspd on a.dm_no=dspd.dm_ctrl_no
    left join deduction_slip_prints dsp on dspd.deduction_slip_prints_id=dsp.id ", $finalSelQry);
  $mainQry = str_replace(':join', " left join deduction_slip_print_details dspd on a.dm_no=dspd.dm_ctrl_no
    left join deduction_slip_prints dsp on dspd.deduction_slip_prints_id=dsp.id ", $mainQry);

  if(!empty($rstCtrlNo) OR (int) $lststatus === 4)
  {
    /**
     * March 06, 2013
     * Fix: Query error when trying to implode the list of all ctrlnos
     */
    //$selqry = str_replace(':printed', " and ( a.dm_no in ('".implode('\', \'', $rstCtrlNo)."') and a.dm_no<>' ' ) ", $selqry);
    $selqry = str_replace(':printed', " and ( a.dm_no in ( ".$qryDsp." ) and a.dm_no<>' ' ) ", $selqry);
    //$mainQry = str_replace(':printed', " and ( a.dm_no in ('".implode('\', \'', $rstCtrlNo)."') and a.dm_no<>' ' ) ", $mainQry);
    $mainQry = str_replace(':printed', " and ( a.dm_no in ( ".$qryDsp." ) and a.dm_no<>' ' ) ", $mainQry);
  }

  $selqry    = str_replace(':printed', '', $selqry);
  $mainQry   = str_replace(':printed', '', $mainQry);

  $mainQry   = str_replace(':join', '', $mainQry);
  $selqry    = str_replace(':join', '', $selqry);

  $selqry    = str_replace(':brace', '(', $selqry);
  $selqry    = str_replace(':endbrace', ')', $selqry);
  $mainQry   = str_replace(':brace', '(', $mainQry);
  $mainQry   = str_replace(':endbrace', ')', $mainQry);

  // $mainQry;
  $recordSet = mssql_query($mainQry);
  $tCount    = mssql_result($recordSet, 0, 'tcount');

  $pages = ceil($tCount/10);

  // and left(rtrim(ltrim(a.period)),len(rtrim(ltrim(a.period)))-5) like '{$namemonth}' and right(ltrim(rtrim(period)),4) like '{$d}'  a.isposted like '{$lstisdm}' and
   echo $selqry;
  $r_select = mssql_query($selqry);
  $nmrow    = mssql_num_rows($r_select);

}
