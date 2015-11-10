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
$query_EditIsiSJKembali = sprintf("SELECT isisjkembali.*, transaksi.Barang, transaksi.QSisaKem, project.Project FROM isisjkembali INNER JOIN transaksi ON isisjkembali.Purchase=transaksi.Purchase INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode WHERE isisjkembali.SJKem = %s ORDER BY isisjkembali.Id ASC", GetSQLValueString($colname_EditIsiSJKembali, "text"));
$EditIsiSJKembali = mysql_query($query_EditIsiSJKembali, $Connection) or die(mysql_error());
$row_EditIsiSJKembali = mysql_fetch_assoc($EditIsiSJKembali);
$totalRows_EditIsiSJKembali = mysql_num_rows($EditIsiSJKembali);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

for($i=0;$i<$totalRows_EditIsiSJKembali;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE transaksi SET QSisaKem=%s WHERE Purchase=%s",
                       GetSQLValueString($_POST['QSisaKem'][$i], "int"),
                       GetSQLValueString($_POST['Purchase'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
}

for($i=0;$i<$totalRows_EditIsiSJKembali;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE isisjkembali SET QTertanda=%s WHERE Id=%s",
                       GetSQLValueString($_POST['QTertanda'][$i], "int"),
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

<script language="javascript">
  function sisa() {
  for(x = 1; x < 11; x++){
    var txtFirstNumberValue = document.getElementById('QSisaKem2'+x).value;
    var txtSecondNumberValue = document.getElementById('QTerima'+x).value;
	var result = parseInt(txtFirstNumberValue) - parseInt(txtSecondNumberValue);
	  if (!isNaN(result)) {
		document.getElementById('QSisaKem'+x).value = result;
      }
   }
   }
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
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <table width="1000" border="0">
    <thead>
      <tr>
		<th>&nbsp;</th>
		<th>No. Isi SJ</th>
		<th>Barang</th>
		<th>Warehouse</th>
		<th>Quantity Sisa Kembali</th>
		<th>Quantity Tertanda</th>
		<th>Quantity Terima</th>
		<th>No. Purchase</th>
      </tr>
    <tbody>      
    <?php $x=1; ?>
	<?php do { ?>
      <tr>
		<td><input name="Id[]" type="hidden" id="Id" value="<?php echo $row_EditIsiSJKembali['Id']; ?>">
		  <input name="QSisaKem2" type="hidden" id="QSisaKem2<?php echo $x; ?>" value="<?php echo $row_EditIsiSJKembali['QSisaKem']; ?>"></td>
		<td><input name="IsiSJKem" type="text" class="textview" id="IsiSJKem" value="<?php echo $row_EditIsiSJKembali['IsiSJKem']; ?>" readonly></td>
		<td><input name="Barang" type="text" class="textview" id="Barang" value="<?php echo $row_EditIsiSJKembali['Barang']; ?>" readonly></td>
		<td><input name="Warehouse[]" type="text" class="textview" id="Warehouse" value="<?php echo $row_EditIsiSJKembali['Warehouse']; ?>" readonly></td>
		<td><input name="QSisaKem[]" type="text" class="textview" id="QSisaKem<?php echo $x; ?>" value="<?php echo $row_EditIsiSJKembali['QSisaKem']; ?>" readonly></td>
		<td><input name="QTertanda[]" type="text" class="textview" id="QTertanda" autocomplete="off" value="<?php echo $row_EditIsiSJKembali['QTertanda']; ?>" readonly></td>
		<td><input name="QTerima[]" type="text" class="textbox" id="QTerima<?php echo $x; ?>" autocomplete="off" onKeyUp="sisa();" value="<?php echo $row_EditIsiSJKembali['QTerima']; ?>"></td>
		<td><input name="Purchase[]" type="text" class="textview" id="Purchase" value="<?php echo $row_EditIsiSJKembali['Purchase']; ?>" readonly></td>
      </tr>
    <?php $x++; ?>
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
      </tr>
      <tr>
		   <td>&nbsp;</td>
		   <td>&nbsp;</td>
		   <td>&nbsp;</td>
		   <td><input type="submit" name="submit" id="submit" class="submit" value="Update"></td>
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
