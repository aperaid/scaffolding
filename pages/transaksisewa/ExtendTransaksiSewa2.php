<?php require_once('../../connections/Connection.php'); ?>
<?php date_default_timezone_set("Asia/Krasnoyarsk"); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$colname_Extend = "-1";
if (isset($_GET['Reference'])) {
  $colname_Extend = $_GET['Reference'];
}

$colname_Extend2 = "-1";
if (isset($_GET['Periode'])) {
  $colname_Extend2 = $_GET['Periode'];
}

$colname_Invoice = "-1";
if (isset($_GET['Reference'])) {
  $colname_Invoice = $_GET['Reference'];
}
mysql_select_db($database_Connection, $Connection);
$query_Invoice = sprintf("SELECT PPN, Transport FROM invoice WHERE Reference = %s", GetSQLValueString($colname_Invoice, "text"));
$Invoice = mysql_query($query_Invoice, $Connection) or die(mysql_error());
$row_Invoice = mysql_fetch_assoc($Invoice);
$totalRows_Invoice = mysql_num_rows($Invoice);

mysql_select_db($database_Connection, $Connection);
$query_LastInvoiceId = "SELECT Id FROM invoice ORDER BY Id DESC";
$LastInvoiceId = mysql_query($query_LastInvoiceId, $Connection) or die(mysql_error());
$row_LastInvoiceId = mysql_fetch_assoc($LastInvoiceId);
$totalRows_LastInvoiceId = mysql_num_rows($LastInvoiceId);

mysql_select_db($database_Connection, $Connection);
$query_Extend = sprintf("SELECT periode.* FROM periode WHERE periode.Reference=%s AND periode.Periode=%s AND SJKem IS NULL AND Deletes != 'ClaimS' AND Deletes != 'ClaimE' ORDER BY periode.Id ASC", GetSQLValueString($colname_Extend, "text"),GetSQLValueString($colname_Extend2, "text"));
$Extend = mysql_query($query_Extend, $Connection) or die(mysql_error());
$row_Extend = mysql_fetch_assoc($Extend);
$totalRows_Extend = mysql_num_rows($Extend);

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO invoice (Invoice, JSC, Tgl, PPN, Transport, Reference, Periode) VALUES (%s, 'Sewa', %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['hd_extendtransaksisewa_Invoice'], "text"),
                       GetSQLValueString($_POST['hd_extendtransaksisewa_Tgl'], "text"),
                       GetSQLValueString($_POST['hd_extendtransaksisewa_PPN'], "int"),
                       GetSQLValueString($_POST['hd_extendtransaksisewa_Transport'], "text"),
                       GetSQLValueString($_POST['hd_extendtransaksisewa_Reference2'], "text"),
                       GetSQLValueString($_POST['hd_extendtransaksisewa_Periode2'], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
}

for($i=0;$i<$totalRows_Extend;$i++){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO periode (Periode, S, E, Quantity, IsiSJKir, Reference, Purchase, Deletes) VALUES (%s, %s, %s, %s, %s, %s, %s, 'Extend')",
                       GetSQLValueString($_POST['hd_extendtransaksisewa_Periode'][$i], "int"),
                       GetSQLValueString($_POST['hd_extendtransaksisewa_S2'][$i], "text"),
                       GetSQLValueString($_POST['hd_extendtransaksisewa_E2'][$i], "text"),
                       GetSQLValueString($_POST['hd_extendtransaksisewa_Quantity'][$i], "int"),
                       GetSQLValueString($_POST['hd_extendtransaksisewa_IsiSJKir'][$i], "text"),
                       GetSQLValueString($_POST['hd_extendtransaksisewa_Reference'][$i], "text"),
					   GetSQLValueString($_POST['hd_extendtransaksisewa_Purchase'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "ViewTransaksiSewa.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
}
?>



<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body onLoad="submit()">
<form action="<?php echo $editFormAction; ?>" id="fm_extendtransaksisewa_form1" name="fm_extendtransaksisewa_form1" method="POST">
  <table width="1350" border="0">
    <tbody>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
          <?php $stgl = 0; $etgl = 0; $tgl = substr($row_Extend['S'], 0, -8); $bln = substr($row_Extend['S'], 3, -5); $bln2 = substr($row_Extend['S'], 3, -5); $thn = substr($row_Extend['S'], 6);
			if ($bln == 1){
				$stgl = '01';
				$etgl = 28;
				$bln = '02';
				if ($thn == 2016 || $thn == 2020 || $thn == 2024){
				$stgl = '01';
				$etgl = 29;
				$bln = '02';
				}
				}
			elseif ($bln == 2){
				$stgl = '01';
				$etgl = 31;
				$bln = '03';
				}
			elseif ($bln == 3){
				$stgl = '01';
				$etgl = 30;
				$bln = '04';
				}
			elseif ($bln == 4){
				$stgl = '01';
				$etgl = 31;
				$bln = '05';
				}
			elseif ($bln == 5){
				$stgl = '01';
				$etgl = 30;
				$bln = '06';
				}
			elseif ($bln == 6){
				$stgl = '01';
				$etgl = 31;
				$bln = '07';
				}
			elseif ($bln == 7){
				$stgl = '01';
				$etgl = 31;
				$bln = '08';
				}
			elseif ($bln == 8){
				$stgl = '01';
				$etgl = 30;
				$bln = '09';
				}
			elseif ($bln == 9){
				$stgl = '01';
				$etgl = 31;
				$bln = '10';
				}
			elseif ($bln == 10){
				$stgl = '01';
				$etgl = 30;
				$bln = '11';
				}
			elseif ($bln == 11){
				$stgl = '01';
				$etgl = 31;
				$bln = '12';
				}
			elseif ($bln == 12){
				$stgl = '01';
				$etgl = 31;
				$bln = '01';
				$thn = $thn + 1;
				}
				
				$S = $bln2. "/". $tgl. "/". $thn;
				$S2 = strtotime("+1 month", strtotime($S));
				$E = $bln2. "/". $tgl. "/". $thn;
				$E2 = strtotime("-1 day +2 month", strtotime($E));
			?>
            <?php $Reference = $row_Extend['Reference']; $Periode = $row_Extend['Periode']; ?>
      <?php do { ?>
        <tr>
          <td><input name="hd_extendtransaksisewa_Periode[]" type="hidden" id="hd_extendtransaksisewa_Periode" value="<?php echo $Periode+1; ?>">
            <input name="hd_extendtransaksisewa_S[]" type="hidden" id="hd_extendtransaksisewa_S" value="<?php echo $stgl, '/', $bln, '/', $thn; ?>">
            <input name="hd_extendtransaksisewa_E[]" type="hidden" id="hd_extendtransaksisewa_E" value="<?php echo $etgl, '/', $bln, '/', $thn; ?>">
            <input name="hd_extendtransaksisewa_S2[]" type="hidden" id="hd_extendtransaksisewa_S2" value="<?php echo date("d/m/Y", $S2); ?>">
            <input name="hd_extendtransaksisewa_E2[]" type="hidden" id="hd_extendtransaksisewa_E2" value="<?php echo date("d/m/Y", $E2); ?>">
            <input name="hd_extendtransaksisewa_Quantity[]" type="hidden" id="hd_extendtransaksisewa_Quantity" value="<?php echo $row_Extend['Quantity']; ?>">
            <input name="hd_extendtransaksisewa_IsiSJKir[]" type="hidden" id="hd_extendtransaksisewa_IsiSJKir" value="<?php echo $row_Extend['IsiSJKir']; ?>">
            <input name="hd_extendtransaksisewa_Reference[]" type="hidden" id="hd_extendtransaksisewa_Reference" value="<?php echo $Reference; ?>">
            <input name="hd_extendtransaksisewa_Purchase[]" type="hidden" id="hd_extendtransaksisewa_Purchase" value="<?php echo $row_Extend['Purchase']; ?>"></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      <?php } while ($row_Extend = mysql_fetch_assoc($Extend)); ?>
      <tr>
        <td><input name="hd_extendtransaksisewa_Invoice" type="hidden" id="hd_extendtransaksisewa_Invoice" value="<?php echo str_pad($row_LastInvoiceId['Id'] + 1, 5, "0", STR_PAD_LEFT); ?>">
          <input name="hd_extendtransaksisewa_Tgl" type="hidden" id="hd_extendtransaksisewa_Tgl" value="<?php echo $etgl, '/', $bln, '/', $thn; ?>">
          <input name="hd_extendtransaksisewa_PPN" type="hidden" id="hd_extendtransaksisewa_PPN" value="<?php echo $row_Invoice['PPN']; ?>">
          <input name="hd_extendtransaksisewa_Transport" type="hidden" id="hd_extendtransaksisewa_Transport" value="<?php echo $row_Invoice['Transport']; ?>">
<input name="hd_extendtransaksisewa_Reference2" type="hidden" id="hd_extendtransaksisewa_Reference2" value="<?php echo $Reference; ?>">
        <input name="hd_extendtransaksisewa_Periode2" type="hidden" id="hd_extendtransaksisewa_Periode2" value="<?php echo $Periode+1; ?>"></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </tbody>
  </table>
  <p>
    <input type="submit" name="bt_extendtransaksisewa_submit" id="bt_extendtransaksisewa_submit" value="">
  </p>
  <p>&nbsp;</p>
  <input type="hidden" name="MM_insert" value="form1">
</form>
</body>
</html>

<script type="text/javascript">
    function submit()
    {
        document.getElementById("bt_extendtransaksisewa_submit").click(); // Simulates button click
        document.submitForm.submit(); // Submits the form without the button
    }
</script>

<?php
  mysql_free_result($Extend);
  mysql_free_result($Invoice);
  mysql_free_result($LastInvoiceId);
?>
