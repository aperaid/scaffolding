<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

// Below old code
mysql_select_db($database_Connection, $Connection);
// Ambil periode dari sjkem paling terakhir
$query_Periode = sprintf("SELECT MAX(periode.Periode) AS Periode FROM periode LEFT JOIN isisjkembali on periode.IsiSJKir = isisjkembali.IsiSJKir WHERE isisjkembali.SJKem = %s AND (Deletes='Sewa' or Deletes='Extend');", GetSQLValueString($_GET['SJKem'], "text"));
$Periode = mysql_query($query_Periode, $Connection) or die(mysql_error());
$row_Periode = mysql_fetch_assoc($Periode);
$totalRows_Periode = mysql_num_rows($Periode);

// Ambil semua isisjkir dari periode untuk penghapusan periode
 mysql_select_db($database_Connection, $Connection);
$query_IsiSJKir = sprintf("SELECT IsiSJKir FROM periode WHERE SJKem = %s", GetSQLValueString($_GET['SJKem'], "text"));
$IsiSJKir = mysql_query($query_IsiSJKir, $Connection) or die(mysql_error());
$row_IsiSJKir = mysql_fetch_assoc($IsiSJKir);
$totalRows_IsiSJKir = mysql_num_rows($IsiSJKir);

$IsiSJKir2 = array();
do{
	$IsiSJKir2[]=$row_IsiSJKir['IsiSJKir'];
} while ($row_IsiSJKir = mysql_fetch_assoc($IsiSJKir));

// Ambil semua quantity dari periode untuk penghapusan periode
mysql_select_db($database_Connection, $Connection);
$query_Quantity = sprintf("SELECT Quantity FROM periode WHERE SJKem = %s AND Deletes = 'Kembali'", GetSQLValueString($_GET['SJKem'], "text"));
$Quantity = mysql_query($query_Quantity, $Connection) or die(mysql_error());
$row_Quantity = mysql_fetch_assoc($Quantity);
$totalRows_Quantity = mysql_num_rows($Quantity);

$Quantity2 = array();
do{
	$Quantity2[]=$row_Quantity['Quantity'];
} while ($row_Quantity = mysql_fetch_assoc($Quantity));

if ((isset($_GET['SJKem'])) && ($_GET['SJKem'] != "")) {
  for($i=0;$i<$totalRows_Quantity;$i++){
  $updateSQL = sprintf("UPDATE periode SET Quantity=Quantity+%s WHERE IsiSJKir=%s AND Periode = %s AND (Deletes = 'Sewa' OR Deletes = 'Extend')",
  					   GetSQLValueString($Quantity2[$i], "int"),
                       GetSQLValueString($IsiSJKir2[$i], "text"),
					   GetSQLValueString($row_Periode['Periode'], "text"));
					   
  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
 }

// Safety Net
if ((isset($_GET['SJKem'])) && ($_GET['SJKem'] != "")) {
  $deleteSQL = sprintf("SELECT delete_sjkem(%s)",
                       GetSQLValueString($_GET['SJKem'], "text"));
  
  $alterSQL = sprintf("ALTER TABLE periode AUTO_INCREMENT = 1");
  $alterSQL2 = sprintf("ALTER TABLE isisjkembali AUTO_INCREMENT = 1");
  $alterSQL3 = sprintf("ALTER TABLE sjkembali AUTO_INCREMENT = 1");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL2, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL3, $Connection) or die(mysql_error());
  
  $deleteGoTo = "SJKembali.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}
}
/*
for($i=0;$i<$totalRows_IsiSJKir;$i++){
if ((isset($_GET['SJKem'])) && ($_GET['SJKem'] != "")) {
  $updateSQL = sprintf("UPDATE isisjkirim INNER JOIN isisjkembali ON isisjkirim.IsiSJKir=isisjkembali.IsiSJKir SET isisjkirim.QSisaKemInsert=isisjkirim.QSisaKemInsert+isisjkembali.QTertanda WHERE isisjkembali.SJKem=%s",
                       GetSQLValueString($_GET['SJKem'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
}

// Hapus invoice yg periode nya Gede 
if ((isset($_GET['SJKem'])) && ($_GET['SJKem'] != "")) {
  $deleteSQL = "DELETE FROM invoice WHERE Periode = $Periodes AND Reference=";

  $alterSQL = sprintf("ALTER TABLE invoice AUTO_INCREMENT = 1");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());
}

if ((isset($_GET['SJKem'])) && ($_GET['SJKem'] != "")) {
  $deleteSQL = sprintf("DELETE FROM periode WHERE SJKem=%s",
                       GetSQLValueString($_GET['SJKem'], "text"));

  $alterSQL = sprintf("ALTER TABLE periode AUTO_INCREMENT = 1");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());
}

if ((isset($_GET['SJKem'])) && ($_GET['SJKem'] != "")) {
  $deleteSQL = sprintf("DELETE FROM isisjkembali WHERE SJKem=%s",
                       GetSQLValueString($_GET['SJKem'], "text"));

  $alterSQL = sprintf("ALTER TABLE isisjkembali AUTO_INCREMENT = 1");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());
}

if ((isset($_GET['SJKem'])) && ($_GET['SJKem'] != "")) {
  $deleteSQL = sprintf("DELETE FROM sjkembali WHERE SJKem=%s",
                       GetSQLValueString($_GET['SJKem'], "text"));

  $alterSQL = sprintf("ALTER TABLE sjkembali AUTO_INCREMENT = 1");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());

  $deleteGoTo = "SJKembali.php";
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