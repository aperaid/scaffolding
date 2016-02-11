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

mysql_select_db($database_Connection, $Connection);
$query_Menu = "SELECT * FROM menu";
$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
$row_Menu = mysql_fetch_assoc($Menu);
$totalRows_Menu = mysql_num_rows($Menu);

$colname_View = "-1";
if (isset($_GET['SJKem'])) {
  $colname_View = $_GET['SJKem'];
}
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT SJKem FROM sjkembali WHERE SJKem = %s", GetSQLValueString($colname_View, "text"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);

$colname_EditIsiSJKembali = "-1";
if (isset($_GET['SJKem'])) {
  $colname_EditIsiSJKembali = $_GET['SJKem'];
}
mysql_select_db($database_Connection, $Connection);
$query_EditIsiSJKembali = sprintf("SELECT isisjkembali.*, isisjkirim.QSisaKem, sjkirim.Tgl, transaksi.Barang, project.Project FROM isisjkembali INNER JOIN isisjkirim ON isisjkembali.IsiSJKir=isisjkirim.IsiSJKir INNER JOIN sjkirim ON isisjkirim.SJKir=sjkirim.SJKir INNER JOIN transaksi ON isisjkembali.Purchase=transaksi.Purchase INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode WHERE isisjkembali.SJKem = %s ORDER BY isisjkembali.Id ASC", GetSQLValueString($colname_EditIsiSJKembali, "text"));
$EditIsiSJKembali = mysql_query($query_EditIsiSJKembali, $Connection) or die(mysql_error());
$row_EditIsiSJKembali = mysql_fetch_assoc($EditIsiSJKembali);
$totalRows_EditIsiSJKembali = mysql_num_rows($EditIsiSJKembali);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

for($i=0;$i<$totalRows_EditIsiSJKembali;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE isisjkembali SET Warehouse=%s, QTerima=%s WHERE Id=%s",
                       GetSQLValueString($_POST['Warehouse'][$i], "text"),
                       GetSQLValueString($_POST['QTerima'][$i], "int"),
                       GetSQLValueString($_POST['Id'][$i], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());

  $updateGoTo = "ViewSJKembali.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  
  header(sprintf("Location: %s", $updateGoTo));
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
      <th align="center"><h2><?php echo $row_EditIsiSJKembali['Project']; ?></h2></th>
    </tr>
  </tbody>
</table>
</p>
<form action="<?php echo $editFormAction; ?>" id="fm_editsjkembali_form1" name="form1" method="POST">
  <table width="1000" border="0">
    <thead>
      <tr>
		<th>&nbsp;</th>
		<th>No. Isi SJ</th>
		<th>Tanggal Kirim</th>
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
		<td><input name="Id[]" type="hidden" id="hd_editsjkembali_Id" value="<?php echo $row_EditIsiSJKembali['Id']; ?>"></td>
		<td><input name="IsiSJKem" type="text" class="textview" id="tx_editsjkembali_IsiSJKem" value="<?php echo $row_EditIsiSJKembali['IsiSJKem']; ?>" readonly></td>
		<td><input name="Tgl" type="text" class="textview" id="tx_editsjkembali_Tgl" value="<?php echo $row_EditIsiSJKembali['Tgl']; ?>" readonly></td>
		<td><input name="Barang" type="text" class="textview" id="tx_editsjkembali_Barang" value="<?php echo $row_EditIsiSJKembali['Barang']; ?>" readonly></td>
		<td><input name="Warehouse[]" type="text" class="textbox" id="tx_editsjkembali_Warehouse" autocomplete="off" value="<?php echo $row_EditIsiSJKembali['Warehouse']; ?>"></td>
		<td><input name="QSisaKem" type="text" class="textview" id="tx_editsjkembali_QSisaKem" value="<?php echo $row_EditIsiSJKembali['QSisaKem']; ?>" readonly></td>
		<td><input name="QTertanda[]" type="text" class="textbox" id="tx_editsjkembali_QTertanda" autocomplete="off" value="<?php echo $row_EditIsiSJKembali['QTertanda']; ?>"></td>
		<td><input name="QTerima[]" type="text" class="textview" id="tx_editsjkembali_QTerima" value="<?php echo $row_EditIsiSJKembali['QTerima']; ?>" readonly></td>
		<td><input name="Purchase" type="text" class="textview" id="tx_editsjkembali_Purchase" value="<?php echo $row_EditIsiSJKembali['Purchase']; ?>" readonly></td>
      </tr>
	<?php } while ($row_EditIsiSJKembali = mysql_fetch_assoc($EditIsiSJKembali)); ?>
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
		   <td>&nbsp;</td>
		   <td><input type="submit" name="submit" id="bt_editsjkembali_submit" class="submit" value="Update"></td>
		   <td align="right"><a href="ViewSJKembali.php?SJKem=<?php echo $row_View['SJKem']; ?>"><button type="button" class="submit">Cancel</button></a></td>
		   <td>&nbsp;</td>
		   <td>&nbsp;</td>
		   <td>&nbsp;</td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_update" value="form1">
</form>

</body>
</html>
<?php
  mysql_free_result($Menu);
  mysql_free_result($EditIsiSJKembali);
  mysql_free_result($View);
?>
