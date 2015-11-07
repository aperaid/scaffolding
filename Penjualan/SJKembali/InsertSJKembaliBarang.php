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
$query_InsertSJKembali = sprintf("SELECT transaksi.Purchase, transaksi.Barang, transaksi.JS, transaksi.Quantity, project.Project FROM transaksi INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference  INNER JOIN project ON pocustomer.PCode=project.PCode WHERE transaksi.Reference = %s ORDER BY transaksi.Id ASC", GetSQLValueString($colname_InsertSJKembali, "text"));
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

mysql_select_db($database_Connection, $Connection);
$query_LastId = "SELECT Id FROM sjkembali ORDER BY Id DESC";
$LastId = mysql_query($query_LastId, $Connection) or die(mysql_error());
$row_LastId = mysql_fetch_assoc($LastId);
$totalRows_LastId = mysql_num_rows($LastId);

for($i=0;$i<$totalRows_InsertSJKembali;$i++){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO isisjkembali (IsiSJKem, Warehouse, QTertanda, QTerima, Purchase, SJKem) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['IsiSJKem'][$i], "text"),
                       GetSQLValueString($_POST['Warehouse'][$i], "text"),
                       GetSQLValueString($_POST['QTertanda'][$i], "int"),
                       GetSQLValueString($_POST['QTerima'][$i], "int"),
                       GetSQLValueString($_POST['Purchase'][$i], "text"),
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
		<th>J/S</th>
		<th>Barang</th>
		<th>Warehouse</th>
		<th>Quantity</th>
		<th>Quantity Tertanda</th>
		<th>Quantity Terima</th>
		<th>No. Purchase</th>
      </tr>
    <tbody>
    <?php $increment = 1; ?>
	<?php do { ?>
	  <tr>
	    <td><input name="Id[]" type="hidden" id="Id" value="<?php echo $row_InsertSJKembali['Id']; ?>"></td>
	    <td><input name="IsiSJKem[]" type="text" class="textbox" id="IsiSJKem" value="<?php echo $row_LastIsiSJKembali['Id'] + $increment; ?>" readonly></td>
	    <td><input name="JS[]" type="text" class="textbox" id="JS" value="<?php echo $row_InsertSJKembali['JS']; ?>" readonly></td>
	    <td><input name="Barang[]" type="text" class="textbox" id="Barang" value="<?php echo $row_InsertSJKembali['Barang']; ?>" readonly></td>
	    <td><input name="Warehouse[]" type="text" class="textbox" id="Warehouse" autocomplete="off"></td>
	    <td><input name="Quantity[]" type="text" class="textbox" id="Quantity" value="<?php echo $row_InsertSJKembali['Quantity']; ?>" readonly></td>
	    <td><input name="QTertanda[]" type="text" class="textbox" id="QTertanda" autocomplete="off" value=0></td>
	    <td><input name="QTerima[]" type="text" class="textbox" id="QTerima" autocomplete="off" value=0></td>
	    <td><input name="Purchase[]" type="text" class="textbox" id="Purchase" value=<?php echo $row_InsertSJKembali['Purchase']; ?> readonly></td>
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
		   <td align="right">&nbsp;</td>
		   <td align="right"><input type="submit" name="submit" id="submit" class="submit" value="Insert"></td>
		   <td></td>
		   <td><a href="CancelKembali.php?Id=<?php echo $row_LastId['Id']; ?>"><button type="button" class="submit">Cancel</button></a></td>
		   <td>&nbsp;</td>
		   <td>&nbsp;</td>
		   <td>&nbsp;</td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>

</body>
</html>
<?php
mysql_free_result($Menu);

mysql_free_result($Select);

mysql_free_result($LastIsiSJKembali);

mysql_free_result($LastId);

mysql_free_result($InsertSJKembali);
?>
