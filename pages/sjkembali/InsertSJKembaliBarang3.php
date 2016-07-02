<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$checkbox = $_SESSION['cb_insertsjkembalibarang_checkbox'];
$remove = preg_replace("/[^0-9,.]/", "", $checkbox);

error_reporting(E_ERROR); // bagian di ilangin error
$array = array();
    for ($i = 0; $i < 10; ++$i) { // krn bagian sini ga ngerti untuk count sesuai byk array
        $array[$i] = $remove[$i];
}
$count = count(array_filter($array));

$arrayaftercount = array();
    for ($i = 0; $i < $count; ++$i) {
        $arrayaftercount[$i] = $remove[$i];
}
	
$Purchase = join(',',$arrayaftercount); 

mysql_select_db($database_Connection, $Connection);
$query_LastIsiSJKembali = "SELECT MAX(Id) AS Id FROM isisjkembali";
$LastIsiSJKembali = mysql_query($query_LastIsiSJKembali, $Connection) or die(mysql_error());
$row_LastIsiSJKembali = mysql_fetch_assoc($LastIsiSJKembali);
$totalRows_LastIsiSJKembali = mysql_num_rows($LastIsiSJKembali);

$colname_GetId = "-1";
if (isset($_GET['Reference'])) {
  $colname_GetId = $_GET['Reference'];
}
mysql_select_db($database_Connection, $Connection);
$query_GetId = sprintf("SELECT MAX(Id) AS Id FROM periode WHERE Reference = %s AND (Deletes = 'Sewa' OR Deletes = 'Extend') GROUP BY IsiSJKir ORDER BY Id DESC", GetSQLValueString($colname_GetId, "text"));
$GetId = mysql_query($query_GetId, $Connection) or die(mysql_error());
$row_GetId = mysql_fetch_assoc($GetId);
$totalRows_GetId = mysql_num_rows($GetId);

$Id = array();
do{
	$Id[] = $row_GetId['Id'];
} while ($row_GetId = mysql_fetch_assoc($GetId));
$Id2 = join(',',$Id);

mysql_select_db($database_Connection, $Connection);
$query_InsertSJKembali = sprintf("SELECT isisjkirim.*, SUM(isisjkirim.QSisaKemInsert) AS QSisaKemInsert, periode.Periode, periode.S, sjkirim.SJKir, sjkirim.Tgl, transaksi.Barang, transaksi.Purchase, transaksi.Reference FROM isisjkirim LEFT JOIN periode ON isisjkirim.IsiSJKir=periode.IsiSJKir INNER JOIN sjkirim ON isisjkirim.SJKir=sjkirim.SJKir INNER JOIN transaksi ON isisjkirim.Purchase=transaksi.Purchase WHERE isisjkirim.Purchase IN ($Purchase) AND periode.Id IN ($Id2) AND transaksi.Reference=%s GROUP BY isisjkirim.Purchase ORDER BY periode.Id ASC", GetSQLValueString($colname_GetId, "text"));
$InsertSJKembali = mysql_query($query_InsertSJKembali, $Connection) or die(mysql_error());
$row_InsertSJKembali = mysql_fetch_assoc($InsertSJKembali);
$totalRows_InsertSJKembali = mysql_num_rows($InsertSJKembali);

mysql_select_db($database_Connection, $Connection);
$query_InsertSJKembali2 = sprintf("SELECT periode.Quantity, periode.IsiSJKir, periode.Purchase FROM periode WHERE periode.Id IN ($Id2) AND periode.Reference=%s ORDER BY periode.Id ASC", GetSQLValueString($colname_GetId, "text"));
$InsertSJKembali2 = mysql_query($query_InsertSJKembali2, $Connection) or die(mysql_error());
$row_InsertSJKembali2 = mysql_fetch_assoc($InsertSJKembali2);
$totalRows_InsertSJKembali2 = mysql_num_rows($InsertSJKembali2);

$Quantity = array();
$IsiSJKir = array();
$Purchase2 = array();
$IsiSJKem = array();
$x = 1;
do{
	$Quantity[]=$row_InsertSJKembali2['Quantity'];
	$IsiSJKir[]=$row_InsertSJKembali2['IsiSJKir'];
	$Purchase2[]=$row_InsertSJKembali2['Purchase'];
	$IsiSJKem[]=$row_LastIsiSJKembali['Id']+$x;
	$x++;
} while ($row_InsertSJKembali2 = mysql_fetch_assoc($InsertSJKembali2));
$Quantity2 = join(',',$Quantity);
$IsiSJKem2 = join(',',$IsiSJKem);

$query = mysql_query($query_InsertSJKembali, $Connection) or die(mysql_error());
$Periode = array();
while($row = mysql_fetch_assoc($query)){
	$Periode[] = $row['Periode'];
}
$Periode2 = join(',',$Periode);

mysql_select_db($database_Connection, $Connection);
$query_Invoice = sprintf("SELECT * FROM invoice WHERE Reference = %s AND Periode IN ($Periode2) GROUP BY Periode ORDER BY Id DESC", GetSQLValueString($colname_GetId, "text"));
$Invoice = mysql_query($query_Invoice, $Connection) or die(mysql_error());
$row_Invoice = mysql_fetch_assoc($Invoice);
$totalRows_Invoice = mysql_num_rows($Invoice);

/*for ($i=0;$i<$totalRows_InsertSJKembali2;$i++){
if ((isset($_GET['Reference'])) && ($_GET['Reference'] != "")) {
  $insertSQL = sprintf("INSERT INTO isisjkembali (IsiSJKem, QTertanda, Purchase, SJKem, Periode, IsiSJKir) SELECT %s, periode.Quantity, periode.Purchase, periode.SJKem, periode.Periode, periode.IsiSJKir FROM periode WHERE periode.SJKem = %s AND periode.IsiSJKir = %s",
                       GetSQLValueString($IsiSJKem[$i], "text"),
					   GetSQLValueString(substr($_SESSION['hd_insertsjkembalibarang2_SJKem'], 1, -1), "text"),
					   GetSQLValueString($IsiSJKir[$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
}
}*/

for ($i=0;$i<$totalRows_InsertSJKembali2;$i++){
if ((isset($_GET['Reference'])) && ($_GET['Reference'] != "")) {
	$updateSQL = sprintf("UPDATE isisjkembali SET isisjkembali.Warehouse = %s WHERE isisjkembali.periode = %s AND isisjkembali.IsiSJKem = %s AND isisjkembali.Warehouse IS NULL",
					   GetSQLValueString(substr($_SESSION['tx_insertsjkembalibarang2_Warehouse'][$i], 1, -1), "text"),
					   GetSQLValueString($_SESSION['hd_insertsjkembalibarang2_Periode'], "int"),
					   GetSQLValueString($IsiSJKem[$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
 
  $insertGoTo = "SJKembali.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
  }
}

if ((isset($_GET['Reference'])) && ($_GET['Reference'] != "")) {
	unset($_SESSION['cb_insertsjkembalibarang_checkbox']);
	unset($_SESSION['tx_insertsjkembali_SJKem']);
	unset($_SESSION['tx_insertsjkembali_Tgl']);
	unset($_SESSION['tx_insertsjkembali_Reference']);
	unset($_SESSION['hd_insertsjkembalibarang2_SJKem']);
	unset($_SESSION['tx_insertsjkembalibarang2_Warehouse']);
	unset($_SESSION['hd_insertsjkembalibarang2_Periode']);
}
?>

<!doctype html>
<html>
<head>
</head>
<body>
<input value="<?php echo substr($_SESSION['tx_insertsjkembalibarang2_Warehouse'][0], 1, -1) ?>">
</body>
</html>

<?php
  mysql_free_result($Select);
  mysql_free_result($LastIsiSJKembali);
  mysql_free_result($Reference);
  mysql_free_result($InsertSJKembali);
  mysql_free_result($LastTglS);
?>