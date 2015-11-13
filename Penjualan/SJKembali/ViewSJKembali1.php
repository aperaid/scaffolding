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

mysql_select_db($database_Connection, $Connection);
$query_Menu = "SELECT * FROM menu";
$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
$row_Menu = mysql_fetch_assoc($Menu);
$totalRows_Menu = mysql_num_rows($Menu);

$colname_ViewIsiSJKembali = "-1";
if (isset($_GET['SJKem'])) {
  $colname_ViewIsiSJKembali = $_GET['SJKem'];
}
mysql_select_db($database_Connection, $Connection);
$query_ViewIsiSJKembali = sprintf("SELECT isisjkembali.*, transaksi.Barang, transaksi.JS, transaksi.QSisaKem, project.Project FROM isisjkembali INNER JOIN transaksi ON isisjkembali.Purchase=transaksi.Purchase INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode WHERE isisjkembali.SJKem = %s ORDER BY isisjkembali.Id ASC", GetSQLValueString($colname_ViewIsiSJKembali, "text"));
$ViewIsiSJKembali = mysql_query($query_ViewIsiSJKembali, $Connection) or die(mysql_error());
$row_ViewIsiSJKembali = mysql_fetch_assoc($ViewIsiSJKembali);
$totalRows_ViewIsiSJKembali = mysql_num_rows($ViewIsiSJKembali);

$colname_View = "-1";
if (isset($_GET['SJKem'])) {
  $colname_View = $_GET['SJKem'];
}
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT SJKem FROM sjkembali WHERE SJKem = %s", GetSQLValueString($colname_View, "text"));
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
      <th align="center"><h2><?php echo $row_ViewIsiSJKembali['Project']; ?></h2></th>
    </tr>
  </tbody>
</table>
</p>
<form id="form1" name="form1" method="POST">
  <table width="1000" border="0">
    <thead>
      <tr>
		<th>&nbsp;</th>
		<th>No. Isi SJ</th>
		<th>J/S</th>
		<th>Barang</th>
		<th>Warehouse</th>
		<th>Quantity Sisa Kembali</th>
		<th>Quantity Tertanda</th>
		<th>Quantity Terima</th>
		<th>No. Purchase</th>
      </tr>
    <tbody>      
	<?php do { ?>
      <tr>
		<td><input name="Id" type="hidden" id="Id"></td>
		<td><input name="IsiSJKem" type="text" class="textview" id="IsiSJKem" value="<?php echo $row_ViewIsiSJKembali['IsiSJKem']; ?>" readonly></td>
		<td><input name="JS" type="text" class="textview" id="JS" value="<?php echo $row_ViewIsiSJKembali['JS']; ?>" readonly></td>
		<td><input name="Barang" type="text" class="textview" id="Barang" value="<?php echo $row_ViewIsiSJKembali['Barang']; ?>" readonly></td>
		<td><input name="Warehouse" type="text" class="textview" id="Warehouse" value="<?php echo $row_ViewIsiSJKembali['Warehouse']; ?>" readonly></td>
		<td><input name="QSisaKem" type="text" class="textview" id="QSisaKem" value="<?php echo $row_ViewIsiSJKembali['QSisaKem']; ?>" readonly></td>
		<td><input name="QTertanda" type="text" class="textview" id="QTertanda" value="<?php echo $row_ViewIsiSJKembali['QTertanda']; ?>" readonly></td>
		<td><input name="QTerima" type="text" class="textview" id="QTerima" value="<?php echo $row_ViewIsiSJKembali['QTerima']; ?>" readonly></td>
		<td><input name="Purchase" type="text" class="textview" id="Purchase" value="<?php echo $row_ViewIsiSJKembali['Purchase']; ?>" readonly></td>
      </tr>
	<?php } while ($row_ViewIsiSJKembali = mysql_fetch_assoc($ViewIsiSJKembali)); ?>
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
		   <td align="center">&nbsp;</td>
		   <td align="center"><a href="EditSJKembali.php?SJKem=<?php echo $row_View['SJKem']; ?>">
                        <button type="button" class="button2">Edit Pengembalian</button></a></td>
		   <td align="center"><a href="EditSJKembaliQuantity.php?SJKem=<?php echo $row_View['SJKem']; ?>">
                        <button type="button" class="button2">Quantity Terima</button></a></td>
	    <td align="right"><a href="SJKembali.php"><button type="button" class="button2">Cancel</button></a></td>
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

mysql_free_result($ViewIsiSJKembali);

mysql_free_result($View);
?>
