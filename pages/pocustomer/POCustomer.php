<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");
	
mysql_select_db($database_Connection, $Connection);
$query_POCustomer = "SELECT pocustomer.*, project.Project, customer.Company, sum(transaksi.Amount*transaksi.Quantity) AS Amount FROM pocustomer INNER JOIN project ON pocustomer.PCode=project.PCode INNER JOIN customer ON project.CCode=customer.CCode LEFT JOIN transaksi on pocustomer.Reference=transaksi.reference GROUP BY pocustomer.Reference";
$POCustomer = mysql_query($query_POCustomer, $Connection) or die(mysql_error());
$row_POCustomer = mysql_fetch_assoc($POCustomer);
$totalRows_POCustomer = mysql_num_rows($POCustomer);
?>


<?php
$PAGE="PO Customer";
$top_menu_sel="menu_po";
include_once($ROOT . 'pages/html_header.php');
include_once($ROOT . 'pages/html_main_header.php');
?>
  
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Purchase Order
			<small>All</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
			<li class="active">PO</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<a href="InsertPOCustomer.php"><button type="button" class="btn btn-success pull-left">New Reference</button></a>
					</div>
					<div class="box-body">
						<table id="tb_pocustomer" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>Reference</th>
									<th>Tgl</th>
									<th>Company</th>
									<th>Project</th>
									<th>Price</th>
								</tr>
							</thead>
						</table>
					</div>
				<!-- /.box-body -->
				</div>
				<!-- /.box -->
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
<script>
	$(document).ready(function () {
		
		// Set Table to Datatable
		var table = $("#tb_pocustomer").DataTable({
		"paging": false,
		"processing": true,
		"serverSide": true,
		"scrollY": "100%",
		"sAjaxSource": "ref_table.php",
		"columnDefs":[
			{
				"targets": [4],
				"render": $.fn.dataTable.render.number( '.', ',', 0, 'Rp ' )
			}
		],
		"order": [[1, "desc"]]
		});
		
		// Set when selected
		$('#tb_pocustomer tbody').on( 'click', 'tr', function () {
			var data = table.row( this ).data();
			window.open("ViewTransaksi.php?Reference="+ data[0],"_self");
		} );
		
		
	});
</script>

<?php
  mysql_free_result($POCustomer);
?>