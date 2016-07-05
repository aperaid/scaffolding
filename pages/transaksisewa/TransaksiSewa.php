<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

// Transaksi Sewa
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("
  SELECT invoice.Invoice, periode.Id, periode.Periode, periode.S, periode.E, periode.IsiSJKir, periode.Reference, periode.Deletes, isisjkirim.SJKir, customer.Customer, project.Project, periode.Reference 
  FROM periode LEFT JOIN isisjkirim ON periode.IsiSJKir=isisjkirim.IsiSJKir 
  LEFT JOIN pocustomer ON periode.Reference=pocustomer.Reference 
  LEFT JOIN project ON pocustomer.PCode=project.PCode 
  LEFT JOIN customer ON project.CCode=customer.CCode 
  LEFT JOIN invoice ON invoice.Reference = pocustomer.Reference AND invoice.Periode = periode.Periode
  WHERE periode.Deletes = 'Sewa' OR periode.Deletes = 'Extend' 
  GROUP BY Invoice.Reference, Invoice.Periode");
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);

// Transaksi Jual
mysql_select_db($database_Connection, $Connection);
$query_TransaksiJual = "SELECT periode.S, isisjkirim.SJKir, pocustomer.Reference, project.Project, customer.Company, project.Project, customer.Customer FROM periode LEFT JOIN isisjkirim ON periode.IsiSJKir = isisjkirim.IsiSJKir LEFT JOIN sjkirim ON isisjkirim.SJKir=sjkirim.SJKir LEFT JOIN transaksi ON sjkirim.Reference=transaksi.Reference LEFT JOIN pocustomer ON transaksi.Reference=pocustomer.Reference LEFT JOIN project ON pocustomer.PCode=project.PCode LEFT JOIN customer ON project.CCode=customer.CCode WHERE periode.Deletes = 'Jual' GROUP BY sjkirim.SJKir";
$TransaksiJual = mysql_query($query_TransaksiJual, $Connection) or die(mysql_error());
$row_TransaksiJual = mysql_fetch_assoc($TransaksiJual);
$totalRows_TransaksiJual = mysql_num_rows($TransaksiJual);

// Transaksi Claim
mysql_select_db($database_Connection, $Connection);
$query_TransaksiClaim = "SELECT transaksiclaim.*, periode.Reference, transaksi.Barang, transaksi.QSisaKem, project.Project, customer.Customer FROM transaksiclaim LEFT JOIN periode ON transaksiclaim.Claim=periode.Claim INNER JOIN transaksi ON transaksiclaim.Purchase=transaksi.Purchase INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode LEFT JOIN customer ON project.CCode=customer.CCode GROUP BY transaksiclaim.Periode ORDER BY transaksiclaim.Id ASC";
$TransaksiClaim = mysql_query($query_TransaksiClaim, $Connection) or die(mysql_error());
$row_TransaksiClaim = mysql_fetch_assoc($TransaksiClaim);
$totalRows_TransaksiClaim = mysql_num_rows($TransaksiClaim);

/*$query_LastPeriode = sprintf("SELECT MAX(Periode) AS Periode FROM periode WHERE Reference = %s", GetSQLValueString($colname_View, "text"));
 $LastPeriode = mysql_query($query_LastPeriode, $Connection) or die(mysql_error());
 $row_LastPeriode = mysql_fetch_assoc($LastPeriode);
 $totalRows_LastPeriode = mysql_num_rows($LastPeriode);*/
?>

<?php
$PAGE="Transaksi Sewa";
$top_menu_sel="menu_sewa";
include_once($ROOT . 'pages/html_header.php');
include_once($ROOT . 'pages/html_main_header.php');
?>
  
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Transaksi
		</h1>
		<ol class="breadcrumb">
			<li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
			<li><a href="TransaksiSewa.php">Transaksi</a></li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#sewa_tab" data-toggle="tab">Sewa</a></li>
						<li><a href="#jual_tab" data-toggle="tab">Jual</a></li>
						<li><a href="#claim_tab" data-toggle="tab">Claim</a></li>
					</ul>
					<div class="tab-content">
						<div class="active tab-pane" id="sewa_tab">
							<table id="tb_viewtransaksisewa_example1" class="table table-bordered table-striped table-responsive">
								<thead>
									<tr>
										<th>Reference</th>
										<th>Periode</th>
										<th>Start</th>
										<th>End</th>
										<th>Customer</th>
										<th>Project</th>
										<th>View</th>
										<th>Extend</th>
									</tr>
								</thead>
								<tbody>
									<?php do { ?>
									<?php
									mysql_select_db($database_Connection, $Connection);
									$query_Periode = sprintf("SELECT MAX(Id) AS Id FROM periode WHERE Reference = %s AND IsiSJKir = %s AND (Deletes = 'Sewa' OR Deletes = 'Extend') GROUP BY IsiSJKir ORDER BY Id ASC", GetSQLValueString($row_View['Reference'], "text"), GetSQLValueString($row_View['IsiSJKir'], "text"));
									$Periode = mysql_query($query_Periode, $Connection) or die(mysql_error());
									$row_Periode = mysql_fetch_assoc($Periode);
									$totalRows_Periode = mysql_num_rows($Periode);

									$Id = $row_Periode['Id'];
									?>
									<tr>
										<td><?php echo $row_View['Reference']; ?></td>
										<td><?php echo $row_View['Periode']; ?></td>
										<td><?php echo $row_View['S']; ?></td>
										<td><?php echo $row_View['E']; ?></td>
										<td><?php echo $row_View['Customer']; ?></td>
										<td><?php echo $row_View['Project']; ?></td>
										<td><a href="../invoice/viewinvoice.php?Reference=<?php echo $row_View['Reference']; ?>&Invoice=<?php echo $row_View['Invoice'] ?>&JS=Sewa&Periode=<?php echo $row_View['Periode']; ?>"><button type="button" class="btn btn-block btn-primary btn-sm">View Invoice</button></a></td>
										 <td><a href="ExtendTransaksiSewa.php?Reference=<?php echo $row_View['Reference']; ?>&Periode=<?php echo $row_View['Periode']; ?>&SJKir=<?php echo $row_View['SJKir']; ?>" onclick="return confirm('Extend Sewa hanya boleh dilakukan di akhir periode dan sudah ada konfirmasi dari customer. Lanjutkan?')"><button type="button" name="bt_viewtransaksisewa_extend" id="bt_viewtransaksisewa_extend" <?php if (($row_Periode['Id'] != $row_View['Id'])){ ?> class="btn btn-block btn-default btn-sm" disabled <?php   } else { ?> class="btn btn-block btn-primary btn-sm" <?php } ?>>Extend</button></a></td>
									</tr>
									<?php } while ($row_View = mysql_fetch_assoc($View)); ?>
								</tbody>
							</table>
						</div>
						<div class="tab-pane" id="jual_tab">
							<table id="tb_transaksijual_example1" class="table table-bordered table-striped table-responsive">
								<thead>
									<tr>
										<th>Reference</th>
										<th>Tgl Kirim</th>
										<th>Project</th>
										<th>Customer</th>
										<th>View</th>
									</tr>
								</thead>
								<tbody>
									<?php do { ?>
									<tr>
										<td><?php echo $row_TransaksiJual['Reference']; ?></td>
										<td><?php echo $row_TransaksiJual['S']; ?></td>
										<td><?php echo $row_TransaksiJual['Project']; ?></td>
										<td><?php echo $row_TransaksiJual['Customer']; ?></td>
										<td><a href="../invoice/ViewInvoiceJual.php?Reference=#"><button type="button" class="btn btn-block btn-primary btn-sm">View Invoice</button></a></td>
										<td><a href="../transaksijual/ViewTransaksiJual.php?Reference=<?php echo $row_TransaksiJual['Reference'] ?>&SJKir=<?php echo $row_TransaksiJual['SJKir']; ?>"><button type="button" class="btn btn-block btn-primary btn-sm">View</button></a></td>
									</tr>
									<?php } while ($row_TransaksiJual = mysql_fetch_assoc($TransaksiJual)); ?>
								</tbody>
							</table>
						</div>
						<div class="tab-pane" id="claim_tab">
							<table id="tb_transaksiclaim_example1" name="tb_transaksiclaim_example1" class="table table-bordered table-striped table-responsive">
								<thead>
									<tr>
										<th>Reference</th>
										<th>Periode</th>
										<th>Tanggal Claim</th>
										<th>Project</th>
										<th>Customer</th>
										<th>View</th>
										<th>Delete</th>
									</tr>
								</thead>
								<tbody>
									<?php do { ?>
									<?php
										mysql_select_db($database_Connection, $Connection);
										$query_PerClaim = sprintf("SELECT MAX(periode.Periode) AS Periode FROM periode WHERE Deletes='Claim' AND Reference=%s AND Claim=%s AND Periode=%s", GetSQLValueString($row_TransaksiClaim['Reference'], "text"), GetSQLValueString($row_TransaksiClaim['Claim'], "text"), GetSQLValueString($row_TransaksiClaim['Periode'], "text"));
										$PerClaim = mysql_query($query_PerClaim, $Connection) or die(mysql_error());
										$row_PerClaim = mysql_fetch_assoc($PerClaim);
										$totalRows_PerClaim = mysql_num_rows($PerClaim);

										mysql_select_db($database_Connection, $Connection);
										$query_PerExtend = sprintf("SELECT MAX(periode.Periode) AS Periode FROM periode WHERE (Deletes='Extend' OR Deletes='Sewa') AND Reference=%s", GetSQLValueString($row_TransaksiClaim['Reference'], "text"));
										$PerExtend = mysql_query($query_PerExtend, $Connection) or die(mysql_error());
										$row_PerExtend = mysql_fetch_assoc($PerExtend);
										$totalRows_PerExtend = mysql_num_rows($PerExtend);

										$Claim = $row_PerClaim['Periode'];
										$Extend = $row_PerExtend['Periode'];
									?>
									<tr>
										<td><?php echo $row_TransaksiClaim['Reference']; ?></td>
										<td><?php echo $row_TransaksiClaim['Periode']; ?></td>
										<td><?php echo $row_TransaksiClaim['Tgl']; ?></td>
										<td><?php echo $row_TransaksiClaim['Project']; ?></td>
										<td><?php echo $row_TransaksiClaim['Customer']; ?></td>
										<td><a href="../transaksiclaim/ViewTransaksiClaim.php?Reference=<?php echo $row_TransaksiClaim['Reference']; ?>&Periode=<?php echo $row_TransaksiClaim['Periode']; ?>"><button type="button" class="btn btn-block btn-primary btn-sm">View</button></a></td>
										<td><a href="../transaksiclaim/DeleteTransaksiClaim.php?Reference=<?php echo $row_TransaksiClaim['Reference']; ?>&Periode=<?php echo $row_TransaksiClaim['Periode']; ?>" onclick="return confirm('Delete Claim Barang?')"><button type="button" <?php if ($Claim = $Extend) { ?> class="btn btn-block btn-sm btn-danger" <?php } else { ?> class="btn btn-block btn-sm btn-default" disabled <?php } ?>>Batal</button></a></td>
									</tr>
									<?php } while ($row_TransaksiClaim = mysql_fetch_assoc($TransaksiClaim)); ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!-- /.nav-tabs-custom -->
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
  
<!-- Footer Wrapper -->
<?php include_once($ROOT . 'pages/footer.php'); ?>
<!-- /.footer-wrapper -->

<!-- page script -->
<script>
	$(function () {
	$("#tb_viewtransaksisewa_example1").DataTable({
		"paging": false
	});
});
</script>

<?php
mysql_free_result($View);
?>