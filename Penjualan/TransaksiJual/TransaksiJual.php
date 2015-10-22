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
$query_TransaksiJual = "SELECT * FROM jualtransaksijual ORDER BY Id ASC";
$TransaksiJual = mysql_query($query_TransaksiJual, $Connection) or die(mysql_error());
$row_TransaksiJual = mysql_fetch_assoc($TransaksiJual);
$totalRows_TransaksiJual = mysql_num_rows($TransaksiJual);
?>

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="../../JQuery/DataTable/css/jquery.dataTables.css">
  
<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="../../JQuery/DataTable/js/jquery.js"></script>
  
<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="../../JQuery/datatable/js/jquery.dataTables.js"></script>

<script>
$(document).ready( function () {
    $('#contentTable').DataTable();
} );
</script>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>PT. BDN | Transaksi Claim</title>
	<link href="../../Button.css" rel="stylesheet" type="text/css">
</head>
<body>
	<div class="layout">
		<div class="pageHeader">
			<div class="title">
				TRANSAKSI JUAL
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
                        	<th class="noinvoice">No. Invoice</th>
                            <th class="tanggal">Tgl Jual</th>
                            <th class="customer">Customer</th>
                            <th class="JSC">J/S/C</th>
                            <th class="project">Project</th>
                            <th class="amount">Amount</th>
                            <th class="status">Status</th>
                            <th class="tombol">Opsi</th>
                        </thead>
                        <tbody>
                                <?php do { ?>
                                <tr>
                                  <td class="noinvoice"><?php echo $row_TransaksiJual['No']; ?></td>
                                  <td class="tanggal"><?php echo $row_TransaksiJual['Tgl']; ?></td>
                                  <td class="customer"><?php echo $row_TransaksiJual['Customer']; ?></td>
                                  <td class="JSC"><?php echo $row_TransaksiJual['JSC']; ?></td>
                                  <td class="project"><?php echo $row_TransaksiJual['Project']; ?></td>
                                  <td class="amount"><?php echo $row_TransaksiJual['Amount']; ?></td>
                                  <td class="status"><?php echo $row_TransaksiJual['Status']; ?></td>
                                  <td class="tombol"><a href="VIewTransaksiJual.php?Id=<?php echo $row_TransaksiJual['Id']; ?>"><button type="button" class="button3">View</button></a>-<a href="DeleteTransaksiJual.php?Id=<?php echo $row_TransaksiJual['Id']; ?>"><button type="button" class="button3">Delete</button></a></td>
                                  </tr>
                                <?php } while ($row_TransaksiJual = mysql_fetch_assoc($TransaksiJual)); ?>
                                </tbody>
                        </table>
                    </div>
                </div>
                            <div class="pageRightMenu">
                                <table class="menuTable"> 
                                    <tr><td><a href="InsertTransaksiJual.php"><button type="button" class="button2">Insert</button></a></td></tr>
                                </table>
                            </div>
                            <div class="pageFooter">
                                <div class="footer">PT. Berlian Djaya Nusantara</div>
                                <div class="copyright">Powered by Apera ERP</div>
                            </div>
                        </div>
                    </body>
                </html>
                <?php
                    mysql_free_result($TransaksiJual);
                    ?>

