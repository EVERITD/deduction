<?php

/*
note:
this is just a static test version using a hard-coded countries array.
normally you would be populating the array out of a database

the returned xml has the following structure
<results>
	<rs>foo</rs>
	<rs>bar</rs>
</results>
*/
include('sqlconnx.php');

	$qrysupp = "select supcode,dbo.CleanApostrophe(supname)+' - '+supcode as suppliername from mgr.supplier where supcode <> '' ";
	$rssupp = mssql_query($qrysupp);

	$input = strtolower( $_GET['input'] );
	$len = strlen($input);
	$aResults = array();

	if ($len)
	{
		for ($i = 0; $i < ($aUsers = mssql_fetch_array($rssupp)); $i++) 
		{
			// had to use utf_decode, here
			// not necessary if the results are coming from mysql
			//
			if (strtolower(substr(utf8_decode($aUsers['suppliername']),0,$len)) == $input)
				$aResults[] = array( "id"=>($i+1) ,"value"=>htmlspecialchars(trim($aUsers['suppliername'])), "info"=>htmlspecialchars(trim($aUsers['supcode'])) );
			
//			if (stripos(utf8_decode($aUsers[$i]), $input) !== false)
//				$aResults[] = array( "id"=>($i+1) ,"value"=>htmlspecialchars($aUsers[$i]), "info"=>htmlspecialchars($aInfo[$i]) );
		}
	}
	
	
	
	
	
	header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
	header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header ("Pragma: no-cache"); // HTTP/1.0
	
	
	
	if (isset($_REQUEST['json']))
	{
		header("Content-Type: application/json");
	
		echo "{\"results\": [";
		$arr = array();
		for ($i=0;$i<count($aResults);$i++)
		{
			$arr[] = "{\"id\": \"".$aResults[$i]['id']."\", \"value\": \"".$aResults[$i]['value']."\", \"info\": \"\"}";
		}
		echo implode(", ", $arr);
		echo "]}";
	}
	else
	{
		header("Content-Type: text/xml");

		echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?><results>";
		for ($i=0;$i<count($aResults);$i++)
		{
			echo "<rs id=\"".$aResults[$i]['id']."\" info=\"".$aResults[$i]['info']."\">".$aResults[$i]['value']."</rs>";
		}
		echo "</results>";
	}
?>