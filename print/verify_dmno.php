<?php
/**
* 
*/
class dmno
{
	
	function __construct()
	{
		include('.../sqlconn.php');
	}
	function dmno2($where)
	{
		$sql="select ltrim(rtrim(dm_no)) as dmno from deduction_master where dm_no in ('{$where}')";
		$qry=mssql_query($sql);

		return $qry;
	}

}

?>