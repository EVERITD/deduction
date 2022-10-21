<?php
error_reporting(E_ALL ^ E_NOTICE);
$myServer = '192.168.16.24';
$myUser = 'sa';
$myPass = 'Masterkey2';
$myDB = "consolidatePO";

$s = @mssql_connect($myServer, $myUser, $myPass)
or die("Couldn't connect to SQL Server on $myServer");

$d = @mssql_select_db($myDB, $s)
or die("Couldn't open database $myDB");

