<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");
?>

<?php
$PAGE="Invoice Sewa";
$top_menu_sel="menu_invoice";
include_once($ROOT . 'pages/html_header.php');
include_once($ROOT . 'pages/html_main_header.php');
?>
  
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Invoice
		</h1>
		<ol class="breadcrumb">
			<li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
			<li class="active">Invoice</li>
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
							<table id="tb_invoicesewa" class="table table-bordered">
								<thead>
									<tr>
										<th>Reference</th>
										<th>No. Invoice</th>
										<th>Project</th>
										<th>Periode</th>
										<th>Perusahaan</th>
										<th>Tgl Invoice</th>
									</tr>
								</thead>
							</table>
						</div>
						<div class="tab-pane" id="jual_tab">
							<table id="tb_invoicejual" class="table table-bordered">
								<thead>
									<tr>
										<th>Reference</th>
										<th>No. Invoice</th>
										<th>Project</th>
										<th>Perusahaan</th>
										<th>Tgl Invoice</th>
									</tr>
								</thead>
							</table>
						</div>
						<div class="tab-pane" id="claim_tab">
							<table id="tb_invoiceclaim" class="table table-bordered">
								<thead>
									<tr>
										<th>Reference</th>
										<th>No. Invoice</th>
										<th>Project</th>
										<th>Perusahaan</th>
										<th>Tgl Invoice</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</section>
	<!-- /.content -->
</div>

<!-- Footer Wrapper -->
<?php include_once($ROOT . 'pages/footer.php'); ?>
<!-- /.footer-wrapper -->
<script>
$(document).ready(function () {

	// Invoice Sewa
	var table = $("#tb_invoicesewa").DataTable({
		"paging": false,
		"processing": true,
		"serverSide": true,
		"sAjaxSource": "invoicesewa_table.php",
		"columnDefs":[
			{
				"targets": [0],
				"visible": false
			}
		],
		"order": [[5, "desc"]]
	});
		
	$('#tb_invoicesewa tbody').on('click', 'tr', function () {
		var data = table.row( this ).data();
		window.open("viewinvoice.php?Reference="+ data[0] + "&Invoice=" + data[1] +"&JS=Sewa&Periode=" + data[3],"_self");
	} );


	// Invoice Jual
	var table2 = $("#tb_invoicejual").DataTable({
		"paging": false,
		"processing": true,
		"serverSide": true,
		"sAjaxSource": "invoicejual_table.php",
		"columnDefs":[
			{
				"targets": [0],
				"visible": false
			}
		],
		"order": [[4, "desc"]]
	});
		
	$('#tb_invoicejual tbody').on('click', 'tr', function () {
		var data2 = table2.row( this ).data();
		window.open("viewinvoicejual.php?Reference="+ data2[0] + "&JS=Jual&Invoice=" + data2[1],"_self");
	} );

	//Invoice Claim
	var table3 = $("#tb_invoiceclaim").DataTable({
		"paging": false,
		"processing": true,
		"serverSide": true,
		"sAjaxSource": "invoiceclaim_table.php",
		"columnDefs":[
			{
				"targets": [0],
				"visible": false
			}
		],
		"order": [[4, "desc"]]
	});
		
	$('#tb_invoiceclaim tbody').on('click', 'tr', function () {
		var data3 = table3.row( this ).data();
		window.open("viewinvoiceclaim.php?Reference="+ data3[0] + "&JS=Jual&Invoice=" + data3[1],"_self");
	} );
});
</script>