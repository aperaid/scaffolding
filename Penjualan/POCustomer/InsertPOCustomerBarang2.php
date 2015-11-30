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

$maxRows_JS = 10;
$pageNum_JS = 0;
if (isset($_GET['pageNum_JS'])) {
  $pageNum_JS = $_GET['pageNum_JS'];
}
$startRow_JS = $pageNum_JS * $maxRows_JS;

$colname_JS = "-1";
if (isset($_GET['Reference'])) {
  $colname_JS = $_GET['Reference'];
}
mysql_select_db($database_Connection, $Connection);
$query_JS = sprintf("SELECT DISTINCT JS FROM transaksi WHERE Reference = %s", GetSQLValueString($colname_JS, "text"));
$query_limit_JS = sprintf("%s LIMIT %d, %d", $query_JS, $startRow_JS, $maxRows_JS);
$JS = mysql_query($query_limit_JS, $Connection) or die(mysql_error());
$row_JS = mysql_fetch_assoc($JS);

if (isset($_GET['totalRows_JS'])) {
  $totalRows_JS = $_GET['totalRows_JS'];
} else {
  $all_JS = mysql_query($query_JS);
  $totalRows_JS = mysql_num_rows($all_JS);
}
$totalPages_JS = ceil($totalRows_JS/$maxRows_JS)-1;

mysql_select_db($database_Connection, $Connection);
$query_LastId = "SELECT Id FROM invoice ORDER BY Id DESC";
$LastId = mysql_query($query_LastId, $Connection) or die(mysql_error());
$row_LastId = mysql_fetch_assoc($LastId);
$totalRows_LastId = mysql_num_rows($LastId);

$colname_Reference = "-1";
if (isset($_GET['Reference'])) {
  $colname_Reference = $_GET['Reference'];
}
mysql_select_db($database_Connection, $Connection);
$query_Reference = sprintf("SELECT Reference FROM transaksi WHERE Reference = %s", GetSQLValueString($colname_Reference, "text"));
$Reference = mysql_query($query_Reference, $Connection) or die(mysql_error());
$row_Reference = mysql_fetch_assoc($Reference);
$totalRows_Reference = mysql_num_rows($Reference);

mysql_select_db($database_Connection, $Connection);
$query_inserted = "SELECT * FROM inserted";
$inserted = mysql_query($query_inserted, $Connection) or die(mysql_error());
$row_inserted = mysql_fetch_assoc($inserted);
$totalRows_inserted = mysql_num_rows($inserted);

for($i=0;$i<$totalRows_JS;$i++){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO invoice (Invoice, JSC, PPN, Transport, Reference) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Invoice'][$i], "text"),
                       GetSQLValueString($_POST['JS'][$i], "text"),
					   GetSQLValueString($_POST['PPN'][$i], "text"),
					   GetSQLValueString($_POST['Transport'][$i], "text"),
                       GetSQLValueString($_POST['Reference'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "POCustomer.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
}
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

<script type="text/javascript">
    function submit()
    {
        document.getElementById("submit").click(); // Simulates button click
        document.submitForm.submit(); // Submits the form without the button
    }
</script>

</head>

<body onLoad="submit()">
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <table width="1350" border="0">
    <tbody>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <?php $x=1; ?>
      <?php do { ?>
        <tr>
          <td>&nbsp;</td>
          <td><input name="Invoice[]" type="hidden" id="Invoice" value="<?php echo str_pad($row_LastId['Id'] + $x, 5, "0", STR_PAD_LEFT); ?>">
          <input name="JS[]" type="hidden" id="JS" value="<?php echo $row_JS['JS']; ?>">
          <input name="PPN[]" type="hidden" id="PPN" value="<?php echo $row_inserted['PPN']; ?>">
          <input name="Transport[]" type="hidden" id="Transport" value="<?php echo $row_inserted['Transport']; ?>">
          <input name="Reference[]" type="hidden" id="Reference" value="<?php echo $row_Reference['Reference']; ?>"></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      <?php $x++; ?>
      <?php } while ($row_JS = mysql_fetch_assoc($JS)); ?>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
  <noscript>
  <input type="submit" name="submit" id="submit" value="Submit">
  </noscript>
</form>



</body>
</html>
<?php
mysql_free_result($JS);

mysql_free_result($LastId);

mysql_free_result($Reference);

mysql_free_result($inserted);


?>
