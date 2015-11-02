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

$colname_View = "-1";
if (isset($_GET['Id'])) {
  $colname_View = $_GET['Id'];
}
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT transaksi.*, project.Project, customer.Customer FROM transaksi INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode INNER JOIN customer ON project.CCode=customer.CCode WHERE transaksi.Id = %s", GetSQLValueString($colname_View, "int"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);

mysql_select_db($database_Connection, $Connection);
$query_Menu = "SELECT * FROM menu";
$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
$row_Menu = mysql_fetch_assoc($Menu);
$totalRows_Menu = mysql_num_rows($Menu);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
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

<table width="800" border="0">


  <tbody>
    <tr>
      <th align="center"><h2><?php echo $row_View['Barang']; ?></h2></th>
    </tr>
  </tbody>
</table>
</p>
<form id="form1" name="form1" method="POST">
  <table width="1000" border="0">
    <tbody>
      <tr>
        <th width="250">&nbsp;</th>
        <th width="100" align="right">No. Purchase</th>
        <th width="75" align="right">&nbsp;</th>
        <td width="557" colspan="2"><input name="Purchase" type="text" class="textview" id="Purchase" value="<?php echo $row_View['Purchase']; ?>" readonly></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Project</th>
        <th align="right">&nbsp;</th>
        <td colspan="2"><input name="Project" type="text" class="textview" id="Project" value="<?php echo $row_View['Project']; ?>" readonly></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Customer</th>
        <th align="right">&nbsp;</th>
        <td colspan="2"><input name="Customer" type="text" class="textview" id="Customer" value="<?php echo $row_View['Customer']; ?>" readonly></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">J/S</th>
        <th align="right">&nbsp;</th>
        <td colspan="2"><input name="JS" type="text" class="textview" id="JS" value="<?php echo $row_View['JS']; ?>" readonly></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Barang</th>
        <th align="right">&nbsp;</th>
        <td colspan="2"><input name="Barang" type="text" class="textview" id="Barang" value="<?php echo $row_View['Barang']; ?>" readonly></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Quantity</th>
        <th align="right">&nbsp;</th>
        <td colspan="2"><input name="Quantity" type="text" class="textview" id="Quantity" value="<?php echo $row_View['Quantity']; ?>" readonly></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Amount</th>
        <th align="right">&nbsp;</th>
        <td colspan="2"><input name="Amount" type="text" class="textview" id="Amount" value="<?php echo $row_View['Amount']; ?>" readonly></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Tanggal Jual</th>
        <th align="right">&nbsp;</th>
        <td colspan="2"><input name="TglStart" type="text" class="textview" id="TglStart" value="<?php echo $row_View['TglStart']; ?>" readonly></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Reference</th>
        <th align="right">&nbsp;</th>
        <td colspan="2"><input name="Reference" type="text" class="textview" id="Reference" value="<?php echo $row_View['Reference']; ?>" readonly></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Status</th>
        <th align="right">&nbsp;</th>
        <td colspan="2"><input name="Status" type="text" class="textview" id="Status" value="<?php echo $row_View['Status']; ?>" readonly></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td colspan="2" align="center"><a><button type="button" class="button2">Print</button></a></td>
        <td width="557"><a href="TransaksiJual.php"><button type="button" class="button2">Cancel</button></a></td>
        <td width="415">&nbsp;</td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
</body>
</htm>
<?php
mysql_free_result($View);

mysql_free_result($Menu);
?>
