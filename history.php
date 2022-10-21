<?php 
session_start();
date_default_timezone_set('Asia/Manila');
		$lcuser  = $_SESSION['user'] ;
		$glbranchcode =  $_SESSION['branch_code'] ;
		$lcusername = $_SESSION['username'] ;
		$lcdeptcode = $_SESSION['dept_code'] 

?><head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Transaction History</title>
<style type="text/css">
<!--
.style1 {
	font-size: 12px;
	font-weight: bold;
}
-->
</style>

</head>
<?php
error_reporting(E_ALL ^ E_NOTICE); 
include('sqlconn.php');
include('function.php');

	if (strlen($_GET['dmno'])>0) {
		$dmno = trim($_GET['dmno']);
	} else {
		if (strlen($_POST['dmno'])>0) {
			$dmno = trim($_POST['dmno']);		
		} else {
			if (strlen($dmno)==0) {
		//		$txtissuedto = "";			
			}
		}
	}
	
	if ($_GET['branch']) {
		$branch =trim($_GET['branch']);
	} else {
		if ($_POST['branch'] != '') {
			$branch = trim($_POST['branch']);
		} else {
			$branch = '';
		}
	}
?>
<?php

$q_vw = "select * from tran_log where dm_no = '{$dmno}' and branch_code = '{$branch}' order by audit_date asc";




		$r_vw = mssql_query($q_vw);

			$res_po = mssql_query($q_vw);
			$nmrow = mssql_num_rows($res_po);
			$d_row = mssql_fetch_array($res_po);
			if ($nmrow == 0) {
			$disabledchange = 'disabled="disabled"';
			}
			if ($result == 0) {
			$result = 1 ;
		}
			$a =10 ;
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
				//	if (strlen($_POST['markall'])>0 || strlen($_POST['chk'])>0) {
					//	$pageno = $_POST['pageno'];			
				//	}else{
						$pageno = 1;
					//	}
			}	
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
//////////////////////////////////////////////////////////////////////////////////////
//$updatestat= "delete from print_ps where userid = '{$lcuser}' " ;
//$updatestatxx = mssql_query($updatestat);	

?>





<link href="css/styles.css" rel="stylesheet" type="text/css">
<body  topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" bgcolor="#5D7AAD">
<form method="post" action="<?php echo $_SERVER['PHP_SELF']?>" name="ar_print">
	<input type="hidden" name="pageno" value="<?php echo $pageno?>">
	<input type="hidden" name="dmno" value="<?php echo $dmno ?>">
	<input type="hidden" name="branch" value="<?php echo $branch ?>">
<TABLE border="1" width="591" height="462" cellpadding="0" cellspacing="0" align="center" bgcolor="#FFFFFF">
<tr><td align="center" valign="top">
		<table width="582" border="0" bgcolor="#ffffff" bordercolor="#FFFFFF" bordercolordark="#FFFFFF" bordercolorlight="#ffffff" cellpadding="1" cellspacing="0">
			<tr>
				<td width="4">&nbsp;</td>
				<td width="99">&nbsp;</td>
				<td width="118">&nbsp;</td>
				<td width="16">&nbsp;</td>
				<td width="6">&nbsp;</td>
				<td width="6">&nbsp;</td>
				<td width="74">&nbsp;</td>
				<td width="51">&nbsp;</td>
				<td width="4">&nbsp;</td>
				<td width="68">&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>																				
			</tr>	
			<tr>
				<td>&nbsp;</td>
				<td colspan="11" align="center"><span class="style1">TRANSACTION HISTORY</span> </td>
				<td>&nbsp;</td>								
			</tr>
			
			<tr>
				<td width="4">&nbsp;</td>
				<td width="99">&nbsp;</td>
				<td width="118">&nbsp;</td>
				<td width="16">&nbsp;</td>
				<td width="6">&nbsp;</td>
				<td width="6">&nbsp;</td>
				<td width="74">&nbsp;</td>
				<td width="51">&nbsp;</td>
				<td width="4">&nbsp;</td>
				<td width="68">&nbsp;</td>	
				<td width="50">&nbsp;</td>
				<td width="50">&nbsp;</td>
				<td width="10">&nbsp;</td>																			
			</tr>	
			<tr>

				<td colspan="13"><hr></td>
			</tr>							
			<tr>
				<td height="18" bgcolor="#99BBDD">&nbsp;</td>
				<td align="center" bgcolor="#99BBDD">Field Change </td>
				<td bgcolor="#99BBDD" align="center" >Old Value</td>
				<td colspan="4" align="center" bgcolor="#99BBDD">New Value </td>
				<td align="center" bgcolor="#99BBDD">Type</td>
				<td bgcolor="#99BBDD">&nbsp;</td>
				<td align="center" bgcolor="#99BBDD">User</td>
				<td colspan="2" align="center" bgcolor="#99BBDD">Date/Time</td>
				<td bgcolor="#99BBDD">&nbsp;</td>				
			</tr>

<?php	

$res_po = mssql_query($q_vw);
$cnt = $pageno*$a;

for ($i = 0; $i < ($p_row = mssql_fetch_array($res_po)); $i++)
{
		$amount = 
		$val = $i % 2;
		
			if ($val == 0) {				
			$colorval = "#CCCCCC";
			}else{
			$colorval =  "#F2F2F2";
			}		
?>
<?php if ($i >= ($cnt-$a) && $i < ($cnt)) {?>
																						
			<tr bgcolor="<?php echo $colorval?>">
				<td height="18">&nbsp;</td>
				<td align="left"><?php echo $p_row["field_change"] ;  ?></td>
				<td><?php echo $p_row["old_value"] ;  ?></td>
				<td colspan="4" align="left"><?php echo $p_row["new_value"] ;  ?></td>
				<td align="center"><?php echo $p_row["remarks"] ;  ?></td>
				<td>&nbsp;</td>
				<td><?php echo $p_row["edit_by"] ;  ?></td>
				<td colspan="3" align="left"><?php echo $p_row["audit_date"] ;  ?></td>
			</tr>

<?php } ?>
<?php } ?>

			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>	
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>												
			</tr>																						
	  </table>
</td></tr>

<table align="center">
<tr>
<td width="43" align="left"></td>
<td width="152" height="35" align="Right"><?php echo $pgrec?> - <?php echo $pgttl?> of <?php echo $nmrow?> Records &nbsp;&nbsp;&nbsp;</td>
						  <td width="40"><input type="submit" name="pagepost" value="First" title="" <?php echo $disabled1?>></td>
						  <td width="41"><input type="submit" name="pagepost" value="Prev" title="" <?php echo $disabled2?>></td>
						  <td width="42"><input type="submit" name="pagepost" value="Next" title="" <?php echo $disabled4?>></td>
						  <td width="43"><input type="submit" name="pagepost" value="Last" title="" <?php echo $disabled3?>></td>
</tr>						  
</table>
</table>

</td></tr>
</TABLE>
</form>
</body>
</html>
