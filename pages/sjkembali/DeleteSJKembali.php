<?php require_once('../../connections/Connection.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}




// Below old code
mysql_select_db($database_Connection, $Connection);
$query_Periode = sprintf("SELECT invoice.Periode FROM invoice LEFT JOIN sjkembali on invoice.Reference = sjkembali.Reference WHERE sjkembali.SJKem = %s AND invoice.Transport IS NULL ORDER BY invoice.Id DESC", GetSQLValueString($_GET['SJKem'], "text"));
$Periode = mysql_query($query_Periode, $Connection) or die(mysql_error());
$row_Periode = mysql_fetch_assoc($Periode);
$totalRows_Periode = mysql_num_rows($Periode);

$Periodess = $row_Periode['Periode'];
$Periodes = $row_Periode['Periode']+1;

mysql_select_db($database_Connection, $Connection);
$query_IsiSJKir = sprintf("SELECT IsiSJKir FROM isisjkembali WHERE SJKem = %s", GetSQLValueString($_GET['SJKem'], "text"));
$IsiSJKir = mysql_query($query_IsiSJKir, $Connection) or die(mysql_error());
$row_IsiSJKir = mysql_fetch_assoc($IsiSJKir);
$totalRows_IsiSJKir = mysql_num_rows($IsiSJKir);

$query = mysql_query($query_IsiSJKir, $Connection) or die(mysql_error());
$IsiSJKir2 = array();
while($row = mysql_fetch_assoc($query)){
	$IsiSJKir2[] = $row['IsiSJKir'];
}
$IsiSJKir3 = join(',',$IsiSJKir2);  

mysql_select_db($database_Connection, $Connection);
$query_Quantity = sprintf("SELECT Quantity FROM periode WHERE SJKem = %s AND Deletes = 'KembaliS' AND IsiSJKir IN ($IsiSJKir3)", GetSQLValueString($_GET['SJKem'], "text"));
$Quantity = mysql_query($query_Quantity, $Connection) or die(mysql_error());
$row_Quantity = mysql_fetch_assoc($Quantity);
$totalRows_Quantity = mysql_num_rows($Quantity);

$query = mysql_query($query_Quantity, $Connection) or die(mysql_error());
$Quantity2 = array();
while($row = mysql_fetch_assoc($query)){
	$Quantity2[] = $row['Quantity'];
}

for($i=0;$i<$totalRows_IsiSJKir;$i++){
if ((isset($_GET['SJKem'])) && ($_GET['SJKem'] != "")) {
  $updateSQL = sprintf("UPDATE isisjkirim SET QSisaKemInsert=QTertanda WHERE IsiSJKir=%s",
                       GetSQLValueString($IsiSJKir2[$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
}

if ((isset($_GET['SJKem'])) && ($_GET['SJKem'] != "")) {
  $deleteSQL = "DELETE FROM invoice WHERE Periode = $Periodes";

  $alterSQL = sprintf("ALTER TABLE invoice AUTO_INCREMENT = 1");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());
}

for($i=0;$i<$totalRows_Quantity;$i++){
if ((isset($_GET['SJKem'])) && ($_GET['SJKem'] != "")) {
  $updateSQL = sprintf("UPDATE periode SET Quantity=Quantity+%s WHERE IsiSJKir=%s AND Periode = %s AND Deletes != 'KembaliS' AND Deletes != 'KembaliE' AND Deletes != 'ClaimS' AND Deletes != 'ClaimE' AND Deletes != 'Jual'",
  					   GetSQLValueString($Quantity2[$i], "int"),
                       GetSQLValueString($IsiSJKir2[$i], "text"),
					   GetSQLValueString($Periodess, "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
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