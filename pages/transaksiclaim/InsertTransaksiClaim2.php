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

session_start();
$Reference = $_SESSION['Reference']; 
$Strip = substr($Reference, 1, -1);

mysql_select_db($database_Connection, $Connection);
$query_LastPeriode = "SELECT MAX(Periode), Reference FROM periode WHERE Reference = '$Strip' AND Deletes != 'ClaimS' AND Deletes != 'ClaimE' AND Deletes != 'KembaliS' AND Deletes != 'KembaliE' AND Deletes != 'Jual'";
$LastPeriode = mysql_query($query_LastPeriode, $Connection) or die(mysql_error());
$row_LastPeriode = mysql_fetch_assoc($LastPeriode);
$totalRows_LastPeriode = mysql_num_rows($LastPeriode);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link href="../../Button.css" rel="stylesheet" type="text/css">

<script type="text/javascript">
    function submit()
    {
        document.getElementById("bt_inserttransaksiclaim2_submit").click(); // Simulates button click
        document.submitForm.submit(); // Submits the form without the button
    }
</script>

</head>

<body onLoad="submit()">
<form id="fm_inserttransaksiclaim2_form1" name="fm_inserttransaksiclaim2_form1" method="post" action="InsertTransaksiClaimBarang.php?Reference=<?php echo $Strip; ?>&&Periode=<?php echo $row_LastPeriode['MAX(Periode)']; ?>">
  <input type="submit" name="bt_inserttransaksiclaim2_submit" id="bt_inserttransaksiclaim2_submit" value="">
</form>
</body>
</html>
<?php
  mysql_free_result($LastPeriode);
?>
