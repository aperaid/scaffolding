<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

mysql_select_db($database_Connection, $Connection);
$query_Purchase = sprintf("SELECT isisjkirim.Purchase FROM isisjkirim LEFT JOIN transaksi ON isisjkirim.Purchase = transaksi.Purchase WHERE isisjkirim.SJKir = %s", GetSQLValueString($_GET['SJKir'], "text"));
$Purchase = mysql_query($query_Purchase, $Connection) or die(mysql_error());
$row_Purchase = mysql_fetch_assoc($Purchase);
$totalRows_IsiSJKir = mysql_num_rows($Purchase);

mysql_select_db($database_Connection, $Connection);
$query_IsiSJKir = sprintf("SELECT isisjkirim.IsiSJKir FROM isisjkirim LEFT JOIN sjkirim ON isisjkirim.SJKir = sjkirim.SJKir WHERE isisjkirim.SJKir = %s", GetSQLValueString($_GET['SJKir'], "text"));
$IsiSJKir = mysql_query($query_IsiSJKir, $Connection) or die(mysql_error());
$row_IsiSJKir = mysql_fetch_assoc($IsiSJKir);
$totalRows_IsiSJKir = mysql_num_rows($IsiSJKir);  

if ((isset($_GET['SJKir'])) && ($_GET['SJKir'] != "")) {
  $updateSQL = sprintf("UPDATE transaksi JOIN isisjkirim ON isisjkirim.Purchase=transaksi.Purchase SET transaksi.QSisaKirInsert=transaksi.QSisaKirInsert+isisjkirim.QKirim WHERE isisjkirim.SJKir=%s",
 					   GetSQLValueString($_GET['SJKir'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}

if ((isset($_GET['SJKir'])) && ($_GET['SJKir'] != "")) {
  $deleteSQL = sprintf("DELETE periode FROM periode JOIN isisjkirim ON periode.IsiSJKir=isisjkirim.IsiSJKir WHERE isisjkirim.SJKir=%s",
  					   GetSQLValueString($_GET['SJKir'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
}

if ((isset($_GET['SJKir'])) && ($_GET['SJKir'] != "")) {
  $deleteSQL = sprintf("DELETE FROM isisjkirim WHERE SJKir=%s;",
  					   GetSQLValueString($_GET['SJKir'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
}

if ((isset($_GET['SJKir'])) && ($_GET['SJKir'] != "")) {
  $deleteSQL = sprintf("DELETE FROM sjkirim WHERE SJKir=%s;",
  					   GetSQLValueString($_GET['SJKir'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  
  $alterSQL = sprintf("ALTER TABLE periode AUTO_INCREMENT = 1");
  $alterSQL2 = sprintf("ALTER TABLE sjkirim AUTO_INCREMENT = 1");
  $alterSQL3 = sprintf("ALTER TABLE isisjkirim AUTO_INCREMENT = 1");
  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL2, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL3, $Connection) or die(mysql_error());
  
  
  $deleteGoTo = "SJKirim.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

/*
if ((isset($_GET['SJKir'])) && ($_GET['SJKir'] != "")) {
  $updateSQL = "UPDATE transaksi SET QSisaKirInsert=QSisaKirInsert+QSisaKem, QSisaKem = 0 WHERE Purchase IN ($Purchases2)";

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}

if ((isset($_GET['SJKir'])) && ($_GET['SJKir'] != "")) {
  $deleteSQL = "DELETE FROM periode WHERE IsiSJKir IN ($IsiSJKirs2)";

  $alterSQL = sprintf("ALTER TABLE periode AUTO_INCREMENT = 1");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());
}

if ((isset($_GET['SJKir'])) && ($_GET['SJKir'] != "")) {
  $deleteSQL = sprintf("DELETE FROM isisjkirim WHERE SJKir=%s",
                       GetSQLValueString($_GET['SJKir'], "text"));

  $alterSQL = sprintf("ALTER TABLE isisjkirim AUTO_INCREMENT = 1");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());
}

if ((isset($_GET['SJKir'])) && ($_GET['SJKir'] != "")) {
  $deleteSQL = sprintf("DELETE FROM sjkirim WHERE SJKir=%s",
                       GetSQLValueString($_GET['SJKir'], "text"));

  $alterSQL = sprintf("ALTER TABLE sjkirim AUTO_INCREMENT = 1");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());

  $deleteGoTo = "SJKirim.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}
*/
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
  mysql_free_result($Purchase);
  mysql_free_result($IsiSJKir);
  mysql_free_result($query);
?>