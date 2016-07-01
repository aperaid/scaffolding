<?php require_once('../../connections/Connection.php'); ?>
<?php
	
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

mysql_select_db($database_Connection, $Connection);
$query_Customer = "SELECT * FROM customer ORDER BY Id ASC";
$Customer = mysql_query($query_Customer, $Connection) or die(mysql_error());
$row_Customer = mysql_fetch_assoc($Customer);
$totalRows_Customer = mysql_num_rows($Customer);
?>

<?php
$PAGE="Customer";
$top_menu_sel="menu_customer";
include_once($ROOT . 'pages/html_header.php');
include_once($ROOT . 'pages/html_main_header.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Customer
			<small>All</small>
			<large><a href="InsertCustomer.php"><button id="bt_customer_insert" name="bt_customer_insert" type="button" class="btn btn-success btn-sm">New Customer</button></a></large>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo $ROOT ?>pages/index.php"><i class="fa fa-dashboard"></i>Home</a></li>
			<li class="active">Customer</li>
		</ol>
	</section>
	
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-body">
						<table id="tb_customer2" name="tb_customer2" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>Cust ID</th>
									<th>Customer Code</th>
									<th>Company Name</th>
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
		
		var table = $("#tb_customer2").DataTable({
		"paging": false,
		"processing": true,
		"serverSide": true,
		"scrollY": "100%",
		"sAjaxSource": "customer_table.php",
		"columnDefs":[
			{
				"targets": [0],
				"visible": false,
				"searchable": false
			}
		],
		"order": [[0, "desc"]]
		});
		
		$('#tb_customer2 tbody').on('click', 'tr', function () {
			var data = table.row( this ).data();
			window.open("viewcustomer.php?Id="+ data[0],"_self");
		} );
		
	});
</script>

<?php
  mysql_free_result($Customer);
  mysql_free_result($User);
  mysql_free_result($Menu);
?>
