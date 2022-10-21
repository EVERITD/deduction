<?php session_start();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Deduction Statistics</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<?php 
include('sqlconn.php');

if ($_GET['show'] != '') {
	$show = $_GET['show'];
} else {
	$show = 1;
}
if ($_GET['stat'] != '') {
	 $_SESSION['stat'] = $_GET['stat'];
} 
if ($_GET['div'] != '') {
	$_SESSION['div'] = $_GET['div'];
} 
if ($_GET['branch'] != '') {
	$_SESSION['branch'] = $_GET['branch'];
} 
if ($_GET['dept'] != '') {
	$_SESSION['dept'] = $_GET['dept'];
}
if ($_GET['cat'] != '') {
	$_SESSION['cat'] = $_GET['cat'];
}
if ($_GET['vendor'] != '') {
	$_SESSION['vendor'] = $_GET['vendor'];
}
if ($_GET['back'] != '') {
	$back = $_GET['back'];
} else {
	$back = '';
}
switch($back) {
	case '1' :
		$_SESSION['div'] = '';
		$_SESSION['branch'] = '';
		$_SESSION['dept'] = '';
		$_SESSION['cat'] = '';
		$_SESSION['vendor'] = '';
		$show = 1;
		break;
	case '2' :
		$_SESSION['branch'] = '';
		$_SESSION['dept'] = '';
		$_SESSION['cat'] = '';
		$_SESSION['vendor'] = '';
		$show = 2;
		break;
	case '3' :
		$_SESSION['dept'] = '';
		$_SESSION['cat'] = '';
		$_SESSION['vendor'] = '';
		$show = 3;
		break;
	case '4' :
		$_SESSION['cat'] = '';
		$_SESSION['vendor'] = '';
		$show = 4;
		break;
	case '5' :
		$_SESSION['cat'] = '';
		$_SESSION['vendor'] = '';
		$show = 5;
		break;
	case '6' :
		$_SESSION['vendor'] = '';
		$show = 6;
		break;
}
//echo $back;
//echo $_SESSION['stat'];
//echo $_SESSION['div'];
//echo $_SESSION['branch'];
//echo $_SESSION['dept'];
//echo $_SESSION['cat'];
?>
<body>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']?>" >

	<div id="main">
		<div id="caption"><a href="?back=1">STATUS</a></div>
		<?php if ($show == 2 or $show == 3 or $show == 4 or $show == 5 or $show == 6) {?>
		<div id="caption"><a href="?back=2">DIVISION</a></div>
		<?php } 
			  if ($show == 3 or $show == 4 or $show == 5 or $show == 6) {?>
		<div id="caption"><a href="?back=3">BRANCH</a></div>
		<?php
			  }
			  if ($show == 4 or $show == 5 or $show == 6) {?>	  
		<div id="caption"><a href="?back=4">DEPARTMENT</a></div>	
		<?php
			  }
			  if ($show == 5 or $show == 6) {?>
		<div id="caption"><a href="?back=5">CATEGORY</a></div>	
		<?php
			}
			  if ($show == 6) {?>
		<div id="caption"><a href="?back=6">VENDOR</a></div>	
		<?php
			}
		?>
<?php
  if ($show == 1 ) {
?>		
      	<div id="result">&nbsp;
<?php 

$stat_qry = "select b.vposted,b.description,count(*) as total_count, 
				  	case when sum(amount) is null then 0 else sum(amount) end as total_amount
					from deduction_master a left join ref_status b on a.vposted = b.vposted 
					group by b.description,b.vposted order by b.vposted";
	$stat_rs = mssql_query($stat_qry);
	$stat_nm_row = mssql_num_rows($stat_rs);
	
	if ($stat_nm_row > 0) {
		echo "<table border='0' align='center'>
		<tr>
		<th>Description</th>
		<th>Total Count</th>
		<th>Total Amount</th>
		</tr>";
		
		
		while($row = mssql_fetch_array($stat_rs))
		  {
		  echo "<tr align='center'>";
		  //echo "<td>&nbsp;" . $row['description'] . "&nbsp;</td>";
		  echo "<td><a href='statistics.php?stat=" . trim($row['vposted']) ."&show=2'>" . $row['description'] . "</a></td>";
		  echo "<td>&nbsp;" . $row['total_count'] . "&nbsp;</td>";
		  echo "<td align='right'>&nbsp;" . number_format($row['total_amount'],2) . "&nbsp;</td>";
		  echo "</tr>";
		  }
		echo "</table></div>";
	}
  } elseif ($show == 2) {
?>	
		
      	<!--<div id="icon">&nbsp;</div>-->
		<div id="result">&nbsp;
<?php
	
$div_qry = "select b.division_code,b.division_name,count(*) as total_count, 
				case when sum(amount) is null then 0 else sum(amount) end as total_amount
				from deduction_master a right join ref_division b
				on a.division_code = b.division_code
				where a.vposted = '{$_SESSION['stat']}' group by b.division_code,b.division_name";
	$div_rs = mssql_query($div_qry);
	$div_nm_row = mssql_num_rows($div_rs);
	
	if ($div_nm_row > 0) {
		echo "<table border='0' align='center'>
		<tr>
		<th>Division</th>
		<th>Total Count</th>
		<th>Total Amount</th>
		</tr>";
		
		
		while($row = mssql_fetch_array($div_rs))
		  {
		  echo "<tr align='center'>";
		  //echo "<td>&nbsp;" . $row['description'] . "&nbsp;</td>";
		  echo "<td><a href='statistics.php?div=" . trim($row['division_code']) ."&show=3'>" . $row['division_name'] . "</a></td>";
		  echo "<td>&nbsp;" . $row['total_count'] . "&nbsp;</td>";
		  echo "<td align='right'>&nbsp;" . number_format($row['total_amount'],2) . "&nbsp;</td>";
		  echo "</tr>";
		  }
		echo "</table></div>";
	}
  } elseif ($show == 3) {
?>	
		
      	<!--<div id="icon">&nbsp;</div>-->
		<div id="result">&nbsp;

<?php	
$branch_qry = "select b.branch_code,b.branch_name,count(*) as total_count, 
			case when sum(amount) is null then 0 else sum(amount) end as total_amount
			from deduction_master a left join ref_branch b
			on a.branch_code = b.branch_code
			where a.vposted = '{$_SESSION['stat']}' and a.division_code = '{$_SESSION['div']}'
			group by b.branch_code,b.branch_name";
	$branch_rs = mssql_query($branch_qry);
	$branch_nm_row = mssql_num_rows($branch_rs);
	
	if ($branch_nm_row > 0) {
		echo "<table border='0' align='center'>
		<tr>
		<th>Branch Name</th>
		<th>Total Count</th>
		<th>Total Amount</th>
		</tr>";
		
		
		while($row = mssql_fetch_array($branch_rs))
		  {
		  echo "<tr align='center'>";
		  //echo "<td>&nbsp;" . $row['description'] . "&nbsp;</td>";
		  echo "<td><a href='statistics.php?branch=" . trim($row['branch_code']) ."&show=4'>" . $row['branch_name'] . "</a></td>";
		  echo "<td>&nbsp;" . $row['total_count'] . "&nbsp;</td>";
		  echo "<td align='right'>&nbsp;" . number_format($row['total_amount'],2) . "&nbsp;</td>";
		  echo "</tr>";
		  }
		echo "</table></div>";
		
	}		
  } elseif ($show == 4) {
?>	
		
      	<!--<div id="icon">&nbsp;</div>-->
		<div id="result">&nbsp;

<?php	
$dept_qry = "select b.dept_code,b.dept_name,count(*) as total_count,
				case when sum(amount) is null then 0 else sum(amount) end as total_amount
				from deduction_master a left join ref_department b 
				on a.dept_code = b.dept_code
				where a.vposted = '{$_SESSION['stat']}' and a.division_code = '{$_SESSION['div']}' 
				and a.branch_code = '{$_SESSION['branch']}' group by b.dept_code,b.dept_name";
	$dept_rs = mssql_query($dept_qry);
	$dept_nm_row = mssql_num_rows($dept_rs);
	
	if ($dept_nm_row > 0) {
		echo "<table border='0' align='center'>
		<tr>
		<th>Department Name</th>
		<th>Total Count</th>
		<th>Total Amount</th>
		</tr>";
		
		
		while($row = mssql_fetch_array($dept_rs))
		  {
		  echo "<tr align='center'>";
		  //echo "<td>&nbsp;" . $row['description'] . "&nbsp;</td>";
		  echo "<td><a href='statistics.php?dept=" . trim($row['dept_code']) ."&show=5'>" . $row['dept_name'] . "</a></td>";
		  echo "<td>&nbsp;" . $row['total_count'] . "&nbsp;</td>";
		  echo "<td align='right'>&nbsp;" . number_format($row['total_amount'],2) . "&nbsp;</td>";
		  echo "</tr>";
		  }
		echo "</table></div>";
		
	}		
  } elseif ($show == 5) {
?>	
		
      	<!--<div id="icon">&nbsp;</div>-->
		<div id="result">&nbsp;

<?php	
$cat_qry = "select b.category_code,b.category_name,count(*) as total_count,
				case when sum(amount) is null then 0 else sum(amount) end as total_amount
				from deduction_master a left join ref_category b
				on a.category_code = b.category_code
				where a.vposted = '{$_SESSION['stat']}' and a.division_code = '{$_SESSION['div']}' 
				and a.branch_code = '{$_SESSION['branch']}' and a.dept_code = '{$_SESSION['dept']}'
				group by b.category_code,b.category_name";
	$cat_rs = mssql_query($cat_qry);
	$cat_nm_row = mssql_num_rows($cat_rs);
	
	if ($cat_nm_row > 0) {
		echo "<table border='0' align='center'>
		<tr>
		<th>Category Name</th>
		<th>Total Count</th>
		<th>Total Amount</th>
		</tr>";
		
		
		while($row = mssql_fetch_array($cat_rs))
		  {
		  echo "<tr align='center'>";
		  //echo "<td>&nbsp;" . $row['description'] . "&nbsp;</td>";
		  echo "<td><a href='statistics.php?cat=" . trim($row['category_code']) ."&show=6'>" . $row['category_name'] . "</a></td>";
		  echo "<td>&nbsp;" . $row['total_count'] . "&nbsp;</td>";
		  echo "<td align='right'>&nbsp;" . number_format($row['total_amount'],2) . "&nbsp;</td>";
		  echo "</tr>";
		  }
		echo "</table></div>";
		
	}		
  } elseif ($show == 6) {
?>	
		
      	<!--<div id="icon">&nbsp;</div>-->
		<div id="result">&nbsp;

<?php	
$vendor_qry = "select vendorcode,suppliername,count(*) as total_count,
			case when sum(amount) is null then 0 else sum(amount) end as total_amount
			from deduction_master 
			where vposted = '{$_SESSION['stat']}' and division_code = '{$_SESSION['div']}' and 
			branch_code = '{$_SESSION['branch']}' and 
			dept_code = '{$_SESSION['dept']}' and category_code = '{$_SESSION['cat']}'
			group by vendorcode,suppliername";
	$vendor_rs = mssql_query($vendor_qry);
	$vendor_nm_row = mssql_num_rows($vendor_rs);
	
	if ($vendor_nm_row > 0) {
		echo "<table border='0' align='center'>
		<tr>
		<th>Supplier Name</th>
		<th>Total Count</th>
		<th>Total Amount</th>
		</tr>";
		
		
		while($row = mssql_fetch_array($vendor_rs))
		  {
		  echo "<tr align='center'>";
		  echo "<td>&nbsp;" . $row['suppliername'] . "&nbsp;</td>";
		  //echo "<td><a href='statistics.php?vendor=" . trim($row['vendorcode']) ."&show=6'>" . $row['suppliername'] . "</a></td>";
		  echo "<td>&nbsp;" . $row['total_count'] . "&nbsp;</td>";
		  echo "<td align='right'>&nbsp;" . number_format($row['total_amount'],2) . "&nbsp;</td>";
		  echo "</tr>";
		  }
		echo "</table></div>";
		
	}		
  } 
?>
	</div>
</form>
</body>
</html>
