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
  $insertSQL = sprintf("INSERT INTO customer (CCode, Company, Customer, Alamat, Zip, Kota, CompPhone, CustPhone, Fax, NPWP, CompEmail, CustEmail) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['CCode'], "text"),
                       GetSQLValueString($_POST['Company'], "text"),
                       GetSQLValueString($_POST['Customer'], "text"),
                       GetSQLValueString($_POST['Alamat'], "text"),
                       GetSQLValueString($_POST['Zip'], "int"),
                       GetSQLValueString($_POST['Kota'], "text"),
                       GetSQLValueString($_POST['CompPhone'], "text"),
                       GetSQLValueString($_POST['CustPhone'], "text"),
                       GetSQLValueString($_POST['Fax'], "text"),
                       GetSQLValueString($_POST['NPWP'], "text"),
                       GetSQLValueString($_POST['CompEmail'], "text"),
                       GetSQLValueString($_POST['CustEmail'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "Customer.php";
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
    var x = document.getElementById("CCode");
    x.value = x.value.toUpperCase();
	var x = document.getElementById("Company");
    x.value = x.value.toUpperCase();
}
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

<table width="950" border="0">


  <tbody>
    <tr>
      <th align="center"><h2>INSERT</h2></th>
    </tr>
  </tbody>
</table>
</p>
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <table width="1005" border="0">
    <tbody>
      <tr>
        <th width="75">&nbsp;</th>
        <th width="125" align="right">Customer Code</th>
        <th width="50" align="right">&nbsp;</th>
        <th colspan="4" align="left"><input name="CCode" type="text" class="textbox" id="CCode" style="width:512px;" autocomplete="off" onKeyUp="capital()"></th>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Company</th>
        <th align="right">&nbsp;</th>
        <th colspan="4" align="left"><input name="Company" type="text" class="textbox" id="Company" style="width:512px;" autocomplete="off" onKeyUp="capital()"></th>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Customer</th>
        <th align="right">&nbsp;</th>
        <th colspan="4" align="left"><input name="Customer" type="text" class="textbox" id="Customer" style="width:512px;" autocomplete="off"></th>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Alamat</th>
        <th align="right">&nbsp;</th>
        <th colspan="4" align="left"><input name="Alamat" type="text" class="textbox" id="Alamat" style="width:512px;" autocomplete="off"></th>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Kota</th>
        <th align="right">&nbsp;</th>
        <th width="150" align="left"><input name="Kota" type="text" class="textbox" id="Kota" autocomplete="off"></th>
        <th width="100" align="right">Zip Code</th>
        <th width="50" align="right">&nbsp;</th>
        <td width="425"><input name="Zip" type="text" class="textbox" id="Zip" autocomplete="off"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Company Phone</th>
        <th align="right">&nbsp;</th>
        <th align="left"><input name="CompPhone" type="text" class="textbox" id="CompPhone" autocomplete="off"></th>
        <th align="right">Fax</th>
        <th align="right">&nbsp;</th>
        <td><input name="Fax" type="text" class="textbox" id="Fax" autocomplete="off"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Customer Phone</th>
        <th align="right">&nbsp;</th>
        <th align="left"><input name="CustPhone" type="text" class="textbox" id="CustPhone" autocomplete="off"></th>
        <th align="right">NPWP</th>
        <th align="right">&nbsp;</th>
        <td><input name="NPWP" type="text" class="textbox" id="NPWP" autocomplete="off"></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <th align="right">Company Email</th>
        <td align="center">&nbsp;</td>
        <td colspan="4" align="left"><input name="CompEmail" type="text" class="textbox" id="CompEmail" style="width:512px;" autocomplete="off"></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <th align="right">Customer Email</th>
        <td align="center">&nbsp;</td>
        <td colspan="4" align="left"><input name="CustEmail" type="text" class="textbox" id="CustEmail" style="width:512px;" autocomplete="off"></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center"><input type="submit" name="submit" id="submit" class="submit" value="Insert"></td>
        <td colspan="2" align="center"><a href="Customer.php"><button type="button" class="submit">Cancel</button></a></td>
        <td>&nbsp;</td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
</body>
</html>
<?php
mysql_free_result($Menu);
?>
