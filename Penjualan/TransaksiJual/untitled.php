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

$maxRows_Purchase = 10;
$pageNum_Purchase = 0;
if (isset($_GET['pageNum_Purchase'])) {
  $pageNum_Purchase = $_GET['pageNum_Purchase'];
}
$startRow_Purchase = $pageNum_Purchase * $maxRows_Purchase;

$colname_SelectReference = "-1";
if (isset($_GET['Id'])) {
  $colname_SelectReference = $_GET['Id'];
}
mysql_select_db($database_Connection, $Connection);
$query_SelectReference = sprintf("SELECT transaksi.Reference, project.Project FROM transaksi INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode WHERE transaksi.Id = %s", GetSQLValueString($colname_SelectReference, "int"));
$SelectReference = mysql_query($query_SelectReference, $Connection) or die(mysql_error());
$row_SelectReference = mysql_fetch_assoc($SelectReference);
$totalRows_SelectReference = mysql_num_rows($SelectReference);

$text=$row_SelectReference['Reference'];

mysql_select_db($database_Connection, $Connection);
$query_Purchase = "SELECT * FROM transaksi WHERE JS = 'Sewa' AND Reference = '$text' ORDER BY Id ASC";
$query_limit_Purchase = sprintf("%s LIMIT %d, %d", $query_Purchase, $startRow_Purchase, $maxRows_Purchase);
$Purchase = mysql_query($query_limit_Purchase, $Connection) or die(mysql_error());
$row_Purchase = mysql_fetch_assoc($Purchase);

if (isset($_GET['totalRows_Purchase'])) {
  $totalRows_Purchase = $_GET['totalRows_Purchase'];
} else {
  $all_Purchase = mysql_query($query_Purchase);
  $totalRows_Purchase = mysql_num_rows($all_Purchase);
}
$totalPages_Purchase = ceil($totalRows_Purchase/$maxRows_Purchase)-1;

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

for($i=0;$i<$totalRows_Purchase;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE transaksi SET Purchase=%s, Barang=%s, Quantity=%s, Amount=%s, TglStart=%s WHERE Id=%s",
                       GetSQLValueString($_POST['Purchase'][$i], "text"),
                       GetSQLValueString($_POST['Barang'][$i], "text"),
                       GetSQLValueString($_POST['Quantity'][$i], "int"),
                       GetSQLValueString($_POST['Amount'][$i], "text"),
                       GetSQLValueString($_POST['TglStart'][$i], "text"),
                       GetSQLValueString($_POST['Id'][$i], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());



  $updateGoTo = "untitled.php";
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
    $( "#TglStart" ).datepicker();
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
      <th align="center"><h2><?php echo $row_SelectReference['Project']; ?></h2></th>
    </tr>
  </tbody>
</table>
</p>
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <table width="1000" border="0">
    <thead>
      <tr>
		<th>&nbsp;</th>
		<th>Purchase</th>
		<th>Barang</th>
		<th>Amount</th>
		<th>Quantity</th>
		<th>Tanggal Permintaan</th>
      </tr>
    <tbody>      
	<?php do { ?>
      <tr>
		<td><input name="Id[]" type="hidden" id="Id" value="<?php echo $row_Purchase['Id']; ?>"></td>
		<td><input name="Purchase[]" type="text" class="textbox" id="Purchase" value="<?php echo $row_Purchase['Purchase']; ?>" readonly></td>
		<td><input name="Barang[]" type="text" class="textbox" id="Barang" value="<?php echo $row_Purchase['Barang']; ?>"></td>
		<td><input name="Amount[]" type="text" class="textbox" id="Amount" value="<?php echo $row_Purchase['Amount']; ?>"></td>
		<td><input name="Quantity[]" type="text" class="textbox" id="Quantity" value="<?php echo $row_Purchase['Quantity']; ?>"></td>
		<td><input name="TglStart[]" type="text" class="textbox" id="TglStart" value="<?php echo $row_Purchase['TglStart']; ?>"></td>
      </tr>
	<?php } while ($row_Purchase = mysql_fetch_assoc($Purchase)); ?>
      <tr>
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
		   <td align="right"><input type="submit" name="submit" id="submit" class="submit" value="Edit"></td>
		   <td></td>
		   <td><a href="TransaksiSewa.php"><button type="button" class="submit">Cancel</button></a></td>
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

mysql_free_result($Purchase);

mysql_free_result($SelectReference);
?>
