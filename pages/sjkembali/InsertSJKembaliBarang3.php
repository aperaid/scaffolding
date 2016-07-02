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

$get_Reference = $_GET['Reference'];

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

$SJKem = substr($_SESSION['hd_insertsjkembalibarang2_SJKem'], 1, -1);

mysql_select_db($database_Connection, $Connection);
$query_IsiSJKem = sprintf("SELECT IsiSJKem FROM isisjkembali WHERE SJKem = %s", GetSQLValueString($SJKem, "text"));
$IsiSJKem = mysql_query($query_IsiSJKem, $Connection) or die(mysql_error());
$row_IsiSJKem = mysql_fetch_assoc($IsiSJKem);
$totalRows_IsiSJKem = mysql_num_rows($IsiSJKem);

$IsiSJKem2 = array();
do{
	$IsiSJKem2[]=$row_IsiSJKem['IsiSJKem'];
} while ($row_IsiSJKem = mysql_fetch_assoc($IsiSJKem));
$IsiSJKem3 = join(',',$IsiSJKem2);

mysql_select_db($database_Connection, $Connection);
$query_InsertSJKembali2 = sprintf("SELECT periode.Quantity, periode.IsiSJKir, periode.Purchase, periode.Periode, isisjkembali.IsiSJKem FROM periode LEFT JOIN isisjkembali ON periode.SJKem=isisjkembali.SJKem WHERE periode.Id IN (SELECT MAX(Id) AS Id FROM periode WHERE Reference = %s AND (Deletes = 'Sewa' OR Deletes = 'Extend') GROUP BY IsiSJKir) AND periode.Reference=%s ORDER BY periode.Id ASC", GetSQLValueString($get_Reference, "text"), GetSQLValueString($get_Reference, "text"));
$InsertSJKembali2 = mysql_query($query_InsertSJKembali2, $Connection) or die(mysql_error());
$row_InsertSJKembali2 = mysql_fetch_assoc($InsertSJKembali2);
$totalRows_InsertSJKembali2 = mysql_num_rows($InsertSJKembali2);

$IsiSJKir = array();
$Purchase = array();
$x = 1;
do{
	$IsiSJKir[]=$row_InsertSJKembali2['IsiSJKir'];
	$Periode=$row_InsertSJKembali2['Periode'];
	$Purchase[]=$row_InsertSJKembali2['Purchase'];
	$x++;
} while ($row_InsertSJKembali2 = mysql_fetch_assoc($InsertSJKembali2));

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
	$updateSQL = sprintf("UPDATE isisjkembali SET isisjkembali.Warehouse = %s WHERE isisjkembali.periode = %s AND isisjkembali.IsiSJKem IN ($IsiSJKem3) AND isisjkembali.Purchase = %s AND isisjkembali.Warehouse IS NULL",
					   GetSQLValueString(substr($_SESSION['tx_insertsjkembalibarang2_Warehouse'][$i], 1, -1), "text"),
					   GetSQLValueString($Periode, "int"),
					   GetSQLValueString($Purchase[$i], "int"));

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
}
?>

<!doctype html>
<html>
<head>
</head>
<body>
<input value="<?php echo $IsiSJKem3 ?>">
</body>
</html>

<?php
  mysql_free_result($IsiSJKem);
  mysql_free_result($InsertSJKembali2);
?>