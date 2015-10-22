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
$query_POCustomer = "SELECT * FROM jualpocustomer ORDER BY Id ASC";
$POCustomer = mysql_query($query_POCustomer, $Connection) or die(mysql_error());
$row_POCustomer = mysql_fetch_assoc($POCustomer);
$totalRows_POCustomer = mysql_num_rows($POCustomer);
?>

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="../../JQuery/datatable/dataTables.css">
  
<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="../../JQuery/jquery-1.10.2.js"></script>
  
<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="../../JQuery/datatable/dataTables.js"></script>

<script>
$(document).ready( function () {
    $('#contentTable').DataTable();
} );
</script>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>PT. BDN | PO Customer</title>
	<link href="../../Button.css" rel="stylesheet" type="text/css">
</head>
<body>
	<div class="layout">
		<div class="pageHeader">
			<div class="title">
				PO CUSTOMER
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
			
			<div class="divTable">
				<table id="contentTable">
                	<thead>
                    	<th >No. Invoice</th>
						<th >Tgl Jual</th>
						<th >Customer</th>
						<th >J/S/C</th>
						<th >Project</th>
						<th >Amount</th>
						<th >Status</th>
						<th >Opsi</th>
                    </thead>
                    <tbody>
					<?php do { ?>
                    <tr>
                      <td ><?php echo $row_POCustomer['Reference']; ?></td>
                      <td ><?php echo $row_POCustomer['Tgl']; ?></td>
                      <td ><?php echo $row_POCustomer['Customer']; ?></td>
                      <td ><?php echo $row_POCustomer['JS']; ?></td>
                      <td ><?php echo $row_POCustomer['Project']; ?></td>
                      <td ><?php echo $row_POCustomer['Amount']; ?></td>
                      <td ><?php echo $row_POCustomer['Status']; ?></td>
                      <td ><a href="ViewPOCustomer.php?Id=<?php echo $row_POCustomer['Id']; ?>"><button type="button" class="button3">View</button></a>-<a href="DeletePOCustomer.php?Id=<?php echo $row_POCustomer['Id']; ?>"><button type="button" class="button3">Delete</button></a></td>
                      </tr>
                    <?php } while ($row_POCustomer = mysql_fetch_assoc($POCustomer)); ?>
					<tr>
						<td class="noinvoice">&nbsp;</td>
						<td class="tanggal">&nbsp;</td>
						<td class="customer">&nbsp;</td>
						<td class="JSC">&nbsp;</td>
						<td class="project">&nbsp;</td>
                        <td class="amount">&nbsp;</td>
                        <td class="status">&nbsp;</td>
						<td class="tombol">&nbsp;</td>
					</tr>
                    </tbody>
				</table>
			</div>
		</div>
		<div class="pageRightMenu">
			<table class="menuTable">
				<tr>
					<td>
						<a href="InsertPOCustomer.php"><button class="button2" type=
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
mysql_free_result($POCustomer);
?>