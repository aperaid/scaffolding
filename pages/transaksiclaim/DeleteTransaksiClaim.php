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

$colname_Edit = "-1";
if (isset($_GET['Id'])) {
  $colname_Edit = $_GET['Id'];
}

mysql_select_db($database_Connection, $Connection);
// Ambil periode dari claim paling terakhir
$query_Periode = sprintf("SELECT MAX(periode.Periode) AS Periode FROM periode LEFT JOIN transaksiclaim on periode.IsiSJKir = transaksiclaim.IsiSJKir WHERE transaksiclaim.Id = %s AND (Deletes='Sewa' or Deletes='Extend');", GetSQLValueString($colname_Edit, "int"));
$Periode = mysql_query($query_Periode, $Connection) or die(mysql_error());
$row_Periode = mysql_fetch_assoc($Periode);
$totalRows_Periode = mysql_num_rows($Periode);

mysql_select_db($database_Connection, $Connection);
$query_Edit = sprintf("SELECT transaksiclaim.Id, transaksiclaim.Claim, transaksiclaim.QClaim, transaksiclaim.Amount, transaksiclaim.Purchase, transaksiclaim.Periode, transaksiclaim.IsiSJKir, transaksi.Barang, transaksi.QSisaKem, transaksi.Reference FROM transaksiclaim LEFT JOIN transaksi ON transaksiclaim.Purchase=transaksi.Purchase WHERE transaksiclaim.Id = %s", GetSQLValueString($colname_Edit, "int"));
$Edit = mysql_query($query_Edit, $Connection) or die(mysql_error());
$row_Edit = mysql_fetch_assoc($Edit);
$totalRows_Edit = mysql_num_rows($Edit);

if ((isset($_GET['Id'])) && ($_GET['Id'] != "")) {
  $updateSQL = sprintf("UPDATE transaksi SET QSisaKem=QSisaKem+%s WHERE Reference=%s AND Purchase=%s",
 					   GetSQLValueString($row_Edit['QClaim'], "int"),
                       GetSQLValueString($row_Edit['Reference'], "text"),
					   GetSQLValueString($row_Edit['Purchase'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}

if ((isset($_GET['Id'])) && ($_GET['Id'] != "")) {
  $updateSQL = sprintf("UPDATE isisjkirim SET QSisaKemInsert=QSisaKemInsert+%s, QSisaKem=QSisaKem+%s WHERE Purchase=%s AND IsiSJKir=%s",
 					   GetSQLValueString($row_Edit['QClaim'], "int"),
					   GetSQLValueString($row_Edit['QClaim'], "int"),
					   GetSQLValueString($row_Edit['Purchase'], "text"),
                       GetSQLValueString($row_Edit['IsiSJKir'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}

if ((isset($_GET['Id'])) && ($_GET['Id'] != "")) {
  $updateSQL = sprintf("UPDATE periode SET Quantity=Quantity+%s WHERE IsiSJKir=%s AND Periode = %s AND purchase = %s AND (Deletes = 'Sewa' OR Deletes = 'Extend')",
  					   GetSQLValueString($row_Edit['QClaim'], "int"),
                       GetSQLValueString($row_Edit['IsiSJKir'], "text"),
					   GetSQLValueString($row_Periode['Periode'], "int"),
					   GetSQLValueString($row_Edit['Purchase'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
  }

if ((isset($_GET['Id'])) && ($_GET['Id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM periode WHERE Reference=%s AND Purchase=%s AND IsiSJKir=%s AND Claim=%s AND (Deletes='ClaimS' OR Deletes='ClaimE')",
  					   GetSQLValueString($row_Edit['Reference'], "text"),
                       GetSQLValueString($row_Edit['Purchase'], "text"),
                       GetSQLValueString($row_Edit['IsiSJKir'], "text"),
					   GetSQLValueString($row_Edit['Claim'], "text"));
					   
  $alterSQL = sprintf("ALTER TABLE periode AUTO_INCREMENT = 1");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());
}

if ((isset($_GET['Id'])) && ($_GET['Id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM invoice WHERE Reference=%s AND Periode=%s AND JSC='Claim'",
  					   GetSQLValueString($row_Edit['Reference'], "text"),
                       GetSQLValueString($row_Periode['Periode'], "int"));
					   
  $alterSQL = sprintf("ALTER TABLE invoice AUTO_INCREMENT = 1");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());
}

if ((isset($_GET['Id'])) && ($_GET['Id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM invoice WHERE Reference=%s AND Periode=%s+1",
  					   GetSQLValueString($row_Edit['Reference'], "text"),
                       GetSQLValueString($row_Periode['Periode'], "int"));
					   
  $alterSQL = sprintf("ALTER TABLE invoice AUTO_INCREMENT = 1");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());
}

if ((isset($_GET['Id'])) && ($_GET['Id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM transaksiclaim WHERE Id=%s",
                       GetSQLValueString($_GET['Id'], "int"));
					   
  $alterSQL = sprintf("ALTER TABLE transaksiclaim AUTO_INCREMENT = 1");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());

  $deleteGoTo = "TransaksiClaim.php";
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