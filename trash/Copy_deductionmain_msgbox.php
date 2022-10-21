<?php 
session_start();
$x = strlen($_SESSION['user']);
if ($x > 0 ) {
	$lcuser  = $_SESSION['user'] ;
	$lcusername = $_SESSION['username'] ;
	$branch =  $_SESSION['branch_code'] ;
	$glbranchname = $_SESSION['branch_name'];	
	$dept_code = $_SESSION['dept_code'] ;
	$lcdeptname = $_SESSION['dept_name'];
	$division_code = $_SESSION['divcode'];
	$lcdivname = $_SESSION['divname'];
	$lcaccrights = $_SESSION['type'];
	date_default_timezone_set('Asia/Manila');
	$press = 'No';

	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
	define('FCPATH', str_replace(SELF, '', __FILE__));
	define('EXT', '.php');

	class Ever_Core {
	
		private static $instance;
	
		public function Ever_Core(){
			self::$instance =& $this;
		}
	
		public static function &get_instance()
		{
			return self::$instance;
		}
	}

	function &get_instance()
	{
		return Ever_Core::get_instance();
	}

	function &load_class($class)
	{
		static $objects = array();
	
		require('class.encrypt'.EXT);
		
		$name = $class;
		$objects[$class] =& instantiate_class(new $name());
		return $objects[$class];
	}

	function &instantiate_class(&$class_object)
	{
		return $class_object;
	}

	$enc =& load_class('Ever_Encrypt');

?>

<html>
<head>

<link href="css_js_messagebox/SyntaxHighlighter.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="css_js_messagebox/shCore.js" language="javascript"></script>
<script type="text/javascript" src="css_js_messagebox/shBrushJScript.js" language="javascript"></script>
<script type="text/javascript" src="css_js_messagebox/ModalPopups.js" language="javascript"></script>

<link href="css/styles.css" rel="stylesheet" type="text/css" />

<link href="css/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="js/jquery-min.js"></script>
<script type="text/javascript" language="javascript" src="js/jquery-ui.js"></script>


<script type="text/javascript" language="javascript">
	function ModalPopupsAlert1() {
		ModalPopups.Alert("jsAlert1",
			"System Message",
			"<div style='padding:25px;'>Please click the checkbox you want to cancel</div>", 
			{
				okButtonText: "Close"
			}
		);
	}
	
	function ModalPopupsAlert2() {
		ModalPopups.Alert("jsAlert1",
			"System Message",
			"<div style='padding:25px;'>Please click the checkbox you want to approve...</div>", 
			{
				okButtonText: "Close"
			}
		);
	}
	
	function ModalPopupsAlert3() {
		ModalPopups.Alert("jsAlert1",
			"System Message",
			"<div style='padding:25px;'>Please click the checkbox you want to extract...</div>", 
			{
				okButtonText: "Close"
			}
		);
	}
	
	function ModalPopupsConfirm1() {
		ModalPopups.Confirm("idConfirm1",
			"System Message",
			"<div style='padding: 25px;'>You are about to cancel the selected transaction.<br/><br/><b>Are you sure?</b></div>", 
			{
				yesButtonText: "Yes",
				noButtonText: "No",
				onYes: "ModalPopupsConfirmYes1()",
				onNo: "ModalPopupsConfirmNo()"
			}
		);
	}
	function ModalPopupsConfirm2() {
		ModalPopups.Confirm("idConfirm1",
			"System Message",
			"<div style='padding: 25px;'>You are about to approve the selected transaction.<br/><br/><b>Are you sure?</b></div>", 
			{
				yesButtonText: "Yes",
				noButtonText: "No",
				onYes: "ModalPopupsConfirmYes()",
				onNo: "ModalPopupsConfirmNo()"
			}
		);
	}
	function ModalPopupsConfirm3() {
		ModalPopups.Confirm("idConfirm1",
			"System Message",
			"<div style='padding: 25px;'>You are about to extract the selected transaction.<br/><br/><b>Are you sure?</b></div>", 
			{
				yesButtonText: "Yes",
				noButtonText: "No",
				onYes: "ModalPopupsConfirmYes()",
				onNo: "ModalPopupsConfirmNo()"
			}
		);
	}
	function ModalPopupsConfirmYes() {
		var press = 'Yes'
		document.getElementById("idpress").value = press;
		ModalPopups.Close("idConfirm1");
	}
	function ModalPopupsConfirmYes1() {
		ModalPopupsPrompt();
		var press = 'Yes'
		document.getElementById("idpress").value = press;
		ModalPopups.Close("idConfirm1");
	}
	function ModalPopupsConfirmNo() {
		var press = 'No'
		document.getElementById("idpress").value = press;
		ModalPopups.Cancel("idConfirm1");
	}
		
	function ModalPopupsPrompt() {
		ModalPopups.Prompt("idPrompt1",
			"Prompt",
			"Please enter your Cancellation Remarks",  
			{
				width: 400,
				height: 100,
				onOk: "ModalPopupsPromptOk()",
				onCancel: "ModalPopupsPromptCancel()"
			}
		);
	}
	
	function ModalPopupsPromptOk()
	{
		if(ModalPopups.GetPromptInput("idPrompt1").value == "") {
			ModalPopups.GetPromptInput("idPrompt1").focus();
			return;
		}
		//alert("You pressed OK\nValue: " + ModalPopups.GetPromptInput("idPrompt1").value);
		var cancelrem = ModalPopups.GetPromptInput("idPrompt1").value;
		document.getElementById("idcancelrem").value = cancelrem;
		ModalPopups.Close("idPrompt1");
//		if (cancelrem != "") {
//			alert("kamote");
//			document.deductionmain.submit();
//		}
	}
	
	function ModalPopupsPromptCancel() {
		alert("You pressed Cancel");
		ModalPopups.Cancel("idPrompt1");
	}
	
</script>

<script language="JavaScript">
<!-- Begin


function expandingWindow(website) {
	var windowprops='width=100,height=100,scrollbars=yes,status=yes,resizable=yes'
	var heightspeed = 2; // vertical scrolling speed (higher = slower)
	var widthspeed = 7;  // horizontal scrolling speed (higher = slower)
	var leftdist = 10;    // distance to left edge of window
	var topdist = 10;     // distance to top edge of window
	
	if (window.resizeTo&&navigator.userAgent.indexOf("Opera")==-1) {
	var winwidth = window.screen.availWidth - leftdist;
	var winheight = window.screen.availHeight - topdist;
	var sizer = window.open("","","left=" + leftdist + ",top=" + topdist +","+ windowprops);
	for (sizeheight = 1; sizeheight < winheight; sizeheight += heightspeed)
	sizer.resizeTo("1", sizeheight);
	for (sizewidth = 1; sizewidth < winwidth; sizewidth += widthspeed)
	sizer.resizeTo(sizewidth, sizeheight);
	sizer.location = website;
}
else
	window.open(website,'mywindow');
}
</script>


<script language="JavaScript">
var scroller;
var ampm;
var actualtitle=document.title+" "
function antiMilitaryTime()
{
	if (hr == "12"){
		ampm="P.M."
	} else if (hr == "13"){
		hr="1"
		ampm="P.M."
	} else if (hr == "14"){
		hr="2"
		ampm="P.M."
	} else if (hr == "15"){
		hr ="3"
		ampm="P.M."
	} else if (hr == "16"){
		hr = "4"
		ampm="P.M."
	} else if (hr == "17"){
		hr = "5"
		ampm="P.M."
	} else if (hr == "18"){
		hr = "6"
		ampm="P.M."
	} else if (hr == "19"){
		hr = "7"
		ampm="P.M."
	} else if (hr == "20"){
		hr = "8"
		ampm="P.M."
	} else if (hr == "21"){
		hr = "9"
		ampm="P.M."
	} else if (hr == "22"){
		hr = "10"
		ampm="P.M."
	} else if (hr == "23"){
		hr = "11"
		ampm="P.M."
	} else if (hr == "24"){
		hr = "12"
	}
}
function addZero()
{
	if (min <= "9"){
		min = "0"+min
	}
	if (sec<= "9"){
		sec = "0"+sec
	}
	if (hr <=9){
		hr = "0"+hr
	}
}
function time()
{
	dt=new Date()
	sec=dt.getSeconds()
	hr=dt.getHours()
	ampm="A.M."
	min=dt.getMinutes()
}
function scroll() 
{
	time()
	antiMilitaryTime()
	addZero()
	var scroller="TIME: "+hr+":"+min+":"+sec+" "+ampm
	var timeout=setTimeout("scroll()", 1000)
  	document.title=actualtitle+scroller
}
if (document.all||document.getElementById)
scroll()
</SCRIPT>

<SCRIPT LANGUAGE="JavaScript">
function Check(chk)
{
	if(document.deduction.Check_ctr.checked==true){
		for (i = 0; i < chk.length; i++)
			chk[i].checked = true ;
	}else{
		for (i = 0; i < chk.length; i++)
			chk[i].checked = false ;
	}
}

//function deletemodal(cmddelete)
//{
//window.showModalDialog("dm_delete.php?dmno=<?php //echo trim($d_row['dm_no'])?>&branch=<?php //echo trim($d_row['branch_code'])?>","","dialogWidth:500px;dialogHeight:500px") 
//}
</script>
<script type="text/javascript" src="css_js_messagebox/shInit.js" language="javascript"></script>
<title>DM Deduction System</title>
</head>
<?php
error_reporting(E_ALL ^ E_NOTICE); 
include('sqlconn.php');
include('function.php');

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

if ($_POST[division_code] != '') {
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

if (strlen($_GET['namemonth'])>0) {
	$namemonth = trim($_GET['namemonth']);
	} else {
	if (strlen($_POST['namemonth'])>0) {
		$namemonth = trim($_POST['namemonth']);

	} else {
		if (strlen($namemonth)==0) {
			$namemonth = "%";			
		}
	}
}

if (strlen($_POST['txtdatefrom'])>0 ){
	$d = $_POST['txtdatefrom'];	 ?>
	<?php if (strlen($d) < 4){?>
		<script language="javascript">
				alert("Invalid Year")
		</script>
	<?php	
			$d = date("Y") ;
		} 
}else{
	$d = date("Y") ;
} 

if (strlen($_POST['txtDate'])==0 or strlen($_POST['txtDate2'])==0) {
	$txtDate = date("m/d/Y");
	$txtDate2 = date("m/d/Y");
}else{
	$txtDate = $_POST['txtDate'];
	$txtDate2 = $_POST['txtDate2'];	
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

if ($_GET['press'] != '') {
	$press = $_GET['press'];
} else {
	if ($_POST['press'] != '') {
		$press = $_POST['press'];
	} else {
		$press = '';
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

switch($lststatus) {
	case "%": //for all status
		$qrystat = "and vposted like '%' ";
		$dateshow = 'Date Encoded';
		$qrydate = " convert(char(12),a.dm_date,101) as dm_date ";
		break;
	case " ": //for all status
		$qrystat = "and vposted like '%' ";
		$dateshow = 'Date Encoded';
		$qrydate = " convert(char(12),a.dm_date,101) as dm_date ";
		break;
	case "0": //for unposted
		$qrystat = " and vposted = 0 ";
		$dateshow = 'Date Encoded';
		$qrydate = " convert(char(12),a.dm_date,101) as dm_date ";
		break;
	case "1": //for reviewed
		$qrystat = " and vposted = 1 ";
		$dateshow = 'Approved Date';
		$qrydate = " convert(char(12),a.review_date,101) as dm_date ";
		break;
	case "2": //for cancelled
		$qrystat = " and vposted = 2 ";
		$dateshow = 'Cancelled Date';
		$qrydate = " convert(char(12),a.cancel_date,101) as dm_date ";
		break;
	case "3": //for extracted
		$qrystat = " and vposted = 3 ";
		$dateshow = 'Extracted Date';
		$qrydate = " convert(char(12),a.extracted_date,101) as dm_date ";
		break;
}

switch($lcaccrights) {
	case 1 :  //admin
		$cmddisabled = '';						//encode new deduction button
		$cmddisabled1 = '';						//cancel button
		$cmddisabled2 = '';						//review button
		$cmddisabled3 = '';						//extract button
		break;
	case 2 :  //encoder
		$cmddisabled = '';							
		$cmddisabled1 = '';
		$cmddisabled2 = 'disabled="disabled"';
		$cmddisabled3 = 'disabled="disabled"';
		$auser = "and encoded_by like '{$lcuser}'";
		break;
	case 3 :  //reviewer
		$cmddisabled = 'disabled="disabled"';
		$cmddisabled1 = 'disabled="disabled"';
		$cmddisabled2 = 'disabled="disabled"';
		$cmddisabled3 = 'disabled="disabled"';
		break;
	case 4 :  //supervisor
		$cmddisabled = '';						//encode new deduction button
		$cmddisabled1 = '';						//cancel button
		$cmddisabled2 = '';						//review button
		$cmddisabled3 = 'disabled="disabled"';
		$auser = "and encoded_by like '{$euser}'";
		break;
	case 5 :  //HO accounting
		$cmddisabled = 'disabled="disabled"';
		$cmddisabled1 = 'disabled="disabled"';
		$cmddisabled2 = 'disabled="disabled"';
		$cmddisabled3 = '';
		$auser = "and encoded_by like '{$euser}'";
		$qrystat = " and vposted = 1 ";
		break;
		
}	

$selqry = "select a.dm_no,a.branch_code,c.dept_code,d.division_code,e.category_name,f.subcat_name,suppliername,{$qrydate},
			a.amount,a.remarks,a.encoded_by,a.vposted 
			from deduction_master a left join ref_branch b on a.branch_code = b.branch_code 
			left join ref_department c on a.dept_code = c.dept_code
			left join ref_division d on a.division_code = d.division_code
			left join ref_category e on a.category_code = e.category_code
			left join ref_subcategory f on a.subcat_code = f.subcat_code
			where a.branch_code like '{$branch}' and a.dept_code like '{$dept_code}' and a.division_code like '{$division_code}' and 
			a.category_code like '{$category_code}' and a.subcat_code like '{$subcat_code}' and a.vposted like '{$lststatus}' and
			left(rtrim(ltrim(a.period)),len(rtrim(ltrim(a.period)))-5) like '{$namemonth}' and right(period,4) like '{$d}' and 
			convert(char(12),dm_date,101) between '{$txtDate}' and '{$txtDate2}' {$auser} {$qrystat} order by a.encoded_date desc";
			
$r_select = mssql_query($selqry);
$nmrow = mssql_num_rows($r_select);
//$d_row = mssql_fetch_array($r_select);
$a = 12;
$x = $nmrow;
$y = $x/$a;
$z = $x%$a;

if ($z > 0) {
	$page = (($x-$z)/$a)+1;
} else {
	$page = $y;
}

if (strlen($_POST['pagepost'])>0) {
	$pageno = $_POST['pageno'];
}
else
{
	$pageno = 1;
}	
//echo $pageno."<br>";
$pagepost = $_POST['pagepost'];
switch ($pagepost) {
	case "First":
		$pageno = 1;
		$disabled1 = 'disabled="disabled"';
		$disabled2 = 'disabled="disabled"';
		$disabled3 = '';
		$disabled4 = '';
	break;
	case "Prev":
		$pageno = $pageno - 1;
		if ($pageno == 1) {
			$disabled1 = 'disabled="disabled"';
			$disabled2 = 'disabled="disabled"';
			$disabled3 = '';
			$disabled4 = '';
		} else {
			$disabled1 = '';
			$disabled2 = '';
			$disabled3 = '';
			$disabled4 = '';
		}
	break;
	case "Next":
		$pageno = $pageno + 1;
		if ($pageno == $page) {
			$disabled1 = '';
			$disabled2 = '';
			$disabled3 = 'disabled="disabled"';
			$disabled4 = 'disabled="disabled"';
		} else {
			$disabled1 = '';
			$disabled2 = '';
			$disabled3 = '';
			$disabled4 = '';
		}
	break;
	case "Last":
		$pageno = $page;
		$disabled1 = '';
		$disabled2 = '';
		$disabled3 = 'disabled="disabled"';
		$disabled4 = 'disabled="disabled"';
	break;
	default:
	
		$disabled1 = 'disabled="disabled"';
		$disabled2 = 'disabled="disabled"';
		$disabled3 = '';
		$disabled4 = '';
	break;
}	
if (($pageno*$a) <= $nmrow) {
	$pgttl = $pageno*$a;
	$pgrec = $pgttl-($a-1);
} else {
	$diff = ($pageno*$a) - $nmrow;
	$pgttl = $nmrow;
	$pgrec = $pgttl-($z-1);
}
$onepage = "no"; 
if($page<=1) {
	if ($x==0) {
		$pgrec = 0;
	}
	$onepage = "yes"; 
}

if ($onepage == "yes") {
	$disabled1 = 'disabled="disabled"';
	$disabled2 = 'disabled="disabled"';
	$disabled3 = 'disabled="disabled"';
	$disabled4 = 'disabled="disabled"';
}
if ($onepage == "yes") {
	$disabled1 = 'disabled="disabled"';
	$disabled2 = 'disabled="disabled"';
	$disabled3 = 'disabled="disabled"';
	$disabled4 = 'disabled="disabled"';
}

if ($markall == "yes" &&  $chk == "yes") {
	$checkedx =  'checked="checked"' ;
	$chk = "" ;
}else{ 
	if ($markall == "" &&  $chk == "yes"){
		$checkedx =  'checked="checked"' ;
	}else {
		$checkedx =  "" ;
		$chk = "" ;
	}	
}

?>

<script type="text/javascript">
   	$(function() {
   	   $('#txtAdate, #txtAdate2').datepicker({
    		defaultDate: "+1w"
       }); 
       $('#cmdidsearch, #cmdidgo').button();
       $('input:submit').button();
       $('#cmdidsearch').button({
           icons:{
               primary: 'ui-icon-wrench'
           }
		});
    });
//txtAdate2
</script>

<?php
	if ($cmdnew != '') {
 ?>
	<script>
		window.open('dm_new.php','','menubar=no,scrollbars=no,toolbar=no,resizable=no,width=400,height=380,left=200,top=95,dependent') 
	</script>
<?php 
	}
	
	if ($cmdlogout != '') {
		session_destroy();
		?>
		<script>
			window.close('deductionmain.php');
			window.open('login.php');
		</script>
		<?php
	} else {
	
	}
?>
<!--background="images/img1.gif" style="padding: 3px;"-->
<!--<link href="css/styles.css" rel="stylesheet" type="text/css">-->
<body bgcolor="#5D7AAD">
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']?>" name = "deductionmain">
	  <table width="100%" height="735" border="0">
        <tr>
          <td>
		  	<table width="100%" border="0" bgcolor="#ffffff">
			  <tr>
				<td width="7">&nbsp;</td>
				<td width="105">&nbsp;</td>
				<td width="271">&nbsp;</td>
				<td width="10">&nbsp;</td>
				<td width="143">&nbsp;</td>
				<td width="129">&nbsp;</td>
				<td width="130">&nbsp;</td>
				<td width="101">&nbsp;</td>
				<td width="130">&nbsp;</td>
				<td width="12">&nbsp;</td>
			  </tr>
			  <tr>
				<td width="7">&nbsp;</td>
				<td width="105"><strong>Branch :</strong> </td>
				<td width="271">
				<?php if ($lcaccrights == 5 or $lcaccrights == 4 ) { ?>
					<select name="branch" onChange="submit()" style="width: 90%;">
                  		<option value="">-All Branch Under <?php echo strtoupper($lcuser); ?>-</option>
                  <?php 
							$seluser = "select distinct a.branch_code,a.branch_name from ref_branch a left join ref_supervisor b on 
								a.branch_code = b.branch_code where b.supervisor = '{$lcuser}' and b.isactive = 1 order by a.branch_code";
							$rsuser = mssql_query($seluser);
						for ($i = 0; $i < ($b_row = mssql_fetch_array($rsuser)); $i++) 
						{
							$selected = "";
							if (trim($b_row['branch_code']) == trim($branch)) {
								$selected = 'selected="selected"';
							} 
						?>
                  <option value="<?php echo $b_row['branch_code']?>" <?php echo $selected?>   > 
				  <?php echo $b_row['branch_code']?> - <?php echo $b_row['branch_name']?> </option>
                  <?php 
 
						}?>
                </select>
				<?php 
				} elseif ($lcaccrights == 3) {
				?>
					<select name="branch" onChange="submit()" style="width: 90%;">
                  		<option value="">-All Branch Under <?php echo strtoupper($lcuser); ?>-</option>
                  <?php 
							$seluser = "select branch_code,branch_name from ref_branch where isactive = 1 order by a.branch_code";
							$rsuser = mssql_query($seluser);
						for ($i = 0; $i < ($b_row = mssql_fetch_array($rsuser)); $i++) 
						{
							$selected = "";
							if (trim($b_row['branch_code']) == trim($branch)) {
								$selected = 'selected="selected"';
							} 
						?>
                  <option value="<?php echo $b_row['branch_code']?>" <?php echo $selected?>   > 
				  <?php echo $b_row['branch_code']?> - <?php echo $b_row['branch_name']?> </option>
                  <?php 
 
						}?>
                </select>
				<?php
				} else {  echo strtoupper($glbranchname); }?></td>
				<td width="10">&nbsp;</td>
				<td width="143"><strong>Category :</strong></td>
				<td colspan="3"><select name="category_code" onChange="submit()" onkeypress="return ignoreenter(this,event)" style="width: 100%;">
                  <option value="">-All-</option>
                  <?php 
						$selcat = "select category_code,category_name from ref_category where isactive = 1 and division_code like '{$division_code}' order by category_name ";
						$r_selcat = mssql_query($selcat);
						for ($i = 0; $i < ($b_row = mssql_fetch_array($r_selcat)); $i++) 
						{
							$selected = "";
							if (trim($b_row['category_code']) == trim($category_code)) {
								$selected = 'selected="selected"';
							} 
						?>
                  <option value="<?php echo $b_row['category_code']?>" 
			  <?php echo $selected?>><?php echo $b_row['category_name']?> </option>
                  <?php 
 
						}?>
                </select></td>
				<td width="130">&nbsp;</td>
				<td width="12">&nbsp;</td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
				<td><strong>User : </strong></td>
				<td><?php if ($lcaccrights == 5 or $lcaccrights == 4) { ?>
						<select name="euser" style="width: 90%;" onChange="submit()">
						<option value="">-All User Under <?php echo strtoupper($lcuser); ?>-</option>
					<?php 
					$selbr = "select a.user_name,a.name from ref_users a left join ref_supervisor b on a.user_name = b.user_name where a.isactive = 1 
							and b.supervisor = '{$lcuser}' and a.branch_code like '{$branch}'";
			
					$r_selbr = mssql_query($selbr);
					for ($i = 0; $i < ($b_row = mssql_fetch_array($r_selbr)); $i++) 
					{
						$selected = "";
						if (trim($b_row['user_name']) == trim($euser)) {
							$selected = 'selected="selected"';
						} 
					?>
						<option value="<?php echo $b_row['user_name']?>" <?php echo $selected?>   > 
				<?php echo $b_row['name']?> </option>
				<?php 
					} ?></select>
				<?php } elseif ($lcaccrights == 3) {?>
					<select name="euser" style="width: 90%;" onChange="submit()">
						<option value="">-All User Under <?php echo strtoupper($lcuser); ?>-</option>
					<?php 
					$selbr = "select user_name,name from ref_users where isactive = 1 and access_right not in (1,5)";
			
					$r_selbr = mssql_query($selbr);
					for ($i = 0; $i < ($b_row = mssql_fetch_array($r_selbr)); $i++) 
					{
						$selected = "";
						if (trim($b_row['user_name']) == trim($euser)) {
							$selected = 'selected="selected"';
						} 
					?>
						<option value="<?php echo $b_row['user_name']?>" <?php echo $selected?>   > 
				<?php echo $b_row['name']?> </option>
				<?php 
					} ?></select>
				<?php } else { echo strtoupper($lcusername); }?></td>
				<td>&nbsp;</td>
				<td><strong>Sub Category :</strong></td>
				<td colspan="3"><select name="subcat_code" onkeypress="return ignoreenter(this,event)" style="width: 100%;">
                  <option value=""> <?php echo $noavail;?> </option>
                  <?php 
						$selsubcat = "select subcat_code,subcat_name from ref_subcategory where isactive = 1 and category_code = '{$category_code}' order by subcat_name ";
						$r_selsubcat = mssql_query($selsubcat);
						for ($i = 0; $i < ($b_row = mssql_fetch_array($r_selsubcat)); $i++) 
						{
							$selected = "";
							if (trim($b_row['subcat_code']) == trim($subcat_code)) {
								$selected = 'selected="selected"';
							} 
						?>
                  <option value="<?php echo $b_row['subcat_code']?>" 
			  <?php echo $selected?>><?php echo $b_row['subcat_name']?> </option>
                  <?php 
 
						}?>
                </select></td>
				<td rowspan="3" align="center"><input type="submit" name="cmdsearch" value=" Search "  title="Add Payment Order Slip" onClick="submit()"  style="width: 90%;height:40; background-color:#FF9933 " ></td>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
				<td><strong>Department :</strong></td>
				<td><?php if ($lcaccrights == 5 or $lcaccrights == 4) { ?>
					<select name="dept_code" onChange="submit()" onkeypress="return ignoreenter(this,event)" style="width: 90%">
					<option value="">-All-</option>
					<?php 
						
						$seldept = "select distinct a.dept_code,a.dept_name from ref_department a left join ref_users b on 
									a.dept_code = b.dept_code where b.user_name like '{$euser}' order by a.dept_code";
						$r_seldept = mssql_query($seldept);
						for ($i = 0; $i < ($b_row = mssql_fetch_array($r_seldept)); $i++) 
						{
							$selected = "";
							if (trim($b_row['dept_code']) == trim($dept_code)) {
								$selected = 'selected="selected"';
							} 
					?>
						<option value="<?php echo $b_row['dept_code']?>" 
			  		<?php echo $selected?>> <?php echo $b_row['dept_name']?> </option>
                  	<?php }?></select>
				<?php } elseif ($lcaccrights == 3) {?>
					<select name="dept_code" onChange="submit()" onkeypress="return ignoreenter(this,event)" style="width: 90%">
					<option value="">-All-</option>
					<?php 
						
						$seldept = "select dept_code,dept_name from ref_department order by a.dept_code";
						$r_seldept = mssql_query($seldept);
						for ($i = 0; $i < ($b_row = mssql_fetch_array($r_seldept)); $i++) 
						{
							$selected = "";
							if (trim($b_row['dept_code']) == trim($dept_code)) {
								$selected = 'selected="selected"';
							} 
					?>
						<option value="<?php echo $b_row['dept_code']?>" 
			  		<?php echo $selected?>> <?php echo $b_row['dept_name']?> </option>
                  	<?php }?></select>
				<?php } else { echo strtoupper($lcdeptname); }?></td>
				<td>&nbsp;</td>
				<td><strong><?php echo $dateshow; ?> From : </strong></td>
				<td><input name="txtDate" type="text" size="11" maxlength="10" id="txtAdate" readonly="readonly" onFocus="document.news_edit.reset.focus();" value="<?php echo $txtDate ?>" onKeyPress="return numbersonly(this, event)"/></td>
				<td><strong><?php echo $dateshow; ?> To :</strong></td>
				<td><input name="txtDate2" type="text" size="11" maxlength="10" id="txtAdate2" readonly="readonly" onFocus="document.news_edit.reset.focus();" value="<?php  echo $txtDate2  ?>" onKeyPress="return numbersonly(this, event)"/></td>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
				<td><strong>Division :</strong></td>
				<td><?php if ($lcaccrights == 5 or $lcaccrights == 4) { ?>
					<select name="division_code" onChange="submit()" onkeypress="return ignoreenter(this,event)" style="width: 90%">
					<option value="">-All-</option>
				<?php 
						echo $seldiv = "select distinct a.division_code,a.division_name from ref_division a left join ref_users b on 
									a.division_code = b.division_code where b.user_name like '{$euser}' order by a.division_code ";

						$r_seldiv = mssql_query($seldiv);
						for ($i = 0; $i < ($b_row = mssql_fetch_array($r_seldiv)); $i++) 
						{
							$selected = "";
							if (trim($b_row['division_code']) == trim($division_code)) {
								$selected = 'selected="selected"';
							} 
						?>
                  <option value="<?php echo $b_row['division_code']?>" 
			  <?php echo $selected?>   > <?php echo $b_row['division_name']?> </option>
                  <?php 
						}?></select>
				<?php } elseif ($lcaccrights == 3) {?>
					<select name="division_code" onChange="submit()" onkeypress="return ignoreenter(this,event)" style="width: 90%">
					<option value="">-All-</option>
				<?php 
						$seldiv = "select division_code,division_name from ref_division order by a.division_code ";

						$r_seldiv = mssql_query($seldiv);
						for ($i = 0; $i < ($b_row = mssql_fetch_array($r_seldiv)); $i++) 
						{
							$selected = "";
							if (trim($b_row['division_code']) == trim($division_code)) {
								$selected = 'selected="selected"';
							} 
						?>
                  <option value="<?php echo $b_row['division_code']?>" 
			  <?php echo $selected?>   > <?php echo $b_row['division_name']?> </option>
                  <?php 
						}?></select>
				<?php } else { echo strtoupper($lcdivname); }?></td>
				<td>&nbsp;</td>
				<td><strong>Deduction Period : </strong></td>
				<td colspan="2"><select name="namemonth" onkeypress="return ignoreenter(this,event)">
								<option value="">All</option>
                  <?php		for ($i= 1; $i<= 12; $i++ )	{
			$selected = "" ;						
			$getmonth = date("F", mktime(0, 0, 0, $i, 0, 0));

					if ($namemonth == $getmonth){
						$selected = 'selected="selected"' ;
						}else{
						$selected = '';					
						}
			?>
                  <option value ="<?php echo $getmonth ?>"<?php echo $selected ?>><?php echo $getmonth ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                  <?php  }	?>
                </select>
			    <input name="txtdatefrom" type="text" size="5" maxlength="4" value="<?php echo $d ?>" onKeyPress="return numbersonly(this, event)"/></td>
				<td><a href="status_bar_graph.php" title="View status" onClick="javascript:window.open('status_bar_graph.php','','scrollbars=no,resizable=0,width=720,height=475,left=100,top=50').focus();return false;" alt="View Status">View Status</a></td>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><strong><?php if ($lcaccrights != 5 ) {?>Status :<?php }?></strong></td>
				<td><?php if ($lcaccrights != 5 ) {?>
					<select name="lststatus" style="width: 90%;" onChange="submit()">
                  <?php 
						$stat = "";
						$stat0 = "";
						$stat1 = "";
						$stat2 = "";
						$stat3 = "";
												
						switch($lststatus) {
							case " ": //for all status
								$stat = 'selected="selected"';
							case "0": //for unposted
								$stat0 = 'selected="selected"';
								break;
							case "1": //for reviewed
								$stat1 = 'selected="selected"';
								break;
							case "2": //for cancelled
								$stat2 = 'selected="selected"';
								break;
							case "3": //for extraction
								$stat3 = 'selected="selected"';
								break;
						}
						
						?>
                  <option value="" <?php echo $stat?>>&nbsp;- All -&nbsp;</option>
                  <option value="0" <?php echo $stat0?>>New</option>
                  <option value="2" <?php echo $stat2?>>Cancelled</option>
                  <option value="1" <?php echo $stat1?>>Approved</option>
                  <option value="3" <?php echo $stat3?>>Extracted</option>
                </select><?php }?></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			</table>

		  </td>
        </tr>
        <tr>
          <td height="2"></td>
        </tr>
		<tr>
          <td height="490" valign="top" bgcolor="#ffffff">
		  	<table width="100%"  border="1" bgcolor="#99BBDD" bordercolor="#FFFFFF" bordercolordark="#FFFFFF" bordercolorlight="#99BBDD" cellpadding="1" cellspacing="0">
            <tr>
			  <td width="2%" align="center" bgcolor="#99BBDD"><strong><input type="checkbox" name="markall" value="yes" onClick="submit()" <?php echo $checkedx ;?> ></strong></td>
              <td width="5%" align="center" bgcolor="#99BBDD"><strong>Control#</strong></td>
              <td width="4%" align="center" bgcolor="#99BBDD"><strong>Branch</strong></td>
              <td width="7%" align="center" bgcolor="#99BBDD"><strong>Dept</strong></td>
              <td width="5%" align="center" bgcolor="#99BBDD"><strong>Division</strong></td>
			  <td width="13%" align="center" bgcolor="#99BBDD"><strong>Vendor Name</strong></td>
              <td width="13%" align="center" bgcolor="#99BBDD"><strong>Category</strong> </td>
              <td width="13%" align="center" bgcolor="#99BBDD"><strong>Sub Category</strong></td>
              <td width="5%" align="center" bgcolor="#99BBDD"><strong><?php echo $dateshow; ?></strong></td>
              <td width="5%" align="center" bgcolor="#99BBDD"><strong>Amt</strong></td>
              <td width="5%" align="center" bgcolor="#99BBDD"><strong>Status</strong></td>
			  <td width="17%" align="center" bgcolor="#99BBDD"><strong>Remarks</strong></td>
              <td width="4%" align="center" bgcolor="#99BBDD"><strong>Action</strong></td>
		    </tr>
			<?php 
			if ($nmrow > 0) {
				$r_select = mssql_query($selqry);
				$cnt = $pageno*$a;
				for ($i = 0; $i < ($d_row = mssql_fetch_array($r_select)); $i++)
				{
					$amount = $d_row['amount'];
					$total = $total + $amount;
					$val = $i % 2;
					if ($val == 0) {				
						$colorval = "#CCCCCC";
					} else {
						$colorval = "#F2F2F2";
					}

					if ($i >= ($cnt-$a) && $i < ($cnt)) 
					{
			?>			
			<tr bgcolor="<?php echo $colorval?>">
			<?php if (($d_row['vposted'] == 1 or $d_row['vposted'] == 3) and $lcaccrights == 2) {
					$chkdisabled = 'disabled="disabled"';
				  } elseif ($d_row['vposted'] == 0 and $lcaccrights == 2) {
				  	$chkdisabled = '';
				  } elseif (($d_row['vposted'] == 1 or $d_row['vposted'] == 3) and $lcaccrights == 4) {
				  	$chkdisabled = 'disabled="disabled"';
				  } elseif ($d_row['vposted'] == 0 and $lcaccrights == 4) {
				  	$chkdisabled = '';
				  } elseif ($d_row['vposted'] == 1 and $lcaccrights == 5) {
				  	$chkdisabled = '';
				  } elseif (($d_row['vposted'] == 3 or $d_row['vposted'] == 0) and $lcaccrights == 5) {
				  	$chkdisabled = 'disabled="disabled"';
				  } 
			?>	
			  <td height="28"><input type="checkbox" name="box[]" value="<?php echo $d_row['dm_no'] ?>" <?php echo $checkedx ;?> <?php echo $chkdisabled;?> /></td>
              <td height="28"><a href="" title="View History" onClick="javascript:window.open('history.php?dmno=<?php echo trim($d_row['dm_no'])?>&branch=<?php echo trim($d_row['branch_code']) ?>','','scrollbars=no,resizable=no,width=600,height=500,left=250,top=95,dependent').focus();return false;" alt="View History"><?php echo $d_row['dm_no']?></a></td>
              <td><?php echo $d_row['branch_code']?></td>
              <td><?php echo $d_row['dept_code']?></td>
              <td><?php echo $d_row['division_code']?></td>
			  <td><?php echo $d_row['suppliername']?></td>
              <td><?php echo $d_row['category_name']?></td>
              <td><?php echo $d_row['subcat_name']?></td>
              <td><?php echo $d_row['dm_date']?></td>
              <td align="right"><?php echo number_format($d_row['amount'],2)?></td>
              <td><?php switch($d_row['vposted']) {
							case 0: //unposted
			  					echo 'New'; 
								break;
							case 1:
								echo 'Approved';
								break;
							case 2:
								echo 'Cancelled';
								break;
							case 3:
								echo 'Extracted';
								break;
						}?></td>
              <td><?php echo $d_row['remarks']?></td>
			  <?php if ($lcaccrights == 4 or $lcaccrights == 2) {
			  			if ($d_row['vposted'] == 1 or $d_row['vposted'] == 3) {
			  ?>
			  <td width="4%" align="center">Edit</td>
			  <?php 	} else {?>
			  <td width="4%" align="center">
			  	<a href="dm_edit.php" onClick="javascript:window.open('dm_edit.php?dmno=<?php echo trim($d_row['dm_no'])?>&branch=<?php echo trim($d_row['branch_code'])?>','','scrollbars=no,resizable=no,width=400,height=420,left=200,top=95,dependent').focus();return false;" alt="Edit DM Deduction">Edit</a></td>
			  <?php 	}
			  		} else {?>
			  <td width="2%" align="center"><a href="dm_edit.php" onClick="javascript:window.open('dm_edit.php?dmno=<?php echo trim($d_row['dm_no'])?>&branch=<?php echo trim($d_row['branch_code'])?>','','scrollbars=no,resizable=no,width=400,height=420,left=200,top=95,dependent').focus();return false;" alt="Edit DM Deduction">Edit</a></td>
			  <?php }?>
            </tr>
			
			<?php
					}
				}
			}
			?>
          </table>
		  
		  </td>
        </tr>
        <tr>
          <td align="right"><table width="100%" border="0" bgcolor="#ffffff">
                      <tr>
					    <td width="46%" align="left"><strong>Total Amount&nbsp;&nbsp;<?php echo number_format($total,2);?></strong></td>
                        <td width="33%" align="right">
						<strong> <?php echo $pgrec?> - <?php echo $pgttl?> of <?php echo $nmrow?> Records |  Page &nbsp;&nbsp;</strong></td>
                        <td width="5%"><input type="submit" name="pagepost" value="First" title="" <?php echo $disabled1?> style="width: 90%;height:20 "></td>
                        <td width="5%"><input type="submit" name="pagepost" value="Prev" title="" <?php echo $disabled2?> style="width: 90%;height:20 "></td>
                        <td width="5%"><input type="submit" name="pagepost" value="Next" title="" <?php echo $disabled3?> style="width: 90%;height:20 "></td>
                        <td width="5%"><input type="submit" name="pagepost" value="Last" title="" <?php echo $disabled4?> style="width: 90%;height:20 "></td>
                      </tr>
                    </table></td>
        </tr>
		<tr>
          <td>
		  	<table width="100%" height="3" border="0" bgcolor="#ffffff">
			<tr>
			  <td width="61%">
					<table width="100%" border="0" bgcolor="#ffffff">
						<tr>
			  			<td width="15%" align="center"><input type="submit" name="cmdnew" value=" Encode New Deduction " <?php echo $cmddisabled?> style="width: 90%;height:40; background-color:#FF9933 "></td>
			  			<td width="15%" align="center"><input type="submit" name="cmdcancel" value="Cancel Record" <?php echo $cmddisabled1?> style="width: 90%;height:40;background-color:#FF9933  "></td>
						<td width="15%" align="center"><input type="submit" name="cmdpost" value="Approve New Record" <?php echo $cmddisabled2?> style="width: 90%;height:40;background-color:#FF9933  "></td>
			  			<td width="16%" align="center"><input type="submit" name="cmdextract" value="Extraction of Deduction" <?php echo $cmddisabled3;?> style="width: 90%;height:40;background-color:#FF9933  " ></td>
			  			<td width="15%" align="center"><input type="submit" name="cmdreport" value="Reports" style="width: 90%;height:40;background-color:#FF9933  "></td>
						<td width="15%" align="center"><input type="submit" name="cmdlogout" value="Logout" style="width: 90%;height:40;background-color:#FF9933  "></td>
						</tr>
				</table>
			</tr>
		  </table></td>
        </tr>
      </table>
	  
	 <input name="cancelrem" type="text" value="<?php echo $cancelrem ?>" id="idcancelrem" readonly="yes" size="1" style="background:none; border:none; font-size:1px"/>
	 <input name="press" type="text" value="<?php echo $press ?>" id="idpress" readonly="yes" size="1" style="background:none; border:none; font-size:1px"/>
	 
		  
<?php 

	if ($cmdcancel != '' ) {
		if ($markall == "yes" ) {
			echo '<a href="javascript:ModalPopupsConfirm1();">.</a>';
			?>
			<script>
				ModalPopupsConfirm1()
			</script>
			<?php
			if ($press == 'Yes') {
				$x_select = mssql_query($selqry);
				for ($x = 0; $x < ($x_row = mssql_fetch_array($x_select)); $x++) {
					$dmnox = $x_row['dm_no'];
					echo $canqry = "Execute dm_cancel '{$dmnox}','{$lcuser}','{$cancelrem}'";
					//$r_canqry = mssql_query($canqry);
				}
			}
		} else {
			if (isset($_POST['box'])) {
				echo '<a href="javascript:ModalPopupsConfirm1();">.</a>';
				?>
				<script>
					ModalPopupsConfirm1();
					//document.deductionmain.submit();
				</script>
				<?php
				if ($press == 'Yes') {
					$forwardxx =  $_POST["box"] ;
					foreach ($forwardxx as $dm) 
					{
						$dmnox = $dm ;
						echo $canqry = "Execute dm_cancel '{$dmnox}','{$lcuser}','{$cancelrem}'";
						//$r_canqry = mssql_query($canqry);
					}
				}
			} else {
				echo '<a href="javascript:ModalPopupsAlert1();">.</a>';
				?>
				<script>
					ModalPopupsAlert1()
				</script>
				<?php 
			}
		}
	} 
	
	if ($cmdpost != '') {
		if ($markall == "yes" ) {
			echo '<a href="javascript:ModalPopupsConfirm2();">.</a>';
			?>
			<script>
				ModalPopupsConfirm2()
			</script>
			<?php
			if ($press == 'Yes') {
				$x_select = mssql_query($selqry);
				for ($x = 0; $x < ($x_row = mssql_fetch_array($x_select)); $x++) {
					$dmnox = $x_row['dm_no'];
					$canqry = "Execute dm_posting '{$dmnox}','{$lcuser}'";
					$r_canqry = mssql_query($canqry);
				}
			}			
		} else {
			if (isset($_POST['box'])) {
				echo '<a href="javascript:ModalPopupsConfirm2();">.</a>';
				?>
				<script>
					ModalPopupsConfirm2()
				</script>
				<?php
				if ($press == 'Yes') {
					$forwardxx =  $_POST["box"] ;
					foreach ($forwardxx as $dm) 
					{
						$dmnox = $dm ;
						$canqry = "Execute dm_posting '{$dmnox}','{$lcuser}'";
						$r_canqry = mssql_query($canqry);
					}
				}
			} else {
				echo '<a href="javascript:ModalPopupsAlert1();">.</a>';
				?>
				<script>
					ModalPopupsAlert2()
				</script>
				<?php 
			}
		}
	} 
	
	if ($cmdextract != '') {
		if ($markall == "yes" ) {
			echo '<a href="javascript:ModalPopupsConfirm3();">.</a>';
			?>
			<script>
				ModalPopupsConfirm3()
			</script>
			<?php
			$qryextno = "Select extraction_refno from ext_autoid ";
			$rsextno = mssql_query($qryextno);
			$extno_nmrow = mssql_num_rows($rsextno);
			$row_extno = mssql_fetch_array($rsextno);
			if ($extno_nmrow == 0) {		
				$extno = str_pad(trim($row_extno['extraction_refno']),9,0).'1';
				$update_xno = "Insert into ext_autoid values ('{$extno}')";
				$rs_xno = mssql_query($update_xno);
			} else {
				$xextno = (int)$row_extno['extraction_refno'] + 1 ;
				$yextno = strlen($xextno);
				$zextno = 10 - (int)$yextno;
				$extno = str_pad(trim(substr($row_extno['extraction_refno'],0,-(int)$yextno)),$zextno,0).$xextno;
				$update_xno = "Update ex_autoid set extraction_refno = '{$extno}' ";
				$rs_xno = mssql_query($update_xno);
			}

			$x_select = mssql_query($selqry);
			for ($x = 0; $x < ($x_row = mssql_fetch_array($x_select)); $x++) {
				$dmnox = $x_row['dm_no'];
				$canqry = "Execute dm_extract '{$dmnox}','{$extno}','{$lcuser}'";
				$r_canqry = mssql_query($canqry);
			}
			echo '<a href="php_excel_gen.php?extno="'.$extno.'>.</a>';	
			//call php_excel_gen.php?extno=<?php echo $extno
			
		} else {
			if (isset($_POST['box'])) {
				echo '<a href="javascript:ModalPopupsConfirm3();">.</a>';
				?>
				<script>
					ModalPopupsConfirm3()
				</script>
				<?php
				$qryextno = "Select extraction_refno from ext_autoid ";
				$rsextno = mssql_query($qryextno);
				$extno_nmrow = mssql_num_rows($rsextno);
				$row_extno = mssql_fetch_array($rsextno);
				if ($extno_nmrow == 0) {		
					$extno = str_pad(trim($row_extno['extraction_refno']),9,0).'1';
					$update_xno = "Insert into ext_autoid values ('{$extno}')";
					$rs_xno = mssql_query($update_xno);
					$_SESSION['extno'] = $extno;
				} else {
					$xextno = (int)$row_extno['extraction_refno'] + 1 ;
					$yextno = strlen($xextno);
					$zextno = 10 - (int)$yextno;
					$extno = str_pad(trim(substr($row_extno['extraction_refno'],0,-(int)$yextno)),$zextno,0).$xextno;
					echo $update_xno = "Update ex_autoid set extraction_refno = '{$extno}' ";
					$rs_xno = mssql_query($update_xno);
					echo $_SESSION['extno'] = $extno;
				}
				
				$forwardxx =  $_POST["box"] ;
				foreach ($forwardxx as $dm) 
				{
					$dmnox = $dm ;
					$canqry = "Execute dm_extract '{$dmnox}','{$extno}','{$lcuser}'";
					$r_canqry = mssql_query($canqry);
				}
				echo '<a href="php_excel_gen.php?extno="'.$extno.'>.</a>';	
				//call php_excel_gen.php
			} else {
				echo '<a href="javascript:ModalPopupsAlert1();">.</a>';
				?>
				<script>
					ModalPopupsAlert3()
				</script>
				<?php 
			}
		}
		
	}
		
?>		
	 
    <input type="hidden" name="pageno" value="<?php echo $pageno;?>" />
	<input type="hidden" name="qrystat" value="<?php echo $qrystat;?>" />
	</form>
	
</body>
</html>
<?php
} else {
?>
	<script>
	location.href ='login.php';
	</script>
<?php
}
?>