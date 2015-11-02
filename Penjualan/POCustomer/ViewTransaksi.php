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

$colname_Purchase = "-1";
if (isset($_GET['Reference'])) {
  $colname_Purchase = $_GET['Reference'];
}
mysql_select_db($database_Connection, $Connection);
$query_Purchase = sprintf("SELECT transaksi.*, project.Project FROM pocustomer INNER JOIN transaksi ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode INNER JOIN customer ON project.CCode=customer.CCode WHERE transaksi.Reference = %s ORDER BY transaksi.Id ASC", GetSQLValueString($colname_Purchase, "text"));
$Purchase = mysql_query($query_Purchase, $Connection) or die(mysql_error());
$row_Purchase = mysql_fetch_assoc($Purchase);
$totalRows_Purchase = mysql_num_rows($Purchase);

$colname_View = "-1";
if (isset($_GET['Reference'])) {
  $colname_View = $_GET['Reference'];
}
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT Reference FROM transaksi WHERE Reference = %s", GetSQLValueString($colname_View, "text"));
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
      <th align="center"><h2><?php echo $row_Purchase['Project']; ?></h2></th>
    </tr>
  </tbody>
</table>
</p>
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <table width="1000" border="0">
    <thead>
      <tr>
		<th>&nbsp;</th>
		<th>No. Purchase</th>
		<th>J/S</th>
		<th>Barang</th>
		<th>Amount</th>
		<th>Quantity</th>
		<th>Tanggal Permintaan</th>
      </tr>
    <tbody>      
	<?php do { ?>
      <tr>
		<td><input name="Id" type="hidden" id="Id" value="<?php echo $row_Purchase['Id']; ?>"></td>
		<td><input name="Purchase" type="text" class="textview" id="Purchase" value="<?php echo $row_Purchase['Purchase']; ?>" readonly></td>
		<td><input name="JS" type="text" class="textview" id="JS" value="<?php echo $row_Purchase['JS']; ?>" readonly></td>
		<td><input name="Barang" type="text" class="textview" id="Barang" value="<?php echo $row_Purchase['Barang']; ?>" readonly></td>
		<td><input name="Amount" type="text" class="textview" id="Amount" value="<?php echo $row_Purchase['Amount']; ?>" readonly></td>
		<td><input name="Quantity" type="text" class="textview" id="Quantity" value="<?php echo $row_Purchase['Quantity']; ?>" readonly></td>
		<td><input name="TglStart" type="text" class="textview" id="TglStart" value="<?php echo $row_Purchase['TglStart']; ?>" readonly></td>
      </tr>
	<?php } while ($row_Purchase = mysql_fetch_assoc($Purchase)); ?>
      <tr>
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
		   <td align="right"><a href="EditTransaksi.php?Reference=<?php echo $row_View['Reference']; ?>">
                        <button type="button" class="button2">Edit Barang</button></a></td>
		   <td></td>
		   <td><a href="POCustomer.php"><button type="button" class="button2">Cancel</button></a></td>
		   <td>&nbsp;</td>
      </tr>
    </tbody>
  </table>
</form>

</body>
</html>
<?php
mysql_free_result($Menu);

mysql_free_result($Purchase);

mysql_free_result($View);
?>
