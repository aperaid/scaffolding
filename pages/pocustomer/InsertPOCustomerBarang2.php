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

session_start();

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$colname_View = "-1";
if (isset($_GET['Reference'])) {
  $colname_View = $_GET['Reference'];
}

mysql_select_db($database_Connection, $Connection);
$query_JS = sprintf("SELECT DISTINCT JS FROM transaksi WHERE Reference=%s AND POCode = (SELECT MAX(POCode) AS POCode FROM transaksi WHERE Reference = %s)", GetSQLValueString($colname_View, "text"), GetSQLValueString($colname_View, "text"));
$JS = mysql_query($query_JS, $Connection) or die(mysql_error());
$row_JS = mysql_fetch_assoc($JS);
$totalRows_JS = mysql_num_rows($JS);

mysql_select_db($database_Connection, $Connection);
$query_LastId = "SELECT MAX(Id) AS Id FROM invoice";
$LastId = mysql_query($query_LastId, $Connection) or die(mysql_error());
$row_LastId = mysql_fetch_assoc($LastId);
$totalRows_LastId = mysql_num_rows($LastId);

$JS2 = array();
$LastId2 = array();
$x = 1;
do{
	$JS2[]=$row_JS['JS'];
	$LastId2[]=str_pad($row_LastId['Id'] + $x, 5, "0", STR_PAD_LEFT);
	$x++;
} while ($row_JS = mysql_fetch_assoc($JS));

mysql_select_db($database_Connection, $Connection);
$query_Reference = sprintf("SELECT Reference FROM transaksi WHERE Reference = %s", GetSQLValueString($colname_View, "text"));
$Reference = mysql_query($query_Reference, $Connection) or die(mysql_error());
$row_Reference = mysql_fetch_assoc($Reference);
$totalRows_Reference = mysql_num_rows($Reference);

$tx_insertpocustomerbarang2_PPN = $_SESSION['tx_insertpocustomerbarang_PPN'];
$tx_insertpocustomerbarang2_Transport = str_replace(".","",substr($_SESSION['tx_insertpocustomerbarang_Transport'], 3));
$tx_insertpocustomerbarang2_Tgl = substr($_SESSION['tx_insertpocustomerbarang_Tgl'], 1, -1);

for($i=0;$i<$totalRows_JS;$i++){
if ((isset($_GET['Reference'])) && ($_GET['Reference'] != "")) {
  $insertSQL = sprintf("INSERT INTO invoice (Invoice, JSC, Tgl, PPN, Transport, Reference, Periode) VALUES (%s, %s, %s, %s, %s, %s, 1)",
                       GetSQLValueString($LastId2[$i], "text"),
                       GetSQLValueString($JS2[$i], "text"),
					   GetSQLValueString($tx_insertpocustomerbarang2_Tgl, "text"),
					   GetSQLValueString($tx_insertpocustomerbarang2_PPN, "text"),
					   GetSQLValueString($tx_insertpocustomerbarang2_Transport, "text"),
					   GetSQLValueString($_GET['Reference'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "POCustomer.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
}

if ((isset($_GET['Reference'])) && ($_GET['Reference'] != "")) {
	unset($_SESSION['tx_insertpocustomer_Tgl']);
	unset($_SESSION['tx_insertpocustomerbarang_PPN']);
	unset($_SESSION['tx_insertpocustomer_Transport']);
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
  mysql_free_result($JS);
  mysql_free_result($LastId);
  mysql_free_result($Reference);
?>
