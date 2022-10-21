<?php

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <link href="css_js_messagebox/SyntaxHighlighter.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="css_js_messagebox/shCore.js" language="javascript"></script>
  <script type="text/javascript" src="css_js_messagebox/shBrushJScript.js" language="javascript"></script>
  <script type="text/javascript" src="css_js_messagebox/ModalPopups.js" language="javascript"></script>

  <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />

  <link href="css/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css" />
  <script language="javascript" src="js/jquery-min.js"></script>
  <script type="text/javascript" language="javascript" src="js/jquery-ui.js"></script>

  <link href="css_autosuggest/styles.css" rel="stylesheet" type="text/css">
  <link href="css_autosuggest/jquery.autocomplete.css" rel="stylesheet" type="text/css">
  <script type="text/javascript" src="js/jquery.js"></script>
  <script type="text/javascript" src="js/jquery.bgiframe.min.js"></script>
  <script type="text/javascript" src="js/jquery.autocomplete.min.js"></script>

  <script type='text/javascript'>
    $(document).ready(function() {
      var data = "<?php echo $_mdata; ?>".split(";;");
      $('input#idvendor').autocomplete("search.php?dept=<?php echo $lcdeptcode; ?>&div=<?php echo $lcdivision; ?>", {
        matchContains: true,
        max: 0,
        minChars: 3,
        select: function(e, u) {
          $(this).val(u.item.value);
        }
      });

      $('input#idvendor').blur(function() {
        if ($.trim($(this).val()) !== '')
          $('#myform').submit();
      })


    });
  </script>
  <style>
    table.table tr,
    table.table tr td {
      padding-bottom: 0 !important;
      padding-top: 0 !important;
    }

    input,
    select {
      margin-bottom: 5px !important;
      margin-top: 5px !important;
    }

    .navbar-inner {
      background-color: #2E0D23;
      background-image: -moz-linear-gradient(top, #333333, #2E0D23);
      background-image: -ms-linear-gradient(top, #333333, #2E0D23);
      background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#333333), to(#2E0D23));
      background-image: -webkit-linear-gradient(top, #333333, #2E0D23);
      background-image: -o-linear-gradient(top, #333333, #2E0D23);
      background-image: linear-gradient(top, #333333, #2E0D23);
      background-repeat: repeat-x;
      filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#333333', endColorstr='#2E0D23', GradientType=0);
      border-bottom: solid 1px #F7803C;
      -webkit-box-shadow: none;
      -moz-box-shadow: none;
      box-shadow: none;
    }

    .style1 {
      color: red;
      font-weight: 900;
    }
  </style>

  <title>Deduction Data Entry</title>

  <script type="text/javascript" language="javascript">
    function ModalPopupsAlert1() {
      ModalPopups.Alert("jsAlert1",
        "System Message",
        "<div style='padding:50px;'>Record successfully saved! <br />[Control Number : " + document.getElementById("iddmno").value + "]<br /><br />Click OK to encode another or click OK, then, click CLOSE button to stop encoding.</div>",


        {
          okButtonText: "OK",
          onOk: "ModalPopupsClose()"
        }
      );
    }

    function ModalPopupsAlert2() {
      ModalPopups.Alert("jsAlert2",
        "System Message",
        "<div style='padding:30px;'>Please Fillup all required fields or verify your vendor...</div>", {
          okButtonText: "CLOSE"
        }
      );
    }

    function ModalPopupsAlert3() {
      ModalPopups.Alert("jsAlert3",
        "System Message",
        "<div style='padding:30px;'>Wrong vendor code or vendor name, Please select again!!!...</div>", {
          okButtonText: "CLOSE"
        }
      );
    }

    function ModalPopupsClose() {
      ModalPopups.Close("jsAlert1");
      //window.close('dm_new.php')
      //document.getElementById("idvendorcode").value = '';
      document.getElementById("idvendor").value = '';
      document.getElementById("idamount").value = 0.00;

    }
  </script>

</head>

<script type="text/javascript">
  $(function() {
    //$('#txtAdate, #txtAdate2').datepicker({
    // defaultDate: "+1w"
    //});
    //$('#cmdidsaved, #cmdidgo').button();
    //$('input:submit').button();
    //$('#cmdidsaved').button({
    //    icons:{
    //        primary: 'ui-icon-wrench'
    //    }
    //});
  });
  ///function for imposing maximum character for textarea remarks
  function imposeMaxLength(Object, MaxLen) {
    return (Object.value.length <= MaxLen);
  }

  //txtAdate2
</script>
<script type="text/javascript" src="css_js_messagebox/shInit.js"></script>

<?php
error_reporting(E_ALL ^ E_NOTICE);
include('sqlconn.php');
include('function.php');

if ($_POST['branch'] != '') {
  $branch = trim($_POST['branch']);
} else {
  $branch = $glbranchcode;
}

if ($_POST['dept_code'] != '') {
  $dept_code = trim($_POST['dept_code']);
} else {
  $dept_code = $lcdeptcode;
}

if ($_POST['division_code'] != '') {
  $division_code = trim($_POST['division_code']);
} else {
  if ($lcdivision == 'BO') {
    $division_code = 'SP';
  } else {
    $division_code = $lcdivision;
  }
}

if ($_POST['category_code'] != '') {
  $category_code = trim($_POST['category_code']);
} else {
  $category_code = "";
}

if ($_POST['subcat_code'] != '') {
  $subcat_code = trim($_POST['subcat_code']);
  $xx = "select subcat_name from ref_subcategory where isactive = 1 and subcat_code = '{$subcat_code}'";
  $yy = mssql_query($xx);
  $zz = mssql_fetch_array($yy);
  $subcat_name = $zz['subcat_name'];
  $lensname = strlen($subcat_name);
  $remlenght = 45 - (int)$lensname;
  //$remcnt = (int)strlen($_POST[txtremarks]);
  if ($subcat_name == substr(trim($_POST['txtremarks']), 0, $lensname)) {
    $flag = 1;
    $txtremarks = '';
  } else {
    $flag = 0;
    $txtremarks = '';
  }

  if ($_POST['txtremarks'] != '' and $flag == 1) {
    $txtremarks = trim($_POST['txtremarks']);
  } elseif ($_POST['txtremarks'] != '' and $flag == 0) {
    $txtremarks = trim($_POST['txtremarks']);
  } //elseif ($_POST[txtremarks] == '' and $flag == 0  ) {
  //    $txtremarks = $subcat_name ;
  //  }

} else {
  $subcat_code = "";
  $txtremarks = "";
}

if ($_POST['disparea'] != '') {
  $disparea = trim($_POST['disparea']);
} else {
  $disparea = "";
}

if ($_POST['lstbuyer'] != '') {
  $lstbuyer = trim($_POST['lstbuyer']);
} else {
  $lstbuyer = "";
}

if ($_POST['vendor'] != '') {
  $vendor = trim($_POST['vendor']);
} else {
  $vendor = "";
}

if (strlen($_POST['txtDate']) == 0) {
  $txtDate = date("m/d/Y");
} else {
  $txtDate = $_POST['txtDate'];
}

if ($_POST['payment'] > 0) {
  $payment = $_POST['payment'];
} else {
  $payment = '';
}

//if ($payment == '1') {
if ($_POST['itmdept'] != '') {
  $itmdept = trim($_POST['itmdept']);
} else {
  $itmdept = '';
}
//} else {
//  $itmdept = '' ;
//}

if ($_POST['period'] != '') {
  $period = $_POST['period'];
} else {
  $period = '';
}

if ($_POST['txtamount'] > 0) {
  if (!is_numeric($_POST['txtamount'])) {
    $txtamount1 = str_replace(',', '', $_POST['txtamount']);
    $txtamount = (float)$txtamount1;
  } else {
    $txtamount = $_POST['txtamount'];
  }
} else {
  $xvalue = 0.00;
  $txtamount = number_format($xvalue, 2);
}

if (strlen($_POST['cmdsaved']) != 0) {
  $cmdsaved = trim($_POST['cmdsaved']);
} else {
  $cmdsaved = "";
}

if (strlen($_POST['cmdclose']) != 0) {
  $cmdclose = trim($_POST['cmdclose']);
} else {
  $cmdclose = "";
}
if ($_POST['txtPromo']) {
  $cmdPromo = $_POST['txtPromo'];
}


if ($cmdclose == 'Close') {
?>
  <script>
    window.close('dm_new.php')
  </script>
<?php
}

// vendor checker if included in consolidatepo
$isConsoPo = FALSE;

if (trim($vendor) !== '') {
  $vC = trim(end(explode('-', $vendor)));

  // this is just
  $subBr = ($branch === 'S399') ? 'S309' : $branch;
  $mQry = "select a.*, b.main_site, b.main_name from
    everlyl_conspo.consolidatepo.dbo.sitegroup_vendors a
    left join everlyl_conspo.consolidatepo.dbo.sitegroup b
    on a.sub_site=b.sub_site
    where a.vendor_code='$vC' and a.sub_site='$subBr' and a.is_active = 1 ";

  $mRs  = mssql_query($mQry);
  $numRows = mssql_num_rows($mRs);

  if ($numRows > 0)
    $isConsoPo = TRUE;

  $siteDetails = array();

  while ($mResults = mssql_fetch_object($mRs))
    $siteDetails[] = $mResults;
}

?>
<style type="text/css"></style>

<body bgcolor="#5D7AAD" style="padding-top: 41px;">

  <div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
      <div class="container">
        <div class="span12" style="margin-left: 0;">
          <ul class="nav">
            <li class="active"><a href="#"><i class="icon-remove-circle icon-white"></i> <strong>Close</strong></a></li>
            <li class="active"><a href="#"><i class="icon-edit icon-white"></i> <strong>Save</strong></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div id="wrapper" class="row-fluid">
    <div id="content" class="span12">

      <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" id='myform'>
        <!---->
        <table class="table">
          <?php
          if ($lcaccrights == 4) { ?>
            <tr>
              <td width="5%">&nbsp;</td>
              <td width="20%">Division</td>
              <td width="5%">:</td>
              <td width="65%" bgcolor="#ffffff"><select name="division_code" onChange="submit()" onkeypress="return ignoreenter(this,event)" style="width: 100%;" tabindex="2">
                  <?php if ($lcdeptcode == 'ACT') {
                    $seldiv = "select  division_code,division_name from ref_division where division_code not in ('BO') order by division_code ";
                  } elseif ($lcdeptcode == 'EDP') {
                    $seldiv = "select  division_code,division_name from ref_division where division_code not in ('BO','SP') order by division_code ";
                  } else {
                    $seldiv = "select  division_code,division_name from ref_division where division_code not in ('BO','DS') order by division_code ";
                  }

                  $r_seldiv = mssql_query($seldiv);
                  for ($i = 0; $i < ($b_row = mssql_fetch_array($r_seldiv)); $i++) {
                    $selected = "";
                    if (trim($b_row['division_code']) == trim($division_code)) {
                      $selected = 'selected="selected"';
                    }
                  ?>
                    <option value="<?php echo $b_row['division_code'] ?>" <?php echo $selected ?>> <?php echo $b_row['division_code'] ?> - <?php echo $b_row['division_name'] ?> </option>
                  <?php

                  } ?>
                </select></td>
              <td width="5%"><span class="style1">*</span></td>
            </tr>
          <?php
          } elseif ($lcaccrights == 2 and $xbranch == 'S399') { ?>
            <tr>
              <td width="5%">&nbsp;</td>
              <td width="20%">Division</td>
              <td width="5%">:</td>
              <td width="65%" bgcolor="#ffffff"><select name="division_code" onChange="submit()" onkeypress="return ignoreenter(this,event)" style="width: 100%;" tabindex="2">
                  <?php
                  if ($lcdeptcode == 'ACT') {
                    $seldiv = "select  division_code,division_name from ref_division where division_code not in ('BO') order by division_code ";
                  } elseif ($lcdeptcode == 'EDP') {
                    $seldiv = "select  division_code,division_name from ref_division where division_code not in ('BO','SP') order by division_code ";
                  } else {
                    $seldiv = "select  division_code,division_name from ref_division where division_code not in ('BO','DS') order by division_code ";
                  }

                  $r_seldiv = mssql_query($seldiv);
                  for ($i = 0; $i < ($b_row = mssql_fetch_array($r_seldiv)); $i++) {
                    $selected = "";
                    if (trim($b_row['division_code']) == trim($division_code)) {
                      $selected = 'selected="selected"';
                    } ?>
                    <option value="<?php echo $b_row['division_code'] ?>" <?php echo $selected ?>> <?php echo $b_row['division_code'] ?> - <?php echo $b_row['division_name'] ?> </option>
                  <?php

                  } ?>
                </select></td>
              <td width="5%"><span class="style1">*</span></td>
            </tr>
          <?php
          } ?>
          <?php
          if (($lcaccrights == 4 or $lcaccrights == 2) and $xbranch == 'S399') { ?>
            <tr>
              <td width="5%">&nbsp;</td>
              <td width="20%">Branch</td>
              <td width="5%">:</td>
              <td width="65%"><select name="branch" onkeypress="return ignoreenter(this,event)" style="width: 100%;" tabindex="1">
                  <?php
                  if ($xbranch == 'S399' and $lcdeptcode == 'ACT' and $lcdivision == 'BO') {
                    if ($division_code == 'SP') {
                      $seluser = "select branch_code,branch_name from ref_branch where isactive = 1 and branch_code not in ('S801','S802','S803', 'S301') order by branch_code";
                    } else {
                      $seluser = "select branch_code,branch_name from ref_branch where isactive = 1 and branch_code in ('S801','S802','S803', 'S301') order by branch_code";
                    }
                  } elseif ($xbranch == 'S399' and $lcdeptcode == 'EDP' and $lcdivision == 'BO') {
                    $seluser = "select branch_code,branch_name from ref_branch where isactive = 1 and branch_code in ('S801','S802','S803', 'S301') order by branch_code";
                  } else {
                    $seluser = "select distinct branch_code,branch_name from ref_branch where isactive = 1 and branch_code not in ('S801','S802','S803', 'S301')
                :where order by branch_code";

                    if ((int) $lcaccrights === 4 and $lcdeptcode === 'OPS')
                      $seluser = str_replace(':where', ' and branch_code in (select distinct branch_code from ref_supervisor where supervisor=\'' . $lcuser . '\')', $seluser);

                    $seluser = str_replace(':where', '', $seluser);
                  }

                  $mSites = array_unique($uniqueMainSites);
                  $rsuser = mssql_query($seluser);
                  $mData = array();
                  while ($b_row = mssql_fetch_array($rsuser)) {
                    $mData[] = $b_row;
                  }

                  foreach ($mData as $m) {
                    $selected = "";
                    if (trim($m['branch_code']) == trim($branch)) {
                      $selected = 'selected="selected"';
                    }

                    echo "<option value='" . trim($m['branch_code']) . "' " . $selected . ">" . $m['branch_name'] . "</option>";
                  ?>
                    <!-- <option value="<?php echo $b_row['branch_code'] ?>" <?php echo $selected ?>   > -->
                    <?php //echo $b_row['branch_name']
                    ?> </option>
                  <?php
                  } ?>
                </select>
              </td>
              <td width="5%"><span class="style1">*</span></td>
            </tr>
          <?php
          } ?>
          <tr>
            <td>&nbsp;</td>
            <td><input type="checkbox" style="float: right;" id="chkid" name="chk" value="0">Vendor</td>
            <td>:</td>
            <td bgcolor="#ffffff">
              <div>
                <input type="text" name="vendor" id="idvendor" value="<?php echo $vendor; ?>" tabindex="8" onchange="javascript: submit();" />
              </div>
            </td>
            <td><span class="style1">*</span></td>
          </tr>
          <?php

          if ($siteDetails[0]->main_site === $siteDetails[0]->sub_site) :

          else :
            if ($isConsoPo) :
              if (count($siteDetails) > 0) :
          ?>
                <tr>
                  <td colspan="5" style="text-align:center;color:#222;font-family:Tahoma;">
                    This transaction will be deducted to <strong><?php echo trim($siteDetails[0]->main_name); ?></strong>.
                  </td>
                </tr>
          <?php
              endif;
            endif;
          endif
          ?>
          <!--<tr>
            <td>&nbsp;</td>
            <td>Department</td>
            <td>:</td>
            <td bgcolor="#C5DFE0"><select name="dept_code" onkeypress="return ignoreenter(this,event)" style="width: 100%;" tabindex="2">
              <?php
              //if ($glbranchcode == 'HO') {
              ?>
              <option value="">- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Select Department&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -</option>
              <?php
              //$seldept = "select  dept_code,dept_name from ref_department order by dept_code ";
              //}else{
              //$seldept = "select  dept_code,dept_name from ref_department where dept_code = '{$lcdeptcode}' order by dept_code ";
              //}
              //$r_seldept = mssql_query($seldept);
              //for ($i = 0; $i < ($b_row = mssql_fetch_array($r_seldept)); $i++)
              //{
              //  $selected = "";
              //  if (trim($b_row['dept_code']) == trim($dept_code)) {
              //    $selected = 'selected="selected"';
              //  }
              ?>
              <option value="<?php //echo $b_row['dept_code']
                              ?>"
                <?php //echo $selected
                ?>   > <?php //echo $b_row['dept_code']
                        ?> - <?php //echo $b_row['dept_name']
                              ?> </option>
              <?php

              //}
              ?>
            </select></td>
            <td><span class="style1">*</span></td>
          </tr>  -->
          <tr>
            <td>&nbsp;</td>
            <td>Category</td>
            <td>:</td>
            <td bgcolor="#ffffff"><select name="category_code" onChange="submit()" onkeypress="return ignoreenter(this,event)" style="width: 100%;" tabindex="3">
                <option value="">- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Select Category&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -</option>
                <?php
                if ($lcdeptcode == 'PUR') {
                  $selcat = "select category_code,category_name from ref_category where isactive = 1 and division_code like '{$division_code}' and dept_code = 'PUR' and isLBR = 0 order by category_name ";
                } elseif ($lcdeptcode == 'MKT') {
                  $selcat = "select category_code,category_name from ref_category where isactive = 1 and dept_code in ('PUR','MKT') and isLBR = 0  order by category_name ";
                } elseif ($lcdeptcode == 'EDP') {
                  $selcat = "select category_code,category_name from ref_category where isactive = 1 and division_code = 'DS' and isLBR = 0 order by category_name ";
                } else {
                  // comment this if statement after roces edit or encode DS for the month of August.
                  if ($branch == 'S342') {
                    // comment this area after roces edit or encode DS for the month of August.
                    $selcat = "select category_code,category_name from ref_category where isactive = 1 and division_code like '{$division_code}' order by category_name ";
                  } else {
                    $selcat = "select category_code,category_name from ref_category where isactive = 1 and division_code like '{$division_code}' and isLBR = 0  order by category_name ";
                  }
                }
                $r_selcat = mssql_query($selcat);
                for ($i = 0; $i < ($b_row = mssql_fetch_array($r_selcat)); $i++) {
                  $selected = "";
                  if (trim($b_row['category_code']) == trim($category_code)) {
                    $selected = 'selected="selected"';
                  }
                ?>
                  <option value="<?php echo $b_row['category_code'] ?>" <?php echo $selected ?>><?php echo $b_row['category_name'] ?> </option>
                <?php

                } ?>
              </select></td>
            <td><span class="style1">*</span></td>
          </tr>
          <tr>
            <td height="31">&nbsp;</td>
            <td>Sub Category </td>
            <td>:</td>
            <td bgcolor="#ffffff"><select name="subcat_code" onkeypress="return ignoreenter(this,event)" onChange="submit();" style="width: 100%;" tabindex="4">
                <option value=""> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </option>
                <?php
                $selsubcat = "select subcat_code,subcat_name from ref_subcategory where isactive = 1 and category_code = '{$category_code}' order by subcat_name ";
                $r_selsubcat = mssql_query($selsubcat);
                for ($i = 0; $i < ($b_row = mssql_fetch_array($r_selsubcat)); $i++) {
                  $selected = "";
                  if (trim($b_row['subcat_code']) == trim($subcat_code)) {
                    $selected = 'selected="selected"';
                  }

                ?>
                  <option value="<?php echo $b_row['subcat_code'] ?>" <?php echo $selected ?>><?php echo $b_row['subcat_name'] ?> </option>
                <?php
                }
                ?>
              </select></td>
            <td><span class="style1">*</span></td>
          </tr>
          <tr>
            <td height="40">&nbsp;</td>
            <td>Promo</td>
            <td>:</td>
            <td bgcolor="#ffffff">
              <input type="text" name="txtPromo" style="width:100%" value="<?php echo $cmdPromo ?>" onchange="javascript: submit();" />
            </td>
            <td>&nbsp;</td>
          </tr>
          <?php if (trim($subcat_code) == 'S000000005' or trim($category_code) == 'C000000005' or trim($subcat_code) == 'S000000035' or trim($category_code) == 'C000000026') { ?>
            <td height="40">&nbsp;</td>
            <td>DisPlay Area</td>
            <td>:</td>
            <td bgcolor="#ffffff"><input type="text" name="disparea" maxlength="30" size="10" id="iddisparea" value="<?php echo $disparea; ?>" style="height: 18px; text-align:left;" tabindex="9" /></td>
            <td>&nbsp;</td>
            </tr>
          <?php } ?>
          <tr>
            <td>&nbsp;</td>
            <td>Mode of Payment </td>
            <td>:</td>
            <td bgcolor="#ffffff"><select name="payment" onkeypress="return ignoreenter(this,event)" onChange="submit();" tabindex="5">
                <option value=""> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </option>
                <?php
                if ($lcdeptcode == 'ACT') {
                  $selpay = "select paymentid,paymentdesc from ref_payment where paymentid = 2 order by paymentid ";
                } else {
                  $selpay = "select paymentid,paymentdesc from ref_payment order by paymentid ";
                }
                $r_selpay = mssql_query($selpay);
                for ($i = 0; $i < ($b_row = mssql_fetch_array($r_selpay)); $i++) {
                  $selected = "";
                  if (trim($b_row['paymentid']) == trim($payment)) {
                    $selected = 'selected="selected"';
                  }

                ?>
                  <option value="<?php echo $b_row['paymentid'] ?>" <?php echo $selected ?>><?php echo $b_row['paymentdesc'] ?> </option>
                <?php
                }
                ?>
              </select></td>
            <td><span class="style1">*</span></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>Item Department </td>
            <td>:</td>
            <td bgcolor="#ffffff"><select name="itmdept" onkeypress="return ignoreenter(this,event)" onChange="submit();" tabindex="5">
                <option value=""> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </option>
                <?php
                $selitmdept = "select deptcode,ltrim(rtrim(deptcode))+'-'+ltrim(rtrim(deptname)) as deptname from ref_prod_dept where isactive = 1 ";
                $r_selitmdept = mssql_query($selitmdept);
                for ($i = 0; $i < ($itmdept_row = mssql_fetch_array($r_selitmdept)); $i++) {
                  $selected = "";
                  if (trim($itmdept_row['deptcode']) == trim($itmdept)) {
                    $selected = 'selected="selected"';
                  }
                ?>
                  <option value="<?php echo $itmdept_row['deptcode'] ?>" <?php echo $selected ?>><?php echo $itmdept_row['deptname'] ?> </option>
                <?php
                }
                ?>

              </select></td>
            <td><span class="style1">&nbsp;</span></td>
          </tr>

          <?php if ($lcdeptcode == 'MKT') { ?>
            <td height="40">&nbsp;</td>
            <td>Buyer</td>
            <td>:</td>
            <td bgcolor="#ffffff"><select name="lstbuyer" onkeypress="return ignoreenter(this,event)" onChange="submit();" style="width: 100%;" tabindex="5">
                <option value=""> &nbsp;&nbsp;-Select buyer-&nbsp;&nbsp; </option>
                <?php
                $selpay = "select buyerid,buyer_code from ref_buyer order by buyer_code ";

                $r_selpay = mssql_query($selpay);
                for ($i = 0; $i < ($b_row = mssql_fetch_array($r_selpay)); $i++) {
                  $selected = "";
                  if (trim($b_row['buyerid']) == trim($lstbuyer)) {
                    $selected = 'selected="selected"';
                  }

                ?>
                  <option value="<?php echo $b_row['buyerid'] ?>" <?php echo $selected ?>><?php echo $b_row['buyer_code'] ?> </option>
                <?php
                }
                ?>
              </select></td>
            <td>&nbsp;</td>
            </tr>
          <?php } else {
            $qrybuyer = "select isnull(buyerid,'') as buyerid from ref_users where user_name = '{$lcuser}'";
            $rsbuyer = mssql_query($qrybuyer);
            $rowbuyer = mssql_fetch_array($rsbuyer);
            $lstbuyer = $rowbuyer['buyerid'];
          } ?>
          <tr>
            <td height="28">&nbsp;</td>
            <td>Period Covered</td>
            <td>:</td>
            <td><input type="text" name="period" maxlength="60" size="10" id="idperiod" value="<?php echo $period; ?>" style="height: 18px; text-align:left;" tabindex="9" /> </td>
            <!--    <td bgcolor="#C5DFE0"><input name="txtDate" type="text" size="11" maxlength="10" id="txtAdate" readonly="readonly" tabindex="4" onFocus="document.news_edit.reset.focus();" value="<?php //echo txtDate
                                                                                                                                                                                                        ?>" onKeyPress="return numbersonly(this, event)"/></td>-->
            <!--<td>
              <select name="namemonth" onkeypress="return ignoreenter(this,event)" tabindex="6" >
                <?php   //for ($i= 1; $i<= 12; $i++ ) {
                //      $selected = "" ;
                //      $getmonth = date("F", mktime(0, 0, 0, $i, 0, 0));
                //
                //          if ($namemonth == $getmonth){
                //            $selected = 'selected="selected"' ;
                //          }else{
                //            $selected = '';
                //          }
                ?>
                  <option value ="<?php //echo $getmonth 
                                  ?>"<?php //echo $selected 
                                      ?>><?php //echo $getmonth 
                                          ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                <?php  //}  
                ?>
              </select>
            <input name="txtdatefrom" type="text" size="5" maxlength="4" value="<?php //echo $txtdatefrom 
                                                                                ?>" onKeyPress="return numbersonly(this, event)" tabindex="7"/></td>
            <td><span class="style1">*</span></td>-->
            <td>&nbsp;</td>
          </tr>
          <!--  <tr>
            <td height="31">&nbsp;</td>
            <td>Vendor </td>
            <td>:</td>
            <td bgcolor="#ffffff"><div> <small style="float:right">
              <input type="text" name="vendorcode" id="idvendorcode" value="<?php //echo $vendorcode;
                                                                            ?>" style="font-size: 0px; width: 0px;" readonly="yes" />
              </small>
                    <input style="width: 100%" type="text" name="vendor" id="idvendor" value="<?php //echo $vendor;
                                                                                              ?>" tabindex="8" />
            </div></td>
            <td><span class="style1">*</span></td>
          </tr>-->

          <tr>
            <td height="40">&nbsp;</td>
            <td>Amount</td>
            <td>:</td>
            <td bgcolor="#ffffff"><input type="text" name="txtamount" maxlength="10" size="10" id="idamount" value="<?php echo  number_format($txtamount, 2); ?>" style="height: 30px; text-align:right; font-size: 22px;" tabindex="9" /></td>
            <td><span class="style1">*</span></td>
          </tr>

          <tr>
            <td height="49">&nbsp;</td>
            <td>Remarks<br />
              (<?php echo $remlenght; ?> characters)</span></td>
            <td>:</td>
            <td bgcolor="#ffffff"><textarea name="txtremarks" rows="3" cols="45" tabindex="11" onKeyPress="return imposeMaxLength(this, <?php echo (int)$remlenght - 1; ?>);"><?php echo $txtremarks; ?></textarea></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="22">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="5">
              <table class="table">
                <tr>
                  <td width="69%" style="border-top: none;">&nbsp;</td>
                  <td width="15%" style="border-top: none;"><input type="submit" name="cmdsaved" id="cmdidSaved" title="Save" onClick="javascript: submit()" value=" Save " tabindex="12" style="width: 90%;height:40; background-color:#FF9933 " /></td>
                  <td width="16%" style="border-top: none;"><input type="submit" name="cmdclose" id="cmdidClose" title="Close" onClick="javascript: self.close();" value="Close" tabindex="13" style="width: 90%;height:40; background-color:#FF9933;" /></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
        <input type="hidden" name="remlenght" value="<?php echo $remlenght; ?>" id="txtHint" />
      </form>
    </div>
  </div>

  <?php
  //echo $glbranchcode;

  if ($cmdsaved == 'Save') {
    $cntlen = count_len($vendor);
    $cnt = (int) strlen($vendor) - (int)$cntlen;
    $cntx = $cnt - 2;   //removing space and -
    $vcode = substr($vendor, $cnt, $cntlen);
    $vname = substr($vendor, 0, $cntx);
    $ver_v = verify_vendor($vcode);
    $vcode_ischange = vendor_is_change($vcode, $vname);
    include('sqlconn_local.php');
    $qrydmno = "Select dm_no from dm_autoid where branch_code = '{$branch}'";
    $rsdmno = mssql_query($qrydmno);
    $dmno_nmrow = mssql_num_rows($rsdmno);

    $create_dmno = "Select cntl_prefix from ref_branch where branch_code = '{$branch}'";
    $rs_dmno = mssql_query($create_dmno);
    $row_dmno = mssql_fetch_array($rs_dmno);
    if ($dmno_nmrow == 0) {
      $dmno = str_pad(trim($row_dmno['cntl_prefix']), 9, 0) . '1';
    } else {
      $rowdmno = mssql_fetch_array($rsdmno);
      $rowdmno['dm_no'];
      $xdmno = (int)$rowdmno['dm_no'] + 1;
      $ydmno = strlen($xdmno);
      $zdmno = 10 - (int)$ydmno;
      $dmno = str_pad(trim($row_dmno['cntl_prefix']), $zdmno, 0) . $xdmno;
    }

    $vcode_ischange = 'Approved';
    if ($vcode_ischange == 'Approved') {
      $ver_v = 1;
      if (strlen($category_code) > 0 and strlen($subcat_code) > 0 and $txtamount > 0 and strlen($vendor) > 0 and $ver_v > 0 and strlen($vcode) > 0) {

        if ($lcaccrights == 4) {
          $div_code = $division_code;
        } else {
          if ($lcdivision == 'BO') {
            $div_code = $division_code;
          } else {
            $div_code = $lcdivision;
          }
        }

        $tmp_dmno = substr(trim($dmno), -7);
        if ($dmno_nmrow == 0) {
          $update_dmno = "Insert into dm_autoid (branch_code,dm_no) values ('{$branch}','{$tmp_dmno}')";
        } else {
          $update_dmno = "Update dm_autoid set dm_no = '{$tmp_dmno}' where branch_code = '{$branch}'";
        }
        //}
        $rsupdate_dmno = mssql_query($update_dmno);
        $qryins = "Execute dm_insert '{$dmno}','{$branch}','{$div_code}','{$lcdeptcode}',
            '{$category_code}','{$subcat_code}','{$cmdPromo}','{$disparea}','{$vcode}','{$vname}','{$period}',
            '$txtamount','$payment','{$txtremarks}','{$lcuser}','{$lstbuyer}','{$itmdept}'";

        $rsins = mssql_query($qryins);
        $vendor = '';

        // well insert the data into the pivot table.
        // just to be sure we will delete all the instances of that dmno

        if ($isConsoPo) {
          if ($siteDetails[0]->main_site === $siteDetails[0]->sub_site) {
          } else {
            $mQry = "select top 1 main_site from everlyl_conspo.consolidatepo.dbo.sitegroup where sub_site='{$branch}'";
            $mainSiteRs = mssql_query($mQry);
            while ($msRs = mssql_fetch_object($mainSiteRs)) {
              $mQry = " delete from conspo_pivot where dm_no='$dmno'; insert into conspo_pivot (dm_no, main_site) values ('{$dmno}', '{$msRs->main_site}') ";
              mssql_query($mQry);
            }
          }
        }

        //$vendorcode = '';
        $txtamount = 0.00;
        echo '<form method="post" name="form_dm" id="form_dm" class="form_dm">';
        echo '<input type="hidden" name="dmno" value="' . $dmno . '" id="iddmno" />';
        echo '</form>';
        echo '<a href="javascript:ModalPopupsAlert1();">.</a>';
  ?>
        <script type="text/javascript" language="javascript">
          ModalPopupsAlert1()
        </script>
      <?php
        $vendor = '';
        //$vendorcode = '';
        $txtamount = 0.00;
      } else {
        echo '<a href="javascript:ModalPopupsAlert2();">.</a>';
      ?>
        <script type="text/javascript" language="javascript">
          ModalPopupsAlert2()
        </script>
      <?php
      }
    } else {
      echo '<a href="javascript:ModalPopupsAlert3();">.</a>';
      ?>
      <script type="text/javascript" language="javascript">
        ModalPopupsAlert3()
      </script>
  <?php
    }
  }

  ?>


  <!--<script type="text/javascript">
  var options = {
    script:"vendorsugg.php?json=false&",
    varname:"input",
    json:true,
    callback: function (obj) { document.getElementById('idvendorcode').value = obj.id; }
  };
  var as_json = new AutoSuggest('idvendor', options);

  var options_xml = {
    script:"vendorsugg.php?",
    varname:"input"
  };
  var as_xml = new AutoSuggest('testinput_xml', options_xml);
</script>-->

</body>

</html>