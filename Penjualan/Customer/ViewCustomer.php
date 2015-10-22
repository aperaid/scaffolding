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

$colname_View = "-1";
if (isset($_GET['Id'])) {
  $colname_View = $_GET['Id'];
}
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT * FROM jualcustomer WHERE Id = %s", GetSQLValueString($colname_View, "int"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);
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
      <th align="center"><h2><?php echo $row_View['Customer']; ?></h2></th>
    </tr>
  </tbody>
</table>
</p>
<form id="form1" name="form1" method="POST">
  <table width="1005" border="0">
    <tbody>
      <tr>
        <th width="75"><input name="Id" type="hidden" id="Id" value="<?php echo $row_View['Id']; ?>"></th>
        <th width="125" align="right">Code</th>
        <th width="50" align="right">&nbsp;</th>
        <th colspan="4" align="left"><input name="Code" type="text" id="Code" style="width:512px;" value="<?php echo $row_View['CCode']; ?>" readonly class="textview"></th>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Customer</th>
        <th align="right">&nbsp;</th>
        <th colspan="4" align="left"><input name="Customer" type="text" id="Customer" style="width:512px;" value="<?php echo $row_View['Customer']; ?>" readonly class="textview"></th>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Alamat</th>
        <th align="right">&nbsp;</th>
        <th colspan="4" align="left"><input name="Alamat" type="text" id="Alamat" style="width:512px;" value="<?php echo $row_View['Alamat']; ?>" readonly class="textview"></th>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Kota</th>
        <th align="right">&nbsp;</th>
        <th width="150" align="left"><input name="Kota" type="text" id="Kota" value="<?php echo $row_View['Kota']; ?>" readonly class="textview"></th>
        <th width="100" align="right">Zip Code</th>
        <th width="50" align="right">&nbsp;</th>
        <td width="425"><input name="ZipCode" type="text" id="ZipCode" value="<?php echo $row_View['ZipCode']; ?>" readonly class="textview"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Provinsi</th>
        <th align="right">&nbsp;</th>
        <th align="left"><input name="Provinsi" type="text" id="Provinsi" value="<?php echo $row_View['Provinsi']; ?>" readonly class="textview"></th>
        <th align="right">Negara</th>
        <th align="right">&nbsp;</th>
        <td><input name="Negara" type="text" id="Negara" value="<?php echo $row_View['Negara']; ?>" readonly class="textview"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Phone</th>
        <th align="right">&nbsp;</th>
        <th align="left"><input name="Phone" type="text" id="Phone" value="<?php echo $row_View['Phone']; ?>" readonly class="textview"></th>
        <th align="right">Fax</th>
        <th align="right">&nbsp;</th>
        <td><input name="Fax" type="text" id="Fax" value="<?php echo $row_View['Fax']; ?>" readonly class="textview"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Email</th>
        <th align="right">&nbsp;</th>
        <th colspan="4" align="left"><input name="Email" type="text" id="Email" style="width:512px;" value="<?php echo $row_View['Email']; ?>" readonly class="textview"></th>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <th align="right">Contact</th>
        <td align="center">&nbsp;</td>
        <td colspan="4" align="left"><input name="Contact" type="text" id="Contact" style="width:512px;" value="<?php echo $row_View['Contact']; ?>" readonly class="textview"></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <th align="right">Memo</th>
        <td align="center">&nbsp;</td>
        <td colspan="4" align="left"><input name="Memo" type="text" id="Memo" style="width:512px;" value="<?php echo $row_View['Memo']; ?>" readonly class="textview"></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <th align="right">Termin Jual</th>
        <td align="center">&nbsp;</td>
        <td align="left"><input name="TerminJual" type="text" id="TerminJual" value="<?php echo $row_View['TerminJual']; ?>" readonly class="textview"></td>
        <th align="right">Termin Sewa</th>
        <td align="center">&nbsp;</td>
        <td><input name="TerminSewa" type="text" id="TerminSewa" value="<?php echo $row_View['TerminSewa']; ?>" readonly class="textview"></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <th align="right">Tipe Customer</th>
        <td align="center">&nbsp;</td>
        <td colspan="4" align="left"><input name="TipeCustomer" type="text" id="TipeCustomer" value="<?php echo $row_View['TipeCustomer']; ?>" readonly class="textview" style="width:512px;"></td>
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
        <td align="center"><a href="EditCustomer.php?Id=<?php echo $row_View['Id']; ?>">
          <button type="button" class="button2">Edit Customer</button></a></td>
        <td colspan="2" align="left"><a><button type="button" class="button2">Print</button></a></td>
        <td align="left"><a href="Customer.php"><button type="button" class="button2">Back</button></a></td>
        <td>&nbsp;</td>
      </tr>
    </tbody>
  </table>
</form>
</body>
</html>
<?php
mysql_free_result($View);
?>
