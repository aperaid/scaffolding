<?php require_once('../../Connections/Connection.php'); ?>
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

mysql_select_db($database_Connection, $Connection);
$query_Menu = "SELECT * FROM menu";
$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
$row_Menu = mysql_fetch_assoc($Menu);
$totalRows_Menu = mysql_num_rows($Menu);

$colname_InsertTransaksiClaim = "-1";
if (isset($_GET['Reference'])) {
  $colname_InsertTransaksiClaim = $_GET['Reference'];
  $colname_Periode = $_GET['Periode'];
}

mysql_select_db($database_Connection, $Connection);
$query_InsertTransaksiClaim = sprintf("SELECT transaksi.Purchase, transaksi.Barang, transaksi.QSisaKem, periode.Periode, periode.IsiSJKir FROM periode LEFT JOIN inserted ON periode.IsiSJKir=inserted.IsiSJKir LEFT JOIN transaksi ON periode.Purchase=transaksi.Purchase LEFT JOIN pocustomer ON transaksi.Reference=pocustomer.Reference WHERE transaksi.Reference = %s AND periode.Periode = %s AND inserted.IsiSJKir ORDER BY transaksi.Id ASC", GetSQLValueString($colname_InsertTransaksiClaim, "text"), GetSQLValueString($colname_Periode, "text"));
$InsertTransaksiClaim = mysql_query($query_InsertTransaksiClaim, $Connection) or die(mysql_error());
$row_InsertTransaksiClaim = mysql_fetch_assoc($InsertTransaksiClaim);
$totalRows_InsertTransaksiClaim = mysql_num_rows($InsertTransaksiClaim);

mysql_select_db($database_Connection, $Connection);
$query_LastClaim = "SELECT Id FROM transaksiclaim ORDER BY Id DESC";
$LastClaim = mysql_query($query_LastClaim, $Connection) or die(mysql_error());
$row_LastClaim = mysql_fetch_assoc($LastClaim);
$totalRows_LastClaim = mysql_num_rows($LastClaim);

$colname_Reference = "-1";
if (isset($_GET['Reference'])) {
  $colname_Reference = $_GET['Reference'];
}
mysql_select_db($database_Connection, $Connection);
$query_Reference = sprintf("SELECT Reference FROM inserted WHERE Reference = %s", GetSQLValueString($colname_Reference, "text"));
$Reference = mysql_query($query_Reference, $Connection) or die(mysql_error());
$row_Reference = mysql_fetch_assoc($Reference);
$totalRows_Reference = mysql_num_rows($Reference);

$colname_Periode = "-1";
if (isset($_GET['Reference'])) {
  $colname_Periode = $_GET['Reference'];
}
mysql_select_db($database_Connection, $Connection);
$query_Periode = sprintf("SELECT MAX(Periode) FROM periode WHERE Reference = %s", GetSQLValueString($colname_Periode, "text"));
$Periode = mysql_query($query_Periode, $Connection) or die(mysql_error());
$row_Periode = mysql_fetch_assoc($Periode);
$totalRows_Periode = mysql_num_rows($Periode);

mysql_select_db($database_Connection, $Connection);
$query_LastId = "SELECT Id FROM invoice ORDER BY Id DESC";
$LastId = mysql_query($query_LastId, $Connection) or die(mysql_error());
$row_LastId = mysql_fetch_assoc($LastId);
$totalRows_LastId = mysql_num_rows($LastId);

for($i=0;$i<$totalRows_InsertTransaksiClaim;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE transaksi SET QSisaKem=QSisaKem-%s WHERE Purchase=%s",
                       GetSQLValueString($_POST['QClaim'][$i], "int"),
                       GetSQLValueString($_POST['Purchase'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
}

for($i=0;$i<$totalRows_InsertTransaksiClaim;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE isisjkirim SET QSisaKem=QSisaKem-%s WHERE IsiSJKir=%s",
                       GetSQLValueString($_POST['QClaim'][$i], "int"),
                       GetSQLValueString($_POST['IsiSJKir'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
}


for($i=0;$i<$totalRows_InsertTransaksiClaim;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE periode SET Quantity=Quantity-%s WHERE IsiSJKir=%s AND Periode=%s",
                       GetSQLValueString($_POST['QClaim'][$i], "int"),
                       GetSQLValueString($_POST['IsiSJKir'][$i], "text"),
					   GetSQLValueString($_POST['Periode'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO invoice (Invoice, JSC, Tgl, PPN, Reference, Periode) VALUES (%s, 'Claim', %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Invoice2'], "text"),
                       GetSQLValueString($_POST['Tgl'], "text"),
                       GetSQLValueString($_POST['PPN'], "int"),
                       GetSQLValueString($_POST['Reference2'], "text"),
                       GetSQLValueString($_POST['Periode2'], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
}

for($i=0;$i<$totalRows_InsertTransaksiClaim;$i++){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO transaksiclaim (Claim, Tgl, QClaim, Amount, Purchase, Periode) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Claim'][$i], "text"),
                       GetSQLValueString($_POST['Tgl'][$i], "text"),
                       GetSQLValueString($_POST['QClaim'][$i], "int"),
					   GetSQLValueString($_POST['Amount'][$i], "int"),
                       GetSQLValueString($_POST['Purchase'][$i], "text"),
					   GetSQLValueString($_POST['Periode'][$i], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "TransaksiClaim.php";
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

<link href="../../Button.css" rel="stylesheet" type="text/css">
<style type="text/css">
body {
	background-image: url(../../Image/Wood.png);
	background-repeat: no-repeat;
}
</style>

<script>
function capital() {
	var x = document.getElementById("PCode");
    x.value = x.value.toUpperCase();
}
</script>

<link href="/scaffolding/JQuery2/jquery-ui.css" rel="stylesheet" type="text/css">
<script src="../../JQuery2/external/jquery/jquery.js"></script>
<script src="../../JQuery2/jquery-ui.js"></script>

<script>
$(function() {
  $( "#Tgl" ).datepicker();
});
</script>

<script type="text/javascript">
$(function() {
    var availableTags = <?php include ("../autocomplete.php");?>;
    $( "#PCode" ).autocomplete({
      source: availableTags
    });
  });
</script>

</head>

<body>
<div style="float:left;width:15%">
  <table width="200" border="0">
    <tbody>
      <tr>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th>&nbsp;</th>
      </tr>
      
				<?php do { ?>    
			    <tr>
				    <td class="Menu">
				      <a href="../<?php echo $row_Menu['link']; ?>">
			          <button type="button" class="button"> <?php echo $row_Menu['nama']; ?></button>
			          </a></td>
				    </tr>
			    <?php } while ($row_Menu = mysql_fetch_assoc($Menu)); ?>
	  <tr>
                    <td class="Menu">&nbsp;</td>
                    </tr>
                    
    </tbody>
  </table>
</div>
<div style="float:left;width:85%">

<table width="1100" border="0">


  <tbody>
    <tr>
      <th align="center"><h2><?php echo $row_InsertTransaksiClaim['Project']; ?></h2></th>
    </tr>
  </tbody>
</table>
</p>
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <table width="1000" border="0">
    <thead>
      <tr>
		<th>&nbsp;</th>
		<th>No. Claim</th>
		<th>Barang</th>
		<th>Quantity Ditempat</th>
		<th>Quantity Claim</th>
		<th>Amount</th>
		<th>No. Purchase</th>
      </tr>
    <tbody>
    <?php $increment = 1; ?>
	<?php do { ?>
	  <tr>
	    <td><input name="IsiSJKir[]" type="hidden" id="IsiSJKir" value="<?php echo $row_InsertTransaksiClaim['IsiSJKir']; ?>">
	      <input name="Reference[]" type="hidden" id="Reference" value="<?php echo $row_Reference['Reference']; ?>">
<input name="Periode[]" type="hidden" id="Periode" value="<?php echo $row_InsertTransaksiClaim['Periode']; ?>">
	      <input name="Invoice[]" type="hidden" id="Invoice" value="<?php echo str_pad($row_LastId['Id'] + $increment, 5, "0", STR_PAD_LEFT); ?>"></td>
	    <td><input name="Claim[]" type="text" class="textview" id="Claim" value="<?php echo $row_LastClaim['Id'] + $increment; ?>" readonly></td>
	    <td><input name="Barang" type="text" class="textview" id="Barang" value="<?php echo $row_InsertTransaksiClaim['Barang']; ?>" readonly></td>
	    <td><input name="QSisaKem" type="text" class="textview" id="QSisaKem" value="<?php echo $row_InsertTransaksiClaim['QSisaKem']; ?>" readonly></td>
	    <td><input name="QClaim[]" type="text" class="textbox" id="QClaim" autocomplete="off" value="0"></td>
	    <td><input name="Amount[]" type="text" class="textbox" id="Amount" autocomplete="off" value="0"></td>
	    <td><input name="Purchase[]" type="text" class="textview" id="Purchase[]" value=<?php echo $row_InsertTransaksiClaim['Purchase']; ?> readonly></td>
	    </tr>
	  <?php $increment++; ?>
	  <?php } while ($row_InsertTransaksiClaim = mysql_fetch_assoc($InsertTransaksiClaim)); ?>
	  <tr>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <th>Tanggal Claim</th>
	    <th>&nbsp;</th>
	    <th>PPN</th>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
      </tr>
	  <tr>
          <td><input name="Invoice2" type="hidden" id="Invoice2" value="<?php echo str_pad($row_LastId['Id'] + 1, 5, "0", STR_PAD_LEFT); ?>">
            <input name="Reference2" type="hidden" id="Reference2" value="<?php echo $row_Reference['Reference']; ?>">
          <input name="Periode2" type="hidden" id="Periode2" value="<?php echo $row_Periode['MAX(Periode)']; ?>"></td>
        <td>&nbsp;</td>
        <td><input name="Tgl" type="text" class="textbox" id="Tgl" autocomplete="off"></td>
          <td>&nbsp;</td>
          <td><input name="PPN" type="text" class="textbox" id="PPN" autocomplete="off" value=""></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
      </tr>
      <tr>
		   <td>&nbsp;</td>
		   <td>&nbsp;</td>
		   <td align="right"><input type="submit" name="submit" id="submit" class="submit" value="Insert"></td>
		   <td>&nbsp;</td>
		   <td><a href="InsertTransaksiClaimBarang.php?Reference=<?php echo $row_Reference['Reference']; ?>&Periode=<?php echo $row_Periode['MAX(Periode)']; ?>"><button type="button" class="submit">Cancel</button></a></td>
		   <td>&nbsp;</td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
  <input type="hidden" name="MM_update" value="form1">
</form>

</body>
</html>
<?php
mysql_free_result($Menu);

mysql_free_result($LastClaim);

mysql_free_result($Reference);

mysql_free_result($Periode);

mysql_free_result($LastId);

mysql_free_result($InsertTransaksiClaim);
?>
