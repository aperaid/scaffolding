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

// Ambil Reference & Periode dari URI
$colname_InsertTransaksiClaim = "-1";
if (isset($_GET['Reference'])) {
  $colname_InsertTransaksiClaim = $_GET['Reference'];
  $colname_Periode = $_GET['Periode'];
}

$checkbox = $_SESSION['cb_inserttransaksiclaimbarang_checkbox'];
$remove = preg_replace("/[^0-9,.]/", "", $checkbox);
$test_count=count($remove);
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

// Ambil ID dari transaksi claim
mysql_select_db($database_Connection, $Connection);
$query_LastClaim = "SELECT MAX(Id) AS Id FROM transaksiclaim";
$LastClaim = mysql_query($query_LastClaim, $Connection) or die(mysql_error());
$row_LastClaim = mysql_fetch_assoc($LastClaim);
$totalRows_LastClaim = mysql_num_rows($LastClaim);

mysql_select_db($database_Connection, $Connection);
$query_GetId = sprintf("SELECT MAX(Id) AS Id FROM periode WHERE Reference = %s AND (Deletes = 'Sewa' OR Deletes = 'Extend') GROUP BY IsiSJKir ORDER BY Id DESC", GetSQLValueString($_GET['Reference'], "text"));
$GetId = mysql_query($query_GetId, $Connection) or die(mysql_error());
$row_GetId = mysql_fetch_assoc($GetId);
$totalRows_GetId = mysql_num_rows($GetId);

$Id = array();
do{
	$Id[] = $row_GetId['Id'];
} while ($row_GetId = mysql_fetch_assoc($GetId));
$Id2 = join(',',$Id);

// Ambil ID isisjkirim, purchase, qsisakem, barang, id periode dkk berdasarkan reference, periode, sewa/extend, dan isisjkir
mysql_select_db($database_Connection, $Connection);
$query_InsertTransaksiClaim = sprintf("SELECT isisjkirim.Purchase, SUM(isisjkirim.QSisaKem) AS QSisaKem, transaksi.Barang, periode.Periode, periode.IsiSJKir, periode.S, periode.E FROM isisjkirim LEFT JOIN periode ON isisjkirim.IsiSJKir = periode.IsiSJKir LEFT JOIN transaksi ON periode.Purchase=transaksi.Purchase LEFT JOIN pocustomer ON transaksi.Reference=pocustomer.Reference WHERE transaksi.Reference = %s AND periode.Periode = %s AND (Deletes = 'Sewa' OR Deletes = 'Extend') AND isisjkirim.Purchase IN ($Purchase) GROUP BY isisjkirim.Purchase ORDER BY periode.Id ASC", GetSQLValueString($colname_InsertTransaksiClaim, "text"), GetSQLValueString($colname_Periode, "text"));
$InsertTransaksiClaim = mysql_query($query_InsertTransaksiClaim, $Connection) or die(mysql_error());
$row_InsertTransaksiClaim = mysql_fetch_assoc($InsertTransaksiClaim);
$totalRows_InsertTransaksiClaim = mysql_num_rows($InsertTransaksiClaim);

mysql_select_db($database_Connection, $Connection);
$query_InsertTransaksiClaim2 = sprintf("SELECT periode.Periode, periode.Quantity, periode.IsiSJKir, periode.Purchase FROM periode WHERE periode.Id IN ($Id2) AND periode.Reference=%s ORDER BY periode.Id ASC", GetSQLValueString($colname_InsertTransaksiClaim, "text"));
$InsertTransaksiClaim2 = mysql_query($query_InsertTransaksiClaim2, $Connection) or die(mysql_error());
$row_InsertTransaksiClaim2 = mysql_fetch_assoc($InsertTransaksiClaim2);
$totalRows_InsertTransaksiClaim2 = mysql_num_rows($InsertTransaksiClaim2);

$Quantity = array();
$IsiSJKir = array();
$Purchase2 = array();
$Claim = array();
$x = 1;
do{
	$Periode=$row_InsertTransaksiClaim2['Periode'];
	$Quantity[]=$row_InsertTransaksiClaim2['Quantity'];
	$IsiSJKir[]=$row_InsertTransaksiClaim2['IsiSJKir'];
	$Purchase2[]=$row_InsertTransaksiClaim2['Purchase'];
	$Claim[]=$row_LastClaim['Id']+$x;
	$x++;
} while ($row_InsertTransaksiClaim2 = mysql_fetch_assoc($InsertTransaksiClaim2));
$Claim2 = join(',',$Claim);

// Ambil reference dari URI masukin ke $colname_Reference
$colname_Reference = "-1";
if (isset($_GET['Reference'])) {
  $colname_Reference = $_GET['Reference'];
}

// Ambil reference dari URI mauskin ke $colname_Periode
$colname_Periode = "-1";
if (isset($_GET['Reference'])) {
  $colname_Periode = $_GET['Reference'];
}

//Insert transaksiclaim
for ($i=0;$i<$totalRows_InsertTransaksiClaim2;$i++){
if ((isset($_GET['Periode'])) && ($_GET['Periode'] != "")) {
  $insertSQL = sprintf("INSERT INTO transaksiclaim (Claim, Tgl, QClaim, Purchase, Periode, IsiSJKir, PPN) SELECT periode.Claim, %s, periode.Quantity, periode.Purchase, periode.Periode, periode.IsiSJKir, %s FROM periode WHERE periode.Claim IN ($Claim2) AND periode.IsiSJKir = %s",
                       GetSQLValueString(substr($_SESSION['hd_inserttransaksiclaimbarang2_E'], 1, -1), "text"),
					   GetSQLValueString($_SESSION['tx_inserttransaksiclaimbarang2_PPN'], "int"),
					   GetSQLValueString($IsiSJKir[$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
}
}

for ($i=0;$i<$totalRows_InsertTransaksiClaim2;$i++){
if ((isset($_GET['Periode'])) && ($_GET['Periode'] != "")) {
	$updateSQL = sprintf("UPDATE transaksiclaim SET transaksiclaim.Amount = %s WHERE transaksiclaim.periode = %s AND transaksiclaim.Claim = %s AND transaksiclaim.Amount IS NULL",
					   GetSQLValueString($_SESSION['tx_inserttransaksiclaimbarang2_Amount'][$i], "int"),
					   GetSQLValueString($Periode, "int"),
					   GetSQLValueString($Claim[$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());

  $insertGoTo = "TransaksiClaim.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
}

if ((isset($_GET['Periode'])) && ($_GET['Periode'] != "")) {
	unset($_SESSION['cb_inserttransaksiclaimbarang_checkbox']);
	unset($_SESSION['tx_inserttransaksiclaim_Tgl']);
	unset($_SESSION['tx_inserttransaksiclaim_Reference']);
	unset($_SESSION['hd_inserttransaksiclaimbarang2_E']);
	unset($_SESSION['tx_inserttransaksiclaimbarang2_Amount']);
	unset($_SESSION['tx_inserttransaksiclaimbarang2_PPN']);
}
?>

<!doctype html>
<html>
<head>
</head>
<body>
<!--<input value="<?php echo $Claim[1] ?>">-->
</body>
</html>

<?php
  mysql_free_result($InsertTransaksiClaim);
  mysql_free_result($LastClaim);
  mysql_free_result($Periode);
  mysql_free_result($LastId);
?>