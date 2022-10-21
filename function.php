<?php
function vendor_arms($strvcode,$strvendor) {
	include('sqlconnx.php');
	$lcvcode = $strvcode;
	$lcvendor = trim($strvendor);
	$qry_supp = "select
		dbo.CleanApostrophe(ltrim(rtrim(cast(replace(rtrim(ltrim(replace(RTRIM(LTRIM(replace(rtrim(ltrim(suppliername)),'*',''))),'$$',''))),'##','')
		as char(230))))) as vendor
		from supplier
		where aptid = '{$lcvcode}' or vendorcode = '{$lcvcode}' 
		union
		select
		dbo.CleanApostrophe(ltrim(rtrim(cast(replace(rtrim(ltrim(replace(RTRIM(LTRIM(replace(rtrim(ltrim(vendorname)),'*',''))),'$$',''))),'##','')
		as char(230))))) as vendor
		from Supplier_New
		where vendorcode = '{$lcvcode}'  " ;

	$rs_supp = mssql_query($qry_supp);
	$row_supp = mssql_fetch_array($rs_supp);
		$new_vendor = trim($row_supp['vendor']);
		if ($lcvendor == $new_vendor) {
			$vval = $lcvendor;
			return $vval;
		} else {
			$vval = $new_vendor;
			return $vval;
		}
}

function vendor_is_change($strvcode,$strvendor) {
	include('sqlconnx.php');
	$lcvcode = $strvcode;
	$lcvendor = trim($strvendor);

	$qry_supp = "with cte as ( select
    replace(dbo.CleanApostrophe(ltrim(rtrim(cast(replace(rtrim(ltrim(replace(RTRIM(LTRIM(replace(rtrim(ltrim(suppliername)),'*',''))),'$$',''))),'##','') as char(230))))),'-',' ') as vendor, '1' as sequence
    from supplier
    where aptid = '{$lcvcode}' or vendorcode = '{$lcvcode}'
    union
    select
    replace(dbo.CleanApostrophe(ltrim(rtrim(cast(replace(rtrim(ltrim(replace(RTRIM(LTRIM(replace(rtrim(ltrim(vendorname)),'*',''))),'$$',''))),'##','')
    as char(230))))),'-',' ') as vendor, '2' as sequence
    from supplier_new
    where vendorcode = '{$lcvcode}'
    ) select top 1 vendor from cte order by sequence asc" ;
	$rs_supp = mssql_query($qry_supp);
	$row_supp = mssql_fetch_array($rs_supp);
	$nmrows = mssql_num_rows($rs_supp);
	if ($nmrows > 0) {
		$vval = null;
			$new_vendor = str_replace("-", "", trim($row_supp['vendor']));
			
			if ($lcvendor == $new_vendor) {
				$vval = 'Approved';
			} else {
				$vval = 'Disapproved';
			}
			
		return $vval;
	} else {
		$vval = 'Disapproved';
		return $vval;
	}
}

function count_len($str) {
	include('sqlconnx.php');
	$vend1 = $str;
	$x = 0;
	$leng = strlen(trim($vend1));
	if ($x == 0) {
		$vend = trim(strrchr($vend1,' - '));

		$qrycount = "select case when vendorcode = '' then len(ltrim(rtrim(aptid))) else len(ltrim(rtrim(vendorcode))) end as countlen
				from supplier where vendorcode = '{$vend}' or aptid = '{$vend}'
				union
				select len(ltrim(rtrim(vendorcode))) as countlen
				from supplier_new where vendorcode = '{$vend}'";

		$rscount = mssql_query($qrycount);
		$rowcount = mssql_fetch_array($rscount);
		$xy = mssql_num_rows($rscount);
		if (!empty($xy)) {
			$x = 1;
			$xcount = $rowcount['countlen'];
			return $xcount;
		}
	}
	if ($x == 0) {
		$vend = substr($vend1,$leng-7);

		$qrycount = "select case when vendorcode = '' then len(ltrim(rtrim(aptid))) else len(ltrim(rtrim(vendorcode))) end as countlen
				from supplier where vendorcode = '{$vend}' or aptid = '{$vend}'
				union
				select len(ltrim(rtrim(vendorcode))) as countlen
				from supplier_new where vendorcode = '{$vend}'";
		$rscount = mssql_query($qrycount);
		$rowcount = mssql_fetch_array($rscount);
		$xy = mssql_num_rows($rscount);
		if (!empty($xy)) {
			$x = 1;
			$xcount = $rowcount['countlen'];
			return $xcount;
		}
	}
	if ($x == 0) {
		$vend = substr($vend1,$leng-6);

		$qrycount = "select case when vendorcode = '' then len(ltrim(rtrim(aptid))) else len(ltrim(rtrim(vendorcode))) end as countlen
				from supplier where vendorcode = '{$vend}' or aptid = '{$vend}'
				union
				select len(ltrim(rtrim(vendorcode))) as countlen
				from supplier_new where vendorcode = '{$vend}'";
		$rscount = mssql_query($qrycount);
		$rowcount = mssql_fetch_array($rscount);
		$xy = mssql_num_rows($rscount);
		if (!empty($xy)) {
			$x = 1;
			$xcount = $rowcount['countlen'];
			return $xcount;
		}
	}
	if ($x == 0) {
		$vend = substr($vend1,$leng-5);

		$qrycount = "select case when vendorcode = '' then len(ltrim(rtrim(aptid))) else len(ltrim(rtrim(vendorcode))) end as countlen
				from supplier where vendorcode = '{$vend}' or aptid = '{$vend}'
				union
				select len(ltrim(rtrim(vendorcode))) as countlen
				from supplier_new where vendorcode = '{$vend}'";
		$rscount = mssql_query($qrycount);
		$rowcount = mssql_fetch_array($rscount);
		$xy = mssql_num_rows($rscount);
		if (!empty($xy)) {
			$x = 1;
			$xcount = $rowcount['countlen'];
			return $xcount;
		}
	}
	if ($x == 0) {
		$vend = substr($vend1,$leng-4);

		$qrycount = "select case when vendorcode = '' then len(ltrim(rtrim(aptid))) else len(ltrim(rtrim(vendorcode))) end as countlen
				from supplier where vendorcode = '{$vend}' or aptid = '{$vend}'
				union
				select len(ltrim(rtrim(vendorcode))) as countlen
				from supplier_new where vendorcode = '{$vend}'";
		$rscount = mssql_query($qrycount);
		$rowcount = mssql_fetch_array($rscount);
		$xy = mssql_num_rows($rscount);
		if (!empty($xy)) {
			$x = 1;
			$xcount = $rowcount['countlen'];
			return $xcount;
		}
	}

}

function verify_editvcode($str,$xdmno) {
	include('sqlconn.php');
	$vend = $str;
	$dmx = $xdmno;
	$ver_ven = "select vendorcode from deduction_master where vendorcode = '{$vend}' and dm_no = '{$dmx}'";
	$rs_ven = mssql_query($ver_ven);
	$row_vene = mssql_num_rows($rs_ven);
	return $row_vene;
}

function verify_vendor($str) {
	include('sqlconnx.php');
	$vend = $str;
	$ver_ven = "select suppliername from supplier where vendorcode = '{$vend}' or aptid = '{$vend}'
	union
	select vendorname as suppliername from supplier_new where vendorcode = '{$vend}'";
	$rs_ven = mssql_query($ver_ven);
	$row_ven = mssql_num_rows($rs_ven);
	return $row_ven;
}

function remark_length($mystr)
{
	include('sqlconn.php');
	$subcat = $mystr;
	$selbr = "select subcat_name from ref_subcategory where isactive = 1 and subcat_code = '{$subcat}'";
	$r_selbr = mssql_query($selbr);
	$b_row = mssql_fetch_array($r_selbr);
	$sblength = strlen(trim($b_row['subcat_name']));
	$remlenght = 45 - (int)$sblength;
	return $remlenght;

}

function strreplace($mystr)
{
	$char = $mystr;
	$charOld = trim($char);
	$charlen = strlen(trim($char));
	$char2 = '';
	$c = 0;
	$char = "";

	while ($c <= $charlen) {
		$char2 = substr($charOld,$c,1);
		if ($char2 =="'") {
			str_replace("'","`",$char2);
			$char = $char.$char2;
		}
		$char = $char.$char2;
		$c++;
	}
	return $char;
}

function strquote($mystr)
{
	$char = $mystr;
	$charOld = trim($char);
	$charlen = strlen(trim($char));
	$char2 = "";
	$c = 0;
	$char = "";

	while ($c <= $charlen) {
		$char2 = substr($charOld,$c,1);
		if ($char2 =="'") {
			$char = $char.$char2;
		}
		$char = $char.$char2;
		$c++;
	}
	return $char;
}


function setcookie_variable($myPost,$myCookie)
{
	$myVar = "";
	if (strlen($_POST[$myPost])>0) {
		$myVar = $_POST[$myPost];
		setcookie($myCookie,$myVar);
	} else {
		$myVar = $_COOKIE[$myCookie];
	}

	return $myVar;
}


function check_post($myPost,$myVal)
{
	if (empty($_POST[$myPost])) {
		$myVal = "";
	}
	return $myVal;
}

function check_date($mydate)
{
	$myVal = "01/01/1900";
	if (strlen($mydate)>0) {
		$myVal = $mydate;
	}
	return $myVal;
}

function check_date2($mydate)
{
	$myVal = "";
	if ($mydate=="01/01/1900") {
		$myVal = "";
	} else {
		$myVal = $mydate;
	}
	return $myVal;
}


function check_num($mynum)
{
	$myVal = 0;
	if (strlen($mynum)>0) {
		$myVal = $mynum;
	}
	return $myVal;
}

function checked($myval)
{
	if ($myval==1) {
		$myval = 'checked="yes"';
	} else {
		$myval = "";
	}
	return $myval;
}

function right_align($myVal,$fldlen)
{
	$nospace = $fldlen-strlen($myVal);
	$cntr = 1;
	while($cntr <= $nospace){
		$myVal = " ".$myVal;
		$cntr = $cntr + 1;
	}

	return $myVal;
}

function _str_split($str, $split_len=1){
    $split_len = (int) $split_len;

    for($a=0; $a<=strlen($str); $a++){
        $matches[$a]= substr($str, $a, 1);
    }
    return $matches;
}

//function del_cookie($name)
//{
//document.cookie = $name +
//'=; expires=Thu, 01-Jan-70 00:00:01 GMT;';
//}

?>
<SCRIPT TYPE="text/javascript">
<!--
// copyright 1999 Idocs, Inc. http://www.idocs.com
// Distribute this script freely but keep this notice in place
function numbersonly(myfield, e, dec)
{
var key;
var keychar;

if (window.event)
   key = window.event.keyCode;
else if (e)
   key = e.which;
else
   return true;
keychar = String.fromCharCode(key);

// control keys
if ((key==null) || (key==0) || (key==8) ||
    (key==9) || (key==13) || (key==27) )
   return false;

// numbers
else if ((("0123456789.").indexOf(keychar) > -1))
   return true;

// decimal point jump
else if (dec && (keychar == "/"))
   {
   myfield.form.elements[dec].focus();
   return false;
   }
else
   return false;
}


function ignoreenter(myfield,e)
{
var keycode;
if (window.event) keycode = window.event.keyCode;
else if (e) keycode = e.which;
else return true;

if (keycode == 13)
   {
   return false;
   }
else
   return true;
}

// Botton Animation
function mouseOver1()
{
document.b11.src ="Images/Conf_yes.JPG";
}
function mouseOut1()
{
document.b11.src ="Images/del_yes.JPG";
}
function mouseOver2()
{
document.b12.src ="Images/Conf_no.JPG";
}
function mouseOut2()
{
document.b12.src ="Images/del_no.JPG";
}

function mouseOver3()
{
document.S1.src ="Images/btnSave2.JPG";
}
function mouseOut3()
{
document.S1.src ="Images/btnSave1.JPG";
}


	function KeyUpEvent()
	{
	   var txtquantity = document.getElementById("qty").value ;
	   var txtunitcost = document.getElementById("untcost").value ;
	   var answer
	//   var txtunitcost = document.getElementById("txtunitcost");
	 //  var txttotcost = document.getElementById("txttotcost");
	  // if ((txtquantity.value != "") && (txtunitcost.value != ""))
	  // {
	   		answer = txtquantity * txtunitcost
			document.getElementById("totcost").value = answer.toFixed(2)
		//	txttotcost.value = parseInt(txt1.value) * parseInt(txt2.value);
	//		alert(txttotcost.value)
	 //  }
	}


//-->
</SCRIPT>
