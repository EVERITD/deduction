<?php
error_reporting(E_ALL ^ E_NOTICE);
// $myServer = '192.168.16.63';
// $myUser = 'sa';
// $myPass = 'donterase';
// $myDB = "DM_DEDUCTION";
$myServer = '192.168.16.63';
$myUser = 'sa';
$myPass = 'donterase';
$myDB = "DEDUCTION_TESTDATA";
// $myServer = '(local)';
// $myUser = 'sa';
// $myPass = 'donterase';
// $myDB = "__TEST__DEDUCTION__";

$s = @mssql_connect($myServer, $myUser, $myPass)
   or die("Couldn't connect to SQL Server on $myServer");

$d = @mssql_select_db($myDB, $s)
   or die("Couldn't open database $myDB");
