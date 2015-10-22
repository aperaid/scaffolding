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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE jualcustomer SET CCode=%s, Customer=%s, Alamat=%s, Kota=%s, ZipCode=%s, Provinsi=%s, Negara=%s, Phone=%s, Fax=%s, Email=%s, Contact=%s, Memo=%s, TerminJual=%s, TerminSewa=%s, TipeCustomer=%s WHERE Id=%s",
                       GetSQLValueString($_POST['CCode'], "text"),
                       GetSQLValueString($_POST['Customer'], "text"),
                       GetSQLValueString($_POST['Alamat'], "text"),
                       GetSQLValueString($_POST['Kota'], "text"),
                       GetSQLValueString($_POST['ZipCode'], "int"),
                       GetSQLValueString($_POST['Provinsi'], "text"),
                       GetSQLValueString($_POST['Negara'], "text"),
                       GetSQLValueString($_POST['Phone'], "text"),
                       GetSQLValueString($_POST['Fax'], "text"),
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString($_POST['Contact'], "text"),
                       GetSQLValueString($_POST['Memo'], "text"),
                       GetSQLValueString($_POST['TerminJual'], "int"),
                       GetSQLValueString($_POST['TerminSewa'], "int"),
                       GetSQLValueString($_POST['TipeCustomer2'], "text"),
                       GetSQLValueString($_POST['Id'], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());

  $updateGoTo = "ViewCustomer.php";
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
$query_Edit = sprintf("SELECT * FROM jualcustomer WHERE Id = %s", GetSQLValueString($colname_Edit, "int"));
$Edit = mysql_query($query_Edit, $Connection) or die(mysql_error());
$row_Edit = mysql_fetch_assoc($Edit);
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
	var x = document.getElementById("Customer");
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
				<tr>
					<td><button class="button" type="button">Warehouse</button></td>
				</tr>
				<tr>
					<td>
                    	<a href="../Customer/Customer.php"><button class="button" type=
                        "button">Customer</button></a>
                    </td>
				</tr>
				<tr>
					<td>
                    	<a href="../Project/Project.php"><button class="button" type=
                    	"button">Project</button></a>
                    </td>
				</tr>
				<tr>
					<td>
						<a href="../POCustomer/POCustomer.php"><button class="button" type=
						"button">PO Customer</button></a>
					</td>
				</tr>
				<tr>
					<td>
						<a href="../TransaksiJual/TransaksiJual.php"><button class="button" type=
						"button">Transaksi Jual</button></a>
					</td>
				</tr>
				<tr>
					<td>
						<a href="../TransaksiSewa/TransaksiSewa.php"><button class="button" type=
						"button">Transaksi Sewa</button></a>
					</td>
				</tr>
				<tr>
					<td><button class="button" type="button">Transport</button></td>
				</tr>
				<tr>
					<td>
						<a href="../TransaksiClaim/TransaksiClaim.php"><button class="button"
						type="button">Transaksi Claim</button></a>
					</td>
				</tr>
				<tr>
					<td>
						<a href="../SewaJT/SewaJT.php"><button class="button" type="button">Sewa
						Jatuh Tempo</button></a>
					</td>
				</tr>
				<tr>
					<td>
						<a href="../SuratJalan/SuratJalan.php"><button class="button" type=
						"button">Surat Jalan</button></a>
					</td>
				</tr>
				<tr>
					<td><button class="button" type="button">Cetak Pengembalian
					Barang</button></td>
				</tr>
    </tbody>
  </table>
</div>

<div style="float:left;width:85%">

<table width="950" border="0">


  <tbody>
    <tr>
      <th align="center"><h2>EDIT</h2></th>
    </tr>
  </tbody>
</table>
</p>
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <table width="1005" border="0">
    <tbody>
      <tr>
        <th width="75"><input name="Id" type="hidden" id="Id" value="<?php echo $row_Edit['Id']; ?>"></th>
        <th width="125" align="right">Code</th>
        <th width="50" align="right">&nbsp;</th>
        <th colspan="4" align="left"><input name="CCode" type="text" id="CCode" style="width:514px;" autocomplete="off" onKeyUp="capital()" value="<?php echo $row_Edit['CCode']; ?>" class="textbox"></th>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Customer</th>
        <th align="right">&nbsp;</th>
        <th colspan="4" align="left"><input name="Customer" type="text" id="Customer" style="width:514px;" autocomplete="off" onKeyUp="capital()" value="<?php echo $row_Edit['Customer']; ?>" class="textbox"></th>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Alamat</th>
        <th align="right">&nbsp;</th>
        <th colspan="4" align="left"><input name="Alamat" type="text" id="Alamat" style="width:514px;" autocomplete="off" value="<?php echo $row_Edit['Alamat']; ?>" class="textbox"></th>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Kota</th>
        <th align="right">&nbsp;</th>
        <th width="150" align="left"><input name="Kota" type="text" class="textbox" id="Kota" autocomplete="off" value="<?php echo $row_Edit['Kota']; ?>"></th>
        <th width="100" align="right">Zip Code</th>
        <th width="50" align="right">&nbsp;</th>
        <td width="419"><input name="ZipCode" type="text" class="textbox" id="ZipCode" autocomplete="off" value="<?php echo $row_Edit['ZipCode']; ?>"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Provinsi</th>
        <th align="right">&nbsp;</th>
        <th align="left"><input name="Provinsi" type="text" id="Provinsi" autocomplete="off" value="<?php echo $row_Edit['Provinsi']; ?>" class="textbox"></th>
        <th align="right">Negara</th>
        <th align="right">&nbsp;</th>
        <td><input name="Negara" type="text" id="Negara" autocomplete="off" value="<?php echo $row_Edit['Negara']; ?>" class="textbox"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Phone</th>
        <th align="right">&nbsp;</th>
        <th align="left"><input name="Phone" type="text" id="Phone" autocomplete="off" value="<?php echo $row_Edit['Phone']; ?>" class="textbox"></th>
        <th align="right">Fax</th>
        <th align="right">&nbsp;</th>
        <td><input name="Fax" type="text" id="Fax" autocomplete="off" value="<?php echo $row_Edit['Fax']; ?>" class="textbox"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Email</th>
        <th align="right">&nbsp;</th>
        <th colspan="4" align="left"><input name="Email" type="text" id="Email" style="width:514px;" autocomplete="off" value="<?php echo $row_Edit['Email']; ?>" class="textbox"></th>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <th align="right">Contact</th>
        <td align="center">&nbsp;</td>
        <td colspan="4" align="left"><input name="Contact" type="text" id="Contact" style="width:514px;" autocomplete="off" value="<?php echo $row_Edit['Contact']; ?>" class="textbox"></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <th align="right">Memo</th>
        <td align="center">&nbsp;</td>
        <td colspan="4" align="left"><input name="Memo" type="text" id="Memo" style="width:514px;" autocomplete="off" value="<?php echo $row_Edit['Memo']; ?>" class="textbox"></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <th align="right">Termin Jual</th>
        <td align="center">&nbsp;</td>
        <td align="left"><input name="TerminJual" type="text" id="TerminJual" autocomplete="off" value="<?php echo $row_Edit['TerminJual']; ?>" class="textbox"></td>
        <th align="right">Termin Sewa</th>
        <td align="center">&nbsp;</td>
        <td><input name="TerminSewa" type="text" id="TerminSewa" autocomplete="off" value="<?php echo $row_Edit['TerminSewa']; ?>" class="textbox"></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <th align="right">Tipe Customer</th>
        <td align="center">&nbsp;</td>
        <td colspan="4" align="left"><input name="TipeCustomer" type="text" class="textbox" id="TipeCustomer" value="<?php echo $row_Edit['TipeCustomer']; ?>" readonly>
          <select name="TipeCustomer2" id="TipeCustomer2">
            <option>1. Eceran</option>
        </select></td>
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
        <td align="center"><input type="submit" name="submit" id="submit" class="submit" value="Edit"></td>
        <td colspan="2" align="center"><a href="ViewCustomer.php?Id=<?php echo $row_Edit['Id']; ?>"><button type="button" class="submit">Cancel</button></a></td>
        <td>&nbsp;</td>
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
