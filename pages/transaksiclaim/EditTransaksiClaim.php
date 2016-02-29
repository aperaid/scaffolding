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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE jualtransaksiclaim SET `No`=%s, TglStart=%s, TglEnd=%s, Customer=%s, JSC=%s, Project=%s, Amount=%s, Status=%s WHERE Id=%s",
                       GetSQLValueString($_POST['tx_edittransaksiclaim_No'], "text"),
                       GetSQLValueString($_POST['tx_edittransaksiclaim_TglStart'], "text"),
                       GetSQLValueString($_POST['tx_edittransaksiclaim_TglEnd'], "text"),
                       GetSQLValueString($_POST['tx_edittransaksiclaim_Customer'], "text"),
                       GetSQLValueString($_POST['tx_edittransaksiclaim_JSC'], "text"),
                       GetSQLValueString($_POST['tx_edittransaksiclaim_Project'], "text"),
                       GetSQLValueString($_POST['tx_edittransaksiclaim_Amount'], "int"),
                       GetSQLValueString($_POST['db_edittransaksiclaim_Status2'], "text"),
                       GetSQLValueString($_POST['hd_edittransaksiclaim_Id'], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());

  $updateGoTo = "ViewTransaksiClaim.php";
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
$query_Edit = sprintf("SELECT * FROM jualtransaksiclaim WHERE Id = %s", GetSQLValueString($colname_Edit, "int"));
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
    var x = document.getElementById("No");
    x.value = x.value.toUpperCase();
	var x = document.getElementById("Customer");
    x.value = x.value.toUpperCase();
	var x = document.getElementById("Project");
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
                    <a href="../../<?php echo $row_Menu['link']; ?>">
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
<form action="<?php echo $editFormAction; ?>" id="fm_edittransaksiclaim_form1" name="fm_edittransaksiclaim_form1" method="POST">
  <table width="1000" border="0">
    <tbody>
      <tr>
        <th width="250"><input name="hd_edittransaksiclaim_Id" type="hidden" id="hd_edittransaksiclaim_Id" value="<?php echo $row_Edit['Id']; ?>"></th>
        <th width="100" align="right">No. Invoice</th>
        <th width="75" align="right">&nbsp;</th>
        <td width="557"><input name="tx_edittransaksiclaim_No" type="text" id="tx_edittransaksiclaim_No" autocomplete="off" value="<?php echo $row_Edit['No']; ?>" onKeyUp="capital()" class="textbox"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Tgl Start</th>
        <th align="right">&nbsp;</th>
        <td><input name="tx_edittransaksiclaim_TglStart" type="text" id="tx_edittransaksiclaim_TglStart" autocomplete="off" value="<?php echo $row_Edit['TglStart']; ?>" class="textbox"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Tgl End</th>
        <th align="right">&nbsp;</th>
        <td><input name="tx_edittransaksiclaim_TglEnd" type="text" id="tx_edittransaksiclaim_TglEnd" autocomplete="off" value="<?php echo $row_Edit['TglEnd']; ?>" class="textbox"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Customer</th>
        <th align="right">&nbsp;</th>
        <td><input name="tx_edittransaksiclaim_Customer" type="text" id="tx_edittransaksiclaim_Customer" autocomplete="off" value="<?php echo $row_Edit['Customer']; ?>" onKeyUp="capital()" class="textbox"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">J/S/C</th>
        <th align="right">&nbsp;</th>
        <td><input name="tx_edittransaksiclaim_JSC" type="text" id="tx_edittransaksiclaim_JSC" autocomplete="off" value="<?php echo $row_Edit['JSC']; ?>" readonly class="textbox"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Project</th>
        <th align="right">&nbsp;</th>
        <td><input name="tx_edittransaksiclaim_Project" type="text" id="tx_edittransaksiclaim_Project" autocomplete="off" value="<?php echo $row_Edit['Project']; ?>" onKeyUp="capital()" class="textbox"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Amount</th>
        <th align="right">&nbsp;</th>
        <td><input name="tx_edittransaksiclaim_Amount" type="text" id="tx_edittransaksiclaim_Amount" autocomplete="off" value="<?php echo $row_Edit['Amount']; ?>" class="textbox"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Status</th>
        <th align="right">&nbsp;</th>
        <td><input name="tx_edittransaksiclaim_Status" type="text" id="tx_edittransaksiclaim_Status" value="<?php echo $row_Edit['Status']; ?>" readonly class="textbox">
          <select name="db_edittransaksiclaim_Status2" id="db_edittransaksiclaim_Status2">
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
        <td colspan="2" align="center"><input type="submit" name="bt_edittransaksiclaim_submit" id="bt_edittransaksiclaim_submit" class="submit" value="Edit"></td>
        <td><a href="ViewTransaksiClaim.php?Id=<?php echo $row_Edit['Id']; ?>"><button type="button" class="submit">Cancel</button></a></td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_update" value="form1">
</form>
</body>
</html>
<?php
  mysql_free_result($Edit);
?>
