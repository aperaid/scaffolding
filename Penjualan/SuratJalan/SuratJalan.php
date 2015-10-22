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
$query_SuratJalan = "SELECT * FROM jualsuratjalan ORDER BY Id ASC";
$SuratJalan = mysql_query($query_SuratJalan, $Connection) or die(mysql_error());
$row_SuratJalan = mysql_fetch_assoc($SuratJalan);
$totalRows_SuratJalan = mysql_num_rows($SuratJalan);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>PT. BDN | Surat Jalan</title>
	<link href="../../Button.css" rel="stylesheet" type="text/css">
</head>
<body>
	<div class="layout">
		<div class="pageHeader">
			<div class="title">
				Surat Jalan
			</div>
		</div>
		<div class="pageLeftMenu">
			<table class="menuTable">
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
			</table>
		</div>
		<div class="pageContent">
			<div class="divTableHeader">
				<table class="tableHeader">
					<tr>
						<th class="noinvoice">No. Invoice</th>
						<th class="tanggal">Tgl Jual</th>
						<th class="noinvoice">No. Customer</th>
						<th class="customer">Customer</th>
						<th class="noinvoice">No. Project</th>
						<th class="project">Project</th>
						<th class="status">Status</th>
                        <th class="status">SJ/PB</th>
						<th class="tombol">Opsi</th>
					</tr>
				</table>
			</div>
			<div class="divTable">
				<table class="contentTable">
					<?php do { ?>
                    <tr>
                      <td class="noinvoice"><?php echo $row_SuratJalan['No']; ?></td>
                      <td class="tanggal"><?php echo $row_SuratJalan['Tgl']; ?></td>
                      <td class="noinvoice"><?php echo $row_SuratJalan['NoCustomer']; ?></td>
                      <td class="customer"><?php echo $row_SuratJalan['Customer']; ?></td>
                      <td class="noinvoice"><?php echo $row_SuratJalan['NoProject']; ?></td>
                      <td class="project"><?php echo $row_SuratJalan['Project']; ?></td>
                      <td class="status"><?php echo $row_SuratJalan['Status']; ?></td>
                      <td class="status"><?php echo $row_SuratJalan['SJPB']; ?></td>
                      <td class="tombol"><a href="ViewSuratJalan.php?Id=<?php echo $row_SuratJalan['Id']; ?>"><button type="button" class="button3">View</button></a>-<a href="DeleteSuratJalan.php?Id=<?php echo $row_SuratJalan['Id']; ?>"><button type="button" class="button3">Delete</button></a></td>
                      </tr>
                    <?php } while ($row_SuratJalan = mysql_fetch_assoc($SuratJalan)); ?>
					<tr>
						<td class="noinvoice">&nbsp;</td>
                      <td class="tanggal">&nbsp;</td>
                      <td class="noinvoice">&nbsp;</td>
                      <td class="customer">&nbsp;</td>
                      <td class="noinvoice">&nbsp;</td>
                      <td class="project">&nbsp;</td>
                      <td class="status">&nbsp;</td>
                      <td class="status">&nbsp;</td>
                      <td class="tombol">&nbsp;</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="pageRightMenu">
			<table class="menuTable">
				<tr>
					<td>
						<a href="InsertSuratJalan.php"><button class="button2" type=
						"button">Insert</button></a>
					</td>
				</tr>
			</table>
		</div>
		<div class="pageFooter">
			<div class="footer">
				PT. Berlian Djaya Nusantara
			</div>
			<div class="copyright">
				Powered by Apera ERP
			</div>
		</div>
	</div>
	
	</body>
</html>
<?php
mysql_free_result($SuratJalan);
?>