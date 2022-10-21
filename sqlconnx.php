<?php
error_reporting(E_ALL ^ E_NOTICE);

$myServer = '192.168.16.63';
$myUser = 'sa';
$myPass = 'donterase';
$myDB = "ARMS";

//$myServer = "it-jayson";
//$myUser = "sa";
//$myPass = "donterase";
//$myDB = "VOUCHERSERIES";

$s = @mssql_connect($myServer, $myUser, $myPass)
   or die("Couldn't connect to SQL Server on $myServer");

$d = @mssql_select_db($myDB, $s)
   or die("Couldn't open database $myDB");
