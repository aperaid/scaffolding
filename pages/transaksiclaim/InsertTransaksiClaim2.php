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

$query_LastReference = "SELECT inserted.Reference, MAX(periode.Periode) FROM inserted LEFT JOIN periode ON inserted.Reference=periode.Reference";
$LastReference = mysql_query($query_LastReference, $Connection) or die(mysql_error());
$row_LastReference = mysql_fetch_assoc($LastReference);
$totalRows_LastReference = mysql_num_rows($LastReference);
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
        document.getElementById("bt_inserttransaksiclaim2_submit").click(); // Simulates button click
        document.submitForm.submit(); // Submits the form without the button
    }
</script>

</head>

<body onLoad="submit()">
<form id="fm_inserttransaksiclaim2_form1" name="fm_inserttransaksiclaim2_form1" method="post" action="InsertTransaksiClaimBarang.php?Reference=<?php echo $row_LastReference['Reference']; ?>&Periode=<?php echo $row_LastReference['MAX(periode.Periode)']; ?>">
  <input type="submit" name="bt_inserttransaksiclaim2_submit" id="bt_inserttransaksiclaim2_submit" value="Submit">
</form>
</body>
</html>
<?php
  mysql_free_result($LastReference);
?>
