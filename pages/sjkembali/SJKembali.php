<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

?>

<?php
$PAGE="SJ Kembali";
$top_menu_sel="menu_sjkembali";
include_once($ROOT . 'pages/html_header.php');
include_once($ROOT . 'pages/html_main_header.php');
?>
  
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Surat Jalan Kembali
		</h1>
		<ol class="breadcrumb">
			<li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
			<li class="active">SJ Kembali</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-body">
						<table id="tb_sjkembali" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Qterima</th>
									<th>SJ Code</th>
									<th>Tgl Tertanda</th>
									<th>Customer</th>
									<th>Project</th>
									<th>View</th>
									<th>Delete</th>
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
<!-- page script -->
<script>
$(document).ready(function () {
	var table = $("#tb_sjkembali").DataTable({
	"paging": false,
	"processing": true,
	"serverSide": true,
	"sAjaxSource": "sjkembali_table.php",
	"order": [[1, "desc"]],
	"columnDefs": [{
				"targets": 6,
				"data": null,
				"render": function ( data, type, row ) {
							if (data[0] > 0) {
								return "<button class='btn btn-block btn-default btn-sm' disabled>Delete</button>";
							} else {
								return "<button class='btn btn-block btn-danger btn-sm'>Delete</button>";
							}
						}
				},
				{
					"targets": 5,
					"data": null,
					"defaultContent": "<button1 class='btn btn-block btn-primary btn-sm'>View</button1>"
				},
				{
					"targets": 0,
					"visible": false
				}]
	});
	$('#tb_sjkembali_example1 tbody').on( 'click', 'button1', function () {
		var data = table.row( $(this).parents('tr') ).data();
		window.open("ViewSJKembali.php?SJKem=" + data[1], "_self");
	});
	$('#tb_sjkembali tbody').on( 'click', 'button', function () {
		var data = table.row( $(this).parents('tr') ).data();
		window.open("DeleteSJKembali.php?SJKem=" + data[1], "_self");
	});

});
</script>