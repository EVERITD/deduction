<?php
session_start();
$x = strlen($_SESSION['user']);
//$x = strlen('gene');
if ($x > 0 ) {
	$lcuser  = $_SESSION['user'] ;
	$glbranchcode = $_SESSION['branch_code'] ;
	$lcusername = $_SESSION['username'] ;
	$lcdeptcode = $_SESSION['dept_code'] ;
	$lcdivision = $_SESSION['divcode'];
	$lcaccrights = $_SESSION['type'];
	date_default_timezone_set('Asia/Manila');

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
<script type="text/javascript"  language="javascript" src="js/jquery-ui.js"></script>

<link rel="stylesheet" href="css/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />
<script type="text/javascript" src="js/bsn.AutoSuggest_c_2.0.js"></script>


<title>Debit Memo Data Entry</title>

<script type="text/javascript" language="javascript">
        function ModalPopupsAlert1() {
            ModalPopups.Alert("jsAlert1",
                "System Message",
                "<div style='padding:25px;'>Data Successfully saved...</div>", 
                {
                    okButtonText: "Ok",
					onOk: "ModalPopupsClose()"
                }
            );
        }
		function ModalPopupsAlert2() {
            ModalPopups.Alert("jsAlert2",
                "System Message",
                "<div style='padding:25px;'>Please Fillup all required fields...</div>", 
                {
                    okButtonText: "Close"
                }
            );
        }
		function ModalPopupsClose() {     
			ModalPopups.Close("jsAlert1");
			window.close('dm_new.php')   
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
	///function for imposing maximum character for textarea remarks
	function imposeMaxLength(Object, MaxLen)
	{
	  return (Object.value.length <= MaxLen);
	}

//txtAdate2
</script>
<script type="text/javascript" src="shInit.js" language="javascript"></script>
<?php
error_reporting(E_ALL ^ E_NOTICE); 
include('sqlconn.php');
include('function.php');

if ($_POST[branch] != '') {
	$branch = trim($_POST[branch]) ; 
}else { 
	$branch = $glbranchcode;
}

if ($_POST[dept_code] != '') {
	$dept_code = trim($_POST[dept_code]) ; 
}else { 
	$dept_code = $lcdeptcode ;
}

if ($_POST[division_code] != '') {
	$division_code = trim($_POST[division_code]) ; 
}else { 
	$division_code = $lcdivision ;
}

if ($_POST[category_code] != '') {
	$category_code = trim($_POST[category_code]) ; 
}else { 
	$category_code = "" ;
}

if ($_POST[subcat_code] != '') {
	$subcat_code = trim($_POST[subcat_code]) ; 
	$remlenght = remark_length($subcat_code);
}else { 
	$subcat_code = "" ;
}

if ($_POST[vendor] != '') {
	$vendor = trim($_POST[vendor]) ; 
}else { 
	$vendor = "" ;
}

if ($_POST[vendorcode] != '') {
	$vendorcode = trim($_POST[vendorcode]) ; 
}else { 
	$vendorcode = "" ;
}

if (strlen($_POST['txtDate'])==0) {
	$txtDate = date("m/d/Y");
}else{
	$txtDate = $_POST['txtDate'];
}

if ($_POST['payment'] > 0) {
	$payment = $_POST['payment'] ; 
}else { 
	$payment = '' ;
}

if ($_POST['txtamount'] > 0) {
	$txtamount = (int)$_POST['txtamount'] ; 
}else { 
	$txtamount = 0 ;
}

$remcnt = (int)strlen($_POST[txtremarks]);
if ($remcnt > (int)$remlenght) {
	$flag = 1;
} else {
	$flag = 0;
}

if ($_POST[txtremarks] != '' and $flag == 0 ) {
	$txtremarks = trim($_POST[txtremarks]) ; 
}else { 
	$txtremarks = '' ;
}

if (strlen($_GET['namemonth'])>0) {
	$namemonth = trim($_GET['namemonth']);
	} else {
	if (strlen($_POST['namemonth'])>0) {
		$namemonth = trim($_POST['namemonth']);

	} else {
		if (strlen($namemonth)==0) {			
			$namemonth = date('F');
		}
	}
}

if (strlen($_POST['txtdatefrom'])>0 ){
	$txtdatefrom = $_POST['txtdatefrom'];	 ?>
	<?php if (strlen($txtdatefrom) < 4){?>
		<script language="javascript">
				alert("Invalid Year")
		</script>
	<?php	
			$txtdatefrom = date("Y") ;
		} 
}else{
	$txtdatefrom = date("Y") ;
} 

if (strlen($_POST[cmdsaved]) != 0) {
	$cmdsaved = trim($_POST[cmdsaved]) ; 
}else { 
	$cmdsaved = "" ;
}

if (strlen($_POST[cmdclose]) != 0) {
	$cmdclose = trim($_POST[cmdclose]) ; 
}else { 
	$cmdclose = "" ;
}

if ($cmdclose == 'Close') {
	?>
	<script>
		window.close('dm_new.php')
	</script>
	<?php
}


?>
<body bgcolor="#5D7AAD">
<div id="wrapper">
<div id="content">

<form method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
<!---->
<table width="382" height="283" border="0" bgcolor="#ffffff" cellpadding="0" cellspacing="0">
  <tr>
    <td width="8" height="22">&nbsp;</td>
    <td width="109">&nbsp;</td>
    <td width="5">&nbsp;</td>
    <td width="251"><!--<div id="txtHint"></div>-->&nbsp;</td>
    <td width="9">&nbsp;</td>
  </tr>
  <tr>
    <!--<td>&nbsp;</td>
    <td>Branch</td>
    <td>:</td>
    <td bgcolor="#C5DFE0"><select name="branch" onkeypress="return ignoreenter(this,event)" style="width: 100%;" tabindex="1">
      <?php //if ($glbranchcode != 'HO' ) {echo 'disabled="disabled"' ;}
							  	//if ($glbranchcode == 'HO') {
							  					  ?>
      <option value="">- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Select Branch&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -</option>
      <?php 
									//$selbr = "select  branch_code,branch_name from ref_branch where isactive = 1 order by branch_code ";
								//}else{
									///$selbr = "select  branch_code,branch_name from ref_branch where branch_code = '{$glbranchcode}' and isactive = 1 order by branch_code ";			
									//$branch = $glbranchcode ;			
								//}
						//$r_selbr = mssql_query($selbr);
					///	for ($i = 0; $i < ($b_row = mssql_fetch_array($r_selbr)); $i++) 
					//	{
					//		$selected = "";
					//		if (trim($b_row['branch_code']) == trim($branch)) {
					//			$selected = 'selected="selected"';
					//		} 
						?>
      <option value="<?php //echo $b_row['branch_code']?>" 
			  <?php //echo $selected?>   > <?php //echo $b_row['branch_code']?> - <?php //echo $b_row['branch_name']?> </option>
      <?php 
 
						///}?>
    </select></td>
    <td><span class="style1">*</span></td>
  </tr>
  <tr>
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
						//	$selected = "";
						//	if (trim($b_row['dept_code']) == trim($dept_code)) {
						//		$selected = 'selected="selected"';
						//	} 
						?>
      <option value="<?php //echo $b_row['dept_code']?>" 
			  <?php //echo $selected?>   > <?php //echo $b_row['dept_code']?> - <?php //echo $b_row['dept_name']?> </option>
      <?php 
 
						//}?>
    </select></td>
    <td><span class="style1">*</span></td>
  </tr>  -->
  <?php if ($lcaccrights == 4) {?>
  <tr>
    <td>&nbsp;</td>
    <td>Division</td>
    <td>:</td>
    <td bgcolor="#ffffff"><select name="division_code" onChange="submit()" onkeypress="return ignoreenter(this,event)" style="width: 100%;" tabindex="3">
      <?php 
						$seldiv = "select  division_code,division_name from ref_division order by division_code ";

						$r_seldiv = mssql_query($seldiv);
						for ($i = 0; $i < ($b_row = mssql_fetch_array($r_seldiv)); $i++) 
						{
							$selected = "";
							if (trim($b_row['division_code']) == trim($division_code)) {
								$selected = 'selected="selected"';
							} 
						?>
      <option value="<?php echo $b_row['division_code']?>" 
			  <?php echo $selected?>   > <?php echo $b_row['division_code']?> - <?php echo $b_row['division_name']?> </option>
      <?php 
 
						}?>
    </select></td>
    <td><span class="style1">*</span></td>
  </tr>
  <?php }?>
  <tr>
    <td height="33">&nbsp;</td>
    <td>Category</td>
    <td>:</td>
    <td bgcolor="#ffffff"><select name="category_code" onChange="submit()" onkeypress="return ignoreenter(this,event)" style="width: 100%;" tabindex="1">
      <option value="">- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Select Category&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -</option>
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
    <td><span class="style1">*</span></td>
  </tr>
  <tr>
    <td height="31">&nbsp;</td>
    <td>Sub Category </td>
    <td>:</td>
    <td bgcolor="#ffffff"><select name="subcat_code" onkeypress="return ignoreenter(this,event)" onChange="submit();" style="width: 100%;" tabindex="2">
      <option value=""> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </option>
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
						}
						?>
    </select></td> 
    <td><span class="style1">*</span></td>
  </tr>
  <tr>
    <td height="28">&nbsp;</td>
    <td>Mode of Payment </td>
    <td>:</td>
    <td bgcolor="#ffffff"><select name="payment" onkeypress="return ignoreenter(this,event)" onChange="submit();" style="width: 100%;" tabindex="5">
      <option value=""> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </option>
      <?php 
						$selpay = "select paymentid,paymentdesc from ref_payment order by paymentid ";
						$r_selpay = mssql_query($selpay);
						for ($i = 0; $i < ($b_row = mssql_fetch_array($r_selpay)); $i++) 
						{
							$selected = "";
							if (trim($b_row['paymentid']) == trim($payment)) {
								$selected = 'selected="selected"';
							}
							
						?>
      <option value="<?php echo $b_row['paymentid']?>" 
			  <?php echo $selected?>><?php echo $b_row['paymentdesc']?> </option>
      <?php 
						}
						?>
    </select></td>
    <td><span class="style1">*</span></td>
  </tr>
  <tr>
    <td height="28">&nbsp;</td>
    <td>Deduction Period </td>
    <td>:</td>
<!--    <td bgcolor="#C5DFE0"><input name="txtDate" type="text" size="11" maxlength="10" id="txtAdate" readonly="readonly" tabindex="4" onFocus="document.news_edit.reset.focus();" value="<?php //echo $txtDate ?>" onKeyPress="return numbersonly(this, event)"/></td>-->
	<td><select name="namemonth" onkeypress="return ignoreenter(this,event)" >
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
	  <input name="txtdatefrom" type="text" size="5" maxlength="4" value="<?php echo $txtdatefrom ?>" onKeyPress="return numbersonly(this, event)"/></td>
    <td><span class="style1">*</span></td>
  </tr>
  <tr>
    <td height="31">&nbsp;</td>
    <td>Vendor </td>
    <td>:</td>
    <td bgcolor="#ffffff"><div> <small style="float:right">
      <input type="text" name="vendorcode" id="idvendorcode" value="<?php echo $vendorcode;?>" style="font-size: 0px; width: 0px;" readonly="yes" />
      </small>
            <input style="width: 100%" type="text" name="vendor" id="idvendor" value="<?php echo $vendor;?>" tabindex="3" />
    </div></td>
    <td><span class="style1">*</span></td>
  </tr>
  
    <tr>
    <td height="40">&nbsp;</td>
    <td>Amount</td>
    <td>:</td>
    <td bgcolor="#ffffff"><input type="text" name="txtamount" maxlength="10" size="10"  value="<?php echo  number_format($txtamount,2)  ;?>" style="width: 100%; height: 30px; text-align:right; font-size: 22px;" tabindex="6" /></td>
    <td><span class="style1">*</span></td>
  </tr>
  <tr>
    <td height="49">&nbsp;</td>
    <td>Remarks<br />
    (<?php echo $remlenght; ?> characters)</span></td>
    <td>:</td>
    <td bgcolor="#ffffff"><textarea name="txtremarks" rows="3" cols="45" style="width: 100%;" tabindex="7" onKeyPress="return imposeMaxLength(this, <?php echo (int)$remlenght - 1; ?>);"><?php echo $txtremarks ;?></textarea></td>
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
    <td colspan="5"><table width="100%" height="27" border="0" cellpadding="0" cellspacing="0">
      <tr>
	    <td width="69%">&nbsp;</td>
        <td width="15%"><input type="submit" name="cmdsaved" id="cmdidSaved" title="Save" onClick="submit()" value=" Save " tabindex="8" style="width: 90%;height:40; background-color:#FF9933 " /></td>
        <td width="16%"><input type="submit" name="cmdclose" id="cmdidClose" title="Close" onClick="submit()" value="Close" tabindex="9" style="width: 90%;height:40; background-color:#FF9933 " /></td>
      </tr>
    </table></td>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<input type="hidden" name="remlenght" value="<?php echo $remlenght;?>" id="txtHint" />
</form>
</div>
</div>

<?php 
if ($cmdsaved == 'Save') {
	$qrydmno = "Select dm_no from dm_autoid where branch_code = '{$glbranchcode}'";
	$rsdmno = mssql_query($qrydmno);
	$dmno_nmrow = mssql_num_rows($rsdmno);
	
	$create_dmno = "Select branch_prefix from ref_branch where branch_code = '{$glbranchcode}'";
	$rs_dmno = mssql_query($create_dmno);
	$row_dmno = mssql_fetch_array($rs_dmno);
	if ($dmno_nmrow == 0) {		
		$dmno = str_pad(trim($row_dmno['branch_prefix']),9,0).'1';
	} else {
		$rowdmno = mssql_fetch_array($rsdmno);
		$xdmno = (int)$rowdmno['dm_no'] + 1 ;
		$ydmno = strlen($xdmno);
		$zdmno = 10 - (int)$ydmno;
		$dmno = str_pad(trim($row_dmno['branch_prefix']),$zdmno,0).$xdmno;
	}
	
	if (strlen($category_code) > 0 and strlen($subcat_code) > 0 and $txtamount > 0 and strlen($namemonth) > 0 and strlen($txtdatefrom) > 0 and strlen($vendor) > 0) {
		$tmp_dmno = str_replace(trim($row_dmno['branch_prefix']),'',$dmno);
		if ($dmno_nmrow == 0) {
			$update_dmno = "Insert into dm_autoid values ('{$glbranchcode}','{$tmp_dmno}')";
		} else {
			$update_dmno = "Update dm_autoid set dm_no = '{$tmp_dmno}' where branch_code = '{$glbranchcode}'";
		}
		$rsupdate_dmno = mssql_query($update_dmno);
		$qryins = "Execute dm_insert '{$dmno}','{$glbranchcode}','{$lcdivision}','{$lcdeptcode}',
					'{$category_code}','{$subcat_code}','{$vendor}','{$namemonth}','{$txtdatefrom}',
					'$txtamount','$payment','{$txtremarks}','{$lcuser}'";
		$rsins = mssql_query($qryins);
		echo '<a href="javascript:ModalPopupsAlert1();">.</a>';
		?>
		<script type="text/javascript" language="javascript">
			ModalPopupsAlert1()
		</script>
		<?php
	} else {
		echo '<a href="javascript:ModalPopupsAlert2();">.</a>';
		?>
		<script type="text/javascript" language="javascript">
			ModalPopupsAlert2()
		</script>
		<?php
	}
	
	
} 
?>

<script type="text/javascript">
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
</script>
</body>
</html>
<?php
}
?>
