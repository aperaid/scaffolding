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

$colname_InsertSJKembali = "-1";
if (isset($_GET['Reference'])) {
  $colname_InsertSJKembali = $_GET['Reference'];
}

mysql_select_db($database_Connection, $Connection);
$query_InsertSJKembali = sprintf("SELECT isisjkirim.*, isisjkirim.QSisaKemInsert, sjkirim.SJKir, sjkirim.Tgl, transaksi.Barang, transaksi.Quantity, transaksi.Purchase, transaksi.Reference FROM isisjkirim INNER JOIN inserted ON isisjkirim.IsiSJKir=inserted.IsiSJKir INNER JOIN sjkirim ON isisjkirim.SJKir=sjkirim.SJKir INNER JOIN transaksi ON isisjkirim.Purchase=transaksi.Purchase WHERE inserted.IsiSJKir ORDER BY isisjkirim.Id ASC", GetSQLValueString($colname_InsertSJKembali, "text"));
$InsertSJKembali = mysql_query($query_InsertSJKembali, $Connection) or die(mysql_error());
$row_InsertSJKembali = mysql_fetch_assoc($InsertSJKembali);
$totalRows_InsertSJKembali = mysql_num_rows($InsertSJKembali);

mysql_select_db($database_Connection, $Connection);
$query_Select = "SELECT SJKem FROM sjkembali ORDER BY Id DESC";
$Select = mysql_query($query_Select, $Connection) or die(mysql_error());
$row_Select = mysql_fetch_assoc($Select);
$totalRows_Select = mysql_num_rows($Select);

mysql_select_db($database_Connection, $Connection);
$query_LastIsiSJKembali = "SELECT Id FROM isisjkembali ORDER BY Id DESC";
$LastIsiSJKembali = mysql_query($query_LastIsiSJKembali, $Connection) or die(mysql_error());
$row_LastIsiSJKembali = mysql_fetch_assoc($LastIsiSJKembali);
$totalRows_LastIsiSJKembali = mysql_num_rows($LastIsiSJKembali);

$colname_Reference = "-1";
if (isset($_GET['Reference'])) {
  $colname_Reference = $_GET['Reference'];
}
mysql_select_db($database_Connection, $Connection);
$query_Reference = sprintf("SELECT Reference FROM transaksi WHERE Reference = %s ORDER BY Id DESC", GetSQLValueString($colname_Reference, "text"));
$Reference = mysql_query($query_Reference, $Connection) or die(mysql_error());
$row_Reference = mysql_fetch_assoc($Reference);
$totalRows_Reference = mysql_num_rows($Reference);

$colname_GetPeriode = "-1";
if (isset($_GET['Reference'])) {
  $colname_GetPeriode = $_GET['Reference'];
}
mysql_select_db($database_Connection, $Connection);
$query_GetPeriode = sprintf("SELECT MAX(periode.Periode) FROM isisjkirim RIGHT JOIN periode ON isisjkirim.IsiSJKir=periode.IsiSJKir LEFT JOIN sjkirim ON isisjkirim.SJKir=sjkirim.SJKir LEFT JOIN transaksi ON sjkirim.Reference=transaksi.Reference WHERE transaksi.Reference = %s", GetSQLValueString($colname_GetPeriode, "text"));
$GetPeriode = mysql_query($query_GetPeriode, $Connection) or die(mysql_error());
$row_GetPeriode = mysql_fetch_assoc($GetPeriode);
$totalRows_GetPeriode = mysql_num_rows($GetPeriode);

mysql_select_db($database_Connection, $Connection);
$query_LastTgl = "SELECT Tgl FROM sjkembali ORDER BY Id DESC";
$LastTgl = mysql_query($query_LastTgl, $Connection) or die(mysql_error());
$row_LastTgl = mysql_fetch_assoc($LastTgl);
$totalRows_LastTgl = mysql_num_rows($LastTgl);

for($i=0;$i<$totalRows_InsertSJKembali;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE isisjkirim SET QSisaKemInsert=QSisaKemInsert-%s WHERE IsiSJKir=%s",
                       GetSQLValueString($_POST['QTertanda'][$i], "int"),
                       GetSQLValueString($_POST['IsiSJKir'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE periode SET Quantity=Quantity-%s WHERE Periode=%s AND IsiSJKir = %s",
                       GetSQLValueString($_POST['QTertanda'][$i], "int"),
                       GetSQLValueString($_POST['Periode'][$i], "text"),
					   GetSQLValueString($_POST['IsiSJKir'][$i], "int"));
  $deleteSQL = sprintf("DELETE FROM periode WHERE Quantity=0");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO periode (Periode, S, E, Quantity, IsiSJKir, SJKem, Reference) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Periode'][$i], "int"),
                       GetSQLValueString($_POST['S'][$i], "text"),
                       GetSQLValueString($_POST['E'][$i], "text"),
                       GetSQLValueString($_POST['QTertanda'][$i], "int"),
                       GetSQLValueString($_POST['IsiSJKir'][$i], "text"),
					   GetSQLValueString($_POST['SJKem'], "text"),
					   GetSQLValueString($_POST['Reference'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
}
}

for($i=0;$i<$totalRows_InsertSJKembali;$i++){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO isisjkembali (IsiSJKem, Warehouse, QTertanda, Purchase, Periode, IsiSJKir, SJKem) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['IsiSJKem'][$i], "text"),
                       GetSQLValueString($_POST['Warehouse'][$i], "text"),
                       GetSQLValueString($_POST['QTertanda'][$i], "int"),
					   GetSQLValueString($_POST['Purchase'][$i], "text"),
                       GetSQLValueString($_POST['Periode'][$i], "text"),
					   GetSQLValueString($_POST['IsiSJKir'][$i], "text"),
                       GetSQLValueString($_POST['SJKem'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "SJKembali.php";
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
      <th align="center"><h2><?php echo $row_InsertSJKembali['Project']; ?></h2></th>
    </tr>
  </tbody>
</table>
</p>
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <table width="1000" border="0">
    <thead>
      <tr>
		<th>&nbsp;</th>
		<th>No. Isi SJ</th>
		<th>Tgl Kirim</th>
		<th>Barang</th>
		<th>Quantity</th>
		<th>Warehouse</th>
		<th>Quantity Sisa Kembali</th>
		<th>Quantity Tertanda</th>
		<th>SJ Code</th>
      </tr>
    <tbody>
    <?php $increment = 1; ?>
	<?php do { ?>
	  <tr>
	    <td><p>
        <?php $FirstDate = substr($row_LastTgl['Tgl'], 2); ?>
	      <input name="Id[]" type="hidden" id="Id" value="<?php echo $row_InsertSJKembali['Id']; ?>">
          <input name="Reference[]" type="hidden" id="Reference" value="<?php echo $row_InsertSJKembali['Reference']; ?>">
	      <input name="IsiSJKir[]" type="hidden" id="IsiSJKir" value="<?php echo $row_InsertSJKembali['IsiSJKir']; ?>">
	      <input name="Purchase[]" type="hidden" id="Purchase" value="<?php echo $row_InsertSJKembali['Purchase']; ?>">
	        <input name="Periode[]" type="hidden" id="Periode" value="<?php echo $row_GetPeriode['MAX(periode.Periode)']; ?>">
	        <input name="S[]" type="hidden" id="S" value="<?php echo '01', $FirstDate; ?>">
	        <input name="E[]" type="hidden" id="E" value="<?php echo $row_LastTgl['Tgl']; ?>">
        </td>
	    <td><input name="IsiSJKem[]" type="text" class="textview" id="IsiSJKem" value="<?php echo $row_LastIsiSJKembali['Id'] + $increment; ?>" readonly></td>
	    <td><input name="Tgl" type="text" class="textview" id="Tgl" value="<?php echo $row_InsertSJKembali['Tgl']; ?>" readonly></td>
	    <td><input name="Barang" type="text" class="textview" id="Barang" value="<?php echo $row_InsertSJKembali['Barang']; ?>" readonly></td>
	    <td><input name="Quantity" type="text" class="textview" id="Quantity" value="<?php echo $row_InsertSJKembali['Quantity']; ?>" readonly></td>
	    <td><input name="Warehouse[]" type="text" class="textbox" id="Warehouse" autocomplete="off"></td>
	    <td><input name="QSisaKem[]" type="text" class="textview" id="QSisaKem" value="<?php echo $row_InsertSJKembali['QSisaKemInsert']; ?>" readonly></td>
	    <td><input name="QTertanda[]" type="text" class="textbox" id="QTertanda" autocomplete="off" value="<?php echo $row_InsertSJKembali['QSisaKemInsert']; ?>"></td>
	    <td><input name="SJKir" type="text" class="textview" id="SJKir" value=<?php echo $row_InsertSJKembali['SJKir']; ?> readonly></td>
	    </tr>
	  <?php $increment++; ?>
	  <?php } while ($row_InsertSJKembali = mysql_fetch_assoc($InsertSJKembali)); ?>
	<tr>
          <td>&nbsp;</td>
        <td><input name="SJKem" type="hidden" class="textbox" id="SJKem" value="<?php echo $row_Select['SJKem']; ?>" readonly></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
      </tr>
      <tr>
		   <td>&nbsp;</td>
		   <td>&nbsp;</td>
		   <td align="right"><input type="submit" name="submit" id="submit" class="submit" value="Insert"></td>
		   <td align="right">&nbsp;</td>
		   <td>&nbsp;</td>
		   <td><a href="InsertSJKembaliBarang.php?Reference=<?php echo $row_Reference['Reference']; ?>"><button type="button" class="submit">Cancel</button></a></td>
		   <td>&nbsp;</td>
		   <td>&nbsp;</td>
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

mysql_free_result($Select);

mysql_free_result($LastIsiSJKembali);

mysql_free_result($Reference);

mysql_free_result($GetPeriode);

mysql_free_result($InsertSJKembali);

mysql_free_result($LastTgl);
?>
