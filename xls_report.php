<?php
session_start();

//error_reporting(-1);
$x = strlen($_SESSION['user']);

if ($x > 0) {

	include('sqlconn.php');

	if (strlen($_GET['br'])>0) {
		$br = $_GET['br'];
	} else {
		if (strlen($_POST['br'])>0) {
			$br = $_POST['br'];
		}
	}

	if (strlen($_GET['dp'])>0) {
		$dp = $_GET['dp'];
	} else {
		if (strlen($_POST['dp'])>0) {
			$dp = $_POST['dp'];
		}
	}

	if (strlen($_GET['dv'])>0) {
		$dv = $_GET['dv'];
	} else {
		if (strlen($_POST['dv'])>0) {
			$dv = $_POST['dv'];
		}
	}

	if (strlen($_GET['ct'])>0) {
		$ct = $_GET['ct'];
	} else {
		if (strlen($_POST['ct'])>0) {
			$ct = $_POST['ct'];
		}
	}

	if (strlen($_GET['sct'])>0) {
		$sct = $_GET['sct'];
	} else {
		if (strlen($_POST['sct'])>0) {
			$sct = $_POST['sct'];
		}
	}

	if (strlen($_GET['st'])>0) {
		$st = $_GET['st'];
	} else {
		if (strlen($_POST['st'])>0) {
			$st = $_POST['st'];
		} else {
			$st = '%';
		}
	}

	if (strlen($_GET['lcaccright'])>0) {
		$lcaccright = $_GET['lcaccright'];
	} else {
		if (strlen($_POST['lcaccright'])>0) {
			$lcaccright = $_POST['lcaccright'];
		}
	}

	if (strlen($_GET['lcuser'])>0) {
		$lcuser = $_GET['lcuser'];
	} else {
		if (strlen($_POST['lcuser'])>0) {
			$lcuser = $_POST['lcuser'];
		}
	}

	if (strlen($_GET['euser'])>0) {
		$euser = $_GET['euser'];
	} else {
		if (strlen($_POST['euser'])>0) {
			$euser = $_POST['euser'];
		}
	}

	if (strlen($_GET['file'])>0) {
		$file = $_GET['file'];
	} else {
		if (strlen($_POST['file'])>0) {
			$file = $_POST['file'];
		}
	}

	if (strlen($_GET['datefr'])>0) {
		$datefr = date_format($_GET['datefr'],'Ymd');
	} else {
		if (strlen($_POST['datefr'])>0) {
			$datefr = date_format($_POST['datefr'],'Ymd');
		}
	}

	if (strlen($_GET['dateto'])>0) {
		$dateto = date_format($_GET['dateto'],'Ymd');
	} else {
		if (strlen($_POST['dateto'])>0) {
			$dateto = date_format($_POST['dateto'],'Ymd');
		}
	}

	$now_date = date('m-d-Y');

	$title = "Deduction_".$now_date;

	$stat = (@$_GET['cmbstatus']) ? '%': $_GET['cmbstatus'];

	$selexport = "execute xls_report_deduction '{$br}','{$dp}','{$dv}','{$ct}','{$sct}','{$st}','{$lcaccright}','{$lcuser}','{$euser}','{$file}','{$datefr}','{$dateto}', '{$stat}'";
	
	$Use_Title = 0;

	$result = mssql_query($selexport);

	$file_type = "vnd.ms-excel";
	$file_ending = "xls";
	$app = "application/";

	// echo "$title.$file_ending";
	// die();

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
		echo $col = mssql_num_fields($result);
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
//					if($j == 9)
//					{
//						$row[$j]	= "'".$row[$j];
//					}
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
	//exit();
}	//end of statement for session user is not empty..
