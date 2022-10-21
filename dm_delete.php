<?php
session_start();
$x = strlen($_SESSION['user']);
if ($x > 0 ) {
	error_reporting(E_ALL ^ E_NOTICE);
	include('sqlconn.php');
	include('function.php');

	if ($_GET['dmno'] != '') {
		$dmno = trim($_GET['dmno']) ;
	}else {
		if ($_POST['dmno'] != '') {
			$dmno = trim($_POST['dmno']) ;
		}
	}

	if ($_POST['cmdyes'] != '') {
		$cmdyes = trim($_POST['cmdyes']);
	} else {
		$cmdyes = '';
	}

	if ($_POST['cmdno'] != '') {
		$cmdno = trim($_POST['cmdno']);
	} else {
		$cmdno = '';
	}
	if ($_POST['txtcancelremarks'] != '') {
		$txtcancelremarks = trim($_POST['txtcancelremarks']);
	} else {
		$txtcancelremarks = '';
	}
  $_dmNo = htmlspecialchars(strip_tags($dmno), ENT_QUOTES);
  $qry = " select dm_no_acctg from deduction_master where dm_no='{$_dmNo}' ";
  $rs = mssql_query($qry);
  $dmNoAcctg = mssql_result($rs, 0, 'dm_no_acctg');
?>


<html>
<head>

<link href="css_js_messagebox/SyntaxHighlighter.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="css_js_messagebox/shCore.js" language="javascript"></script>
<script type="text/javascript" src="css_js_messagebox/shBrushJScript.js" language="javascript"></script>
<script type="text/javascript" src="css_js_messagebox/ModalPopups.js" language="javascript"></script>

<link href="css/modal.css" rel="stylesheet" type="text/css" />
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="js/jquery-min.js"></script>
<script type="text/javascript"  language="javascript" src="js/jquery-ui.js"></script>
<title>Debit Memo Update</title>

<script type="text/javascript" language="javascript">
	function ModalPopupsAlert1() {
		ModalPopups.Alert("jsAlert1",
			"System Message",
			"<div style='padding:25px;'>Update record successfully!!!</div>",
			{
				okButtonText: "Ok",
				onOk: "ModalPopupsClose()"
			}
		);
	}

	function ModalPopupsAlert2() {
		ModalPopups.Alert("jsAlert2",
			"System Message",
			"<div style='padding:25px;'>Cancel Remarks is a required field!!!</div>",
			{
				okButtonText: "Close"
			}
		);
	}

	function ModalPopupsAlert3() {
		ModalPopups.Alert("jsAlert2",
			"System Message",
			"<div style='padding:25px;'>This Record is already posted!!!</div>",
			{
				okButtonText: "Close"
			}
		);
	}

	function ModalPopupsClose() {
		ModalPopups.Close("jsAlert1");
		window.close('dm_delete.php')
	}


</script>


</head>
<script type="text/javascript">
   	$(function() {
   	   $('#txtAdate, #txtAdate2').datepicker({
    		defaultDate: "+1w"
       });
       $('#cmdidsaved, #cmdidgo').button();
       $('input:submit').button();
       $('#cmdidsaved').button({
           icons:{
               primary: 'ui-icon-wrench'
           }
       });
    });
//txtAdate2
</script>
<link href="css/styles.css" rel="stylesheet" type="text/css">
<body background="images/img1.gif" style="padding: 3px;">
<form method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
<table width="351" height="226" border="0" bgcolor="#F2FEFF" cellpadding="0" cellspacing="0">
  <tr>
    <td width="10" height="26">&nbsp;</td>
    <td width="163">&nbsp;</td>
    <td width="5">&nbsp;</td>
    <td width="158">&nbsp;</td>
    <td width="15">&nbsp;</td>
  </tr>
  <tr>
    <td height="29">&nbsp;</td>
    <td colspan="3" align="center">
      <strong>Are you sure you want to cancel this transaction?</strong>
      <br>
      <strong>DS#: <?php echo $dmNoAcctg; ?></strong>
    </td>
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
    <td height="53">&nbsp;</td>
    <td colspan="3" valign="top" align="center">Cancellation Remarks :
      <textarea name="txtcancelremarks" rows="3" cols="30" style="width: 60%;"><?php echo $txtcancelremarks ;?></textarea>* </td>
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
    <td height="48">&nbsp;</td>
    <td align="center"><input type="submit" name="cmdyes" title="Yes" onClick="submit()" value="Yes" style="width: 90%;height:40 " /></td>
    <td>&nbsp;</td>
    <td align="center"><input type="submit" name="cmdno" title="No" onClick="submit()" value="Close" style="width: 90%;height:40 "/></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="26">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<input type="hidden" name="dmno" value="<?php echo $dmno?>">
<?php
if ($cmdyes == 'Yes') {
	// $selvalidation = "Select * from deduction_master where dm_no = '{$dmno}' and isposted = 0";
  $selvalidation = "Select * from deduction_master where dm_no = '{$dmno}'";
	$rsvalidation = mssql_query($selvalidation);
	$rowvalidation = mssql_num_rows($rsvalidation);
	if ($rowvalidation > 0) {
		if (strlen($txtcancelremarks) > 0 ) {
			$deldmno = "update deduction_master set vposted = 2,cancel_remarks = '{$txtcancelremarks}',cancel_by = '{$_SESSION['user']}',cancel_date = getdate() where dm_no = '{$dmno}' and vposted <> 3 ";
			$rsdmno = mssql_query($deldmno);
			echo '<a href="javascript:ModalPopupsAlert1();">.</a>';
			?>
			<script>
				ModalPopupsAlert1()
			</script>
			<?php
		} else {
		?>
			<script>
				ModalPopupsAlert2()
			</script>
		<?php
		}
	} else {
	?>
		<script>
			ModalPopupsAlert3()
		</script>
	<?php
	}
} elseif ($cmdno == 'Close') {
		?>
		<script>
			window.close('dm_delete.php');
		</script>
		<?php
}
?>

</form>
</body>
</html>
<?php
}  // (x>0) condition
?>
