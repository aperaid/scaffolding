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
  $insertSQL = sprintf("INSERT INTO jualcustomer (CCode, Customer, Alamat, Kota, ZipCode, Provinsi, Negara, Phone, Fax, Email, Contact, Memo, TerminJual, TerminSewa, TipeCustomer) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
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
                       GetSQLValueString($_POST['TipeCustomer'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "Customer.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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
        <th width="125" align="right">Code</th>
        <th width="50" align="right">&nbsp;</th>
        <th colspan="4" align="left"><input name="CCode" type="text" id="CCode" autocomplete="off" onKeyUp="capital()" style="width:510px;" class="textbox"></th>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Customer</th>
        <th align="right">&nbsp;</th>
        <th colspan="4" align="left"><input name="Customer" type="text" id="Customer" autocomplete="off" style="width:510px;" onKeyUp="capital()" class="textbox"></th>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Alamat</th>
        <th align="right">&nbsp;</th>
        <th colspan="4" align="left"><input name="Alamat" type="text" id="Alamat" autocomplete="off" style="width:510px;" class="textbox"></th>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Kota</th>
        <th align="right">&nbsp;</th>
        <th width="150" align="left"><input name="Kota" type="text" class="textbox" id="Kota" autocomplete="off"></th>
        <th width="100" align="right">Zip Code</th>
        <th width="50" align="right">&nbsp;</th>
        <td width="425"><input name="ZipCode" type="text" class="textbox" id="ZipCode" autocomplete="off"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Provinsi</th>
        <th align="right">&nbsp;</th>
        <th align="left"><input name="Provinsi" type="text" id="Provinsi" autocomplete="off" class="textbox"></th>
        <th align="right">Negara</th>
        <th align="right">&nbsp;</th>
        <td><input name="Negara" type="text" id="Negara" autocomplete="off" class="textbox"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Phone</th>
        <th align="right">&nbsp;</th>
        <th align="left"><input name="Phone" type="text" id="Phone" autocomplete="off" class="textbox"></th>
        <th align="right">Fax</th>
        <th align="right">&nbsp;</th>
        <td><input name="Fax" type="text" id="Fax" autocomplete="off" class="textbox"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Email</th>
        <th align="right">&nbsp;</th>
        <th colspan="4" align="left"><input name="Email" type="text" id="Email" autocomplete="off" style="width:510px;" class="textbox"></th>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <th align="right">Contact</th>
        <td align="center">&nbsp;</td>
        <td colspan="4" align="left"><input name="Contact" type="text" id="Contact" autocomplete="off" style="width:510px;" class="textbox"></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <th align="right">Memo</th>
        <td align="center">&nbsp;</td>
        <td colspan="4" align="left"><input name="Memo" type="text" id="Memo" autocomplete="off" style="width:510px;" class="textbox"></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <th align="right">Termin Jual</th>
        <td align="center">&nbsp;</td>
        <td align="left"><input name="TerminJual" type="text" id="TerminJual" autocomplete="off" class="textbox"></td>
        <th align="right">Termin Sewa</th>
        <td align="center">&nbsp;</td>
        <td><input name="TerminSewa" type="text" id="TerminSewa" autocomplete="off" class="textbox"></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <th align="right">Tipe Customer</th>
        <td align="center">&nbsp;</td>
        <td align="left"><select name="TipeCustomer" id="TipeCustomer">
          <option>1. Eceran</option>
        </select></td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td>&nbsp;</td>
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