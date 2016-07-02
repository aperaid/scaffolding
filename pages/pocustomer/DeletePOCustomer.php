<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

if ((isset($_GET['Reference'])) && ($_GET['Reference'] != "")) {
  $deleteSQL = sprintf("DELETE FROM pocustomer WHERE Reference = %s",
  					   GetSQLValueString($_GET['Reference'], "Text"));
					   
  $alterSQL = sprintf("ALTER TABLE pocustomer AUTO_INCREMENT = 1");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());

  $deleteGoTo = "POCustomer.php";
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