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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO pocustomer (Reference, Tgl, PCode) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['Reference'], "text"),
                       GetSQLValueString($_POST['Tgl'], "text"),
                       GetSQLValueString($_POST['PCode'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "InsertPOCustomerBarang.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_Connection, $Connection);
$query_Menu = "SELECT * FROM menu";
$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
$row_Menu = mysql_fetch_assoc($Menu);
$totalRows_Menu = mysql_num_rows($Menu);

mysql_select_db($database_Connection, $Connection);
$query_Reference = "SELECT Id FROM pocustomer ORDER BY Id DESC";
$Reference = mysql_query($query_Reference, $Connection) or die(mysql_error());
$row_Reference = mysql_fetch_assoc($Reference);
$totalRows_Reference = mysql_num_rows($Reference);
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
    $( "#Tgl" ).datepicker();
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
      <th align="center"><h2>INSERT</h2></th>
    </tr>
  </tbody>
</table>
</p>
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <table width="1000" border="0">
    <tbody>
      <tr>
        <th width="250">&nbsp;</th>
        <th width="100" align="right">Reference</th>
        <th width="75" align="right">&nbsp;</th>
        <td width="557"><input name="Reference" type="text" class="textbox" id="Reference" onKeyUp="capital()" value="<?php echo str_pad($row_Reference['Id']+1, 5, "0", STR_PAD_LEFT); ?>/<?php echo date("dmy") ?>" readonly></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Tanggal</th>
        <th align="right">&nbsp;</th>
        <td><input name="Tgl" type="text" class="textbox" id="Tgl" autocomplete="off"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">PCode</th>
        <th align="right">&nbsp;</th>
        <td><input name="PCode" type="text" class="textbox" id="PCode" autocomplete="off" onKeyUp="capital()"></td>
      </tr>
      <tr>
        <th height="39">&nbsp;</th>
        <th align="right">&nbsp;</th>
        <th align="right">&nbsp;</th>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td align="center"><input type="submit" name="submit" id="submit" class="submit" value="Insert"></td>
        <td align="center">&nbsp;</td>
        <td><a href="POCustomer.php"><button type="button" class="submit">Cancel</button></a></td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>

</body>
</html>
<?php
mysql_free_result($Menu);

mysql_free_result($Reference);
?>
