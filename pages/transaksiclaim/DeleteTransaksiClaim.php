<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

$colname_Edit = "-1";
if (isset($_GET['Reference'])) {
  $colname_Edit = $_GET['Reference'];
  $colname_Periode = $_GET['Periode'];
}

// Ambil claim code, quantity, amount, purchase#, periode, dll
mysql_select_db($database_Connection, $Connection);
$query_Edit = sprintf("SELECT SUM(periode.Quantity) AS Quantity, periode.IsiSJKir, periode.Purchase, periode.Claim FROM periode WHERE periode.Periode = %s AND periode.Reference = %s AND periode.Deletes = 'Claim' GROUP BY periode.IsiSJKir", GetSQLValueString($colname_Periode, "text"), GetSQLValueString($colname_Edit, "text"));
$Edit = mysql_query($query_Edit, $Connection) or die(mysql_error());
$row_Edit = mysql_fetch_assoc($Edit);
$totalRows_Edit = mysql_num_rows($Edit);


$query = mysql_query($query_Edit, $Connection) or die(mysql_error());
$Quantity = array();
while($row = mysql_fetch_assoc($query)){
	$Quantity[] = $row['Quantity'];
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
// Balikin Quantity ke QsisaKem Anggapannya masih di sewa
if ((isset($_GET['Periode'])) && ($_GET['Periode'] != "")) {
for($i=0;$i<$totalRows_Edit;$i++){
  $updateSQL = sprintf("UPDATE transaksi SET QSisaKem=QSisaKem+%s WHERE Reference=%s AND Purchase=%s",
 					   GetSQLValueString($Quantity[$i], "int"),
                       GetSQLValueString($colname_Edit, "text"),
					   GetSQLValueString($Purchase[$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
}

// Update isisjkirim
// Balikin Qsisakeminsert dan Qsisa kembali masing2 sesuai dgn jumlah apa yg udah diclaim
if ((isset($_GET['Periode'])) && ($_GET['Periode'] != "")) {
for($i=0;$i<$totalRows_Edit;$i++){
  $updateSQL = sprintf("UPDATE isisjkirim SET QSisaKemInsert=QSisaKemInsert+%s, QSisaKem=QSisaKem+%s WHERE Purchase=%s AND IsiSJKir=%s",
 					   GetSQLValueString($Quantity[$i], "int"),
					   GetSQLValueString($Quantity[$i], "int"),
					   GetSQLValueString($Purchase[$i], "text"),
					   GetSQLValueString($IsiSJKir[$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
}

// Update Periode
// Balikin quantity seperti semula sesua isisjkirim mana yg udah di claim
if ((isset($_GET['Periode'])) && ($_GET['Periode'] != "")) {
for($i=0;$i<$totalRows_Edit;$i++){
  $updateSQL = sprintf("UPDATE periode SET Quantity=Quantity+%s WHERE IsiSJKir=%s AND Purchase=%s AND Periode = %s AND (Deletes = 'Sewa' OR Deletes = 'Extend')",
  					   GetSQLValueString($Quantity[$i], "int"),
					   GetSQLValueString($IsiSJKir[$i], "text"),
					   GetSQLValueString($Purchase[$i], "text"),
					   GetSQLValueString($colname_Periode, "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
  }
}

// Delete Periode
// Delete ClaimE dan Claim sesuai nomor purchase dan reference dan isisjkir mana yg udah kena claim
if ((isset($_GET['Periode'])) && ($_GET['Periode'] != "")) {
  $deleteSQL = sprintf("DELETE FROM periode WHERE Reference=%s AND Purchase IN ($Purchase2) AND IsiSJKir IN ($IsiSJKir2) AND Deletes='Claim'",
  					   GetSQLValueString($colname_Edit, "text"));
					   
  $alterSQL = sprintf("ALTER TABLE periode AUTO_INCREMENT = 1");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());
}

// Delete Invoice
// Hapus semua invoice yg 
if ((isset($_GET['Periode'])) && ($_GET['Periode'] != "")) {
  $deleteSQL = sprintf("DELETE FROM invoice WHERE Reference=%s AND Periode=%s AND JSC='Claim'",
  					   GetSQLValueString($colname_Edit, "text"),
                       GetSQLValueString($colname_Periode, "int"));
					   
  $alterSQL = sprintf("ALTER TABLE invoice AUTO_INCREMENT = 1");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());
}

// Delete Transaksi
if ((isset($_GET['Periode'])) && ($_GET['Periode'] != "")) {
  for($i=0;$i<$totalRows_Edit;$i++){
  // delete transaksi claim
  $deleteSQL = sprintf("DELETE FROM transaksiclaim WHERE Purchase = %s AND Periode = %s",
					   GetSQLValueString($Purchase[$i], "text"),
					   GetSQLValueString($colname_Periode, "int"));
  // abis delete, auto increment nya dijadiin 1 lagi				   
  $alterSQL = sprintf("ALTER TABLE transaksiclaim AUTO_INCREMENT = 1");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());
  
  // Redirect ke transaksi claim abis delete
  $deleteGoTo = "../transaksisewa/TransaksiSewa.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
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