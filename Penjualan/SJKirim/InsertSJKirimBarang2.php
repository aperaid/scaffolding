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

$colname_InsertSJKirim = "-1";
if (isset($_GET['Reference'])) {
  $colname_InsertSJKirim = $_GET['Reference'];
}

mysql_select_db($database_Connection, $Connection);
$query_InsertSJKirim = sprintf("SELECT transaksi.Purchase, transaksi.Barang, transaksi.JS, transaksi.QSisaKirInsert, transaksi.Reference, project.Project FROM transaksi RIGHT JOIN inserted ON transaksi.Purchase=inserted.Purchase LEFT JOIN pocustomer ON transaksi.Reference=pocustomer.Reference LEFT JOIN project ON pocustomer.PCode=project.PCode WHERE transaksi.Reference = %s AND inserted.Purchase ORDER BY transaksi.Id ASC", GetSQLValueString($colname_InsertSJKirim, "text"));
$InsertSJKirim = mysql_query($query_InsertSJKirim, $Connection) or die(mysql_error());
$row_InsertSJKirim = mysql_fetch_assoc($InsertSJKirim);
$totalRows_InsertSJKirim = mysql_num_rows($InsertSJKirim);

mysql_select_db($database_Connection, $Connection);
$query_Select = "SELECT SJKir FROM sjkirim ORDER BY Id DESC";
$Select = mysql_query($query_Select, $Connection) or die(mysql_error());
$row_Select = mysql_fetch_assoc($Select);
$totalRows_Select = mysql_num_rows($Select);

mysql_select_db($database_Connection, $Connection);
$query_LastIsiSJKirim = "SELECT Id FROM isisjkirim ORDER BY Id DESC";
$LastIsiSJKirim = mysql_query($query_LastIsiSJKirim, $Connection) or die(mysql_error());
$row_LastIsiSJKirim = mysql_fetch_assoc($LastIsiSJKirim);
$totalRows_LastIsiSJKirim = mysql_num_rows($LastIsiSJKirim);

$colname_Reference = "-1";
if (isset($_GET['Reference'])) {
  $colname_Reference = $_GET['Reference'];
}
mysql_select_db($database_Connection, $Connection);
$query_Reference = sprintf("SELECT Reference FROM transaksi WHERE Reference = %s ORDER BY Id DESC", GetSQLValueString($colname_Reference, "text"));
$Reference = mysql_query($query_Reference, $Connection) or die(mysql_error());
$row_Reference = mysql_fetch_assoc($Reference);
$totalRows_Reference = mysql_num_rows($Reference);

mysql_select_db($database_Connection, $Connection);
$query_LastTgl = "SELECT Tgl FROM sjkirim ORDER BY Id DESC";
$LastTgl = mysql_query($query_LastTgl, $Connection) or die(mysql_error());
$row_LastTgl = mysql_fetch_assoc($LastTgl);
$totalRows_LastTgl = mysql_num_rows($LastTgl);

/*if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $truncateSQL = sprintf("TRUNCATE TABLE inserted");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($truncateSQL, $Connection) or die(mysql_error());
}

for($i=0;$i<$totalRows_InsertSJKirim;$i++){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO inserted (Purchase) VALUES (%s)",
                       GetSQLValueString($_POST['Purchase'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
}
}*/

for($i=0;$i<$totalRows_InsertSJKirim;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE transaksi SET QSisaKirInsert=QSisaKirInsert-%s WHERE Purchase=%s",
                       GetSQLValueString($_POST['QKirim'][$i], "int"),
                       GetSQLValueString($_POST['Purchase'][$i], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
}

for($i=0;$i<$totalRows_InsertSJKirim;$i++){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO isisjkirim (IsiSJKir, Warehouse, QKirim, Purchase, SJKir) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['IsiSJKir'][$i], "text"),
                       GetSQLValueString($_POST['Warehouse'][$i], "text"),
                       GetSQLValueString($_POST['QKirim'][$i], "int"),
                       GetSQLValueString($_POST['Purchase'][$i], "text"),
                       GetSQLValueString($_POST['SJKir'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
  
    $insertSQL = sprintf("INSERT INTO periode (Periode, S, E, Quantity, IsiSJKir, Reference, Deletes) VALUES (1, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['S'][$i], "text"),
                       GetSQLValueString($_POST['E'][$i], "text"),
                       GetSQLValueString($_POST['QKirim'][$i], "int"),
                       GetSQLValueString($_POST['IsiSJKir'][$i], "text"),
					   GetSQLValueString($_POST['Reference'][$i], "text"),
					   GetSQLValueString($_POST['JS'][$i], "text"));
					   
	$deleteSQL = sprintf("DELETE FROM periode WHERE Deletes='Jual'");
	$alterSQL = sprintf("ALTER TABLE sjkembali AUTO_INCREMENT = 1");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());

  $insertGoTo = "SJKirim.php";
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
      <th align="center"><h2><?php echo $row_InsertSJKirim['Project']; ?></h2></th>
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
		<th>J/S</th>
		<th>Barang</th>
		<th>Warehouse</th>
		<th>Quantity Sisa Kirim</th>
		<th>Quantity Kirim</th>
		<th>No. Purchase</th>
      </tr>
    <tbody>
    <?php $tgl = 0; $bln = substr($row_LastTgl['Tgl'], 3, -5); $thn = substr($row_LastTgl['Tgl'], 6);
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
    <?php $increment = 1; ?>
	<?php do { ?>
	  <tr>
	    <td><p>
	      <input name="Id[]" type="hidden" id="Id" value="<?php echo $row_InsertSJKirim['Id']; ?>">
	      <input name="S[]" type="hidden" id="S" value="<?php echo $row_LastTgl['Tgl']; ?>">
	      <input name="E[]" type="hidden" id="E" value="<?php echo $tgl, '/', $bln, '/', $thn; ?>">
	      <input name="Reference[]" type="hidden" id="Reference" value="<?php echo $row_InsertSJKirim['Reference']; ?>">
          </p></td>
	    <td><input name="IsiSJKir[]" type="text" class="textview" id="IsiSJKir" value="<?php echo $row_LastIsiSJKirim['Id'] + $increment; ?>" readonly></td>
	    <td><input name="JS[]" type="text" class="textview" id="JS" value="<?php echo $row_InsertSJKirim['JS']; ?>" readonly></td>
	    <td><input name="Barang[]" type="text" class="textview" id="Barang" value="<?php echo $row_InsertSJKirim['Barang']; ?>" readonly></td>
	    <td><input name="Warehouse[]" type="text" class="textbox" id="Warehouse" autocomplete="off"></td>
	    <td><input name="QSisaKirInsert[]" type="text" class="textview" id="QSisaKirInsert" value="<?php echo $row_InsertSJKirim['QSisaKirInsert']; ?>" readonly></td>
	    <td><input name="QKirim[]" type="text" class="textbox" id="QKirim" autocomplete="off" value="<?php echo $row_InsertSJKirim['QSisaKirInsert']; ?>"></td>
	    <td><input name="Purchase[]" type="text" class="textview" id="Purchase" value=<?php echo $row_InsertSJKirim['Purchase']; ?> readonly></td>
	    </tr>
	  <?php $increment++; ?>
	  <?php } while ($row_InsertSJKirim = mysql_fetch_assoc($InsertSJKirim)); ?>
	<tr>
          <td>&nbsp;</td>
        <td><input name="SJKir" type="hidden" class="textbox" id="SJKir" value="<?php echo $row_Select['SJKir']; ?>" readonly></td>
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
		   <td><a href="InsertSJKirimBarang.php?Reference=<?php echo $row_Reference['Reference']; ?>"><button type="button" class="submit">Cancel</button></a></td>
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

mysql_free_result($LastIsiSJKirim);

mysql_free_result($Reference);

mysql_free_result($LastTgl);

mysql_free_result($InsertSJKirim);
?>
