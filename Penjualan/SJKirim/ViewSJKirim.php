<?php require_once('../../Connections/Connection.php'); ?>
<?php require_once('../../Connections/Connection.php'); ?>
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

mysql_select_db($database_Connection, $Connection);
$query_Menu = "SELECT * FROM menu";
$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
$row_Menu = mysql_fetch_assoc($Menu);
$totalRows_Menu = mysql_num_rows($Menu);

$colname_ViewIsiSJKirim = "-1";
if (isset($_GET['SJKir'])) {
  $colname_ViewIsiSJKirim = $_GET['SJKir'];
}
mysql_select_db($database_Connection, $Connection);
$query_ViewIsiSJKirim = sprintf("SELECT isisjkirim.*, transaksi.Barang, transaksi.QSisa, project.Project FROM isisjkirim INNER JOIN transaksi ON isisjkirim.Purchase=transaksi.Purchase INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode WHERE isisjkirim.SJKir = %s ORDER BY isisjkirim.Id ASC", GetSQLValueString($colname_ViewIsiSJKirim, "text"));
$ViewIsiSJKirim = mysql_query($query_ViewIsiSJKirim, $Connection) or die(mysql_error());
$row_ViewIsiSJKirim = mysql_fetch_assoc($ViewIsiSJKirim);
$totalRows_ViewIsiSJKirim = mysql_num_rows($ViewIsiSJKirim);

$colname_View = "-1";
if (isset($_GET['SJKir'])) {
  $colname_View = $_GET['SJKir'];
}
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT SJKir FROM sjkirim WHERE SJKir = %s", GetSQLValueString($colname_View, "text"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);
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
                    <button type="button" class="button">
					<?php echo $row_Menu['nama']; ?></button></a></td>
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
      <th align="center"><h2><?php echo $row_ViewIsiSJKirim['Project']; ?></h2></th>
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
		<th>Barang</th>
		<th>Warehouse</th>
		<th>Quantity Sisa</th>
		<th>Quantity Kirim</th>
		<th>Quantity Tertanda</th>
		<th>No. Purchase</th>
		<th>&nbsp;</th>
      </tr>
    <tbody>      
	<?php do { ?>
      <tr>
		<td><input name="Id" type="hidden" id="Id"></td>
		<td><input name="IsiSJKir" type="text" class="textview" id="IsiSJKir" value="<?php echo $row_ViewIsiSJKirim['IsiSJKir']; ?>" readonly></td>
		<td><input name="Barang" type="text" class="textview" id="Barang" value="<?php echo $row_ViewIsiSJKirim['Barang']; ?>" readonly></td>
		<td><input name="Warehouse" type="text" class="textview" id="Warehouse" value="<?php echo $row_ViewIsiSJKirim['Warehouse']; ?>" readonly></td>
		<td><input name="QSisa" type="text" class="textview" id="QSisa" value="<?php echo $row_ViewIsiSJKirim['QSisa']; ?>" readonly></td>
		<td><input name="QKirim" type="text" class="textview" id="QKirim" value="<?php echo $row_ViewIsiSJKirim['QKirim']; ?>" readonly></td>
		<td><input name="QTertanda" type="text" class="textview" id="QTertanda" value="<?php echo $row_ViewIsiSJKirim['QTertanda']; ?>" readonly></td>
		<td><input name="Purchase" type="text" class="textview" id="Purchase" value="<?php echo $row_ViewIsiSJKirim['Purchase']; ?>" readonly></td>
		<td></td>
      </tr>
	<?php } while ($row_ViewIsiSJKirim = mysql_fetch_assoc($ViewIsiSJKirim)); ?>
      <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
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
		   <td align="center"><a href="EditSJKirim.php?SJKir=<?php echo $row_View['SJKir']; ?>">
                        <button type="button" class="button2">Edit Pengiriman</button></a></td>
		   <td align="center"><a href="EditSJKirimQuantity.php?SJKir=<?php echo $row_View['SJKir']; ?>">
                        <button type="button" class="button2">Quantity Tertanda</button></a></td>
		   <td align="center"><a href="SJKirim.php"><button type="button" class="button2">Cancel</button></a></td>
		   <td>&nbsp;</td>
		   <td>&nbsp;</td>
		   <td>&nbsp;</td>
		   <td>&nbsp;</td>
      </tr>
    </tbody>
  </table>
</form>

</body>
</html>
<?php
mysql_free_result($Menu);

mysql_free_result($ViewIsiSJKirim);

mysql_free_result($View);
?>
