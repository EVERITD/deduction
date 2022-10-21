<?php
if ($_GET['s'] != '') {
  $s = $_GET['s'];
  $s = '';
} else {
  $s = '';
}

if ($_GET['branch'] != '') {
  $branch = $_GET['branch'];
} else {
  $branch = '%';
}

if ($_GET['euser'] != '') {
  $euser = trim($_GET['euser']);
} else {
  $euser = '%';
}

if ($_GET['dept_code'] != '') {
  $dept_code = trim($_GET['dept_code']);
} else {
  if ($_GET['dept_code'] != '') {
    $dept_code = trim($_GET['dept_code']) ;
  }else {
    $dept_code = "%" ;
  }
}

if ($_GET['division_code'] != '') {
  $division_code = trim($_GET['division_code']) ;
}else {
  $division_code = "%" ;
}

if ($_GET['category_code'] != '') {
  $category_code = trim($_GET['category_code']) ;
  $noavail = '';
}else {
  $category_code = "%" ;
  $noavail = 'No Subcategory Available';
}

if ($_GET['subcat_code'] != '') {
  $subcat_code = trim($_GET['subcat_code']) ;
}else {
  $subcat_code = "%" ;
}

if(in_array(@$_GET['cmbstat'], array('0','1')) AND isset($_GET['cmbstat']))
{
  $selStat = $_GET['cmbstat'];
  $selstats[$selStat] = 'selected="selected"';

  if($selstats !== '%')
  {
    $printedIsApprove = ' and deducted='.$selStat.' ';
  }
}

if (strlen($_GET['txtDate'])==0 or strlen($_GET['txtDate2'])==0) {
  //$txtDate = date("m/d/Y");
  //$txtDate2 = date("m/d/Y");
  $txtDate = '';
  $txtDate2 = '';
  //$qrydm_date = "";
}else{
  $txtDate = $_GET['txtDate'];
  $txtDate2 = $_GET['txtDate2'];
}

if ($_GET['vendor'] != '') {
  $vendor = trim($_GET['vendor']) ;
  $cntlen = strrpos($vendor, "-");
  $cnt = (int)strlen($vendor) - (int)$cntlen;
  $cntx = $cnt - 2;   //removing space and -
  $vcode = substr($vendor,-$cntx,$cntlen);
  $qry_vcode = " and vendorcode like '{$vcode}' ";

  include("sqlconn.php");
}else {
  $vendor     = "" ;
  $vcode      = '';
  $qry_vcode  = "";
}

if ($_GET['fileid'] != '') {
  $fileid = trim($_GET['fileid']);
  $qryfile = " and g.id like '{$fileid}'";
} else {
  $fileid = '';
  $qryfile = '';
}

if (strlen($_GET['cmdsearch'])!= 0) {
  $cmdsearch = trim($_GET['cmdsearch']);
} else {
  $cmdsearch = '';
}

if (strlen($_GET['cmdnew']) != 0) {
   $cmdnew = trim($_GET['cmdnew']) ;
}else {
  $cmdnew = "" ;
}

if (strlen($_GET['cmdpost']) != 0) {
   $cmdpost = trim($_GET['cmdpost']) ;
}else {
  $cmdpost = "" ;
}

if (strlen($_GET['cmdcancel']) != 0) {
  $cmdcancel = trim($_GET['cmdcancel']) ;
}else {
  $cmdcancel = "" ;
}

if (strlen($_GET['cmdextract']) != 0) {
   $cmdextract = trim($_GET['cmdextract']) ;
}else {
  $cmdextract = "" ;
}

if (strlen($_GET['cmdreport']) != 0) {
   $cmdreport = trim($_GET['cmdreport']) ;
}else {
  $cmdreport = "" ;
}

if (strlen($_GET['cmdlogout']) != 0) {
   $cmdlogout = trim($_GET['cmdlogout']) ;
}else {
  $cmdlogout = "" ;
}

if ($_GET['markall'] != '') {
  $markall = $_GET['markall'] ;
  $chk  = $_GET['markall'] ;
}

if ($_GET['chk'] != '') {
  $chk = $_GET['chk'];
} else {
  if ($_GET['chk'] != '') {
    $chk = $_GET['chk'] ;
  }
}

if ($_GET['cancelrem'] != '') {
  $cancelrem = $_GET['cancelrem'];
} else {
  if ($_GET['cancelrem'] != '') {
    $cancelrem = $_GET['cancelrem'];
  } else {
    $cancelrem = '';
  }
}

if ($_GET['lststatus'] != '') {
  $lststatus = trim($_GET['lststatus']);
}else{
  $lststatus = "%";
}

switch($lststatus) {
  case "%": //for all status
    $qrystat = "and vposted like '%' ";
    $dateshow = 'Date Encoded';
    $qrydate = " convert(char(12),a.dm_date,101) as dm_date, ";
    $qrydmno = " a.dm_no_acctg as dmno, '' as cvnumber, ";
    //$qrydmno = " a.dm_no_acctg as dmno, isnull((select top 1 isnull(aptref,'') as cvnumber from arms.dbo.accountspayable where aptno = a.dm_no_acctg and a.dm_no_acctg <> ''),'') as cvnumber, ";
    if (strlen($_GET['txtDate'])>0 or strlen($_GET['txtDate2'])>0) {
      // $qrydm_date = " and convert(char(12),dm_date,101) between '{$txtDate}' and '{$txtDate2}'";
      $qrydm_date = " and convert(char(12),dm_date,112) between convert(char(8), cast('{$txtDate}' as datetime), 112) and convert(char(8), cast('{$txtDate2}' as datetime), 112)";
    }
    break;
  case " ": //for all status
    $qrystat = "and vposted like '%' ";
    $dateshow = 'Date Encoded';
    $qrydate = " convert(char(12),a.dm_date,101) as dm_date, ";
    $qrydmno = " a.dm_no_acctg as dmno, '' as cvnumber, ";
    //$qrydmno = " a.dm_no_acctg as dmno, isnull((select top 1 isnull(aptref,'') as cvnumber from arms.dbo.accountspayable where aptno = a.dm_no_acctg and a.dm_no_acctg <> ''),'') as cvnumber, ";
    if (strlen($_GET['txtDate'])>0 or strlen($_GET['txtDate2'])>0) {
      // $qrydm_date = " and convert(char(12),dm_date,101) between '{$txtDate}' and '{$txtDate2}'";
      $qrydm_date = " and convert(char(12),dm_date,112) between convert(char(8), cast('{$txtDate}' as datetime), 112) and convert(char(8), cast('{$txtDate2}' as datetime), 112)";
    }
    break;
  case "0": //for unposted
    $qrystat = " and vposted = 0 ";
    $dateshow = 'Date Encoded';
    $qrydate = " convert(char(12),a.dm_date,101) as dm_date, ";
    $qrydmno = " a.dm_no_acctg as dmno, '' as cvnumber, ";
    //$qrydmno = " a.dm_no_acctg as dmno, isnull((select top 1 isnull(aptref,'') as cvnumber from arms.dbo.accountspayable where aptno = a.dm_no_acctg and a.dm_no_acctg <> ''),'') as cvnumber, ";
    if (strlen($_GET['txtDate'])>0 or strlen($_GET['txtDate2'])>0) {
      // $qrydm_date = " and convert(char(12),dm_date,101) between '{$txtDate}' and '{$txtDate2}'";
      $qrydm_date = " and convert(char(12),dm_date,112) between convert(char(8), cast('{$txtDate}' as datetime), 112) and convert(char(8), cast('{$txtDate2}' as datetime), 112)";
    }
    break;
  case "1": //for approved
    $qrystat = " and vposted = 1 ";
    $dateshow = 'Approved Date';
    $qrydate = " convert(char(12),a.review_date,101) as dm_date, ";
    $qrydmno = " a.dm_no_acctg as dmno, '' as cvnumber, ";
    //$qrydmno = " a.dm_no_acctg as dmno, isnull((select top 1 isnull(aptref,'') as cvnumber from arms.dbo.accountspayable where aptno = a.dm_no_acctg and a.dm_no_acctg <> ''),'') as cvnumber, ";
    if (strlen($_GET['txtDate'])>0 or strlen($_GET['txtDate2'])>0) {
      // $qrydm_date = " and convert(char(12),review_date,101) between '{$txtDate}' and '{$txtDate2}'";
      $qrydm_date = " and convert(char(12),dm_date,112) between convert(char(8), cast('{$txtDate}' as datetime), 112) and convert(char(8), cast('{$txtDate2}' as datetime), 112)";
    }
    break;
  case "2": //for cancelled
    $qrystat = " and vposted = 2 ";
    $dateshow = 'Cancelled Date';
    $qrydate = " convert(char(12),a.cancel_date,101) as dm_date, ";
    $qrydmno = " a.dm_no_acctg as dmno, '' as cvnumber, ";
    //$qrydmno = " a.dm_no_acctg as dmno, isnull((select top 1 isnull(aptref,'') as cvnumber from arms.dbo.accountspayable where aptno = a.dm_no_acctg and a.dm_no_acctg <> ''),'') as cvnumber, ";
    if (strlen($_GET['txtDate'])>0 or strlen($_GET['txtDate2'])>0) {
      // $qrydm_date = " and convert(char(12),cancel_date,101) between '{$txtDate}' and '{$txtDate2}'";
      $qrydm_date = " and convert(char(12),dm_date,112) between convert(char(8), cast('{$txtDate}' as datetime), 112) and convert(char(8), cast('{$txtDate2}' as datetime), 112)";
    }
    break;
  case "3": //for extracted
    $qrystat = " and vposted = 3 ";
    $dateshow = 'Extracted Date';
    $qrydate = " convert(char(12),a.extracted_date,101) as dm_date, ";
    $qrydmno = " a.dm_no_acctg as dmno, '' as cvnumber, ";
    //$qrydmno = " a.dm_no_acctg as dmno, isnull((select top 1 isnull(aptref,'') as cvnumber from arms.dbo.accountspayable where aptno = a.dm_no_acctg and a.dm_no_acctg <> ''),'') as cvnumber, ";
    if (strlen($_GET['txtDate'])>0 or strlen($_GET['txtDate2'])>0) {
      // $qrydm_date = " and convert(char(12),extracted_date,101) between '{$txtDate}' and '{$txtDate2}'";
      $qrydm_date = " and convert(char(12),dm_date,112) between convert(char(8), cast('{$txtDate}' as datetime), 112) and convert(char(8), cast('{$txtDate2}' as datetime), 112)";
    }
    break;
  case "4": //for printed
    $qrystat = " and vposted not in (0, 2, 3) ";
    $qrydate = " convert(char(12),a.dm_date,101) as dm_date, ";
    $prDate  = "dsp.printed_date, dspd.batch, ";
    $qrydmno = " a.dm_no_acctg as dmno, '' as cvnumber, ";
    //$qrydmno = " a.dm_no_acctg as dmno, isnull((select top 1 isnull(aptref,'') as cvnumber from arms.dbo.accountspayable where aptno = a.dm_no_acctg and a.dm_no_acctg <> ''),'') as cvnumber, ";
    $qryDsp = " select dm_ctrl_no from deduction_slip_print_details dspd left join deduction_slip_prints dsp on dspd.deduction_slip_prints_id=dsp.id
      where dsp.branch like '{$branch}' :date ";
    if(@$_GET['txtDate'] or @$_GET['txtDate2'])
      $qryDsp = str_replace(':date', " and dsp.printed_date between convert(char(8), cast('{$txtDate}' as datetime), 112) and convert(char(8), cast('{$txtDate2}' as datetime), 112) ", $qryDsp);
    else
      $qryDsp = str_replace(':date', '', $qryDsp);

  	// echo $qryDsp;
  	// die();

    $rstDsp = mssql_query($qryDsp);
    $rstCtrlNo = array();
    while($rsDsp = mssql_fetch_object($rstDsp))
    {
      $rstCtrlNo[] = $rsDsp->dm_ctrl_no;
    }

    break;
}

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
      $auser = "and encoded_by like '{$lcuser}'";
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
$cmdPrintDs = ($_SESSION['dept_code'] === 'OPS' AND $xbranch !== 'S399') ? ' disabled="disabled" ': '';
$cmddisabledx = $cmddisabled2;

if($lcdeptname === 'Marketing')
  $cmddisabledx = '';

//period=<?php echo $namemonth&d=<?php echo $d&

if ($_GET['s'] == 1) {}
else
{
  $pageNumber = htmlspecialchars(strip_tags($_GET['txthiddenpageno']), ENT_QUOTES);

  $condition = (!is_null(@$_GET['cmbstat'])) ? " case when a.vposted = 0 then 'New' else
        case when a.vposted = 1 then
            case when a.deducted = 0 then
              'Printed-Undeducted'
            else
              'Printed-Deducted'
            end
          else
            'Cancelled'
        end
      end,
    ": "  case when a.vposted = 0 then 'New' else
          case when a.vposted = 1 then 'Approved' else
            'Cancelled'
          end
        end,
    ";

  $select = " a.dm_no,{$qrydmno} case when ltrim(rtrim(a.branch_code)) = 'S399' then 'S306' else a.branch_code end as branch_code,c.dept_code,d.division_code,
    ltrim(rtrim(a.vendorcode)) as vpcode, ltrim(rtrim(a.suppliername)) as vpname,
    e.category_name,f.subcat_name,{$qrydate} a.amount,{$condition}
    isnull(h.buyer_code,'') as buyer_code,isnull(i.paymentdesc,'') as paymentdesc,a.period,
    a.remarks,ltrim(rtrim(a.vendorcode))+' '+ltrim(rtrim(a.suppliername)) as suppliername,
    isnull(a.cancel_remarks,'') as cancel_remarks,a.encoded_by,isDMprinted,isPosted,
    convert(char(8), a.review_date, 112) as review_date, {$prDate}
    dsp.printed_date, dspd.batch, convert(char(8), cancel_date, 112) as cancelleddate ";

  $where = " a.branch_code like '{$branch}' and a.dept_code like '{$dept_code}' and
    a.division_code like '{$division_code}' and
    a.category_code like '{$category_code}' and a.subcat_code like '{$subcat_code}'
    {$qrydm_date} {$auser} {$qrystat} {$qryfile} {$qry_vcode} and
    a.division_code not in ('BO') :deducted ";

  $mselqry = "select :page :select
    from deduction_master a left join ref_branch b on a.branch_code = b.branch_code
    left join ref_department c on a.dept_code = c.dept_code
    left join ref_division d on a.division_code = d.division_code
    left join ref_category e on a.category_code = e.category_code
    left join ref_subcategory f on a.subcat_code = f.subcat_code
    left join deductions_upload g on a.eposted = g.id
    left join ref_buyer h on a.buyerid = h.buyerid
    left join ref_payment i on a.paymentid = i.paymentid
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
     * Fix: Query error when trying to implode all the dm_ctrlno
     */
    // $selqry = str_replace(':printed', " and ( a.dm_no in ( ".$qryDsp." ) and a.dm_no<>' ' ) ", $selqry);
    $selqry   = str_replace(':printed', " and ( a.dm_no in ('".implode('\', \'', $rstCtrlNo)."') and a.dm_no<>' ' ) ", $selqry);
    // $mainQry = str_replace(':printed', " and ( a.dm_no in ('".implode('\', \'', $rstCtrlNo)."') and a.dm_no<>' ' ) ", $mainQry);
    $mainQry  = str_replace(':printed', " and ( a.dm_no in ( ".$qryDsp." ) and a.dm_no<>' ' ) ", $mainQry);
  }

  $selqry    = str_replace(':printed', '', $selqry);
  $mainQry   = str_replace(':printed', '', $mainQry);

  $mainQry   = str_replace(':join', '', $mainQry);
  $selqry    = str_replace(':join', '', $selqry);

  $selqry    = str_replace(':brace', '(', $selqry);
  $selqry    = str_replace(':endbrace', ')', $selqry);

  $mainQry   = str_replace(':brace', '(', $mainQry);
  $mainQry   = str_replace(':endbrace', ')', $mainQry);

  $recordSet = mssql_query($mainQry);
  $tCount    = mssql_result($recordSet, 0, 'tcount');

  $pages = ceil($tCount/10);

  //and left(rtrim(ltrim(a.period)),len(rtrim(ltrim(a.period)))-5) like '{$namemonth}' and right(ltrim(rtrim(period)),4) like '{$d}'  a.isposted like '{$lstisdm}' and

  $r_select = mssql_query($selqry);
  $nmrow    = mssql_num_rows($r_select);
  /*echo $selqry;*/

}
