<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

mysql_select_db($database_Connection, $Connection);
$query_Menu = "SELECT * FROM menu";
$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
$row_Menu = mysql_fetch_assoc($Menu);
$totalRows_Menu = mysql_num_rows($Menu);

$colname_Purchase = "-1";
if (isset($_GET['Reference'])) {
  $colname_Purchase = $_GET['Reference'];
}

//Company Details TAB
mysql_select_db($database_Connection, $Connection);
$query_detail = sprintf("SELECT project.*, customer.*, pocustomer.Tgl as tgl FROM project INNER JOIN customer ON project.CCode=customer.CCode INNER JOIN pocustomer ON pocustomer.PCode=project.PCode WHERE pocustomer.Reference = %s", GetSQLValueString($colname_Purchase, "text"));
$detail = mysql_query($query_detail, $Connection) or die(mysql_error());
$row_detail = mysql_fetch_assoc($detail);
$totalRows_detail = mysql_num_rows($detail);
//Company Details End

//Overview table Tab
mysql_select_db($database_Connection, $Connection);
$query_Purchase = sprintf("SELECT transaksi.JS AS js, transaksi.Barang AS barang, transaksi.Quantity AS quantity, transaksi.Amount AS price, transaksi.QSisaKir AS qsisakirim, transaksi.QSisaKem AS qsisakembali FROM transaksi INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference WHERE transaksi.Reference = %s ORDER BY transaksi.Id", GetSQLValueString($colname_Purchase, "text"));
$Purchase = mysql_query($query_Purchase, $Connection) or die(mysql_error());
$row_Purchase = mysql_fetch_assoc($Purchase);
$totalRows_Purchase = mysql_num_rows($Purchase);
//Overview table Tab End

$colname_View = "-1";
if (isset($_GET['Reference'])) {
  $colname_View = $_GET['Reference'];
}

//Untuk Ambil reference doang
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT Id, Reference FROM pocustomer WHERE Reference = %s", GetSQLValueString($colname_View, "text"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);
//Untuk ambil reference doang

//PO Tab
mysql_select_db($database_Connection, $Connection);
$query_po = sprintf("SELECT po.POCode AS pocode, po.Tgl AS tgl FROM po LEFT JOIN transaksi ON transaksi.POCode=po.POCode WHERE transaksi.Reference='$colname_View' GROUP BY po.POCode");
$view_po = mysql_query ($query_po, $Connection) or die(mysql_error());
$row_po = mysql_fetch_assoc($view_po);
// PO Tab

//SJKirim Tab
mysql_select_db($database_Connection, $Connection);
$query_sjkirim = sprintf("SELECT sjkirim.SJKir AS sjkirim_id, sjkirim.Tgl AS tgl, sum(isisjkirim.QTertanda) AS qtertanda FROM isisjkirim LEFT JOIN sjkirim ON isisjkirim.SJKir=sjkirim.SJKir WHERE sjkirim.Reference=%s GROUP BY sjkirim.SJKir", GetSQLValueString($colname_View, "text"));
$view_sjkirim = mysql_query ($query_sjkirim, $Connection) or die(mysql_error());
$row_sjkirim = mysql_fetch_assoc($view_sjkirim);
//SJKirim TAB End

//SJKembali Tab
mysql_select_db($database_Connection, $Connection);
$query_sjkembali = sprintf("SELECT sjkembali.SJKem AS sjkembali_id, sjkembali.Tgl AS tgl, sum(isisjkembali.QTerima) AS qterima FROM isisjkembali LEFT JOIN sjkembali ON isisjkembali.SJKem=sjkembali.SJKem WHERE sjkembali.Reference=%s GROUP BY sjkembali.SJKem", GetSQLValueString($colname_View, "text"));
$view_sjkembali = mysql_query ($query_sjkembali, $Connection) or die(mysql_error());
$row_sjkembali = mysql_fetch_assoc($view_sjkembali);
//SJKirim TAB End

//FUNCTION BUTTON DISABLE
mysql_select_db($database_Connection, $Connection);
$query_check = sprintf("SELECT check_POCustomer('$colname_View') AS result");
$check = mysql_query($query_check, $Connection) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);
//Function button disable end

//SJkirim buttton disabled
mysql_select_db($database_Connection, $Connection);
$query_sjkirimcheck = sprintf("SELECT check_sjkirimbutton('$colname_View') AS result");
$sjkirimcheck = mysql_query($query_sjkirimcheck, $Connection) or die(mysql_error());
$row_sjkirimcheck = mysql_fetch_assoc($sjkirimcheck);
//sjkirim button disabled end

//SJkembali buttton disabled
mysql_select_db($database_Connection, $Connection);
$query_sjkembalicheck = sprintf("SELECT check_sjkembalibutton('$colname_View') AS result");
$sjkembalicheck = mysql_query($query_sjkembalicheck, $Connection) or die(mysql_error());
$row_sjkembalicheck = mysql_fetch_assoc($sjkembalicheck);
//sjkembali button disabled end

?>


<?php
$PAGE="View PO";
$top_menu_sel="menu_po";
include_once($ROOT . 'pages/html_header.php');
include_once($ROOT . 'pages/html_main_header.php');
?>
  
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
		Referensi
		</h1>
		<ol class="breadcrumb">
			<li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
			<li><a href="../POCustomer/POCustomer.php">Reference</a></li>
			<li class="active">View Reference</li>
		</ol>
	</section>

	<!-- Content Body -->
	<section class="content">
		<div class="col-md-12">
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#overall_tab" data-toggle="tab">Overall</a></li>
					<li><a href="#po_tab" data-toggle="tab">PO</a></li>
					<li><a href="#sjkirim_tab" data-toggle="tab">SJKirim</a></li>
					<li><a href="#sjkembali_tab" data-toggle="tab">SJKembali</a></li>
					<li><a href="#claim_tab" data-toggle="tab">Claim</a></li>
					<li><a href="#invoice_tab" data-toggle="tab">Invoice</a></li>
				</ul>
				<div class="tab-content">
					<!-- OVERALL TAB -->
					<div class="active tab-pane" id="overall_tab">
						<div class="box-body">
							<!-- title row -->
							<div class="row">
								<div class="col-md-12">
									<h2 class="page-header">
										<i class="fa fa-globe"></i> PT. BDN | <?php echo $colname_View; ?>
										<small class="pull-right">Date: <?php echo $row_detail['tgl']; ?></small>
									</h2>
								</div>
							</div>
							<!-- info row -->
							<div class="row">
								<div class="col-sm-4">
									Company
									<address>
										<strong><?php echo $row_detail['Company']; ?></strong><br>
										<?php echo $row_detail['Alamat']; ?><br>
										<?php echo $row_detail['Kota']; ?>,  <?php echo $row_detail['Zip']; ?><br>
										Phone: <?php echo $row_detail['CompPhone']; ?><br>
										Email: <?php echo $row_detail['CompEmail']; ?>
									</address>
								</div>
								<div class="col-sm-4">
									Project
									<address>
										<strong><?php echo $row_detail['Project']; ?></strong><br>
										<?php echo $row_detail['Alamat']; ?><br>
										<?php echo $row_detail['Kota']; ?>,  <?php echo $row_detail['Zip']; ?><br>
									</address>
								</div>
								<div class="col-sm-4">
									Contact Person
									<address>
										<strong><?php echo $row_detail['Customer']; ?></strong><br>
										Phone: <?php echo $row_detail['CustPhone']; ?><br>
										Email: <?php echo $row_detail['CustEmail']; ?>
									</address>
								</div>
							</div>
							<!-- Table row -->
							<div class="row">
								<div class="col-md-12 table-responsive">
									<table class="table table-striped">
										<thead>
											<tr>
												<th>J/S</th>
												<th>Item Name</th>
												<th>Quantity</th>
												<th>Price</th>
												<th>Progress</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody>
											<?php do { ?>
											<tr>
												<td><?php echo $row_Purchase['js']; ?></td>
												<td><?php echo $row_Purchase['barang']; ?></td>
												<td><?php echo $row_Purchase['quantity']; ?></td>
												<td>Rp <?php echo number_format($row_Purchase['price'], 2,',', '.'); ?></td>
												<?php /* Kalau SEWA */ if ($row_Purchase['js'] == "Sewa") { ?>
													<?php /* belum dikirim */ if ($row_Purchase['qsisakirim'] == $row_Purchase['quantity'] && $row_Purchase['qsisakembali'] == 0){ ?>
														<td>
															<div class="progress progress-xs">
															  <div class="progress-bar progress-bar-red" style="width:10%"></div>
															</div>
														</td>
														<td><span class="badge bg-red">Belum Dikirim</span></td>

													<?php } /* setengah dikirim */ elseif (($row_Purchase['qsisakirim'] < $row_Purchase['quantity']) && $row_Purchase['qsisakirim'] != 0){ ?>
														<td>
															<div class="progress progress-xs">
															  <div class="progress-bar progress-bar-yellow" style="width:25%"></div>
															</div>
														</td>
														<td><span class="badge bg-yellow">Separuh Terkirim</span></td>

													<?php } /* pengiriman selesai, dalam proses penyewaan */elseif ($row_Purchase['qsisakirim'] == 0 && $row_Purchase['qsisakembali'] == $row_Purchase['quantity']){ ?>
														<td>
															<div class="progress progress-xs">
															  <div class="progress-bar progress-bar-blue" style="width:50%"></div>
															</div>
														</td>
														<td><span class="badge bg-blue">Pengiriman Selesai, dalam penyewaan</span></td>

													<?php } /* setengah dikembalikan */ elseif (($row_Purchase['qsisakembali'] < $row_Purchase['quantity']) && $row_Purchase['qsisakembali'] != 0){ ?>
														<td>
															<div class="progress progress-xs">
															  <div class="progress-bar progress-bar-yellow" style="width:75%"></div>
															</div>
														</td>
														<td><span class="badge bg-yellow">Separuh Kembali</span></td>

													<?php } /* selesai dikembalikan */ elseif ($row_Purchase['qsisakembali'] == 0 && $row_Purchase['qsisakirim'] == 0){ ?>
														<td>
															<div class="progress progress-xs">
															  <div class="progress-bar progress-bar-green" style="width:100%"></div>
															</div>
														</td>
														<td><span class="badge bg-green">Semua Kembali/Claimed, Transaksi Selesai</span></td>
													<?php } ?>
													
													<?php } /* kalau JUAL */ elseif($row_Purchase['js'] == "Jual") { ?>
														<?php /* belum dikirim */ if ($row_Purchase['qsisakirim'] == $row_Purchase['quantity']){ ?>
															<td>
																<div class="progress progress-xs">
																	<div class="progress-bar progress-bar-red" style="width:10%"></div>
																</div>
															</td>
															<td><span class="badge bg-red">Belum Dikirim</span></td>

														<?php } /* setengah dikirim */ elseif ($row_Purchase['qsisakirim'] < $row_Purchase['quantity'] && $row_Purchase['qsisakirim'] != 0){ ?>
															<td>
																<div class="progress progress-xs">
																  <div class="progress-bar progress-bar-yellow" style="width:50%"></div>
																</div>
															</td>
															<td><span class="badge bg-yellow">Separuh Terkirim</span></td>

														<?php } /* pengiriman selesai, dalam proses penyewaan */elseif ($row_Purchase['qsisakirim'] == 0){ ?>
															<td>
																<div class="progress progress-xs">
																	<div class="progress-bar progress-bar-green" style="width:100%"></div>
																</div>
															</td>
															<td><span class="badge bg-green">Selesai Dikirim, Penjualan Selesai</span></td>
														<?php } } ?>
											</tr>
											<?php } while ($row_Purchase = mysql_fetch_assoc($Purchase)); ?>
										</tbody>
									</table>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<a href="#" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
									<a href="POCustomer.php">																				<button 						type="button" 	class="btn btn-default pull-left" style="margin-right: 5px;">Back</button></a>
									<a href="../sjkirim/InsertSJKirim.php?Reference=<?php echo $row_View['Reference']; ?>">				<button id="SJKirim_button" 	type="button"  	style="margin-right: 5px;"	<?php if ($row_sjkirimcheck['result'] == 0)		{ ?>	class="btn btn-default pull-right" disabled	<?php } else {?>	class="btn btn-success pull-right"	<?php }?>	>SJ Kirim</button></a>
									<a href="../sjkembali/InsertSJKembali.php?Reference=<?php echo $row_View['Reference']; ?>">			<button id="SJKembali_button" 	type="button"  	style="margin-right: 5px;"	<?php if ($row_sjkembalicheck['result'] == 0) 	{ ?>	class="btn btn-default pull-right" disabled	<?php } else {?>	class="btn btn-warning pull-right"	<?php }?>	>SJ Kembali</button></a>
									<a href="../transaksiclaim/inserttransaksiclaim.php?Reference=<?php echo $row_View['Reference']; ?>">	<button id="claim_button" 		type="button"  	style="margin-right: 5px;"	<?php if ($row_sjkembalicheck['result'] == 0) 	{ ?>	class="btn btn-default pull-right" disabled	<?php } else {?>	class="btn btn-info pull-right"		<?php }?>	>Claim</button></a>
									<a href="EditPOCustomer.php?Id=<?php echo $row_View['Id']; ?>">							<button id="edit_button"		type="button"  	style="margin-right: 5px;"	<?php if ($row_check['result'] == 1) 			{ ?>	class="btn btn-default pull-right" disabled	<?php } else {?>	class="btn btn-primary pull-right"	<?php }?>	>Edit</button></a>
									<a href="DeletePOCustomer.php?Reference=<?php echo $row_View['Reference']; ?>">						<button id="delete_button" 		type="button"  	style="margin-right: 5px;"	<?php if ($row_check['result'] == 1) 			{ ?>	class="btn btn-default pull-right" disabled	<?php } else {?>	class="btn btn-danger pull-right"	<?php }?> onclick="return confirm('Delete PO Customer?')">Delete</button></a>
									<a href="InsertPOCustomerBarang.php?Reference=<?php echo $colname_View; ?>"><button id="insertPO_button" type="button" style="margin-right: 5px;" class="btn btn-success pull-right">Insert PO</button></a>
								</div>
							</div>
						</div>
					</div>
					
					<!-- PO TAB -->
					<div class="tab-pane" id="po_tab">
						<div class="box-body">
							<table id="tb_po" class="table table-condensed">
								<thead>
									<tr>
										<th>PO Code</th>
										<th>Tgl</th>
										<th width="10%">View</th>
									</tr>
								</thead>
								<tbody>
									<?php do { ?>
									<tr>
										<td><?php echo $row_po['pocode']; ?></td>
										<td><?php echo $row_po['tgl']; ?></td>
										<td><a href="../po/ViewPO.php?POCode=<?php echo $row_po['pocode']; ?>"><button class="btn btn-primary btn-sm">View</button></a></td>
									</tr>
									<?php } while ($row_po = mysql_fetch_assoc($view_po)); ?>
								</tbody>
							</table>
						</div>
					</div>
					
					<!-- SJKIRIM TAB -->
					<div class="tab-pane" id="sjkirim_tab">
						<div class="box-body">
							<table class="table table-condensed">
								<tr>
									<th>SJKir</th>
									<th>Tanggal</th>
									<th>Progress</th>
									<th>Status</th>
									<th>View</th>
								</tr>
								<?php do { ?>
								<tr>
									<td><?php echo $row_sjkirim['sjkirim_id']; ?></td>
									<td><?php echo $row_sjkirim['tgl']; ?></td>
									<?php if($row_sjkirim['qtertanda'] != 0 ){ ?>
										<td>
											<div class="progress progress-xs">
												<div class="progress-bar progress-bar-success" style="width: 100%"></div>
											</div>
										</td>                    
										<td><span class="badge bg-green">Selesai Dikirim</span></td>
									<?php }
									else { ?>
										<td>
											<div class="progress progress-xs">
											  <div class="progress-bar progress-bar-yellow" style="width: 50%"></div>
											</div>
										</td>                    
										<td><span class="badge bg-yellow">Dalam Pengiriman</span></td>
									<?php } ?>
									<td><a href="../sjkirim/ViewSJKirim.php?SJKir=<?php echo $row_sjkirim['sjkirim_id']; ?>"><button class="btn btn-primary">View</button></a></td>
								</tr>
								<?php } while ($row_sjkirim = mysql_fetch_assoc($view_sjkirim)); ?>
							</table>
						</div>
					</div>

					<!-- SJKEMBALI TAB-->
					<div class="tab-pane" id="sjkembali_tab">
						<div class="box-body">
							<table class="table table-condensed">
								<thead>
									<tr>
										<th>SJKem</th>
										<th>Tanggal</th>
										<th>Progress</th>
										<th>Status</th>
										<th>View</th>
									</tr>
								</thead>
								
								<?php do { ?>
								<tr>
									<td><?php echo $row_sjkembali['sjkembali_id']; ?></td>
									<td><?php echo $row_sjkembali['tgl']; ?></td>
									<?php if($row_sjkembali['qterima'] != 0 ){ ?>
										<td>
											<div class="progress progress-xs">
												<div class="progress-bar progress-bar-success" style="width: 100%"></div>
											</div>
										</td>                    
										<td><span class="badge bg-green">Selesai Dikembalikan</span></td>
									<?php }
									else { ?>
										<td>
											<div class="progress progress-xs">
												<div class="progress-bar progress-bar-yellow" style="width: 50%"></div>
											</div>
										</td>                    
										<td><span class="badge bg-yellow">Dalam Pengambilan</span></td>
									<?php } ?>
									<td><a href="../sjKembali/ViewSJKembali.php?SJKem=<?php echo $row_sjkembali['sjkembali_id']; ?>"><button class="btn btn-primary">View</button></a></td>
								</tr>
								<?php } while ($row_sjkembali = mysql_fetch_assoc($view_sjkembali)); ?>
							</table>
						</div>
					</div>
				
					<!-- Claim TAB -->
					<div class="tab-pane" id="claim_tab">
					</div>
					
					<!-- Invoice TAB -->
					<div class="tab-pane" id="invoice_tab">
					</div>
					
				</div>
				<!-- /.tab-content -->
			</div>
		</div>
	<div class="clearfix"></div>
	</section>
</div>
	
	<!-- /.content-wrapper -->
  
<!-- Footer Wrapper -->
<?php include_once($ROOT . 'pages/footer.php'); ?>
<!-- /.footer-wrapper -->

<?php
  mysql_free_result($detail);
  mysql_free_result($Purchase);
  mysql_free_result($View);
  mysql_free_result($view_sjkirim);
  mysql_free_result($view_sjkembali);
  mysql_free_result($check);
  mysql_free_result($sjkirimcheck);
  mysql_free_result($sjkembalicheck);
?>