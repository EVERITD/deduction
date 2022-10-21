<?php
session_start();

//if (strlen($_SESSION['user'])>0) {

	include('sqlconn.php');
	
	if (strlen($_GET['extno'])>0) {
		$extno = $_GET['extno'];
	} else {
		if (strlen($_POST['extno'])>0) {
			$extno = $_POST['extno'];
		}
	}
	
	$selexport = "select a.dm_no,a.branch_code,a.division_code,a.dept_code,c.category_name,a.remarks1,a.amount,'' as posted_date 
				from deduction_master a left join deduction_extraction b on a.dm_no = b.dm_no 
				left join ref_category c on a.category_code = c.category_code
				where b.extraction_refno = '{$extno}'";
	
	
	$Use_Title = 0;
	
	$now_date = date('m-d-Y');
	
	$dmdate = date("m.d.Y", strtotime($txtDate))."-".date("m.d.Y", strtotime($txtDate2));
	
	$title = "Deduction_".$dmdate;
	$result = mssql_query($selexport);
	
	$file_type = "vnd.ms-excel";
	$file_ending = "xls";
	$app = "application/";
	
	
	header("Content-Type: $app$file_type");
	header("Content-Disposition: attachment; filename=$title.$file_ending");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	
	/*    FORMATTING FOR EXCEL DOCUMENTS ('.xls')   */
	//create title with timestamp:
		if ($Use_Title == 1){
			echo("$title\n");
		}
	//define separator (defines columns in excel & tabs in word)
		$sep = "\t"; //tabbed character
	//start of printing column names as names of MSSQL fields
		$col = mssql_num_fields($result);
		for ($i = 0; $i < $col; $i++) {
			echo mssql_field_name($result,$i) . "\t";
		}
		print("\n");
	//end of printing column names
	//start while loop to get data
	
		while($row = mssql_fetch_row($result))
		{
	//		echo mssql_num_fields($result);
			//set_time_limit(60); // HaRa
			$schema_insert = "";
			for($j=0; $j<mssql_num_fields($result);$j++)
			{			
				if(!isset($row[$j]))
					$schema_insert .= "NULL".$sep;
				elseif ($row[$j] != "")
					$schema_insert .= "$row[$j]".$sep;
				else
					$schema_insert .= "".$sep;
			}
			$schema_insert = str_replace($sep."$", "", $schema_insert);
			//this corrects output in excel when table fields contain \n or \r
			//these two characters are now replaced with a space
			$schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
			$schema_insert .= "\t";
			print(trim($schema_insert));
			print "\n";
		}
	//}
	
//}	//end of statement for session user is not empty..
