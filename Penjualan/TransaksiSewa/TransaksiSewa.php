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
	$query_TransaksiSewa = "SELECT * FROM jualtransaksisewa ORDER BY Id ASC";
	$TransaksiSewa = mysql_query($query_TransaksiSewa, $Connection) or die(mysql_error());
	$row_TransaksiSewa = mysql_fetch_assoc($TransaksiSewa);
	$totalRows_TransaksiSewa = mysql_num_rows($TransaksiSewa);
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
	<title>PT. BDN | Transaksi Claim</title>
	<link href="../../Button.css" rel="stylesheet" type="text/css">
</head>
<body>
	<div class="layout">
		<div class="pageHeader">
			<div class="title">
				TRANSAKSI SEWA</div>
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
                    <table id="contentTable" class="mainTable">
                    	<thead>
                        	<th>No</th>
                            <th>TglStart</th>
                            <th>TglEnd</th>
                            <th>Customer</th>
                            <th>JSC</th>
                            <th>Project</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Opsi</th>
                        </thead>
                        <tbody>
                        	<?php do { ?>
                            <tr>
                                <td><?php echo $row_TransaksiSewa['No']; ?></td>
                                <td><?php echo $row_TransaksiSewa['TglStart']; ?></td>
                                <td><?php echo $row_TransaksiSewa['TglEnd']; ?></td>
                                <td><?php echo $row_TransaksiSewa['Customer']; ?></td>
                                <td><?php echo $row_TransaksiSewa['JSC']; ?></td>
                                <td><?php echo $row_TransaksiSewa['Project']; ?></td>
                                <td><?php echo $row_TransaksiSewa['Amount']; ?></td>
                                <td><?php echo $row_TransaksiSewa['Status']; ?></td>
                                <td><a href="ViewTransaksiSewa.php?Id=<?php echo $row_TransaksiSewa['Id']; ?>"><button type="button" class="button3">View</button></a>-<a href="DeleteTransaksiSewa.php?Id=<?php echo $row_TransaksiSewa['Id']; ?>"><button type="button" class="button3">Delete</button></a></td>
                            </tr>
                            <?php } while ($row_TransaksiSewa = mysql_fetch_assoc($TransaksiSewa)); ?>
                            
                        </tbody>
                    </table>
                </div>            	
            </div>
            <div class="pageRightMenu">
            	<table class="menuTable"> 
                	<tr><td><a href="InsertTransaksiSewa.php"><button type="button" class="button2">Insert</button></a></td></tr>
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
	mysql_free_result($TransaksiSewa);
	?>