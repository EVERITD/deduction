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
    $dept_code = trim($_GET['dept_code']);
  } else {
    $dept_code = "%";
  }
}

if ($_GET['division_code'] != '') {
  $division_code = trim($_GET['division_code']);
} else {
  $division_code = "%";
}

if ($_GET['category_code'] != '') {
  $category_code = trim($_GET['category_code']);
  $noavail = '';
} else {
  $category_code = "%";
  $noavail = 'No Subcategory Available';
}

if ($_GET['subcat_code'] != '') {
  $subcat_code = trim($_GET['subcat_code']);
} else {
  $subcat_code = "%";
}

if (in_array(@$_GET['cmbstat'], array('0', '1')) and isset($_GET['cmbstat'])) {
  $selStat = $_GET['cmbstat'];
  $selstats[$selStat] = 'selected="selected"';

  if ($selstats !== '%') {
    $printedIsApprove = ' and deducted=' . $selStat . ' ';
  }
}

if (strlen($_GET['txtDate']) == 0 or strlen($_GET['txtDate2']) == 0) {
  //$txtDate = date("m/d/Y");
  //$txtDate2 = date("m/d/Y");
  $txtDate = '';
  $txtDate2 = '';
  //$qrydm_date = "";
} else {
  $txtDate = date("Ymd", strtotime($_GET['txtDate']));
  $txtDate2 = date("Ymd", strtotime($_GET['txtDate2']));
}


if ($_GET[vendor] != '') {
  $vendor = trim($_GET[vendor]);
  $cntlen = count_len($vendor);
  $cnt = (int)strlen($vendor) - (int)$cntlen;
  $cntx = $cnt - 2;   //removing space and -
  $vcode = substr($vendor, $cnt, $cntlen);
  $qry_vcode = " and vendorcode like '{$vcode}' ";

  include("sqlconn.php");
} else {
  $vendor     = "";
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

if (strlen($_GET['cmdsearch']) != 0) {
  $cmdsearch = trim($_GET['cmdsearch']);
} else {
  $cmdsearch = '';
}

if (strlen($_GET['cmdnew']) != 0) {
  $cmdnew = trim($_GET['cmdnew']);
} else {
  $cmdnew = "";
}

if (strlen($_GET['cmdpost']) != 0) {
  $cmdpost = trim($_GET['cmdpost']);
} else {
  $cmdpost = "";
}

if (strlen($_GET['cmdcancel']) != 0) {
  $cmdcancel = trim($_GET['cmdcancel']);
} else {
  $cmdcancel = "";
}

if (strlen($_GET['cmdextract']) != 0) {
  $cmdextract = trim($_GET['cmdextract']);
} else {
  $cmdextract = "";
}

if (strlen($_GET['cmdreport']) != 0) {
  $cmdreport = trim($_GET['cmdreport']);
} else {
  $cmdreport = "";
}

if (strlen($_GET['cmdlogout']) != 0) {
  $cmdlogout = trim($_GET['cmdlogout']);
} else {
  $cmdlogout = "";
}

if ($_GET['markall'] != '') {
  $markall = $_GET['markall'];
  $chk  = $_GET['markall'];
}

if ($_GET['chk'] != '') {
  $chk = $_GET['chk'];
} else {
  if ($_GET['chk'] != '') {
    $chk = $_GET['chk'];
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
} else {
  $lststatus = "%";
}

$branchesListing = '';
if (0 === strripos($branch, 'OB')) {
  $cleanBranch = str_replace('OB-', '', $branch);
  $branchesListing = '\'' . implode('\', \'', $subSitesList[$cleanBranch]) . '\'';
}

$branchesListing = (empty($branchesListing)) ? '\'' . $branch . '\'' : $branchesListing;
$branchCondition = ($branch === '%') ? 'like' : 'in';

/*switch($lststatus) {
  case "%": //for all status
    $qrystat = "and vposted like '%' ";
    $dateshow = 'Date Encoded';
    $qrydate = " convert(char(12),a.dm_date,101) as dm_date, ";
    $qrydmno = " a.dm_no_acctg as dmno, '' as cvnumber, ";
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
    $qryDsp = " select dm_ctrl_no from deduction_slip_print_details dspd left join deduction_slip_prints dsp on dspd.deduction_slip_prints_id=dsp.id
      where dsp.branch like '{$branch}' :date ";
    if(@$_GET['txtDate'] or @$_GET['txtDate2'])
      $qryDsp = str_replace(':date', " and dsp.printed_date between convert(char(8), cast('{$txtDate}' as datetime), 112) and convert(char(8), cast('{$txtDate2}' as datetime), 112) ", $qryDsp);
    else
      $qryDsp = str_replace(':date', '', $qryDsp);

    //echo $qryDsp;
    $rstDsp = mssql_query($qryDsp);
    $rstCtrlNo = array();
    while($rsDsp = mssql_fetch_object($rstDsp))
    {
      $rstCtrlNo[] = $rsDsp->dm_ctrl_no;
    }

    break;
}*/

switch ($lststatus) {
  case "%": //for all status
    $stat = "
    CASE
      WHEN a.vposted = 1
          OR a.vposted = 0 THEN 'Approved'
      WHEN a.vposted = 2 THEN 'Cancelled'
    END AS status,";
    $qrystat = "and vposted like '%' ";
    $dateshow = 'Date Encoded';
    $qrydate = " convert(char(8),a.dm_date,112) as dm_date, ";
    $qrydmno = " a.dm_no_acctg as dmno, ";
    if (strlen($_POST['txtDate']) > 0 or strlen($_POST['txtDate2']) > 0) {
      $qrydm_date = " and convert(char(8),dm_date,112) between convert(char(8), cast('{$txtDate}' as datetime), 112) and convert(char(8), cast('{$txtDate2}' as datetime), 112)";
    } else {
      $qrydm_date = " and convert(char(8),dm_date,112) between convert(char(8), cast('{$txtDate}' as datetime), 112) and convert(char(8), cast('{$txtDate2}' as datetime), 112)";
    }
    break;
  case " ": //for all status
    $stat = "'New' as status,";
    $qrystat = "and vposted like '%' ";
    $dateshow = 'Date Encoded';
    $qrydate = " convert(char(8),a.dm_date,112) as dm_date, ";
    $qrydmno = " a.dm_no_acctg as dmno, ";
    if (strlen($_POST['txtDate']) > 0 or strlen($_POST['txtDate2']) > 0) {
      // $qrydm_date = " and convert(char(12),dm_date,101) between '{$txtDate}' and '{$txtDate2}'";
      $qrydm_date = " and convert(char(8),dm_date,112) between convert(char(8), cast('{$txtDate}' as datetime), 112) and convert(char(8), cast('{$txtDate2}' as datetime), 112)";
    } else {
      $qrydm_date = " and convert(char(8),dm_date,112) between convert(char(8), cast('{$txtDate}' as datetime), 112) and convert(char(8), cast('{$txtDate2}' as datetime), 112)";
    }
    break;
  case "0": //for unposted
    $stat = "'New' as status,";
    $qrystat = " and vposted = 0 ";
    $dateshow = 'Date Encoded';
    $qrydate = " convert(char(8),a.dm_date,112) as dm_date, ";
    $qrydmno = " a.dm_no_acctg as dmno, ";
    if (strlen($_POST['txtDate']) > 0 or strlen($_POST['txtDate2']) > 0) {
      // $qrydm_date = " and convert(char(12),dm_date,101) between '{$txtDate}' and '{$txtDate2}'";
      $qrydm_date = " and convert(char(8),dm_date,112) between convert(char(8), cast('{$txtDate}' as datetime), 112) and convert(char(8), cast('{$txtDate2}' as datetime), 112)";
    } else {
      $qrydm_date = " and convert(char(8),dm_date,112) between convert(char(8), cast('{$txtDate}' as datetime), 112) and convert(char(8), cast('{$txtDate2}' as datetime), 112)";
    }
    break;
  case "1": //for approved
    $stat = "'Approved' as status,";
    $qrystat = " and vposted = 1 ";
    $dateshow = 'Approved Date';
    $qrydate = " convert(char(8),a.review_date,112) as dm_date, ";
    $qrydmno = " a.dm_no_acctg as dmno, ";
    if (strlen($_POST['txtDate']) > 0 or strlen($_POST['txtDate2']) > 0) {
      // $qrydm_date = " and convert(char(12),review_date,101) between '{$txtDate}' and '{$txtDate2}'";
      $qrydm_date = " and convert(char(8),dm_date,112) between convert(char(8), cast('{$txtDate}' as datetime), 112) and convert(char(8), cast('{$txtDate2}' as datetime), 112)";
    } else {
      $qrydm_date = " and convert(char(8),dm_date,112) between convert(char(8), cast('{$txtDate}' as datetime), 112) and convert(char(8), cast('{$txtDate2}' as datetime), 112)";
    }
    break;
  case "2": //for cancelled
    $stat = "'Cancelled' as status,";
    $qrystat = " and vposted = 2 ";
    $dateshow = 'Cancelled Date';
    $qrydate = " convert(char(8),a.cancel_date,112) as dm_date, ";
    $qrydmno = " a.dm_no_acctg as dmno, ";
    if (strlen($_POST['txtDate']) > 0 or strlen($_POST['txtDate2']) > 0) {
      // $qrydm_date = " and convert(char(12),cancel_date,101) between '{$txtDate}' and '{$txtDate2}'";
      $qrydm_date = " and convert(char(8),dm_date,112) between convert(char(8), cast('{$txtDate}' as datetime), 112) and convert(char(8), cast('{$txtDate2}' as datetime), 112)";
    } else {
      $qrydm_date = " and convert(char(8),dm_date,112) between convert(char(8), cast('{$txtDate}' as datetime), 112) and convert(char(8), cast('{$txtDate2}' as datetime), 112)";
    }
    break;
  case "3": //for extracted
    $stat = "'Extracted' as status,";
    $qrystat = " and vposted = 3 ";
    $dateshow = 'Extracted Date';
    $qrydate = " convert(char(8),a.extracted_date,112) as dm_date, ";
    $qrydmno = " a.dm_no_acctg as dmno, ";
    if (strlen($_POST['txtDate']) > 0 or strlen($_POST['txtDate2']) > 0) {
      // $qrydm_date = " and convert(char(12),extracted_date,101) between '{$txtDate}' and '{$txtDate2}'";
      $qrydm_date = " and convert(char(8),dm_date,112) between convert(char(8), cast('{$txtDate}' as datetime), 112) and convert(char(8), cast('{$txtDate2}' as datetime), 112)";
    } else {
      $qrydm_date = " and convert(char(8),dm_date,112) between convert(char(8), cast('{$txtDate}' as datetime), 112) and convert(char(8), cast('{$txtDate2}' as datetime), 112)";
    }
    break;
  case "4": //for printed
    $stat = "'Printed' as status,";
    $qrystat = " and vposted not in (0, 2, 3) ";
    $qrydate = " convert(char(8),a.dm_date,112) as dm_date, ";
    $qrydmno = " a.dm_no_acctg as dmno, ";
    $qryDsp = " select dm_ctrl_no from deduction_slip_print_details dspd left join deduction_slip_prints dsp on dspd.deduction_slip_prints_id=dsp.id
      where dsp.branch :branch :date ";
    // like '{$branch}'
    $qryDsp = str_replace(':branch', ($branchCondition === 'like') ? 'like' . $branchesListing :
      'in (' . $branchesListing . ')', $qryDsp);

    if ($_GET['txtDate'] or $_GET['txtDate2'])
      $qryDsp = str_replace(':date', " and dsp.printed_date between
        cast(convert(char(8), cast('{$txtDate}' as datetime), 112) as bigint)
        and cast(convert(char(8), cast('{$txtDate2}' as datetime), 112) as bigint) ", $qryDsp);
    else
      $qryDsp = str_replace(':date', '', $qryDsp);

    $rstDsp = mssql_query($qryDsp);
    $rstCtrlNo = array();
    while ($rsDsp = mssql_fetch_object($rstDsp)) {
      $rstCtrlNo[] = $rsDsp->dm_ctrl_no;
    }
    break;
}

$lcbuyeridx = trim($lcbuyerid);
$buyers = ('' !== trim($lcbuyerid) and !is_null($lcbuyerid)) ? ' and a.buyerid=\'' . $lcbuyeridx . '\' ' : '';

/*switch($lcaccrights) {
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
    //$qrystat = " and vposted = 1 ";
    break;

}*/

switch ($lcaccrights) {
  case 1:  //admin
    $cmddisabled  = '';           //encode new deduction button
    $cmddisabled1 = '';           //cancel button
    $cmddisabled2 = '';           //review button
    $cmddisabled3 = '';           //extract button
    break;
  case 2:  //encoder
    $cmddisabled  = '';
    $cmddisabled1 = '';
    $cmddisabled2 = 'disabled="disabled"';
    $cmddisabled3 = 'disabled="disabled"';
    if (trim($_SESSION['dept_code']) == 'MKT') {
      if ($euser == '%') {
        $auser = "and a.encoded_by in (select user_name from ref_users where dept_code in ('PUR','MKT'))";
      } else {
        $auser = "and a.encoded_by like '{$euser}'";
      }
    } else {
      $auser = "and a.encoded_by like '{$lcuser}' $buyers ";
    }
    break;
  case 3:  //reviewer
    $cmddisabled  = 'disabled="disabled"';
    $cmddisabled1 = 'disabled="disabled"';
    $cmddisabled2 = 'disabled="disabled"';
    $cmddisabled3 = 'disabled="disabled"';
    if ($euser == '%') {
      $auser = "and a.encoded_by like '{$euser}' ";
    } else {
      $auser = "and a.encoded_by = '{$euser}'";
    }
    break;
  case 4:  //supervisor
    $cmddisabled  = '';           //encode new deduction button
    $cmddisabled1 = '';           //cancel button
    $cmddisabled2 = '';           //review button
    $cmddisabled3 = 'disabled="disabled"';
    if ($euser == '%') {
      $auser = "and a.encoded_by in (select user_name from ref_supervisor where supervisor = '{$lcuser}') ";
    } else {
      $auser = "and a.encoded_by like '{$euser}'";
    }
    break;
  case 5:  //audit user (view all branch, all user but can't edit or cancel)
    $cmddisabled = 'disabled="disabled"';
    $cmddisabled1 = 'disabled="disabled"';
    $cmddisabled2 = 'disabled="disabled"';
    $cmddisabled3 = '';
    //$auser = "and encoded_by like '{$euser}'";
    if ($euser == '%') {
      $auser = "and a.encoded_by like '%' ";
    } else {
      $auser = "and a.encoded_by like '{$euser}'";
    }
    //$qrystat = " and vposted = 1 ";
    $sqlqry1 = "execute ds_update_deducted_AP '{$txtDate}','{$txtDate2}'";
    $rsqry1 = @mssql_query($query1);

    $sqlqry2 = "execute ds_update_deducted_OD '{$txtDate}','{$txtDate2}'";
    $rsqry2 = @mssql_query($query2);
    break;
}

// ops and branch s399 are not allowed to print ds
$cmdPrintDs = ($_SESSION['dept_code'] === 'OPS' and $xbranch !== 'S399') ? ' disabled="disabled" ' : '';
$cmddisabledx = $cmddisabled2;

if ($lcdeptname === 'Marketing')
  $cmddisabledx = '';

//period=<?php echo $namemonth&d=<?php echo $d&

if ($_GET['s'] == 1) {
} else {
  $pageNumber = htmlspecialchars(strip_tags($_GET['txthiddenpageno']), ENT_QUOTES);

  /*$condition = (!is_null(@$_GET['cmbstat'])) ? " case when a.vposted = 0 then 'New' else
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
    isnull(h.buyer_code,'') as buyer_code,ltrim(rtrim(a.department))+'-'+ltrim(rtrim(j.deptname)) as itmdept,isnull(i.paymentdesc,'') as paymentdesc,a.period,
    case when a.remarks1 is null then a.remarks else a.remarks1 end as remarks,ltrim(rtrim(a.vendorcode))+' '+ltrim(rtrim(a.suppliername)) as suppliername,
    isnull(a.cancel_remarks,'') as cancel_remarks,a.encoded_by,isDMprinted,isPosted,
    convert(char(8), a.review_date, 112) as review_date, {$prDate}
    dsp.printed_date, dspd.batch, convert(char(8), cancel_date, 112) as cancelleddate,a.lbr_number ";

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
    left join ref_prod_dept j on j.deptcode = a.department
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
  /* $selqry   = str_replace(':printed', " and ( a.dm_no in ('".implode('\', \'', $rstCtrlNo)."') and a.dm_no<>' ' ) ", $selqry);
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
  $mainQry   = str_replace(':endbrace', ')', $mainQry);*/


  $select = " distinct a.dm_no,{$qrydmno}case when a.ap_od = 'AP' then j.aptno when a.ap_od = 'Other Deduction' then dod.voucher_or_no else '' end as cvno,case when ltrim(rtrim(a.branch_code)) = 'S399' then 'S306' else a.branch_code end as branch_code,c.dept_code,d.division_code,ltrim(rtrim(a.vendorcode)) as vpcode, ltrim(rtrim(a.suppliername)) as vpname,e.category_name,f.subcat_name,a.promo,
    {$qrydate}
    a.amount,
    {$stat}isnull(h.buyer_code,'') as buyer_code,ltrim(rtrim(a.department))+ ' - '+ltrim(rtrim(k.deptname)) as department,
    isnull(i.paymentdesc,'') as paymentdesc,a.period,
    case when a.remarks1 is null then a.remarks else a.remarks1 end as remarks,isnull(a.cancel_remarks,'') as cancel_remarks,a.encoded_by,review_by,
  case when a.dm_no_acctg = '' or a.dm_no_acctg is null or a.vposted = 2 then a.vposted else 4 end as vposted,isDMprinted,isPosted,
    isnull(h.buyer_code,'') as buyer_code,convert(char(8), a.review_date, 112) as review_date,
    dsp.printed_date, dspd.batch, convert(char(8), cancel_date, 112) as cancelleddate, a.remarks1, a.paymentid, conspo.main_site,
    case when j.aptno is null or j.aptno = '' then 0 else 1 end as deducted,a.encoded_date,a.lbr_number ";

  $where = " a.branch_code :branch and a.dept_code like '{$dept_code}' and
    a.division_code like '{$division_code}' and
    a.category_code like '{$category_code}' and a.subcat_code like '{$subcat_code}'
    {$qrydm_date} {$auser} {$qrystat} {$qryfile} :vcode and
    a.division_code not in ('BO') :deducted ";

  $where = str_replace(':vcode', ($isConsPo) ? $consPo : $qry_vcode, $where);

  $where = str_replace(':branch', ($branchCondition === 'like') ? 'like' . $branchesListing :
    'in (' . $branchesListing . ')', $where);

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
    left join arms.dbo.accountspayable j on a.dm_no_acctg = j.aptno
    left join ref_prod_dept k on a.department = k.deptcode
    left join debitmemo_otherdeduct dod on a.branch_code = dod.branch_code and a.dm_no_acctg = dod.dmno
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

  if ($selstats !== '%') {
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

  if (!empty($rstCtrlNo) or (int) $lststatus === 4) {
    /**
     * March 06, 2013
     * Fix: Query error when trying to implode the list of all ctrlnos
     */
    //$selqry = str_replace(':printed', " and ( a.dm_no in ('".implode('\', \'', $rstCtrlNo)."') and a.dm_no<>' ' ) ", $selqry);
    $selqry = str_replace(':printed', " and ( a.dm_no in ( " . $qryDsp . " ) and a.dm_no<>' ' ) ", $selqry);
    //$mainQry = str_replace(':printed', " and ( a.dm_no in ('".implode('\', \'', $rstCtrlNo)."') and a.dm_no<>' ' ) ", $mainQry);
    $mainQry = str_replace(':printed', " and ( a.dm_no in ( " . $qryDsp . " ) and a.dm_no<>' ' ) ", $mainQry);
  }

  $selqry    = str_replace(':printed', '', $selqry);
  $mainQry   = str_replace(':printed', '', $mainQry);

  $mainQry   = str_replace(':join', '', $mainQry);
  $selqry    = str_replace(':join', '', $selqry);

  $selqry    = str_replace(':brace', '(', $selqry);
  $selqry    = str_replace(':endbrace', ')', $selqry);
  $mainQry   = str_replace(':brace', '(', $mainQry);
  $mainQry   = str_replace(':endbrace', ')', $mainQry);
  mssql_query("SET ANSI_NULLS ON; SET ANSI_WARNINGS ON");
  $recordSet = mssql_query($mainQry);
  mssql_query("SET ANSI_NULLS OFF;SET ANSI_WARNINGS OFF");

  $tCount    = mssql_result($recordSet, 0, 'tcount');

  $pages = ceil($tCount / 10);

  //and left(rtrim(ltrim(a.period)),len(rtrim(ltrim(a.period)))-5) like '{$namemonth}' and right(ltrim(rtrim(period)),4) like '{$d}'  a.isposted like '{$lstisdm}' and
  //echo $selqry;
  //die();
  // var_dump($selqry);
  // die();

  mssql_query("SET ANSI_NULLS ON; SET ANSI_WARNINGS ON");
  $r_select = mssql_query($selqry);
  mssql_query("SET ANSI_NULLS OFF;SET ANSI_WARNINGS OFF");
  $nmrow    = mssql_num_rows($r_select);
}
