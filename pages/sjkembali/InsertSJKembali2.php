<?php require_once('../../connection/connection.php'); ?>
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

$query_LastReference = "SELECT Reference FROM sjkembali ORDER BY Id DESC";
$LastReference = mysql_query($query_LastReference, $Connection) or die(mysql_error());
$row_LastReference = mysql_fetch_assoc($LastReference);
$totalRows_LastReference = mysql_num_rows($LastReference);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>

<script type="text/javascript">
    function submit()
    {
        document.getElementById("submit").click(); // Simulates button click
        document.submitForm.submit(); // Submits the form without the button
    }
</script>

</head>

<body onLoad="submit()">
<form id="form1" name="form1" method="post" action="InsertSJKembaliBarang.php?Reference=<?php echo $row_LastReference['Reference']; ?>">
  <input type="submit" name="submit" id="submit" value="">
</form>
</body>
</html>
<?php
mysql_free_result($LastReference);
?>