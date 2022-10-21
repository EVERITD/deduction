<?php

 include('bar_graph.class');
 include('sqlconn.php');
 
 // Create labels for each value
 //$value0[] = array();
 //$value1[] = array();
 //$value3[] = array();
$qrymonth = "select distinct month(dm_date) as month from deduction_master where vposted = 0 ";
$rs_qry = mssql_query($qrymonth);
$nm_rows = mssql_num_rows($rs_qry);
if ($nm_rows != 0) {
for ($i=0;$i<($row_month = mssql_fetch_array($rs_qry));$i++) {
	$lbls = $row_month['month'];
	switch ($lbls) {
		case 1 :
			$labels[] = 'Jan';
			$qryunpost = "select count(dm_no) as unpost from deduction_master where vposted = 0 and month(dm_date) = 1";
			$rsunpost = mssql_query($qryunpost);
			$valueunpost = mssql_fetch_array($rsunpost);
			$value0[] = $valueunpost['unpost'];
			$qrypost = "select count(dm_no) as post from deduction_master where vposted = 1 and month(dm_date) = 1";
			$rspost = mssql_query($qrypost);
			$valuepost = mssql_fetch_array($rspost);
			$value1[] = $valuepost['post'];
			$qryextract = "select count(dm_no) as extract from deduction_master where vposted = 3 and month(dm_date) = 1";
			$rsextract = mssql_query($qryextract);
			$valueextract = mssql_fetch_array($rsextract);
			$value3[] = $valueextract['extract'];
			break;
		case 2 :
			$labels[] = 'Feb';
			$qryunpost = "select count(dm_no) as unpost from deduction_master where vposted = 0 and month(dm_date) = 2";
			$rsunpost = mssql_query($qryunpost);
			$valueunpost = mssql_fetch_array($rsunpost);
			$value0[] = $valueunpost['unpost'];
			$qrypost = "select count(dm_no) as post from deduction_master where vposted = 1 and month(dm_date) = 2";
			$rspost = mssql_query($qrypost);
			$valuepost = mssql_fetch_array($rspost);
			$value1[] = $valuepost['post'];
			$qryextract = "select count(dm_no) as extract from deduction_master where vposted = 3 and month(dm_date) = 2";
			$rsextract = mssql_query($qryextract);
			$valueextract = mssql_fetch_array($rsextract);
			$value3[] = $valueextract['extract'];
			break;
		case 3 :
			$labels[] = 'Mar';
			$qryunpost = "select count(dm_no) as unpost from deduction_master where vposted = 0 and month(dm_date) = 3";
			$rsunpost = mssql_query($qryunpost);
			$valueunpost = mssql_fetch_array($rsunpost);
			$value0[] = $valueunpost['unpost'];
			$qrypost = "select count(dm_no) as post from deduction_master where vposted = 1 and month(dm_date) = 3";
			$rspost = mssql_query($qrypost);
			$valuepost = mssql_fetch_array($rspost);
			$value1[] = $valuepost['post'];
			$qryextract = "select count(dm_no) as extract from deduction_master where vposted = 3 and month(dm_date) = 3";
			$rsextract = mssql_query($qryextract);
			$valueextract = mssql_fetch_array($rsextract);
			$value3[] = $valueextract['extract'];
			break;
		case 4 :
			$labels[] = 'Apr';	
			$qryunpost = "select count(dm_no) as unpost from deduction_master where vposted = 0 and month(dm_date) = 4";
			$rsunpost = mssql_query($qryunpost);
			$valueunpost = mssql_fetch_array($rsunpost);
			$value0[] = $valueunpost['unpost'];
			$qrypost = "select count(dm_no) as post from deduction_master where vposted = 1 and month(dm_date) = 4";
			$rspost = mssql_query($qrypost);
			$valuepost = mssql_fetch_array($rspost);
			$value1[] = $valuepost['post'];
			$qryextract = "select count(dm_no) as extract from deduction_master where vposted = 3 and month(dm_date) = 4";
			$rsextract = mssql_query($qryextract);
			$valueextract = mssql_fetch_array($rsextract);
			$value3[] = $valueextract['extract'];
			break;
		case 5 :
			$labels[] = 'May';		
			$qryunpost = "select count(dm_no) as unpost from deduction_master where vposted = 0 and month(dm_date) = 5";
			$rsunpost = mssql_query($qryunpost);
			$valueunpost = mssql_fetch_array($rsunpost);
			$value0[] = $valueunpost['unpost'];
			$qrypost = "select count(dm_no) as post from deduction_master where vposted = 1 and month(dm_date) = 5";
			$rspost = mssql_query($qrypost);
			$valuepost = mssql_fetch_array($rspost);
			$value1[] = $valuepost['post'];
			$qryextract = "select count(dm_no) as extract from deduction_master where vposted = 3 and month(dm_date) = 5";
			$rsextract = mssql_query($qryextract);
			$valueextract = mssql_fetch_array($rsextract);
			$value3[] = $valueextract['extract'];	
			break;
		case 6 :
			$labels[] = 'Jun';			
			$qryunpost = "select count(dm_no) as unpost from deduction_master where vposted = 0 and month(dm_date) = 6";
			$rsunpost = mssql_query($qryunpost);
			$valueunpost = mssql_fetch_array($rsunpost);
			$value0[] = $valueunpost['unpost'];
			$qrypost = "select count(dm_no) as post from deduction_master where vposted = 1 and month(dm_date) = 6";
			$rspost = mssql_query($qrypost);
			$valuepost = mssql_fetch_array($rspost);
			$value1[] = $valuepost['post'];
			$qryextract = "select count(dm_no) as extract from deduction_master where vposted = 3 and month(dm_date) = 6";
			$rsextract = mssql_query($qryextract);
			$valueextract = mssql_fetch_array($rsextract);
			$value3[] = $valueextract['extract'];
			break;
		case 7 :
			$labels[] = 'Jul';	
			$qryunpost = "select count(dm_no) as unpost from deduction_master where vposted = 0 and month(dm_date) = 7";
			$rsunpost = mssql_query($qryunpost);
			$valueunpost = mssql_fetch_array($rsunpost);
			$value0[] = $valueunpost['unpost'];
			$qrypost = "select count(dm_no) as post from deduction_master where vposted = 1 and month(dm_date) = 7";
			$rspost = mssql_query($qrypost);
			$valuepost = mssql_fetch_array($rspost);
			$value1[] = $valuepost['post'];
			$qryextract = "select count(dm_no) as extract from deduction_master where vposted = 3 and month(dm_date) = 7";
			$rsextract = mssql_query($qryextract);
			$valueextract = mssql_fetch_array($rsextract);
			$value3[] = $valueextract['extract'];
			break;
		case 8 :
			$labels[] = 'Aug';
			$qryunpost = "select count(dm_no) as unpost from deduction_master where vposted = 0 and month(dm_date) = 8";
			$rsunpost = mssql_query($qryunpost);
			$valueunpost = mssql_fetch_array($rsunpost);
			$value0[] = $valueunpost['unpost'];
			$qrypost = "select count(dm_no) as post from deduction_master where vposted = 1 and month(dm_date) = 8";
			$rspost = mssql_query($qrypost);
			$valuepost = mssql_fetch_array($rspost);
			$value1[] = $valuepost['post'];
			$qryextract = "select count(dm_no) as extract from deduction_master where vposted = 3 and month(dm_date) = 8";
			$rsextract = mssql_query($qryextract);
			$valueextract = mssql_fetch_array($rsextract);
			$value3[] = $valueextract['extract'];
			break;
		case 9 :
			$labels[] = 'Sep';	
			$qryunpost = "select count(dm_no) as unpost from deduction_master where vposted = 0 and month(dm_date) = 9";
			$rsunpost = mssql_query($qryunpost);
			$valueunpost = mssql_fetch_array($rsunpost);
			$value0[] = $valueunpost['unpost'];
			$qrypost = "select count(dm_no) as post from deduction_master where vposted = 1 and month(dm_date) = 9";
			$rspost = mssql_query($qrypost);
			$valuepost = mssql_fetch_array($rspost);
			$value1[] = $valuepost['post'];
			$qryextract = "select count(dm_no) as extract from deduction_master where vposted = 3 and month(dm_date) = 9";
			$rsextract = mssql_query($qryextract);
			$valueextract = mssql_fetch_array($rsextract);
			$value3[] = $valueextract['extract'];
			break;
		case 10 :
			$labels[] = 'Oct';
			$qryunpost = "select count(dm_no) as unpost from deduction_master where vposted = 0 and month(dm_date) = 10";
			$rsunpost = mssql_query($qryunpost);
			$valueunpost = mssql_fetch_array($rsunpost);
			$value0[] = $valueunpost['unpost'];
			$qrypost = "select count(dm_no) as post from deduction_master where vposted = 1 and month(dm_date) = 10";
			$rspost = mssql_query($qrypost);
			$valuepost = mssql_fetch_array($rspost);
			$value1[] = $valuepost['post'];
			$qryextract = "select count(dm_no) as extract from deduction_master where vposted = 3 and month(dm_date) = 10";
			$rsextract = mssql_query($qryextract);
			$valueextract = mssql_fetch_array($rsextract);
			$value3[] = $valueextract['extract'];		
			break;
		case 11 :
			$labels[] = 'Nov';	
			$qryunpost = "select count(dm_no) as unpost from deduction_master where vposted = 0 and month(dm_date) = 11";
			$rsunpost = mssql_query($qryunpost);
			$valueunpost = mssql_fetch_array($rsunpost);
			$value0[] = $valueunpost['unpost'];
			$qrypost = "select count(dm_no) as post from deduction_master where vposted = 1 and month(dm_date) = 11";
			$rspost = mssql_query($qrypost);
			$valuepost = mssql_fetch_array($rspost);
			$value1[] = $valuepost['post'];
			$qryextract = "select count(dm_no) as extract from deduction_master where vposted = 3 and month(dm_date) = 11";
			$rsextract = mssql_query($qryextract);
			$valueextract = mssql_fetch_array($rsextract);
			$value3[] = $valueextract['extract'];		
			break;
		case 12 :
			$labels[] = 'Dec';			
			$qryunpost = "select count(dm_no) as unpost from deduction_master where vposted = 0 and month(dm_date) = 12";
			$rsunpost = mssql_query($qryunpost);
			$valueunpost = mssql_fetch_array($rsunpost);
			$value0[] = $valueunpost['unpost'];
			$qrypost = "select count(dm_no) as post from deduction_master where vposted = 1 and month(dm_date) = 12";
			$rspost = mssql_query($qrypost);
			$valuepost = mssql_fetch_array($rspost);
			$value1[] = $valuepost['post'];
			$qryextract = "select count(dm_no) as extract from deduction_master where vposted = 3 and month(dm_date) = 12";
			$rsextract = mssql_query($qryextract);
			$valueextract = mssql_fetch_array($rsextract);
			$value3[] = $valueextract['extract'];
			break;
	}
}
 
  // Create values for the graph
 $values = array($value0, $value1, $value3);
// print_r ($values);
//  $values = array(array(10, 6, 17, 25), array(5, 8, 12, 23), array(12, 12, 12, 12));
 // Distance between tick marks on y-axis
 $interval = 1;

 // Color to display bars in.  
 // The number of colors must match the number of data sets
 $bar_color1 = array(87, 138, 254); // dark purple
 $bar_color2 = array(255, 49, 49); // dark red
 $bar_color3 = array(255, 249, 90);
 $bc = array($bar_color1, $bar_color2, $bar_color3);

 // Create the graph object
 // bar_graph( int width, int height, string x-axis label, string y-axis label,
 //            array bar-grouping labels, int space between tick marks,
 //            int space between bars, array data set(s) )
 $bg = new bar_graph(700, 450, 'M O N T H S', 'D E D U C T I O N   S T A T U S', $labels, $interval, 5, $values);

 // Set up the graph
 $bg->set_font_size(9);
 $bg->set_bg_color(array(0,47,45));
 $bg->set_font_color(array(255,255,255));
 $bg->set_bar_color($bc);
 
 // Create a key.  
 // The number of key labels must match the number of data sets.
 // The first label will be assigned to the first data set etc...
 $bg->key(array('New', 'Approved', 'Extracted'));
 $bg->graph();
} else {
	echo '<h1>No Records Found!!!</h1>';
}
?>