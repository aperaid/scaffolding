<?php require_once('../../Connections/Connection.php'); ?><?php
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
	$query_TransaksiClaim = "SELECT * FROM jualtransaksiclaim ORDER BY Id ASC";
	$TransaksiClaim = mysql_query($query_TransaksiClaim, $Connection) or die(mysql_error());
	$row_TransaksiClaim = mysql_fetch_assoc($TransaksiClaim);
	$totalRows_TransaksiClaim = mysql_num_rows($TransaksiClaim);
	?>
<!------------------------------------------------------->
<link rel="stylesheet" type="text/css" href="../../JQuery/Layout/layout.css">
<script type="text/javascript" src="../../JQuery/Layout/jquery.js"></script>
<script type="text/javascript" src="../../JQuery/Layout/jquery.ui.all.js"></script>
<script type="text/javascript" src="../../JQuery/Layout/jquery.layout.js"></script>
<script type='text/javascript'>
	var jq126 = jQuery.noConflict();
</script>
<script type="text/javascript">
	var myLayout;// a var is required because this page utilizes: myLayout.allowOverflow() method
	
	jq126(document).ready(function () {
	myLayout = jq126('body').layout({
		// enable showOverflow on west-pane so popups will overlap north pane
		west__showOverflowOnHover: true
	
	//,	west__fxSettings_open: { easing: "easeOutBounce", duration: 750 }
	});
	
	});
	
</script>
<!------------------------------------------------------->
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
<!------------------------------------------------------->
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>PT. BDN | Transaksi Claim</title>
		<link href="../../Button.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div class="ui-layout-north">
			<div class="title">
				TRANSAKSI CLAIM
			</div>
		</div>
		<div class="ui-layout-west">
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
						Barang</button>
					</td>
				</tr>
			</table>
		</div>
		<div class="ui-layout-south">
			<div class="footer">
				PT. Berlian Djaya Nusantara
			</div>
			<div class="copyright">
				Powered by Apera ERP
			</div>
		</div>
		<div class="ui-layout-east">
			<table class="menuTable">
				<tr>
					<td>
						<a href="InsertTransaksiClaim.php"><button class="button2" type=
							"button">Insert</button></a>
					</td>
				</tr>
			</table>
		</div>
		<div class="ui-layout-center">
			<table id="contentTable">
				<thead>
					<th class="noinvoice">No. Invoice</th>
					<th class="tanggal">Tgl Start</th>
					<th class="tanggal">Tgl End</th>
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
						<td><?php echo $row_TransaksiClaim['No']; ?></td>
						<td><?php echo $row_TransaksiClaim['TglStart']; ?></td>
						<td><?php echo $row_TransaksiClaim['TglEnd']; ?></td>
						<td><?php echo $row_TransaksiClaim['Customer']; ?></td>
						<td><?php echo $row_TransaksiClaim['JSC']; ?></td>
						<td><?php echo $row_TransaksiClaim['Project']; ?></td>
						<td><?php echo $row_TransaksiClaim['Amount']; ?></td>
						<td><?php echo $row_TransaksiClaim['Status']; ?></td>
						<td><a href="ViewTransaksiClaim.php?Id=<?php echo $row_TransaksiClaim['Id']; ?>"><button type="button" class="button3">View</button></a>-<a href="DeleteTransaksiClaim.php?Id=<?php echo $row_TransaksiClaim['Id']; ?>"><button type="button" class="button3">Delete</button></a></td>
						</td>
					</tr>
					<?php } while ($row_TransaksiClaim = mysql_fetch_assoc($TransaksiClaim)); ?>
	</body>
	</table>
	</div>
	</body>
</html>
<?php
	mysql_free_result($TransaksiClaim);
	?>