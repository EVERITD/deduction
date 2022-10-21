<?php
include('sqlconnx.php');

$q = strtolower($_GET["q"]);

if (!$q) {
	return;
}
	$qrysupp = "execute get_supplier '{$q}' ";
	$rssupp 	= mssql_query($qrysupp);
	$numrow 	= mssql_num_rows($rssupp);
	$consignor = '';
	$dept 	= htmlspecialchars(strip_tags($_GET['dept']), ENT_QUOTES);
	$div 	= htmlspecialchars(strip_tags($_GET['div']), ENT_QUOTES);
	for ($i = 0; $i < ($d_row = mssql_fetch_array($rssupp)); $i++)
	{
		$consignor = trim(end(explode('-', $d_row['vendor'])));
		if(in_array($dept, array('MKT', 'PUR', 'OPS')) AND strlen($consignor) >= 8)
			$items[$d_row['vendor']] = $d_row['vendor'];

		if(!in_array($dept, array('MKT', 'PUR', 'OPS')))
			$items[$d_row['vendor']] = $d_row['vendor'];
		//var_dump($items);
		//var_dump($dept);
		
	}

	// anong requirement d2?
	// dept? kapag bo?
	
if (isset($items) && is_array($items) && count($items) )
{
	foreach ($items as $key => $value)
	{
		if (strpos(strtolower($value), $q) !== false) {
			echo "$key|$value\n";
		}
	}
}

?>