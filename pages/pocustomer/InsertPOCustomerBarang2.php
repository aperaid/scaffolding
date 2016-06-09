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

$maxRows_JS = 10;
$pageNum_JS = 0;
if (isset($_GET['pageNum_JS'])) {
  $pageNum_JS = $_GET['pageNum_JS'];
}
$startRow_JS = $pageNum_JS * $maxRows_JS;

$colname_JS = "-1";
if (isset($_GET['Reference'])) {
  $colname_JS = $_GET['Reference'];
}
mysql_select_db($database_Connection, $Connection);
$query_JS = sprintf("SELECT DISTINCT JS FROM transaksi WHERE Reference = %s", GetSQLValueString($colname_JS, "text"));
$query_limit_JS = sprintf("%s LIMIT %d, %d", $query_JS, $startRow_JS, $maxRows_JS);
$JS = mysql_query($query_limit_JS, $Connection) or die(mysql_error());
$row_JS = mysql_fetch_assoc($JS);

if (isset($_GET['totalRows_JS'])) {
  $totalRows_JS = $_GET['totalRows_JS'];
} else {
  $all_JS = mysql_query($query_JS);
  $totalRows_JS = mysql_num_rows($all_JS);
}
$totalPages_JS = ceil($totalRows_JS/$maxRows_JS)-1;

mysql_select_db($database_Connection, $Connection);
$query_LastId = "SELECT Id FROM invoice ORDER BY Id DESC";
$LastId = mysql_query($query_LastId, $Connection) or die(mysql_error());
$row_LastId = mysql_fetch_assoc($LastId);
$totalRows_LastId = mysql_num_rows($LastId);

$colname_Reference = "-1";
if (isset($_GET['Reference'])) {
  $colname_Reference = $_GET['Reference'];
}
mysql_select_db($database_Connection, $Connection);
$query_Reference = sprintf("SELECT Reference FROM transaksi WHERE Reference = %s", GetSQLValueString($colname_Reference, "text"));
$Reference = mysql_query($query_Reference, $Connection) or die(mysql_error());
$row_Reference = mysql_fetch_assoc($Reference);
$totalRows_Reference = mysql_num_rows($Reference);

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO pocustomer (Reference, Tgl, PCode) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['hd_insertpocustomerbarang2_Reference'], "text"),
                       GetSQLValueString($_POST['hd_insertpocustomerbarang2_Tgl'], "text"),
					   GetSQLValueString($_POST['hd_insertpocustomerbarang2_PCode'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
}

for($i=0;$i<$totalRows_JS;$i++){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO invoice (Invoice, JSC, PPN, Transport, Reference, Tgl, Periode) VALUES (%s, %s, %s, %s, %s, %s, 1)",
                       GetSQLValueString($_POST['hd_insertpocustomerbarang2_Invoice'][$i], "text"),
                       GetSQLValueString($_POST['hd_insertpocustomerbarang2_JS'][$i], "text"),
					   GetSQLValueString($_POST['hd_insertpocustomerbarang2_PPN'], "text"),
					   GetSQLValueString($_POST['hd_insertpocustomerbarang2_Transport'], "text"),
                       GetSQLValueString($_POST['hd_insertpocustomerbarang2_Reference'], "text"),
					   GetSQLValueString($_POST['hd_insertpocustomerbarang2_Tgl2'], "text"));

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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	unset($_SESSION['tx_insertpocustomer_Reference']);
	unset($_SESSION['tx_insertpocustomer_Tgl']);
	unset($_SESSION['tx_insertpocustomer_PCode']);
	unset($_SESSION['tx_insertpocustomer_PPN']);
	unset($_SESSION['tx_insertpocustomer_Transport']);
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>

<script type="text/javascript">
    function submit()
    {
        document.getElementById("bt_insertpocustomerbarang2_submit").click(); // Simulates button click
        document.submitForm.submit(); // Submits the form without the button
    }
</script>

</head>

<body onLoad="submit()">
<form action="<?php echo $editFormAction; ?>" id="fm_insertpocustomerbarang2_form1" name="fm_insertpocustomerbarang2_form1" method="POST">
  <table width="1350" border="0">
    <tbody>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
	  <?php
	  $tx_insertpocustomerbarang2_Reference = substr($_SESSION['tx_insertpocustomer_Reference'], 1, -1);
	  $tx_insertpocustomerbarang2_Tgl = substr($_SESSION['tx_insertpocustomer_Tgl'], 1, -1);
	  $tx_insertpocustomerbarang2_PCode = substr($_SESSION['tx_insertpocustomer_PCode'], 1, -1);
	  $tx_insertpocustomerbarang2_PPN = $_SESSION['tx_insertpocustomerbarang_PPN'];
	  $tx_insertpocustomerbarang2_Transport = str_replace(".","",substr($_SESSION['tx_insertpocustomerbarang_Transport'], 3));
 	  ?>
      <?php $tgl = 0; $bln = substr($tx_insertpocustomerbarang2_Tgl, 3, -5); $thn = substr($tx_insertpocustomerbarang2_Tgl, 6);
			if ($bln == 1){
				$tgl = 31;
				$bln = '01';
				}
			elseif ($bln == 2){
				$tgl = 28;
				$bln = '02';
				if ($thn == 2016 || $thn == 2020 || $thn == 2024){
				$tgl = 29;
				$bln = '02';
				}
				}
			elseif ($bln == 3){
				$tgl = 31;
				$bln = '03';
				}
			elseif ($bln == 4){
				$tgl = 30;
				$bln = '04';
				}
			elseif ($bln == 5){
				$tgl = 31;
				$bln = '05';
				}
			elseif ($bln == 6){
				$tgl = 30;
				$bln = '06';
				}
			elseif ($bln == 7){
				$tgl = 31;
				$bln = '07';
				}
			elseif ($bln == 8){
				$tgl = 31;
				$bln = '08';
				}
			elseif ($bln == 9){
				$tgl = 30;
				$bln = '09';
				}
			elseif ($bln == 10){
				$tgl = 31;
				$bln = '10';
				}
			elseif ($bln == 11){
				$tgl = 30;
				$bln = '11';
				}
			elseif ($bln == 12){
				$tgl = 31;
				$bln = '12';
				}
			?>
      <?php $x=1; ?>
      <?php do { ?>
        <tr>
          <td>&nbsp;</td>
          <td><input name="hd_insertpocustomerbarang2_Invoice[]" type="hidden" id="hd_insertpocustomerbarang2_Invoice" value="<?php echo str_pad($row_LastId['Id'] + $x, 5, "0", STR_PAD_LEFT); ?>">
          <input name="hd_insertpocustomerbarang2_JS[]" type="hidden" id="hd_insertpocustomerbarang2_JS" value="<?php echo $row_JS['JS']; ?>">
          <input name="hd_insertpocustomerbarang2_PPN" type="hidden" id="hd_insertpocustomerbarang2_PPN" value="<?php echo $tx_insertpocustomerbarang2_PPN; ?>">
          <input name="hd_insertpocustomerbarang2_Transport" type="hidden" id="hd_insertpocustomerbarang2_Transport" value="<?php echo $tx_insertpocustomerbarang2_Transport; ?>">
          <input name="hd_insertpocustomerbarang2_Reference" type="hidden" id="tx_insertpocustomerbarang2_Reference" value="<?php echo $tx_insertpocustomerbarang2_Reference; ?>"></td>
          <input name="hd_insertpocustomerbarang2_Tgl" type="hidden" id="hd_insertpocustomerbarang2_Tgl" value="<?php echo $tx_insertpocustomerbarang2_Tgl; ?>"></td>
          <input name="hd_insertpocustomerbarang2_PCode" type="hidden" id="hd_insertpocustomerbarang2_PCode" value="<?php echo $tx_insertpocustomerbarang2_PCode; ?>"></td>
          <input name="hd_insertpocustomerbarang2_Tgl2" type="hidden" id="hd_insertpocustomerbarang2_Tgl2" value="<?php echo $tgl, '/', $bln, '/', $thn; ?>"></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      <?php $x++; ?>
      <?php } while ($row_JS = mysql_fetch_assoc($JS)); ?>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
  <input type="submit" name="bt_insertpocustomerbarang2_submit" id="bt_insertpocustomerbarang2_submit" value="">
</form>
</body>
</html>
<?php
  mysql_free_result($JS);
  mysql_free_result($LastId);
  mysql_free_result($Reference);
?>
