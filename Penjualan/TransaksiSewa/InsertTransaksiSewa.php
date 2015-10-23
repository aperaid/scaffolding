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
  $insertSQL = sprintf("INSERT INTO jualtransaksisewa (`No`, TglStart, TglEnd, Customer, JSC, Project, Amount, Status) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['No'], "text"),
                       GetSQLValueString($_POST['TglStart'], "text"),
                       GetSQLValueString($_POST['TglEnd'], "text"),
                       GetSQLValueString($_POST['Customer'], "text"),
                       GetSQLValueString($_POST['JSC'], "text"),
                       GetSQLValueString($_POST['Project'], "text"),
                       GetSQLValueString($_POST['Amount'], "int"),
                       GetSQLValueString($_POST['Status'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "TransaksiSewa.php";
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
    var x = document.getElementById("No");
    x.value = x.value.toUpperCase();
	var x = document.getElementById("Customer");
    x.value = x.value.toUpperCase();
	var x = document.getElementById("Project");
    x.value = x.value.toUpperCase();
}
</script>

<link href="../../JQuery/jquery-ui.css" rel="stylesheet" type="text/css">
<script src="../../JQuery/jquery-1.10.2.js"></script>
<script src="../../JQuery/jquery-ui.js"></script>
<script>
$(function() {
  $( "#TglStart,#TglEnd" ).datepicker();
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
        <th width="100" align="right">No. Invoice</th>
        <th width="75" align="right">&nbsp;</th>
        <td width="557"><input name="No" type="text" id="No" autocomplete="off" onKeyUp="capital()" class="textbox"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Tgl Start</th>
        <th align="right">&nbsp;</th>
        <td><input name="TglStart" type="text" id="TglStart" autocomplete="off" class="textbox"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Tgl End</th>
        <th align="right">&nbsp;</th>
        <td><input name="TglEnd" type="text" id="TglEnd" autocomplete="off" class="textbox"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Customer</th>
        <th align="right">&nbsp;</th>
        <td><input name="Customer" type="text" id="Customer" autocomplete="off" onKeyUp="capital()" class="textbox"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">J/S/C</th>
        <th align="right">&nbsp;</th>
        <td><input name="JSC" type="text" id="JSC" autocomplete="off" readonly value="Sewa" class="textbox"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Project</th>
        <th align="right">&nbsp;</th>
        <td><input name="Project" type="text" id="Project" autocomplete="off" onKeyUp="capital()" class="textbox"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Amount</th>
        <th align="right">&nbsp;</th>
        <td><input name="Amount" type="text" id="Amount" autocomplete="off" class="textbox"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Status</th>
        <th align="right">&nbsp;</th>
        <td><select name="Status" id="Status">
            <option>F</option>
            <option>P</option>
            <option>O</option>
        </select></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td colspan="2" align="center"><input type="submit" name="submit" id="submit" class="submit" value="Insert"></td>
        <td><a href="TransaksiSewa.php"><button type="button" class="submit">Cancel</button></a></td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
</body>
</html>