<?php
//  
// +------------------------------------------------------------------------+
// | PHP version 5.0 					                                  	|
// +------------------------------------------------------------------------+
// | Description:													      	|
// | Class to populate drop down using AJAX + PHP 	  						|	
// | 																		|	
// +------------------------------------------------------------------------+
// | Author				: Neeraj Thakur <neeraj_th@yahoo.com>   			|
// | Created Date     	: 18-12-2006                  						|
// | Last Modified    	: 18-12-2006                  						|
// | Last Modified By 	: Neeraj Thakur                  					|
// +------------------------------------------------------------------------+


DEFINE ('DB_USER', 'sa');
DEFINE ('DB_PASSWORD', 'masterkey');
DEFINE ('DB_HOST', 'everlyl');
DEFINE ('DB_NAME', 'dm_deduction');

class AjaxDropdown
{
	var $table;
	
	function AjaxDropdown()
	{		
		// Make the connnection and then select the database.
		$dbc = @mssql_connect (DB_HOST, DB_USER, DB_PASSWORD) OR die ('Could not connect to MySQL: ' );
		mssql_select_db (DB_NAME,$dbc) OR die ('Could not select the database: ' . mysql_error() );
		$this->table = "ref_supervisor";
	}
	
	function dbConnect()
	{
		DEFINE ('LINK', mssql_connect (DB_HOST, DB_USER, DB_PASSWORD));
	}
	
	function getXML($id)
	{
		$this->dbConnect();
		$query = "SELECT user_name,supervisor FROM $this->table where supervisor = {$id} ";
		$result = mssql_query ($query);
		
		$xml = '<?xml version="1.0" encoding="ISO-8859-1" ?>';
		$xml .= '<categories>';
		for($i=0;$i < ($row = mssql_fetch_array($result));$i++) 
		//while($row = mssql_fetch_array($result))
		{
			$xml .= '<category>';
			$xml .= '<id>'. $row['user_name'] .'</id>';
			$xml .= '<fname>'. $row['supervisor'] .'</fname>';
			$xml .= '</category>';
		}
		$xml .= '</categories>';
		mssql_close();		
		return $xml;
	}	
	
	function getArray($id)
	{
		$this->dbConnect();
		$query = "SELECT user_name,supervisor FROM $this->table where supervisor = '{$id}' ";
		$result = mssql_query($query);
		$arr = array();
		for($i=0;$i < ($row = mssql_fetch_array($result));$i++) 
		//while($row = mssql_fetch_array($result))
		{
			$arr[] = $row;
		}
		mssql_close();		
		return $arr;
	}
}

if ( @$_GET['method'] == 'getXML' )
{
	header("Content-Type: application/xml; charset=UTF-8");
	$obj = new AjaxDropdown();
	echo $obj->getXML(@$_GET['param']);
}
?>