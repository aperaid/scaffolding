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
	
	mysql_select_db($database_Connection, $Connection);
	$query_Menu = "SELECT * FROM menu";
	$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
	$row_Menu = mysql_fetch_assoc($Menu);
	$totalRows_Menu = mysql_num_rows($Menu);
	
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
				TRANSAKSI JUAL
			</div>
		</div>
		<div class="ui-layout-west">
			<table class="menuTable">
					
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
                    
			</table>
		</div>
		<div class="ui-layout-south">
			<div class="footer">PT. Berlian Djaya Nusantara</div>
			<div class="copyright">Powered by Apera ERP</div>
		</div>
		<div class="ui-layout-east">
			<table class="menuTable">
				<tr>
					<td><a href="InsertTransaksiJual.php"><button type="button" class="button2">Insert</button></a></td>
				</tr>
			</table>
		</div>
		<div class="ui-layout-center">
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
	</body>
</html>
<?php
	mysql_free_result($TransaksiJual);
	?>