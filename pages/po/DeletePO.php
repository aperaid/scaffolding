<?php
require_once('../../connections/Connection.php');
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

if ((isset($_GET['POCode'])) && ($_GET['POCode'] != "")) {
	
	//GET POCode
	$pocode=$_GET['POCode'];
	//GET POCode
	
	//GET Reference for redirect
	mysql_select_db($database_Connection, $Connection);
	$query_reference = sprintf("SELECT DISTINCT transaksi.Reference AS reference FROM transaksi WHERE transaksi.POCode='$pocode'");
	$reference = mysql_query($query_reference, $Connection) or die(mysql_error());
	$row_reference= mysql_fetch_assoc($reference);
	
	$ref = $row_reference['reference'];
	//GET Reference for redirect
	
	//1. Delete PO in po Table
	$query_deletepo = sprintf("DELETE FROM po WHERE POCode='$pocode'");
	$deletepo = mysql_query($query_deletepo, $Connection) or die(mysql_error());
	mysql_free_result($deletepo);
	//1. Delete PO in po Table
	
	//2. Delete PO in transaksi
	$query_deletepotransaksi = sprintf("DELETE FROM transaksi WHERE POCode='$pocode'");
	$deletepotransaksi = mysql_query($query_deletepotransaksi, $Connection) or die(mysql_error());
	mysql_free_result($deletepotransaksi);
	//2. Delete PO in transaksi
	
	//3. Alter PO 
	$query_alterpo = sprintf("ALTER TABLE po AUTO_INCREMENT = 1");
	$alterpo = mysql_query($query_alterpo, $Connection) or die(mysql_error());
	mysql_free_result($alterpo);
	
	//4. Alter transaksi
	$query_altertransaksi = sprintf("ALTER TABLE po AUTO_INCREMENT = 1");
	$altertransaksi = mysql_query($query_altertransaksi, $Connection) or die(mysql_error());
	mysql_free_result($altertransaksi);
	
	$deleteGoTo = "../pocustomer/ViewTransaksi.php?Reference=$ref";
	if (isset($_SERVER['QUERY_STRING'])) {
		$deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
		$deleteGoTo .= $_SERVER['QUERY_STRING'];
	}
	
	header(sprintf("Location: %s", $deleteGoTo));
}
?>