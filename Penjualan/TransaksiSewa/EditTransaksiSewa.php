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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE jualtransaksisewa SET `No`=%s, TglStart=%s, TglEnd=%s, Reference=%s WHERE Id=%s",
                       GetSQLValueString($_POST['No'], "text"),
                       GetSQLValueString($_POST['TglStart'], "text"),
                       GetSQLValueString($_POST['TglEnd'], "text"),
                       GetSQLValueString($_POST['Reference'], "text"),
                       GetSQLValueString($_POST['Id'], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());

  $updateGoTo = "ViewTransaksiSewa.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_Edit = "-1";
if (isset($_GET['Id'])) {
  $colname_Edit = $_GET['Id'];
}
mysql_select_db($database_Connection, $Connection);
$query_Edit = sprintf("SELECT jualpocustomer.*, jualproject.Project, jualcustomer.Customer FROM jualpocustomer INNER JOIN jualproject ON jualpocustomer.PCode=jualproject.PCode INNER JOIN jualcustomer ON jualproject.CCode=jualcustomer.CCode WHERE jualpocustomer.Id = %s", GetSQLValueString($colname_Edit, "int"));
$Edit = mysql_query($query_Edit, $Connection) or die(mysql_error());
$row_Edit = mysql_fetch_assoc($Edit);
$totalRows_Edit = mysql_num_rows($Edit);

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

<script>
function capital() {
    var x = document.getElementById("PCode");
    x.value = x.value.toUpperCase();
}
</script>

<link href="/scaffolding/JQuery2/jquery-ui.css" rel="stylesheet" type="text/css">
<script src="../../JQuery2/external/jquery/jquery.js"></script>
<script src="../../JQuery2/jquery-ui.js"></script>

<script>
$(function() {
  $( "#TglStart,#TglEnd" ).datepicker();
});
</script>

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
      <th align="center"><h2>EDIT</h2></th>
    </tr>
  </tbody>
</table>
</p>
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <table width="1000" border="0">
    <tbody>
      <tr>
        <th width="250"><input name="Id" type="hidden" id="Id" value="<?php echo $row_Edit['Id']; ?>"></th>
        <th width="100" align="right">Invoice</th>
        <th width="75" align="right">&nbsp;</th>
        <td width="557"><input name="Invoice" type="text" class="textbox" id="Invoice" autocomplete="off" onKeyUp="capital()" value="<?php echo $row_Edit['Invoice']; ?>" readonly></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Tgl Start</th>
        <th align="right">&nbsp;</th>
        <td><input name="TglStart" type="text" class="textbox" id="TglStart" autocomplete="off" value="<?php echo $row_Edit['TglStart']; ?>"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Tgl End</th>
        <th align="right">&nbsp;</th>
        <td><input name="TglEnd" type="text" class="textbox" id="TglEnd" autocomplete="off" value="<?php echo $row_Edit['TglEnd']; ?>"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Nama Barang</th>
        <th align="right">&nbsp;</th>
        <td><input name="Nama" type="text" class="textbox" id="Nama" autocomplete="off" value="<?php echo $row_Edit['Nama']; ?>"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Quantity</th>
        <th align="right">&nbsp;</th>
        <td><input name="Quantity" type="text" class="textbox" id="Quantity" autocomplete="off" value="<?php echo $row_Edit['Quantity']; ?>"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">J/S</th>
        <th align="right">&nbsp;</th>
        <td><input name="JS" type="text" class="textbox" id="JS" value="<?php echo $row_Edit['JS']; ?>" readonly></td>
      </tr>
      <tr>
        <th height="39">&nbsp;</th>
        <th align="right">Amount</th>
        <th align="right">&nbsp;</th>
        <td><input name="Amount" type="text" class="textbox" id="Amount" autocomplete="off" onKeyUp="capital()" value="<?php echo $row_Edit['Amount']; ?>"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Project Code</th>
        <th align="right">&nbsp;</th>
        <td><input name="PCode" type="text" class="textbox" id="PCode" autocomplete="off" onKeyUp="capital()" value="<?php echo $row_Edit['PCode']; ?>"></td>
      </tr>
      <tr>
        <th height="39">&nbsp;</th>
        <th align="right">&nbsp;</th>
        <th align="right">&nbsp;</th>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td align="center"><input type="submit" name="submit" id="submit" class="submit" value="Edit"></td>
        <td align="center">&nbsp;</td>
        <td><a href="ViewTransaksiSewa.php?Id=<?php echo $row_Edit['Id']; ?>"><button type="button" class="submit">Cancel</button></a></td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="MM_update" value="form1">
</form>
</body>
</html>
<?php
mysql_free_result($Edit);

mysql_free_result($Menu);
?>
