<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

// Transaksi Claim
mysql_select_db($database_Connection, $Connection);
$query_TransaksiClaim = "
	SELECT transaksiclaim.*, invoice.Invoice, periode.Reference, transaksi.Barang, transaksi.QSisaKem, project.Project, customer.Customer 
	FROM transaksiclaim 
	LEFT JOIN periode ON transaksiclaim.Claim=periode.Claim 
	INNER JOIN transaksi ON transaksiclaim.Purchase=transaksi.Purchase 
	INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference 
	INNER JOIN project ON pocustomer.PCode=project.PCode
	LEFT JOIN customer ON project.CCode=customer.CCode
	LEFT JOIN invoice ON transaksi.Reference=invoice.Reference AND invoice.Periode=transaksiclaim.Periode AND invoice.JSC='Claim'
	GROUP BY transaksiclaim.Periode
	ORDER BY transaksiclaim.Id ASC";
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

							<table id="tb_viewtransaksisewa" class="table table-bordered">
								<thead>
									<tr>
										<th>id</th>
										<th>maxid</th>
										<th>Reference</th>
										<th>Invoice</th>
										<th>Periode</th>
										<th>E</th>
										<th>Project</th>
										<th>View</th>
										<th>Extend</th>
									</tr>
								</thead>
							</table>

						</div>
						<div class="tab-pane" id="jual_tab">

							<table id="tb_viewtransaksijual" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>Reference</th>
										<th>Invoice</th>
										<th>Project</th>
										<th>Invoice</th>
									</tr>
								</thead>
							</table>

						</div>
						<div class="tab-pane" id="claim_tab">

							<table id="tb_viewtransaksiclaim" class="table table-bordered table-striped">
								<thead>
									<th>periodeclaim</th>
									<th>periodeextend</th>
									<th>Reference</th>
									<th>No. Invoice</th>
									<th>Periode</th>
									<th>Tanggal Claim</th>
									<th>Project</th>
									<th>View</th>
									<th>Batal Claim</th>
								</thead>
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
$(document).ready(function () {
	var table = $("#tb_viewtransaksisewa").DataTable({
	"paging": false,
	"processing": true,
	"serverSide": true,
	"sAjaxSource": "sewa_table.php",
	"order": [[2, "desc"]],
	"columnDefs": [{
				"targets": 8,
				"data": null,
				"render": function ( data, type, row ) {
							if (data[0] == data[1]) {
								return "<button class='btn btn-block btn-primary btn-sm'>Extend</button>";
							} else {
								return "<button class='btn btn-block btn-primary btn-sm' disabled>Extend</button>";
							}
						}
				},
				{
					"targets": 7,
					"data": null,
					"defaultContent": "<button1 class='btn btn-block btn-primary btn-sm'>Invoice</button1>"
				},
				{
					"targets" : 0,
					"visible" : false
				},
				{
					"targets" : 1,
					"visible" : false
				}]
	});
	
	$('#tb_viewtransaksisewa tbody').on( 'click', 'button1', function () {
		var data = table.row( $(this).parents('tr') ).data();
		window.open("../invoice/viewinvoice.php?Reference=" + data[2] + "&Invoice=" + data[3] + "&JS=Sewa&Periode=" + data[4], "_self");
	});

	$('#tb_viewtransaksisewa tbody').on( 'click', 'button', function () {
		var data = table.row( $(this).parents('tr') ).data();
		window.open("ExtendTransaksiSewa.php?Reference="+ data[2] +"&Periode=" + data[4] , "_self");
	});

	var table2 = $("#tb_viewtransaksijual").DataTable({
		"paging": false,
		"processing": true,
		"serverSide": true,
		"sAjaxSource": "jual_table.php",
		"columnDefs": [{
				"targets": 3,
				"defaultContent": "<button1 class='btn btn-block btn-primary btn-sm'>Invoice</button1>"
				}]
	});

	$('#tb_viewtransaksijual tbody').on( 'click', 'button1', function () {
		var data = table2.row( $(this).parents('tr') ).data();
		window.open("../invoice/ViewInvoiceJual.php?Reference=" + data[0] + "&JS=Jual&Invoice="+ data[1] , "_self");
	});

	var table3 = $("#tb_viewtransaksiclaim").DataTable({
		"paging" : false,
		"processing" : true,
		"serverSide" : true,
		"sAjaxSource" : "claim_table.php",
		"columnDefs": [{
			"targets": 0,
			"visible": false
		},{
			"targets": 1,
			"visible": false
		},
		{
			"targets": 8,
			"data": null,
			"render": function ( data, type, row ) {
							if (data[0] == data[1]) {
								return "<button class='btn btn-block btn-danger btn-sm'>Batal</button>";
							} else {
								return "<button class='btn btn-block btn-danger btn-sm' disabled>Batal</button>";
							}
						}
		},
		{
			"targets": 7,
			"defaultContent": "<button1 class='btn btn-block btn-primary btn-sm'>Invoice</button1>"
		}]
	});

	$('#tb_viewtransaksiclaim tbody').on( 'click', 'button1', function () {
		var data = table3.row( $(this).parents('tr') ).data();
		window.open("../invoice/viewinvoiceclaim.php?Reference="+data[0]+"&JS=Claim&Invoice=" + data[1] + "&Periode=" + data[2] , "_self");
	});

	$('#tb_viewtransaksiclaim tbody').on( 'click', 'button2', function () {
		var data = table3.row( $(this).parents('tr') ).data();
		window.open("../transaksiclaim/DeleteTransaksiClaim.php?Reference="+data[0]+"&Periode=" + data[2] , "_self");
	});

});
</script>
