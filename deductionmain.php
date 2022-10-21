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
  	$lcbuyerid = @$_SESSION['lcbuyerid'];

  	if (isset($_POST['dept_code'])) {
  		$dept_code = $_POST['dept_code'];
  	} 

	date_default_timezone_set('Asia/Manila');

	$_query = "select password from ref_users where user_name='{$lcuser}' ";
	$_rs    = mssql_query($_query);
	$_row  = mssql_fetch_array($_rs);
	$_pass = $_row['password'];

	// decrypt
	$_query = " execute decrypt_pass '{$_pass}'";
	$_rs    = mssql_query($_query);
	$_row  = mssql_fetch_array($_rs);
	$_pass = $_row['pass'];


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

	$lQry = " select * from everlyl_conspo.consolidatepo.dbo.sitegroup order by main_site ";
	$lRs  = mssql_query($lQry);

	$mainSitesList   = array();
	$uniqueMainSites = array();
	$subSitesList    = array();

	while($cpRs = mssql_fetch_object($lRs))
	{
		$mainSitesList[trim($cpRs->sub_site)] = array(
			'code' => trim($cpRs->main_site),
			'name' => trim($cpRs->main_name),
		);
		$subSitesList[trim($cpRs->main_site)][] = trim($cpRs->sub_site);
		$uniqueMainSites[] = trim($cpRs->main_site);
	}


?>

<html>
<head>

<link href="css_js_messagebox/SyntaxHighlighter.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="css_js_messagebox/shCore.js" language="javascript"></script>
<script type="text/javascript" src="css_js_messagebox/shBrushJScript.js" language="javascript"></script>
<script type="text/javascript" src="css_js_messagebox/ModalPopups.js" language="javascript"></script>

<link href="css/chosen.css" rel="stylesheet" type="text/css">
<link href="css/styles.css" rel="stylesheet" type="text/css">

<link href="css/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css">
<script language="javascript" src="js/jquery1.7.js"></script>
<script type="text/javascript" language="javascript" src="js/jquery-ui.js"></script>

<link rel="stylesheet" href="css/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />
<script type="text/javascript" src="js/bsn.AutoSuggest_c_2.0.js"></script>

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

	function ModalPopupsAlert4() {
		ModalPopups.Alert("jsAlert1",
			"System Message",
			"<div style='padding:25px;'>Approved Records Successfully</div>",
			{
				okButtonText: "Ok",
				onOk: "ModalPopupsClose()"
			}
		);
	}

	function ModalPopupsAlert5() {
		ModalPopups.Alert("jsAlert1",
			"System Message",
			"<div style='padding:25px;'>Extract Records Successfully</div>",
			{
				okButtonText: "Ok",
				onOk: "ModalPopupsClose()"
			}
		);
	}
	function ModalPopupsClose() {
		ModalPopups.Close("jsAlert1");
	}

</script>

<link href="css_autosuggest/styles.css" rel="stylesheet" type="text/css">
<link href="css_autosuggest/jquery.autocomplete.css" rel="stylesheet" type="text/css">

<!--<script type="text/javascript" src="js/jquery.js"></script> -->
<script type="text/javascript" src="js/jquery.bgiframe.min.js"></script>
<script type="text/javascript" src="js/jquery.autocomplete.min.js"></script>
<script type='text/javascript'>
	$(document).ready(function(){
    	var data = "<?php echo $_mdata; ?>".split(";;");
		$('input#idvendor').autocomplete("search.php", {
			matchContains: true,
			max: 0,
			minChars: 3
		});

	});
</script>

<script language="JavaScript">

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
<script type="text/javascript" src="js/chosen.jquery.min.js" language="javascript"></script>
<title>DM Deduction System</title>
</head>
<?php
error_reporting(E_ALL ^ E_NOTICE);

include('function.php');
include('include_deduction_on.php');
include('withvoucher.php');

//$d_row = mssql_fetch_array($r_select);
$a = 10;
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
	// $onepage = "yes";
	$onepage = "no";
}

// if ($onepage == "yes") {
// 	$disabled1 = 'disabled="disabled"';
// 	$disabled2 = 'disabled="disabled"';
// 	$disabled3 = 'disabled="disabled"';
// 	$disabled4 = 'disabled="disabled"';
// }
// if ($onepage == "yes") {
// 	$disabled1 = 'disabled="disabled"';
// 	$disabled2 = 'disabled="disabled"';
// 	$disabled3 = 'disabled="disabled"';
// 	$disabled4 = 'disabled="disabled"';
// }

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
     	$('#cmdidsearch, #cmdidgo, #cmdsearch, .massupload, .cmdtoexcel').button();
     	$('input:submit, #pmultislip').button();

     	$('#pmultislip').on('click', function(e){
     	  e.preventDefault();
     		 var cmbdept = "<?php echo $_SESSION['dept_code'];?>"; 

         if(cmbdept != 'PUR' && cmbdept != 'MKT' )
     	  {
     	  		url2 = "deductionslip_on.php?branch=<?php echo $branch?>";
     	  	
     	  }
		else
			{
				url2 = "deduction_slip_purch.php";
			}	

		
         window.location.href = url2;  
     	});
     	$('#cmdidsearch, #cmdidgo, #cmdsearch, .massupload, .cmdtoexcel').button();
     	$('input:submit, #print').button();
     	$('#print').on('click', function(e){
     	  e.preventDefault();
     	window.location.href = 'deduction_slip_purch.php?branch=<?php echo $branch; ?>';
     	});
     	$('#cmdidsearch').button({
         icons:{
             primary: 'ui-icon-wrench'
         }
			});
      $('.cmdtoexcel').on('click', function(e){
      	e.preventDefault();
      	$('#cmddownloadid').trigger('click');
      	return false;
      });
      $('#cmddownloadid').on('click', function(e){
      	e.preventDefault();
      	data = $(this).parent('form').serialize();
      	window.location.href = 'xls_report_v2.php?'+data;
      	return false;
      });
   <?php
			if($_pass == 'pass'):
		?>
			$('#chngpass').dialog({
				height: 100,
				width: 300,
				modal: true,
				resizable: false,
				buttons: {
					"Cancel": function()
					{
						$('#chngpass').dialog('close');
					},
					"Change Password": function()
					{
						$('#mmuser').bind('click', function() {
							window.location = $(this).attr('href');
						});
						$('#mmuser').trigger('click');
					}
				}
			});
		<?php
			else:
		?>
			$('#chngpass').dialog({
				height: 100,
				width: 300,
				modal: true,
				autoOpen: false,
				resizable: false,
				buttons: {
					"Cancel": function()
					{
						$('#chngpass').dialog('close');
					},
					"OK": function()
					{
						$('#mmuser').bind('click', function() {
							window.location = $(this).attr('href');
						});
						$('#mmuser').trigger('click');
					}
				}
			});
		<?php
			endif;
		?>

    });
//txtAdate2
</script>

<?php
	if ($cmdnew != '') {
 ?>
	<script>
		window.open('dm_new_on.php','','menubar=no,scrollbars=yes,toolbar=no,resizable=no,width=400,height=<?php echo $height;?>,left=200,top=95,dependent')
	</script>
<?php
	}

	if ($cmdlogout != '') {
		session_destroy();
		?>
		<script>
			// window.close('deductionmain.php');
			// window.open('login.php');
			window.location.href = 'login.php';
		</script>
		<?php
	} else {

	}

	if ($cmdreport != '') {
	?>
	<script>
		window.open("xls_report.php?br=<?php echo $branch?>&dp=<?php echo $dept_code?>&dv=<?php echo $division_code?>&ct=<?php echo $category_code?>&sct=<?php echo $subcat_code?>&st=<?php echo $lststatus?>&lcaccright=<?php echo $_SESSION['type']?>&lcuser=<?php echo $lcuser?>&euser=<?php echo $euser?>&file=<?php echo $fileid?>&datefr=<?php echo $txtDate?>&dateto=<?php echo $txtDate2?>",'','scrollbars=no,resizable=no,width=800,height=800,left=200,top=95,dependent')
	</script>
	<?php
	}
?>
<style type="text/css">
	.user-info {border-bottom:solid 1px #0000F9;margin-bottom:10px;padding-top:5px;height:25px;}
	.user-info div.greetings {float:left;font-size:18px;margin-top: -5px;}
	ul.user-menus {list-style:none;float:right;margin-top:0;}
	ul.user-menus li {float:left;margin-right:5px;}
	ul.user-menus li:last-child{margin-right: 0;}
	ul.user-menus li.divider{width: 10px;margin-right:10px;border-right:solid 1px #ccc;}
	ul li a {font-weight: bold;}
	.flat-button{padding: 5px 12px; white-space: nowrap;vertical-align: middle;border: 1px solid #DDD;background: transparent;
		cursor: pointer;color: #666;text-shadow: 0 1px 1px white;-webkit-box-shadow: 0 1px 1px #fff;
		-moz-box-shadow: 0 1px 1px #fff;box-shadow: 0 1px 1px #fff; -webkit-transition: all 0.2s ease-in-out;
		-moz-transition: all 0.2s ease-in-out; -ms-transition: all 0.2s ease-in-out; -o-transition: all 0.2s ease-in-out;
  		transition: all 0.2s ease-in-out;}
  	.tbl-header{margin-bottom: 5px;}
	.table{width: 100%;margin-bottom:20px;}
	.table.table-striped th {font-size:10px;padding: 5px;line-height:20px;background-color: #99BBDD;border-top: solid 1px #fff;}
	.table.table-striped td {line-height: 20px;padding: 3px;}
	.table.table-striped th,.table.table-striped td {border-right: solid 1px #ddd;}
	.table.table-striped tbody tr:first-child{border-top:none;}
	.table.table-striped tbody tr td{border-top:solid 1px #ddd;vertical-align: top;}
	.table.table-striped tbody tr:nth-child(even) {background-color: #f2f2f2;}
	.table.table-striped th:last-child,.table.table-striped td:last-child {border-right: none;}
	.table.table-striped {border: solid 1px #ddd;}
	.table.table-striped tbody tr td span{display: block;}
	.label-success td {background-color: #dff0d8;}
	.label-warning td {background-color: #fcf8e3;}
	.label-error td {background-color: #f2dede;}
	.label-info td {background-color: #d9edf7;}
	.search-parameters .table tbody tr td {padding:5px;}
	.search-parameters input,.search-parameters select {height:20px;padding-top:1px;padding-left:2px;}
	.search-parameters input:hover {border: solid 1px #ddd;}
	.toggler{position:absolute;top:40px;left:5px;}
/*	.massupload{background:#fcfcfc;color:#0325C2;font-size: 16px !important;width:80%;border: solid 1px #ddd;padding-top: 10px;padding-bottom: 10px;}
	.massupload:hover{border:solid 1px #ddd;background:#f2f2f2;}
	.massupload:active,.massupload:visited{color:#1300FB !important;text-decoration: none;}
*/	.refresh-button{float:left;width: 48%;vertical-align: top;text-align: center !important;}
	.sbutton-controls{float:right; width: 50%; margin-left: 5px;}
</style>
<script type="text/javascript">
	(function(){
		$('.toggler').live('click', function(e){
			e.preventDefault();
			var $thead = $('.tbl-header');
			$thead.slideToggle();
		});
	})();

	$(document).ready(function($) {
		//$('select[name=fileid]').chosen();
	});
</script>
<body>

	<form method="post" action="<?php echo $_SERVER['PHP_SELF']?>" name = "deductionmain">

	<div class="user-info">
		<div class="greetings">
			<strong>DEDUCTION</strong>
		</div>
		<ul class="user-menus">
			<li><a href="statistics.php" title="View status" onClick="javascript:window.open('statistics.php','','scrollbars=yes,resizable=0,width=550,height=450,left=100,top=50').focus();return false;" alt="View Status">View Status</a></li>
			<li class="divider">&nbsp;</li>
			<li><a href='income.php'>SP-RI Report </a></li>
			<li class="divider">&nbsp;</li>
			<li><a href='reports.php'>SP-Total Deductions </a></li>
			<li class="divider">&nbsp;</li>
			<li><a href='s_penalty.php'>Penalty-SP Summary</a></li>
			<li class="divider">&nbsp;</li>
			<li style="text-align: center;">Hi, <a href="register.php?/edit/<?php echo $enc->encode(SELF); ?>" title="Edit Password" id='mmuser'><?php echo $lcusername; ?></a>!</li>
		</ul>
	</div>

	<a href="#" class="toggler ui-icon ui-icon-wrench">
		Hide Search Options
	</a>

	<div class="tbl-header">
		<div class="search-parameters">
			<table class="table">
				<tbody>
					<tr>
						<td width="10%">Division</td>
						<td width="20%">
						<?php if($lcaccrights == 5 or $lcaccrights == 4): ?>
							<select name="division_code" onChange="submit()" onkeypress="return ignoreenter(this,event)" style="width: 90%">
								<option value="">-All-</option>
								<?php
									if ($xbranch == 'S399') {
									$seldiv = "select distinct division_code,division_name from ref_division where division_code not in ('BO')
												order by division_code ";
									} else {
									$seldiv = "select distinct a.division_code,a.division_name from ref_division a left join ref_users b on
												a.division_code = b.division_code where b.user_name like '{$euser}' and a.division_code not in ('BO')
												order by a.division_code ";
									}

									$r_seldiv = mssql_query($seldiv);
									for ($i = 0; $i < ($b_row = mssql_fetch_array($r_seldiv)); $i++):
										$selected = "";
										if (trim($b_row['division_code']) == trim($division_code)) {
											$selected = 'selected="selected"';
										}
              							echo "<option value=".$b_row['division_code'] . " " . $selected . "> " . $b_row['division_name'] ." </option>";
			                		endfor;
			                	?>
							</select>
						<?php elseif ($lcaccrights == 3): ?>
							<select name="division_code" onChange="submit()" onkeypress="return ignoreenter(this,event)" style="width: 90%">
								<option value="">-All-</option>
								<?php
									$seldiv = "select division_code,division_name from ref_division where division_code not in ('BO') order by division_code ";

									$r_seldiv = mssql_query($seldiv);
									for ($i = 0; $i < ($b_row = mssql_fetch_array($r_seldiv)); $i++)
									{
										$selected = "";
										if (trim($b_row['division_code']) == trim($division_code)) {
											$selected = 'selected="selected"';
										}
										echo "<option value=" . $b_row['division_code'] . " " . $selected . ">" . $b_row['division_name'] . "</option>";
									}
								?>
							</select>
						<?php elseif ($lcaccrights == 2 and $xbranch == 'S399' and $_SESSION['dept_code'] <> 'OPS'): ?>
							<select name="division_code" onChange="submit()" onkeypress="return ignoreenter(this,event)" style="width: 90%">
								<option value="">-All-</option>
								<?php
									$seldiv = "select division_code,division_name from ref_division where division_code not in ('BO') order by division_code ";

									$r_seldiv = mssql_query($seldiv);
									for ($i = 0; $i < ($b_row = mssql_fetch_array($r_seldiv)); $i++)
									{
										$selected = "";
										if (trim($b_row['division_code']) == trim($division_code)) {
											$selected = 'selected="selected"';
										}

				                  		echo "<option value=" . $b_row['division_code']. " " . $selected . ">" . $b_row['division_name'] . "</option>";
				                  	}
				                ?>
							</select>
						<?php
							else: echo strtoupper($lcdivname);
							endif
						?>
						</td>
						<td width="10%">Category</td>
						<td width="25%">
							<select name="category_code" onChange="submit()" onkeypress="return ignoreenter(this,event)" style="width: 100%;">
								<option value="">-All-</option>
								<?php
									if ($_SESSION['dept_code'] == 'PUR') {
										$selcat = "select category_code,category_name from ref_category where isactive = 1 and division_code like '{$division_code}'
													and dept_code = 'PUR' order by category_name ";
									} elseif ($_SESSION['dept_code'] == 'MKT') {
										$selcat = "select category_code,category_name from ref_category where isactive = 1 and dept_code in ('PUR','MKT')
													order by category_name ";
									} elseif ($_SESSION['dept_code'] == 'EDP') {
										$selcat = "select category_code,category_name from ref_category where isactive = 1 and division_code =
													'{$_SESSION['divcode']}' order by category_name ";
									} elseif ($_SESSION['dept_code'] == 'OPS' and $lcaccrights == '2') {
										$selcat = "select category_code,category_name from ref_category where isactive = 1 and division_code =
													'{$_SESSION['divcode']}' order by category_name ";
									} else {
											$selcat = "select category_code,category_name from ref_category where isactive = 1 and division_code
													like '{$division_code}' order by category_name ";
									}
									$r_selcat = mssql_query($selcat);
									for ($i = 0; $i < ($b_row = mssql_fetch_array($r_selcat)); $i++)
									{
										$selected = "";
										if (trim($b_row['category_code']) == trim($category_code)) {
											$selected = 'selected="selected"';
										}
										echo "<option value=" . $b_row['category_code'] . " " . $selected . ">" . $b_row['category_name'] . "</option>";
									}
								?>
							</select>
						</td>
						<td width="35%" rowspan="10" class="ctrls" style="text-align: center; padding-left: 20px; padding-right: 20px; vertical-align: top;">
							<div class="refresh-button">
								<input type="submit" name="cmdsearch" value=" REFRESH"  title="Add Payment Order Slip" onClick="submit()" style="height:95px; background-color:#FF9933; width: 100%; padding-left: 5px !important; font-size: 16px;text-align: center;">
								<input type="button" onClick="javascript: window.location.href = 'uploader2/index.php';" style="margin-top: 5px;height:60px;background-color:#FF9933;width: 100%;" class="massupload" value="Mass Upload">
							</div>
							<div class="sbutton-controls">
								<input type="submit" name="cmdnew" value=" Encode New Deduction " <?php echo $cmddisabled?> style="height:40; background-color:#FF9933;width: 100%;">
								<input type="submit" name="cmdpost" value="Approve New Deduction" <?php echo $cmddisabled2?> style="height:40;background-color:#FF9933;width: 100%;">
								<input type="button" style="height:40;background-color:#FF9933;width: 100%;" <?php echo $cmdPrintDs; ?>  id="pmultislip" value="Deduction Slip Printing">
	    						<input type="submit" name="cmdlogout" value="Logout" style="height:40;background-color:#FF9933;width:100%;">
							</div>
						</td>
					</tr>
					<tr>
						<td>Branch</td>
						<td>
							<?php
								$mSites = array_unique($uniqueMainSites);
								if ($lcaccrights == 5 or $lcaccrights == 4 ):
							?>
								<select name="branch" onChange="submit()" style="width: 90%;">
                  					<option value="">-All Branch Under <?php echo strtoupper($lcuser); ?>-</option>
                  					<?php

                  					$marker = 0;
								    if ((int) $lcaccrights === 5 OR (int) $lcaccrights === 4 )
								    {
								        if ($xbranch === 'S399') {
								            if ($_SESSION['dept_code'] === 'ACT') {
								                $seluser = "select distinct branch_code,branch_name,branch_known_name from ref_branch where isactive = 1
								                    order by branch_code";
								            } elseif ($_SESSION['dept_code'] === 'EDP'){
								                $seluser = "select distinct branch_code,branch_name,branch_known_name from ref_branch where isactive = 1
								                    and branch_code in ('S801','S802','S803', 'S301') order by branch_code";
								            }
								            elseif($_SESSION['dept_code'] === 'PUR')
								            {
								                $seluser = "select distinct branch_code,branch_name,branch_known_name from ref_branch where isactive = 1
								                    and branch_code not in ('S801','S802','S803', 'S301') order by branch_code";
								            } else {
								                // this query will handle the branch handle by the supervisor
								                $seluser = "select distinct branch_code,branch_name,branch_known_name from ref_branch where isactive = 1
								                    and branch_code not in ('S801','S802','S803', 'S301') :admin
								                    order by branch_code";

								                if(strpos($lcuser, 'admin') === false AND trim($_SESSION['dept_code']) !== 'MKT')
								                {
								                    $toReplace = "and branch_code in ( select distinct branch_code from
								                    	ref_supervisor where supervisor='".trim($lcuser)."' ) ";
								                    $seluser = str_replace(':admin', $toReplace, $seluser);
								                }
								                else
								                    echo $seluser = str_replace(':admin', '', $seluser);
								                if($_SERVER["REMOTE_HOST"] == "192.168.17.128") {
								                	echo $seluser;
								                }
								            }
								        } else {
								            $seluser = "select distinct a.branch_code,a.branch_name,branch_known_name from ref_branch a left join ref_supervisor b on
								            a.branch_code = b.branch_code where b.supervisor = '{$lcuser}' and b.isactive = 1
								            and a.branch_code not in ('S801','S802','S803', 'S301') order by a.branch_code";
								        }
								        $marker = 1;
									}
								    elseif((int) $lcaccrights === 3)
								    {
								        if ($_SESSION['dept_code'] === 'ACT') {
								            $seluser = "select distinct branch_code,branch_name,branch_known_name from ref_branch where isactive = 1
								                order by branch_code";
								        } elseif ($_SESSION['dept_code'] === 'EDP'){
								            $seluser = "select distinct branch_code,branch_name,branch_known_name from ref_branch where isactive = 1
								                and branch_code in ('S801','S802','S803', 'S301') order by branch_code";
								        } else {
								            $seluser = "select distinct branch_code,branch_name,branch_known_name from ref_branch where isactive = 1
								                and branch_code not in ('S801','S802','S803', 'S301') order by branch_code";
								        }
								        $marker = 1;
								    }
								    elseif((int) $lcaccrights === 2 and $xbranch === 'S399' and $_SESSION['dept_code'] <> 'OPS')
								    {
								        if ($_SESSION['dept_code'] === 'ACT') {
								            $seluser = "select distinct branch_code,branch_name,branch_known_name from ref_branch where isactive = 1
								                order by branch_code";
								        } elseif (trim($_SESSION['dept_code']) === 'EDP'){
								            $seluser = "select distinct branch_code,branch_name,branch_known_name from ref_branch where isactive = 1
								                    and branch_code in ('S801','S802','S803', 'S301') order by branch_code";
								        } else {
								            $seluser = "select distinct branch_code,branch_name,branch_known_name from ref_branch where isactive = 1
								                and branch_code not in ('S801','S802','S803', 'S301') order by branch_code";
								        }
								        $marker = 1;
								    }
								    else
								    {
								        $marker = 0;
								        echo '<option value="'.$branch.'" selected="selected">'.$glbranchname.'</option>';
								    }

								    if($marker AND trim($seluser) !== '')
								    {
											$rsuser = mssql_query($seluser);
											$mData = array();
											while($b_row = mssql_fetch_array($rsuser))
											{
												$mData[] = $b_row;
											}

												$selected = "";
												foreach($mData as $m)
												{

													$sbranch = (strtoupper(trim($m['branch_code'])) === 'S399') ? 'S306': $m['branch_code'];

													$selected = "";
													if (trim($m['branch_code']) == trim($branch)) {
														$selected = 'selected="selected"';
													}

													echo "<option value='" . trim($m['branch_code']) . "' " . $selected . ">".$sbranch.' &mdash; '.$m['branch_known_name'] . "</option>";
												}
									  }
									?>
				                </select>
							<?php elseif($lcaccrights == 3): ?>
								<select name="branch" onChange="submit()" style="width: 90%;">
                  					<option value="">-All Branch Under <?php echo strtoupper($lcuser); ?>-</option>
										<?php
											if ($_SESSION['dept_code'] == 'ACT') {
												$seluser = "select distinct branch_code,branch_name,branch_known_name from ref_branch where isactive = 1
													order by branch_code";
											} elseif ($_SESSION['dept_code'] == 'EDP'){
												$seluser = "select distinct branch_code,branch_name,branch_known_name from ref_branch where isactive = 1
													and branch_code in ('S801','S802','S803') order by branch_code";
											} else {
												$seluser = "select distinct branch_code,branch_name,branch_known_name from ref_branch where isactive = 1
													and branch_code not in ('S801','S802','S803') order by branch_code";
											}
											$rsuser = mssql_query($seluser);
											for ($i = 0; $i < ($b_row = mssql_fetch_array($rsuser)); $i++)
											{
												$selected = "";
												if (trim($b_row['branch_code']) == trim($branch)) $selected = 'selected="selected"';

						                  		echo "<option value='" . $b_row['branch_code'] . "' " . $selected . ">" . $b_row['branch_code'] .' &mdash; '.$b_row['branch_known_name'] . "</option>";
						                  	}
						                ?>
                				</select>
							<?php elseif($lcaccrights == 2 and $xbranch == 'S399' and $_SESSION['dept_code'] <> 'OPS'): ?>
								<select name="branch" onChange="submit()" style="width: 90%;">
            			      		<option value="">-All Branch Under <?php echo strtoupper($lcuser); ?>-</option>
					                  	<?php
											if ($_SESSION['dept_code'] == 'ACT') {
												$seluser = "select distinct branch_code,branch_name,branch_known_name from ref_branch where isactive = 1
													order by branch_code";
											} elseif (trim($_SESSION['dept_code']) == 'EDP'){
												$seluser = "select distinct branch_code,branch_name,branch_known_name from ref_branch where isactive = 1
														and branch_code in ('S801','S802','S803') order by branch_code";
											} else {
												$seluser = "select distinct branch_code,branch_name,branch_known_name from ref_branch where isactive = 1
													and branch_code not in ('S801','S802','S803') order by branch_code";
											}
											$rsuser = mssql_query($seluser);
											for ($i = 0; $i < ($b_row = mssql_fetch_array($rsuser)); $i++)
											{
												$selected = "";

												if (trim($b_row['branch_code']) == trim($branch)) {
													$selected = 'selected="selected"';
												}
								                echo "<option value=" . $b_row['branch_code'] . " " . $selected . ">" . $b_row['branch_name'] . "</option>";
								            }
                  						?>
                				</select>
							<?php
								else: echo strtoupper($glbranchname);
								endif;
							?>
						</td>
						<td>Sub-Category</td>
						<td>
							<select name="subcat_code" onkeypress="return ignoreenter(this,event)" style="width: 100%;">
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
										echo "<option value=" . $b_row['subcat_code'] . " " . $selected . ">" . $b_row['subcat_name'] . "</option>";
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
					<td>Department</td>
						<td>
							<?php if ($lcaccrights == 5 or $lcaccrights == 4): ?>
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
												echo "<option value=" . $b_row['dept_code'] . " " . $selected . ">" . $b_row['dept_name'] . "</option>";
											}
					                  	?>
								</select>
							<?php elseif ($lcaccrights == 3): ?>
								<select name="dept_code" onChange="submit()" onkeypress="return ignoreenter(this,event)" style="width: 90%">
									<option value="">-All-</option>
										<?php

											$seldept = "select dept_code,dept_name from ref_department order by dept_code";
											$r_seldept = mssql_query($seldept);
											for ($i = 0; $i < ($b_row = mssql_fetch_array($r_seldept)); $i++)
											{
												$selected = "";
												if (trim($b_row['dept_code']) == trim($dept_code)) {
													$selected = 'selected="selected"';
												}
												echo "<option value=" . $b_row['dept_code'] . " " . $selected . ">" . $b_row['dept_name'] . "</option>";
											}
										?>
					                </select>
									<?php
										else: echo strtoupper($lcdeptname);
										endif;
									?>
						</td>
						<td><?php echo $dateshow; ?> From</td>
						<td>
							<input name="txtDate" type="text" size="15" maxlength="10" id="txtAdate" onFocus="" value="<?php echo $txtDate ?>" onKeyPress="return numbersonly(this, event)"/>
							<span><?php echo $dateshow; ?> To</span>
							<input name="txtDate2" type="text" size="15" maxlength="10" id="txtAdate2" onFocus="" value="<?php  echo $txtDate2  ?>" onKeyPress="return numbersonly(this, event)"/>
						</td>
					</tr>
					<tr>
						<td>User</td>
						<td>
							<?php if ($lcaccrights == 5 or $lcaccrights == 4): ?>
								<select name="euser" style="width: 90%;" onChange="submit()">
									<option value="">-All User Under <?php echo strtoupper($lcuser); ?>-</option>
									<?php
										if (trim($_SESSION['dept_code']) == 'AUD'){
										$selbr = "select user_name,name from ref_users where isactive = 1 and access_right not in ('1','5','3') ";
										} else {
										$selbr = "select a.user_name,a.name from ref_users a left join ref_supervisor b on a.user_name = b.user_name where a.isactive = 1
												and b.supervisor = '{$lcuser}' ";
												//and a.branch_code like '{$branch}'
										}

										$r_selbr = mssql_query($selbr);
										for ($i = 0; $i < ($b_row = mssql_fetch_array($r_selbr)); $i++)
										{
											$selected = "";
											if (trim($b_row['user_name']) == trim($euser)) {
												$selected = 'selected="selected"';
											}
											echo "<option value=" . $b_row['user_name'] . " " . $selected . ">" . $b_row['name'] . "</option>";
										}
									?>
								</select>
							<?php elseif ($lcaccrights == 3): ?>
								<select name="euser" style="width: 90%;" onChange="submit()">
									<option value="">-All User Under <?php echo strtoupper($lcuser); ?>-</option>
										<?php
											$selbr = "select distinct a.user_name,a.name from ref_users a left join ref_supervisor b on a.user_name = b.user_name where a.isactive = 1 and dept_code in ('PUR','MKT','OPS','ACT') ";

											$r_selbr = mssql_query($selbr);
											for ($i = 0; $i < ($b_row = mssql_fetch_array($r_selbr)); $i++)
											{
												$selected = "";
												if (trim($b_row['user_name']) == trim($euser))
												{
													$selected = 'selected="selected"';
												}
												echo "<option value=" . $b_row['user_name'] . " " . $selected . ">" . $b_row['name'] . "</option>";
											}
										?>
								</select>
							<?php elseif ($lcaccrights == 2 and trim($_SESSION['dept_code']) == 'MKT'): ?>
								<select name="euser" style="width: 90%;" onChange="submit()">
									<option value="">-All User Under <?php echo strtoupper($lcuser); ?>-</option>
										<?php
											$selbr = "select distinct a.user_name,a.name from ref_users a left join ref_supervisor b on a.user_name = b.user_name where a.isactive = 1 and dept_code in ('PUR','MKT') ";

											$r_selbr = mssql_query($selbr);
											for ($i = 0; $i < ($b_row = mssql_fetch_array($r_selbr)); $i++)
											{
												$selected = "";
												if (trim($b_row['user_name']) == trim($euser)) {
													$selected = 'selected="selected"';
												}
												echo "<option value=" . $b_row['user_name'] . " " . $selected . ">" . $b_row['name'] . "</option>";
											}
										?>
								</select>
								<?php
									else: echo strtoupper($lcusername);
									endif;
								?>
						</td>
						<td>Status</td>
						<td>
							<?php if ($lcaccrights != 6 ): ?>
							  <select name="lststatus" style="width: 50%;" onChange="submit()">
							    <?php
									$stat = "";
									$stat0 = "";
									$stat1 = "";
									$stat2 = "";
									$stat3 = "";
									$stat4 = "";

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
//										case "3": //for extraction
//											$stat3 = 'selected="selected"';
//											break;
										case "4": //for printed
											$stat4 = 'selected="selected"';
											break;
									}

								?>
							    <option value="" <?php echo $stat?>>&nbsp;- All -&nbsp;</option>
							    <option value="0" <?php echo $stat0?>>New</option>
							    <option value="2" <?php echo $stat2?>>Cancelled</option>
							    <option value="1" <?php echo $stat1?>>Approved</option>
<!--							    <option value="3" <?php //echo $stat3?>>Extracted</option>-->
							    <option value="4" <?php echo $stat4?>>Printed</option>
							  </select>
							<?php endif; ?>
							<?php if(in_array((int) $lststatus, array(4))): ?>
							<select name="cmbstat" id="cmbstatid" onChange="submit()">
								<option value="%" <?php echo $selstats['%']; ?>>All</option>
								<option value="1" <?php echo $selstats[1]; ?>>Deducted</option>
								<option value="0" <?php echo $selstats[0]; ?>>Undeducted</option>
							</select>
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td>Filename</td>
						<td>
							<?php
								if ($lcaccrights == 5 or $lcaccrights == 4 ):
					  			if ($xbranch == 'S399') {
										$selfile = "select id as fileid,filename from deductions_upload where status_id = 3
													order by upload_date desc, ref_division_id,ref_branch_id,ref_department_id";
									} else {
										$selfile = "select a.id as fileid,a.filename from deductions_upload a
													left join ref_supervisor b on a.upload_by = b.user_name
													where b.supervisor = '{$lcuser}' and b.isactive = 1 and a.status_id = 3
													group by a.id,a.filename,a.ref_branch_id
													order by a.filename,a.ref_branch_id";
									}
							?>
								<select name="fileid" onChange="submit()" style="clear:both;width: 90%;">
                  					<option value=""></option>
                  <?php
										$rsfile = mssql_query($selfile);
										for ($i = 0; $i < ($b_row = mssql_fetch_array($rsfile)); $i++)
										{
											$selected = "";
											if (trim($b_row['fileid']) == trim($fileid)) {
												$selected = 'selected="selected"';
											}
											echo "<option value=" . $b_row['fileid'] . " " . $selected . ">" . $b_row['filename'] . "</option>";
										}
									?>
				                </select>
							<?php elseif($lcaccrights == 3): ?>
								<select name="fileid" onChange="submit()" style="clear:both;width: 90%;">
                  					<option value=""></option>
				                  	<?php
										$selfile = "select  id as fileid,filename from deductions_upload where status_id = 3 order by upload_date desc, ref_branch_id";
										$rsfile = mssql_query($selfile);
										for ($i = 0; $i < ($b_row = mssql_fetch_array($rsfile)); $i++)
										{
											$selected = "";
											if (trim($b_row['fileid']) == trim($fileid)) {
												$selected = 'selected="selected"';
											}
						                  	echo "<option value=" . $b_row['fileid'] . " " . $selected . ">" . $b_row['filename'] . "</option>";
						                }
						            ?>
				                </select>
							<?php elseif ($lcaccrights == 2 and $xbranch == 'S399'): ?>
								<select name="fileid" onChange="submit()" style="clear:both;width: 90%;">
                  					<option value=""></option>
					                  	<?php
											$selfile = "select id as fileid,filename from deductions_upload where upload_by = '{$lcuser}' and status_id = 3
														order by upload_date desc, filename";
											$rsfile = mssql_query($selfile);
											for ($i = 0; $i < ($b_row = mssql_fetch_array($rsfile)); $i++)
											{
												$selected = "";
												if (trim($b_row['fileid']) == trim($fileid)) {
													$selected = 'selected="selected"';
												}
								                echo "<option value=" . $b_row['fileid'] . " " . $selected . ">" . $b_row['filename'] . "</option>";
								            }
								        ?>
				                </select>
							<?php else: ?>
								<select name="fileid" onChange="submit()" style="clear:both;width: 90%;">
                  					<option value=""></option>
                  						<?php
											$selfile = "select id as fileid,filename from deductions_upload where upload_by = '{$lcuser}' and status_id = 3 order by upload_date desc, filename";
											$rsfile = mssql_query($selfile);
											for ($i = 0; $i < ($b_row = mssql_fetch_array($rsfile)); $i++)
											{
												$selected = "";
												if (trim($b_row['fileid']) == trim($fileid)) {
													$selected = 'selected="selected"';
												}
					                  			echo "<option value=" . $b_row['fileid'] . " " . $selected . ">" . $b_row['filename'] . "</option>";
					                  		}
					                  	?>
					                	</select>
					        <?php endif; ?>
						</td>
						<td>Vendor</td>
						<td>
							<input style="width: 50%" type="text" name="vendor" id="idvendor" value="<?php echo $vendor; ?>" tabindex="8" />
							<input type="checkbox" name="cmbconspo" id="cmbconspo" value="0" <?php echo (isset($_POST['cmbconspo'])) ? 'checked="checked"': ''; ?>>with Ordering Branch
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<div class="mcontent">

		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th width="3%"><strong><input type="checkbox" name="markall" value="yes" onClick="submit()" <?php echo $checkedx ;?> ></strong></th>
					<th width="5%%">Control#</th>
					<th width="6%">DS #</th>
					<th width="6%">CV #</th>
					<th width="7%">Branch</th>
					<th width="23%">Vendor Name</th>
					<th width="10%">Category</th>
					<th width="6%">Amt</th>
					<th width="6%">Buyer</th>
					<th width="10%">Department</th>
					<th width="8%">Payment Type</th>
					<th width="7%">Period</th>
					<th width="5%">Encoded by</th>
					<th width="5%">Approved by</th>
					<th width="7%">Status</th>
					<?php if ($lcaccrights == '3' or $lcaccrights == '5'): ?>
						<th width="6%"><strong>Action</strong></th>
					<?php else: ?>
						<th width="6%"><strong>Action</strong></th>
					<?php endif; ?>
				</tr>
			</thead>
			<tbody>
			<?php
			if ($nmrow > 0):
				// $r_select = mssql_query($selqry);

				$cnt = $pageno*$a;
				$firstPage = $a;
				$lastPage = $cnt;
				$dataids = array();

				for ($i = 0; $i < ($d_row = mssql_fetch_array($r_select)); $i++):
					$amount = $d_row['amount'];
					$total = $total + $amount;
					$val = $i % 2;

					if ($val == 0) {
						$colorval = "style='background-color: #fff;'";
					} else {
						$colorval = "style='background-color: #F2F2F2;'";
					}

          			switch($d_row['vposted']) {
						case 0: //unposted
		  					$stat = 'New';
							$colorval = '';
							$sclass = 'label-warning';
							break;
						case 1:
							// $colorval = 'green';
							// normal color
							$stat = 'Approved';
							$sclass = 'label-success';
							break;
						case 2:
							$stat = 'Cancelled';
							$colorval = '';
							$sclass = 'label-error';
							break;
						case 3:
							$colorval = '';
							$sclass = 'label-info';
							$stat = 'Extracted';
							break;
					}

					if ($i >= ($cnt-$a) && $i < ($cnt)):
						if($d_row['dmno'] !== ' ') $dataids[] = $d_row['dmno'];

						$dsp = " select deduction_slip_prints.printed_date from deduction_slip_prints left join
							deduction_slip_print_details on deduction_slip_prints.id=deduction_slip_print_details.deduction_slip_prints_id
							where ltrim(rtrim(deduction_slip_print_details.dm_ctrl_no))='{$d_row['dm_no']}' ";
						$dspRst = mssql_query($dsp);
						$prDate = @mssql_result($dspRst, 0, 'printed_date');
			?>
				<tr class="<?php echo $class; ?>" <?php echo $colorval; ?>>
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
					  } elseif ($d_row['vposted'] == 2) {
					  	$chkdisabled = 'disabled="disabled"';
					  }
				?>
				  	<td rowspan="2" height="12"><input type="checkbox" name="box[]" value="<?php echo $d_row['dm_no'] ?>" <?php echo $checkedx ;?> <?php echo $chkdisabled;?> /></td>
	              	<td rowspan="2">
	              		<a href="" title="View History" onClick="javascript:window.open('history.php?dmno=<?php echo trim($d_row['dm_no'])?>&branch=<?php echo trim($d_row['branch_code']) ?>','','scrollbars=no,resizable=no,width=600,height=500,left=250,top=95,dependent').focus();return false;" alt="View History"><?php echo $d_row['dm_no']?></a>
	              		<span style="display: block;"><em>
	              			<?php echo ($d_row['dm_date']) ? date('Y-m-d', strtotime($d_row['dm_date'])): ''; ?></em>
	              		</span>
	              		<span style="display: block;"><em>
	              			 <?php echo ($d_row['lbr_number']) ? 'LBR#:'. $d_row['lbr_number'] : ''; ?></em>
	              		</span>
	              	</td>
				  	<td rowspan="2">
				  		<span style="display: block;"><?php echo $d_row['dmno']; ?></span>
				  		<span style="display: block;"><em><?php echo ($prDate) ? date('Y-m-d', strtotime($prDate)): ''; ?></em></span>
				  	</td>
				  	<td rowspan="2" style="text-align: center;" id="vnumber<?php echo trim($d_row['dmno']); ?>">&nbsp;</td>
	              	<td rowspan="2">
	              		<span>Branch: <?php echo (trim($d_row['branch_code']) == 'S399') ? 'S306' : $d_row['branch_code']; ?></span>
	              		<span>Dept: <?php echo $d_row['dept_code']; ?></span>
	              		<span>Division: <?php echo $d_row['division_code']; ?></span>
	              	</td>
	              	<td>
	              		<?php echo $d_row['suppliername']?>
					</td>
					<td>
						<?php echo $d_row['category_name']?>
						<span><?php echo $d_row['subcat_name']?></span>
					</td>
					<td style="text-align: right;"><?php echo number_format($d_row['amount'],2)?></td>
					<td><?php echo $d_row['buyer_code']?></td>
					<td><?php echo $d_row['department']?></td>
					<td><?php echo $d_row['paymentdesc']?></td>
					<td><?php echo $d_row['period']?></td>
					<td><?php echo $d_row['encoded_by']?></td>
					<td><?php echo $d_row['review_by']?></td>
                  	<td style="text-align: center;">
                  		<span style="display: block;">
                  			<?php
								switch($d_row['vposted'])
								{
								case 0: //vposted
									echo 'New';
									break;
								case 1:
									echo 'Approved';
									break;
								case 2:
									echo 'Cancelled';
									break;
								case 4:
									echo 'Printed';
									break;
								}



            //      				if((int) $d_row['paymentid'] === 2 and trim($d_row['vposted'] != '2'))
//                  				{
//	                  				switch ((int) $lststatus) {
//	                  					case 4:
//	                  						echo "Printed";
//	                  						break;
//	                  					default:
//	                  						//var_dump($d_row['review_date']);
//	                  						echo (!is_null($d_row['review_date']) AND !is_null($prDate)) ?
//	                  						 'Printed': $stat;
//	                  						break;
//	                  				}
//								}
//								elseif (trim($d_row['vposted'] == '2'))
//								{
//									echo "Cancelled";
//                  				} else
//                  					echo $stat;
                  				// if( ((int) $lststatus !== 4 AND ! $d_row['printed_date']) AND trim($d_row['dmno']) === '') echo $stat;
                  				// else echo "Printed";
                  			 ?>
                  		</span>
                  		<span style="display: block;">
                  			<em>
                  			<?php
                  				if((int) $d_row['paymentid'] === 2)
                  				{
	                  				switch ((int) $lststatus) {
	                  					case 4:
		                  					echo date('Y-m-d', strtotime($d_row['printed_date']))
		                  						.' [<strong>'.$d_row['batch'].'</strong>]';
	                  						break;
	                  				  case 2:
		                  					echo date('Y-m-d', strtotime($d_row['cancelleddate']));
		                  					break;
	                  				  case 0:
		                  					echo date('Y-m-d', strtotime($d_row['dm_date']));
		                  					break;
	                  					default:
	                  						//echo (! is_null($d_row['review_date'])) ?
	                  						echo (! is_null($prDate)) ?
	                  						  date('Y-m-d', strtotime($d_row['review_date'])): '';
	                  						break;
	                  				}
	                  			} else
	                  				echo (is_null(strtotime($d_row['review_date']))) ?
	                  					date('Y-m-d', strtotime($d_row['dm_date'])):
	                  					date('Y-m-d', strtotime($d_row['review_date']));
                  				//if((int) $lststatus !== 4 AND $d_row['printed_date'])
                  				//	echo (! is_null($d_row['review_date'])) ? date('Y-m-d', strtotime($d_row['review_date'])): '';
                  				//else
                  			?>
                  			</em>
                  		</span>
                  	</td>
                  	<td>
                  	<?php
                  	  $_supervisor = 4;
                  	  $_encoder = 2;

                  	  $withcv = new WithVoucher();
                  	  $isdeducted = trim($d_row['dmno']) === '' ? 0 : $withcv->verifycv($d_row['branch_code'],$d_row['dmno']);


                  	  $_vposted = ($d_row['vposted'] === 4) ? 1: $d_row['vposted'];
	                    /*echo $_vposted;
    					echo $isdeducted;*/
                  	  	//if ($isdeducted === 0 ):

                  	  $fromLBR = $withcv->checklbr(trim($d_row['dm_no']));
                  	  $lbr_numrec = strlen(trim($fromLBR[0]->lbr_number));

                  	  if ($lbr_numrec == 0)
                  	  {
                  			if (in_array((int) $lcaccrights, array($_supervisor, $_encoder))):
                  				$_vpostedArr = array(1,2,3); // order => 1:approved, 2:cancelled, 3:extracted
                        		if (in_array((int) $_vposted, $_vpostedArr) and $isdeducted == 0):
							  		if ((int) $lcaccrights == 4 and $_vposted == 1 AND trim($d_row['dmno']) == '' and $isdeducted == 0):
					          			?>
                                    		<a href="dm_edit.php" class="ui-icon ui-icon-pencil" style="float: left; display: inline-block;" onClick="javascript:window.open('dm_edit.php?dmno=<?php echo trim($d_row['dm_no'])?>&branch=<?php echo trim($d_row['branch_code'])?>','','scrollbars=yes,resizable=no,width=400,height=510,left=200,top=95,dependent').focus();return false;" alt="Edit DM Deduction">Edit</a>
                                    		<a href="dm_delete.php" class="ui-icon ui-icon-close" style="float: left; display: inline-block;" onClick="javascript:window.open('dm_delete.php?dmno=<?php echo trim($d_row['dm_no'])?>&branch=<?php echo trim($d_row['branch_code'])?>','','scrollbars=no,resizable=no,Width=380,height=260,left=200,top=95,dependent').focus();return false;" alt="Delete DM Deduction">Cancel</a>
                          				<?php
                          			else:
                                		if((int) $_vposted == 1 AND ((int) $lcaccrights == 4) and $isdeducted == 0):
	                                	// remove this condition to allowed print/approved status to be cancelled
	                                	// AND trim($d_row['dmno']) !== ''
                            		?>
                                    	<a href="dm_delete.php" class="ui-icon ui-icon-close" style="float: left; display: inline-block;" onClick="javascript:window.open('dm_delete.php?dmno=<?php echo trim($d_row['dm_no'])?>&branch=<?php echo trim($d_row['branch_code'])?>','','scrollbars=no,resizable=no,Width=380,height=260,left=200,top=95,dependent').focus();return false;" alt="Delete DM Deduction">Cancel</a>
                            		<?php
                            			endif;
                        			endif;
                        		else:
                        			if ($_vposted <> 2 and $isdeducted == 0):

                        		?>
                                		<a href="dm_edit.php" class="ui-icon ui-icon-pencil" style="float: left; display: inline-block;" onClick="javascript:window.open('dm_edit.php?dmno=<?php echo trim($d_row['dm_no'])?>&branch=<?php echo trim($d_row['branch_code'])?>','','scrollbars=yes,resizable=no,width=400,height=510,left=200,top=95,dependent').focus();return false;" alt="Edit DM Deduction">Edit</a>
                                		<a href="dm_delete.php" class="ui-icon ui-icon-close" style="float: left; display: inline-block;" onClick="javascript:window.open('dm_delete.php?dmno=<?php echo trim($d_row['dm_no'])?>&branch=<?php echo trim($d_row['branch_code'])?>','','scrollbars=no,resizable=no,Width=380,height=260,left=200,top=95,dependent').focus();return false;" alt="Delete DM Deduction">Cancel</a>                                	
                        		<?php
                        			endif;
                        		endif;
                        	else:
                        ?>
                            <!-- <a href="dm_edit.php" onClick="javascript:window.open('dm_edit.php?dmno=<?php //echo json_encode(array(trim($d_row['dm_no'])))?>&branch=<?php //echo trim($d_row['branch_code'])?>','','scrollbars=yes,resizable=no,width=400,height=510,left=200,top=95,dependent').focus();return false;" alt="Edit DM Deduction">View</a> -->
                  	<?php 
                  			endif; 
                  	  }
                  	  	//endif;

                  	  include('sqlconn.php');
                  	?>
                  	</td>

	            </tr>
	            <tr class="<?php echo $class; ?>" <?php echo $colorval; ?>>
	            	<td colspan="5" <?php echo ($class) ? 'style="border-top: none;"': ''; ?>>
	            		<?php echo (trim($d_row['remarks1']) === '') ? trim($d_row['remarks']): trim($d_row['remarks1']); ?>&nbsp;
	            		<?php if((int) $lststatus === 2):
	            			echo '<em style="color: red; font-weight: 900;">
	            			Cancellation Remarks: '.$d_row['cancel_remarks'].'</em>';
	            		endif; ?>
	            	</td>
	            	<td colspan="3" style="color: red;">
	            		<?php if(! is_null($d_row['main_site'])): ?>
	            		Deducted to: <strong><?php echo trim($d_row['main_site']).' &mdash; '.$mainSitesList[trim($d_row['main_site'])]['name']; ?></strong>
	            		<?php endif; ?>
	            	</td>
	            </tr>
			<?php
						endif;
					endfor;
				endif;
				$nmrow = $tCount;
			?>
				<tr>
					<td colspan="100%" style="border-bottom: solid 1px #ddd;">
						<input type="hidden" name="txthiddenpageno" value="<?php echo $pageNo; ?>">
						<ul class="user-menus">
							<li><strong> <?php echo $pgrec; ?> - <?php echo $pgttl; ?> of <?php echo $nmrow; ?> Records |  Page &nbsp;&nbsp;</strong></li>
							<li><input type="submit" name="pagepost" value="First" title="" <?php echo $disabled1; ?> style="height:20;"></li>
							<li><input type="submit" name="pagepost" value="Prev" title="" <?php echo $disabled2; ?> style="height:20;"></li>
							<li><input type="submit" name="pagepost" value="Next" title="" <?php echo $disabled3; ?> style="height:20;"></li>
							<li><input type="submit" name="pagepost" value="Last" title="" <?php echo $disabled4; ?> style="height:20;"></li>
						</ul>
						<strong>Total Amount: &nbsp;&nbsp;<?php echo number_format($total,2);?></strong>
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="100%">
					<?php 
					if ($nmrow >= 4500 && $_SERVER["REMOTE_HOST"] <> "192.168.17.128")
					{
					?>
						<label>Unable to export records..</label>
					<?php
					} else {
					?>
						<a class="cmdtoexcel" href="xls_report_v2.php?br=<?php echo $branch?>&dp=<?php echo $dept_code?>&dv=<?php echo $division_code?>&ct=<?php echo $category_code?>&sct=<?php echo $subcat_code?>&st=<?php echo $lststatus?>&lcaccright=<?php echo $_SESSION['type']?>&lcuser=<?php echo $lcuser?>&euser=<?php echo $euser?>&file=<?php echo $fileid?>&datefr=<?php echo $txtDate?>&dateto=<?php echo $txtDate2?>&cmbstatus=<?php echo $_POST['cmbstat']; ?>">Export to Excel</a>						
					<?php
					}
					?>
					</td>
				</tr>
			</tfoot>
		</table>

	</div>

<?php
//echo $selqry;
//echo $lststatus;
	//if ($cmdcancel != '' ) {
//		if ($markall == "yes" ) {
//			echo '<a href="javascript:ModalPopupsConfirm1();">.</a>';
//			?>
			<script>
//				ModalPopupsConfirm1()
			</script>
			<?php
//			if ($press == 'Yes') {
//				$x_select = mssql_query($selqry);
//				for ($x = 0; $x < ($x_row = mssql_fetch_array($x_select)); $x++) {
//					$dmnox = $x_row['dm_no'];
//					$canqry = "Execute dm_cancel '{$dmnox}','{$lcuser}','{$cancelrem}'";
//					$r_canqry = mssql_query($canqry);
//				}
//			}
//		} else {
//			if (isset($_POST['box'])) {
//				echo '<a href="javascript:ModalPopupsConfirm1();">.</a>';
//				?>
				<script>
//					ModalPopupsConfirm1();
//					//document.deductionmain.submit();
				</script>
				<?php
//				if ($press == 'Yes') {
//					$forwardxx =  $_POST["box"] ;
//					foreach ($forwardxx as $dm)
//					{
//						$dmnox = $dm ;
//						$canqry = "Execute dm_cancel '{$dmnox}','{$lcuser}','{$cancelrem}'";
//						$r_canqry = mssql_query($canqry);
//					}
//				}
//			} else {
//				echo '<a href="javascript:ModalPopupsAlert1();">.</a>';
//				?>
				<script>
//					ModalPopupsAlert1()
//				</script>
				<?php
//			}
//		}
//	}

	if ($cmdpost != '') {
		if ($markall == "yes" ) {
			$x_select = mssql_query($selqry);
			for ($x = 0; $x < ($x_row = mssql_fetch_array($x_select)); $x++) {
				$dmnox = $x_row['dm_no'];
				$canqry = "Execute dm_posting '{$dmnox}','{$lcuser}'";
				$r_canqry = mssql_query($canqry);
			}
			echo '<a href="javascript:ModalPopupsAlert4();">.</a>';
			?>
			<script>
				//ModalPopupsConfirm2()
				ModalPopupsAlert4();
			</script>
			<?php
		} else {
			if (isset($_POST['box'])) {
				$forwardxx =  $_POST["box"] ;
				foreach ($forwardxx as $dm)
				{
					$dmnox = $dm ;
					$canqry = "Execute dm_posting '{$dmnox}','{$lcuser}'";
					$r_canqry = mssql_query($canqry);
				}
				echo '<a href="javascript:ModalPopupsAlert4();">.</a>';
				?>
				<script>
					ModalPopupsAlert4()
				</script>
				<?php
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
				$update_xno = "Update ext_autoid set extraction_refno = '{$extno}' ";
				$rs_xno = mssql_query($update_xno);
			}

			$x_select = mssql_query($selqry);
			for ($x = 0; $x < ($x_row = mssql_fetch_array($x_select)); $x++) {
				$dmnox = $x_row['dm_no'];
				$canqry = "Execute dm_extract '{$dmnox}','{$extno}','{$lcuser}'";
				$r_canqry = mssql_query($canqry);
			}
			//echo '<a href="php_excel_gen.php?extno="'.$extno.'>.</a>';
			//call php_excel_gen.php?extno=<?php echo $extno
			echo '<a href="javascript:ModalPopupsAlert5();">.</a>';
			?>
			<script>
				window.open("php_xls_gen.php?extno=<?php echo $_SESSION['extno']?>",'','scrollbars=no,resizable=no,width=50,height=10,left=355,top=340');
				ModalPopupsAlert5();
			</script>
			<?php
		} else {
			if (isset($_POST['box'])) {
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
					$update_xno = "Update ext_autoid set extraction_refno = '{$extno}' ";
					$rs_xno = mssql_query($update_xno);
					$_SESSION['extno'] = $extno;
				}

				$forwardxx =  $_POST["box"] ;
				foreach ($forwardxx as $dm)
				{
					$dmnox = $dm ;
					$canqry = "Execute dm_extract '{$dmnox}','{$extno}','{$lcuser}'";
					$r_canqry = mssql_query($canqry);
				}
				echo '<a href="javascript:ModalPopupsAlert5();">.</a>';
				?>
				<script>
					window.open("php_xls_gen.php?extno=<?php echo $_SESSION['extno']?>",'','scrollbars=no,resizable=no,width=50,height=10,left=355,top=340');
					ModalPopupsAlert5();
				</script>
				<?php

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
	//echo $seluser;

?>

	 <input name="cancelrem" type="text" value="<?php echo $cancelrem ?>" id="idcancelrem" readonly="yes" size="1" style="background:none; border:none; font-size:1px" />
	 <input name="press" type="text" value="<?php echo $press ?>" id="idpress" readonly="yes" size="1" style="background:none; border:none; font-size:1px" />
	  <!--style="background:none; border:none; font-size:1px"-->


    <input type="hidden" name="pageno" value="<?php echo $pageno;?>" />
	  <input type="hidden" name="qrystat" value="<?php echo $qrystat;?>" />
	  <input type="submit" name="cmddownload" id="cmddownloadid" value="download" style="display:none;">
	</form>

	<div id="chngpass" title="Change Password">
        <p>
        You're password is not yet changed, please change your password for security purposes. Please don't <strong>share</strong> your password to others.
        </p>
    </div>



<script type="text/javascript">
	var options = {
		script:"filesugg.php?json=false&",
		varname:"input",
		json:true,
		callback: function (obj) { document.getElementById('fileid').value = obj.id; }
	};
	var as_json = new AutoSuggest('idfilename', options);

	var options_xml = {
		script:"filesugg.php?",
		varname:"input"
	};
	var as_xml = new AutoSuggest('testinput_xml', options_xml);

	(function(){
		var dataids = '<?php echo json_encode($dataids); ?>'
		, url = 'vouchers.php';
		// process the voucher
		$.post(url, {dataid: dataids}).success(function(data){
			$.each(data, function(a, b){
				$('#vnumber'+a).html('<span style="display: block;">'+b.id+'</span><span style="display: block;">'+b.date+'</span>');
				// $('#vnumber'+a).parent('tr').addClass('success').next('tr').addClass('success').children().css('border-top', 'none');
			});
		});
	})();
</script>

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

