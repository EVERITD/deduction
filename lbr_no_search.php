<?php
 					$myServer = 'EVERSQL';
                    $myUser = 'sa';
                    $myPass = 'masterkey1';
                    $myDB = "LBR";

                    $s = @mssql_connect($myServer, $myUser, $myPass)
                    or die("Couldn't connect to SQL Server on $myServer");

                    $d = @mssql_select_db($myDB, $s)
                    or die("Couldn't open database $myDB");



 $q = trim($_GET['q']);
$sql = "select ltrim(rtrim(lbr_no)) as lbr_no from lbr_master where lbr_no like '%$q%'";
$query  = mssql_query($sql);


	while ($value = mssql_fetch_array($query))
	{
		if (strpos(strtolower($value['lbr_no']), $value2) !== false) 
		{
			echo $value['lbr_no']."\n";

		}
		
	}


?>