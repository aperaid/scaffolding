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

mysql_select_db($database_Connection, $Connection);
$query_SewaJT = "SELECT * FROM jualsewajt ORDER BY Id ASC";
$SewaJT = mysql_query($query_SewaJT, $Connection) or die(mysql_error());
$row_SewaJT = mysql_fetch_assoc($SewaJT);
$totalRows_SewaJT = mysql_num_rows($SewaJT);
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
        <td align="center"><h2>SEWA JATUH TEMPO</h2></td>
      </tr>
    </tbody>
  </table>
  <div style="float:left;width:85%; height:485px; overflow:auto">
  <table width="950" border="1">
    <tbody>
      <tr>
        <th>No. Invoice</th>
        <th>Tgl Invoice</th>
        <th>Customer</th>
        <th>J/S</th>
        <th>Project</th>
        <th>Amount</th>
        <th>Status</th>
        <th>&nbsp;</th>
      </tr>
        <?php do { ?>
        <tr>
          <td align="center"><?php echo $row_SewaJT['No']; ?></td>
          <td width="70" align="center"><?php echo $row_SewaJT['Tgl']; ?></td>
          <td align="center"><?php echo $row_SewaJT['Customer']; ?></td>
          <td align="center"><?php echo $row_SewaJT['JS']; ?></td>
          <td align="center"><?php echo $row_SewaJT['Project']; ?></td>
          <td align="center"><?php echo $row_SewaJT['Amount']; ?></td>
          <td align="center"><?php echo $row_SewaJT['Status']; ?></td>
          <td width="300" align="center"><a href="ViewSewaJT.php?Id=<?php echo $row_SewaJT['Id']; ?>"><button type="button" class="button3">View</button></a> --- <a href="DeleteSewaJT.php?Id=<?php echo $row_SewaJT['Id']; ?>"><button type="button" class="button3">Delete</button></a></td>
          </tr>
        <?php } while ($row_SewaJT = mysql_fetch_assoc($SewaJT)); ?>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </tbody>
  </table>
  </div>
  <table width="950" border="0">
  <tbody>
    <tr>
      <td><a href="InsertSewaJT.php"><button type="button" class="button2">Insert</button></a></td>
    </tr>
  </tbody>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($SewaJT);
?>
