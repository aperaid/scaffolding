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
$query_InsertTransaksiClaim = sprintf("SELECT transaksi.Purchase, transaksi.Barang, transaksi.JS, periode.Quantity, periode.IsiSJKir FROM periode LEFT JOIN transaksi ON periode.Purchase=transaksi.Purchase WHERE periode.SJKem IS NULL AND periode.Reference=%s AND periode.Periode=%s ORDER BY periode.Id ASC
", GetSQLValueString($colname_InsertTransaksiClaim, "text"), GetSQLValueString($colname_Periode, "text"));
$InsertTransaksiClaim = mysql_query($query_InsertTransaksiClaim, $Connection) or die(mysql_error());
$row_InsertTransaksiClaim = mysql_fetch_assoc($InsertTransaksiClaim);
$totalRows_InsertTransaksiClaim = mysql_num_rows($InsertTransaksiClaim);

mysql_select_db($database_Connection, $Connection);
$query_LastId = "SELECT Id FROM transaksiclaim ORDER BY Id DESC";
$LastId = mysql_query($query_LastId, $Connection) or die(mysql_error());
$row_LastId = mysql_fetch_assoc($LastId);
$totalRows_LastId = mysql_num_rows($LastId);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE inserted SET IsiSJKir=''");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}

for($i=0;$i<$totalRows_InsertTransaksiClaim;$i++){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO inserted (IsiSJKir) VALUES (%s)",
                       GetSQLValueString($_POST['checkbox'][$i], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
  
  $insertGoTo = "InsertTransaksiClaimBarang2.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}}

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
				      <a href="../../<?php echo $row_Menu['link']; ?>">
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
		<th>Pilih Claim</th>
		<th>J/S</th>
		<th>Barang</th>
		<th>Quantity Ditempat</th>
		<th>No. Purchase</th>
      </tr>
    <tbody>
    <?php $increment = 1; ?>
	<?php do { ?>
	  <tr>
	    <td align="center"><input type="checkbox" name="checkbox[]" id="checkbox" value="<?php echo $row_InsertTransaksiClaim['IsiSJKir']; ?>"></td>
	    <td><input name="JS[]" type="text" class="textview" id="JS" value="<?php echo $row_InsertTransaksiClaim['JS']; ?>" readonly></td>
	    <td><input name="Barang[]" type="text" class="textview" id="Barang" value="<?php echo $row_InsertTransaksiClaim['Barang']; ?>" readonly></td>
	    <td><input name="Quantity[]" type="text" class="textview" id="Quantity" value="<?php echo $row_InsertTransaksiClaim['Quantity']; ?>" readonly></td>
	    <td><input name="Purchase[]" type="text" class="textview" id="Purchase" value=<?php echo $row_InsertTransaksiClaim['Purchase']; ?> readonly></td>
	    </tr>
	  <?php $increment++; ?>
	  <?php } while ($row_InsertTransaksiClaim = mysql_fetch_assoc($InsertTransaksiClaim)); ?>
	<tr>
          <td>&nbsp;</td>
        <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
      </tr>
      <tr>
		   <td>&nbsp;</td>
		   <td align="right">&nbsp;</td>
		   <td align="left"><input type="submit" name="submit" id="submit" class="submit" value="Pilih"></td>
	    <td><a href="TransaksiClaim.php"><button type="button" class="submit">Cancel</button></a></td>
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

mysql_free_result($LastId);

mysql_free_result($InsertTransaksiClaim);
?>
