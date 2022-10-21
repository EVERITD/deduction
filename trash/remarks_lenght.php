<?php
session_start();

$subcat=$_GET["subcat"];

include('sqlconn.php');
 
		$selbr = "select subcat_name from ref_subcategory where isactive = 1 and subcat_code = '{$subcat}'";

		$r_selbr = mssql_query($selbr);
		$b_row = mssql_fetch_array($r_selbr);
		$sblength = strlen(trim($b_row['subcat_name'])); 
		$remlenght = 44 - (int)$sblength;
?>

