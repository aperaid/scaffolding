<?php require_once('../../connections/Connection.php'); ?>
<?php date_default_timezone_set("Asia/Krasnoyarsk"); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$colname_Extend = "-1";
if (isset($_GET['Reference'])) {
  $colname_Extend = $_GET['Reference'];
}

$colname_Extend2 = "-1";
if (isset($_GET['Periode'])) {
  $colname_Extend2 = $_GET['Periode'];
}


mysql_select_db($database_Connection, $Connection);
$query_LastInvoiceId = "SELECT MAX(Id)AS Id FROM invoice";
$LastInvoiceId = mysql_query($query_LastInvoiceId, $Connection) or die(mysql_error());
$row_LastInvoiceId = mysql_fetch_assoc($LastInvoiceId);
$totalRows_LastInvoiceId = mysql_num_rows($LastInvoiceId);

mysql_select_db($database_Connection, $Connection);
$query_Extend = sprintf("SELECT periode.* FROM periode LEFT JOIN isisjkirim ON periode.IsiSJKir=isisjkirim.IsiSJKir WHERE periode.Reference=%s AND periode.Periode=%s AND periode.SJKem IS NULL AND (Deletes = 'Sewa' OR Deletes = 'Extend')", GetSQLValueString($colname_Extend, "text"),GetSQLValueString($colname_Extend2, "text"));

$Extend = mysql_query($query_Extend, $Connection) or die(mysql_error());
$row_Extend = mysql_fetch_assoc($Extend);
$totalRows_Extend = mysql_num_rows($Extend);

$Quantity = array();
$IsiSJKir = array();
$Purchase = array();
do{
	$Periode=$row_Extend['Periode'];
	$date = $row_Extend['E'];
	$Quantity[]=$row_Extend['Quantity'];
	$IsiSJKir[]=$row_Extend['IsiSJKir'];
	$Reference=$row_Extend['Reference'];
	$Purchase[]=$row_Extend['Purchase'];
	
} while ($row_Extend = mysql_fetch_assoc($Extend));

$date2 = str_replace('/', '-', $date);
$FirstDate = strtotime("+1 day", strtotime($date2));
$FirstDate2 = date("d/m/Y", $FirstDate);
$LastDate = strtotime("+1 month", strtotime($date2));
$LastDate2 = date("d/m/Y", $LastDate);

$LastInvoice = str_pad($row_LastInvoiceId['Id'] + 1, 5, "0", STR_PAD_LEFT);

if ((isset($_GET['Periode'])) && ($_GET['Periode'] != "")) {
  $insertSQL = sprintf("INSERT INTO invoice (Invoice, JSC, Tgl, Reference, Periode) VALUES (%s, 'Sewa', %s, %s, %s)",
                       GetSQLValueString($LastInvoice, "text"),
                       GetSQLValueString($LastDate2, "text"),
                       GetSQLValueString($Reference, "text"),
                       GetSQLValueString($Periode+1, "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
}

for($i=0;$i<$totalRows_Extend;$i++){
if ((isset($_GET['Periode'])) && ($_GET['Periode'] != "")) {
  $insertSQL = sprintf("INSERT INTO periode (Periode, S, E, Quantity, IsiSJKir, Reference, Purchase, Claim, Deletes) VALUES (%s, %s, %s, %s, %s, %s, %s, '', 'Extend')",
                       GetSQLValueString($Periode+1, "int"),
                       GetSQLValueString($FirstDate2, "text"),
                       GetSQLValueString($LastDate2, "text"),
                       GetSQLValueString($Quantity[$i], "int"),
                       GetSQLValueString($IsiSJKir[$i], "text"),
                       GetSQLValueString($Reference, "text"),
					   GetSQLValueString($Purchase[$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "TransaksiSewa.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
}
?>



<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>

</body>
</html>

<?php
  mysql_free_result($Extend);
  mysql_free_result($LastInvoiceId);
?>
