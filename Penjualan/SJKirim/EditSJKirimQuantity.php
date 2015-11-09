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
if (isset($_GET['SJKir'])) {
  $colname_View = $_GET['SJKir'];
}
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT SJKir FROM sjkirim WHERE SJKir = %s", GetSQLValueString($colname_View, "text"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);

$colname_EditIsiSJKirim = "-1";
if (isset($_GET['SJKir'])) {
  $colname_EditIsiSJKirim = $_GET['SJKir'];
}
mysql_select_db($database_Connection, $Connection);
$query_EditIsiSJKirim = sprintf("SELECT isisjkirim.*, transaksi.Barang, transaksi.Quantity, transaksi.QSisa, project.Project FROM isisjkirim INNER JOIN transaksi ON isisjkirim.Purchase=transaksi.Purchase INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode WHERE isisjkirim.SJKir = %s ORDER BY isisjkirim.Id ASC", GetSQLValueString($colname_EditIsiSJKirim, "text"));
$EditIsiSJKirim = mysql_query($query_EditIsiSJKirim, $Connection) or die(mysql_error());
$row_EditIsiSJKirim = mysql_fetch_assoc($EditIsiSJKirim);
$totalRows_EditIsiSJKirim = mysql_num_rows($EditIsiSJKirim);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

/*if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $truncateSQL = sprintf("TRUNCATE TABLE inserted");
  
  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($truncateSQL, $Connection) or die(mysql_error());
}

for($i=0;$i<$totalRows_EditIsiSJKirim;$i++){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO inserted (Purchase) VALUES (%s)",
                       GetSQLValueString($_POST['Purchase'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
}
}*/

for($i=0;$i<$totalRows_EditIsiSJKirim;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE transaksi SET QSisa=%s WHERE Purchase=%s",
                       GetSQLValueString($_POST['QSisa'][$i], "int"),
                       GetSQLValueString($_POST['Purchase'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
}

for($i=0;$i<$totalRows_EditIsiSJKirim;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE isisjkirim SET QTertanda=%s WHERE Id=%s",
                       GetSQLValueString($_POST['QTertanda'][$i], "int"),
                       GetSQLValueString($_POST['Id'][$i], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());

  $updateGoTo = "ViewSJKirim.php";
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
  function sisa1() {
    var txtFirstNumberValue = document.getElementById('QSisa21').value;
    var txtSecondNumberValue = document.getElementById('QTertanda1').value;
	var result = parseInt(txtFirstNumberValue) - parseInt(txtSecondNumberValue);
	  if (!isNaN(result)) {
		document.getElementById('QSisa1').value = result;
      }
   }
</script>

<script language="javascript">
  function sisa2() {
    var txtFirstNumberValue = document.getElementById('QSisa22').value;
    var txtSecondNumberValue = document.getElementById('QTertanda2').value;
	var result = parseInt(txtFirstNumberValue) - parseInt(txtSecondNumberValue);
	  if (!isNaN(result)) {
		document.getElementById('QSisa2').value = result;
      }
   }
</script>

<script language="javascript">
  function sisa3() {
    var txtFirstNumberValue = document.getElementById('QSisa23').value;
    var txtSecondNumberValue = document.getElementById('QTertanda3').value;
	var result = parseInt(txtFirstNumberValue) - parseInt(txtSecondNumberValue);
	  if (!isNaN(result)) {
		document.getElementById('QSisa3').value = result;
      }
   }
</script>

<script language="javascript">
  function sisa4() {
    var txtFirstNumberValue = document.getElementById('QSisa24').value;
    var txtSecondNumberValue = document.getElementById('QTertanda4').value;
	var result = parseInt(txtFirstNumberValue) - parseInt(txtSecondNumberValue);
	  if (!isNaN(result)) {
		document.getElementById('QSisa4').value = result;
      }
   }
</script>

<script language="javascript">
  function sisa5() {
    var txtFirstNumberValue = document.getElementById('QSisa25').value;
    var txtSecondNumberValue = document.getElementById('QTertanda5').value;
	var result = parseInt(txtFirstNumberValue) - parseInt(txtSecondNumberValue);
	  if (!isNaN(result)) {
		document.getElementById('QSisa5').value = result;
      }
   }
</script>

<script language="javascript">
  function sisa6() {
    var txtFirstNumberValue = document.getElementById('QSisa26').value;
    var txtSecondNumberValue = document.getElementById('QTertanda6').value;
	var result = parseInt(txtFirstNumberValue) - parseInt(txtSecondNumberValue);
	  if (!isNaN(result)) {
		document.getElementById('QSisa6').value = result;
      }
   }
</script>

<script language="javascript">
  function sisa7() {
    var txtFirstNumberValue = document.getElementById('QSisa27').value;
    var txtSecondNumberValue = document.getElementById('QTertanda7').value;
	var result = parseInt(txtFirstNumberValue) - parseInt(txtSecondNumberValue);
	  if (!isNaN(result)) {
		document.getElementById('QSisa7').value = result;
      }
   }
</script>

<script language="javascript">
  function sisa8() {
    var txtFirstNumberValue = document.getElementById('QSisa28').value;
    var txtSecondNumberValue = document.getElementById('QTertanda8').value;
	var result = parseInt(txtFirstNumberValue) - parseInt(txtSecondNumberValue);
	  if (!isNaN(result)) {
		document.getElementById('QSisa8').value = result;
      }
   }
</script>

<script language="javascript">
  function sisa9() {
    var txtFirstNumberValue = document.getElementById('QSisa29').value;
    var txtSecondNumberValue = document.getElementById('QTertanda9').value;
	var result = parseInt(txtFirstNumberValue) - parseInt(txtSecondNumberValue);
	  if (!isNaN(result)) {
		document.getElementById('QSisa9').value = result;
      }
   }
</script>

<script language="javascript">
  function sisa10() {
    var txtFirstNumberValue = document.getElementById('QSisa210').value;
    var txtSecondNumberValue = document.getElementById('QTertanda10').value;
	var result = parseInt(txtFirstNumberValue) - parseInt(txtSecondNumberValue);
	  if (!isNaN(result)) {
		document.getElementById('QSisa10').value = result;
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
      <th align="center"><h2><?php echo $row_EditIsiSJKirim['Project']; ?></h2></th>
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
      </tr>
    <tbody>      
    <?php $x=1; ?>
	<?php do { ?>
      <tr>
		<td><input name="Id[]" type="hidden" id="Id" value="<?php echo $row_EditIsiSJKirim['Id']; ?>">
		  <input name="QSisa2" type="hidden" id="QSisa2<?php echo $x; ?>" value="<?php echo $row_EditIsiSJKirim['QSisa']; ?>"></td>
		<td><input name="IsiSJKir" type="text" class="textview" id="IsiSJKir" value="<?php echo $row_EditIsiSJKirim['IsiSJKir']; ?>" readonly></td>
		<td><input name="Barang" type="text" class="textview" id="Barang" value="<?php echo $row_EditIsiSJKirim['Barang']; ?>" readonly></td>
		<td><input name="Warehouse[]" type="text" class="textview" id="Warehouse" value="<?php echo $row_EditIsiSJKirim['Warehouse']; ?>" readonly></td>
		<td><input name="QSisa[]" type="text" class="textview" id="QSisa<?php echo $x; ?>" value="<?php echo $row_EditIsiSJKirim['QSisa']; ?>" readonly></td>
		<td><input name="QKirim[]" type="text" class="textview" id="QKirim" autocomplete="off" value="<?php echo $row_EditIsiSJKirim['QKirim']; ?>" readonly></td>
		<td><input name="QTertanda[]" type="text" class="textbox" id="QTertanda<?php echo $x; ?>" autocomplete="off" onKeyUp="sisa<?php echo $x; ?>();" value="<?php echo $row_EditIsiSJKirim['QTertanda']; ?>"></td>
		<td><input name="Purchase[]" type="text" class="textview" id="Purchase" value="<?php echo $row_EditIsiSJKirim['Purchase']; ?>" readonly></td>
      </tr>
    <?php $x++; ?>
	<?php } while ($row_EditIsiSJKirim = mysql_fetch_assoc($EditIsiSJKirim)); ?>
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
		   <td align="right"><a href="ViewSJKirim.php?SJKir=<?php echo $row_View['SJKir']; ?>"><button type="button" class="submit">Cancel</button></a></td>
		   <td>&nbsp;</td>
		   <td>&nbsp;</td>
		   <td>&nbsp;</td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="MM_insert" value="form1">
</form>

</body>
</html>
<?php
mysql_free_result($Menu);

mysql_free_result($EditIsiSJKirim);

mysql_free_result($View);
?>
