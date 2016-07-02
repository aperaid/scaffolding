<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

if ((isset($_GET['CCode'])) && ($_GET['CCode'] != "")) {
  $deleteSQL = sprintf("DELETE FROM customer WHERE CCode = %s",
  					   GetSQLValueString($_GET['CCode'], "Text"));
  
  $alterSQL = sprintf("ALTER TABLE customer AUTO_INCREMENT = 1");
  
  //Check Connection
  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());
  
  $deleteGoTo = "Customer.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}
?>

<!doctype html>
<html>
<head>
</head>
<body>
</body>
</html>

