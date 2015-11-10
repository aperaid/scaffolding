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
	$query_TransaksiJual = "SELECT transaksi.*, project.Project, customer.Customer FROM transaksi INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode INNER JOIN customer ON project.CCode=customer.CCode WHERE JS = 'Jual'";
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

		<div class="ui-layout-center">
			<table id="contentTable" class="display">
				<thead>
					<tr>
					<th>No. Purchase</th>
					<th>Project</th>
					<th>J/S</th>
					<th>Nama Barang</th>
					<th>Quantity</th>
					<th>Amount</th>
					<th>Tanggal Jual</th>
					<th>Status</th>
					<th>Opsi</th>
				</thead>
				<tbody>
					<?php do { ?>
					<tr>
						<td><?php echo $row_TransaksiJual['Purchase']; ?></td>
						<td><?php echo $row_TransaksiJual['Project']; ?></td>
						<td><?php echo $row_TransaksiJual['JS']; ?></td>
						<td><?php echo $row_TransaksiJual['Barang']; ?></td>
						<td><?php echo $row_TransaksiJual['Quantity']; ?></td>
						<td><?php echo $row_TransaksiJual['Amount']; ?></td>
					  <td><?php echo $row_TransaksiJual['TglStart']; ?></td>
						<td align="center"><?php
							if ($row_TransaksiJual['Quantity'] == $row_TransaksiJual['QSisaKir']){
								echo 'O';
							}elseif ($row_TransaksiJual['QSisaKir'] == 0 && $row_TransaksiJual['QSisaKem'] == 0) {
								echo'F';
							}elseif ($row_TransaksiJual['Quantity'] |= $row_TransaksiJual['QSisaKir']){
								echo 'P';
							}
							?>
                        </td>
						<td><a href="ViewTransaksiJual.php?Id=<?php echo $row_TransaksiJual['Id']; ?>"><button type="button" class="button3">View</button>
						</a><a href="DeleteTransaksiJual.php?Id=<?php echo $row_TransaksiJual['Id']; ?>">
					  <button type="button" class="button3">Delete</button></a></td>
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