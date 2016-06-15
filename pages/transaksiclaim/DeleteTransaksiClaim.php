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
if (isset($_GET['Reference'])) {
  $colname_Edit = $_GET['Reference'];
  $colname_Periode = $_GET['Periode'];
}

mysql_select_db($database_Connection, $Connection);
// Ambil periode dari claim paling terakhir
$query_ClaimId = sprintf("SELECT transaksiclaim.Id FROM transaksiclaim LEFT JOIN periode on transaksiclaim.IsiSJKir = periode.IsiSJKir WHERE periode.Reference = %s AND transaksiclaim.Periode = %s AND (Deletes='Sewa' or Deletes='Extend') GROUP BY transaksiclaim.Id;", GetSQLValueString($colname_Edit, "text"), GetSQLValueString($colname_Periode, "text"));
$ClaimId = mysql_query($query_ClaimId, $Connection) or die(mysql_error());
$row_ClaimId = mysql_fetch_assoc($ClaimId);
$totalRows_ClaimId = mysql_num_rows($ClaimId);

$query = mysql_query($query_ClaimId, $Connection) or die(mysql_error());
$Id = array();
while($row = mysql_fetch_assoc($query)){
	$Id[] = $row['Id'];
}
$Id2 = join(',',$Id); 

mysql_select_db($database_Connection, $Connection);
// Ambil periode dari claim paling terakhir
$query_Periode = "SELECT MAX(periode.Periode) AS Periode FROM periode LEFT JOIN transaksiclaim on periode.IsiSJKir = transaksiclaim.IsiSJKir WHERE transaksiclaim.Id IN ($Id2) AND (Deletes='Sewa' or Deletes='Extend');";
$Periode = mysql_query($query_Periode, $Connection) or die(mysql_error());
$row_Periode = mysql_fetch_assoc($Periode);
$totalRows_Periode = mysql_num_rows($Periode);

// Ambil claim ID, claim code, qclaim, amount, purchase#, periode, dll
mysql_select_db($database_Connection, $Connection);
$query_Edit = "SELECT transaksiclaim.Id, transaksiclaim.Claim, transaksiclaim.QClaim, transaksiclaim.Amount, transaksiclaim.Purchase, transaksiclaim.Periode, transaksiclaim.IsiSJKir, transaksi.Barang, transaksi.QSisaKem, transaksi.Reference FROM transaksiclaim LEFT JOIN transaksi ON transaksiclaim.Purchase=transaksi.Purchase WHERE transaksiclaim.Id IN ($Id2)";
$Edit = mysql_query($query_Edit, $Connection) or die(mysql_error());
$row_Edit = mysql_fetch_assoc($Edit);
$totalRows_Edit = mysql_num_rows($Edit);


$query = mysql_query($query_Edit, $Connection) or die(mysql_error());
$QClaim = array();
while($row = mysql_fetch_assoc($query)){
	$QClaim[] = $row['QClaim'];
}

$query = mysql_query($query_Edit, $Connection) or die(mysql_error());
$Claim = array();
while($row = mysql_fetch_assoc($query)){
	$Claim[] = $row['Claim'];
}
$Claim2 = join(',',$Claim); 

$query = mysql_query($query_Edit, $Connection) or die(mysql_error());
$Purchase = array();
while($row = mysql_fetch_assoc($query)){
	$Purchase[] = $row['Purchase'];
}
$Purchase2 = join(',',$Purchase); 

$query = mysql_query($query_Edit, $Connection) or die(mysql_error());
$IsiSJKir = array();
while($row = mysql_fetch_assoc($query)){
	$IsiSJKir[] = $row['IsiSJKir'];
}
$IsiSJKir2 = join(',',$IsiSJKir); 

// Update Transaksi
if ((isset($_GET['Periode'])) && ($_GET['Periode'] != "")) {
for($i=0;$i<$totalRows_Edit;$i++){
  $updateSQL = sprintf("UPDATE transaksi SET QSisaKem=QSisaKem+%s WHERE Reference=%s AND Purchase=%s",
 					   GetSQLValueString($QClaim[$i], "int"),
                       GetSQLValueString($row_Edit['Reference'], "text"),
					   GetSQLValueString($Purchase[$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
}

// Update isisjkirim
if ((isset($_GET['Periode'])) && ($_GET['Periode'] != "")) {
for($i=0;$i<$totalRows_Edit;$i++){
  $updateSQL = sprintf("UPDATE isisjkirim SET QSisaKemInsert=QSisaKemInsert+%s, QSisaKem=QSisaKem+%s WHERE Purchase=%s AND IsiSJKir=%s",
 					   GetSQLValueString($QClaim[$i], "int"),
					   GetSQLValueString($QClaim[$i], "int"),
					   GetSQLValueString($Purchase[$i], "text"),
					   GetSQLValueString($IsiSJKir[$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
}

// Update Periode
if ((isset($_GET['Periode'])) && ($_GET['Periode'] != "")) {
for($i=0;$i<$totalRows_Edit;$i++){
  $updateSQL = sprintf("UPDATE periode SET Quantity=Quantity+%s WHERE IsiSJKir=%s AND Purchase=%s AND Periode = %s AND (Deletes = 'Sewa' OR Deletes = 'Extend')",
  					   GetSQLValueString($QClaim[$i], "int"),
					   GetSQLValueString($IsiSJKir[$i], "text"),
					   GetSQLValueString($Purchase[$i], "text"),
					   GetSQLValueString($row_Periode['Periode'], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
  }
}

// Delete Periode
if ((isset($_GET['Periode'])) && ($_GET['Periode'] != "")) {
  $deleteSQL = sprintf("DELETE FROM periode WHERE Reference=%s AND Purchase IN ($Purchase2) AND IsiSJKir IN ($IsiSJKir2) AND Claim IN ($Claim2) AND (Deletes='ClaimS' OR Deletes='ClaimE')",
  					   GetSQLValueString($row_Edit['Reference'], "text"));
					   
  $alterSQL = sprintf("ALTER TABLE periode AUTO_INCREMENT = 1");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());
}

// Delete Invoice
if ((isset($_GET['Periode'])) && ($_GET['Periode'] != "")) {
  $deleteSQL = sprintf("DELETE FROM invoice WHERE Reference=%s AND Periode=%s AND JSC='Claim'",
  					   GetSQLValueString($row_Edit['Reference'], "text"),
                       GetSQLValueString($row_Periode['Periode'], "int"));
					   
  $alterSQL = sprintf("ALTER TABLE invoice AUTO_INCREMENT = 1");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());
}

// Delete Invoice
if ((isset($_GET['Periode'])) && ($_GET['Periode'] != "")) {
  $deleteSQL = sprintf("DELETE FROM invoice WHERE Reference=%s AND Periode=%s+1",
  					   GetSQLValueString($row_Edit['Reference'], "text"),
                       GetSQLValueString($row_Periode['Periode'], "int"));
					   
  $alterSQL = sprintf("ALTER TABLE invoice AUTO_INCREMENT = 1");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());
}

// Delete Transaksi
if ((isset($_GET['Periode'])) && ($_GET['Periode'] != "")) {
  // delete transaksi claim sesuai ID
  $deleteSQL = "DELETE FROM transaksiclaim WHERE Id IN ($Id2)";
  // abis delete, auto increment nya dijadiin 1 lagi				   
  $alterSQL = sprintf("ALTER TABLE transaksiclaim AUTO_INCREMENT = 1");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());
  
  // Redirect ke transaksi claim abis delete
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