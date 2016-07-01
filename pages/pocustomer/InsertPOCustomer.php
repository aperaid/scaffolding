<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO pocustomer (Reference, Tgl, PCode) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['tx_insertpocustomer_Reference'], "text"),
                       GetSQLValueString($_POST['tx_insertpocustomer_Tgl'], "text"),
					   GetSQLValueString($_POST['tx_insertpocustomer_PCode'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
  
  $insertGoTo = "POCustomer.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_Connection, $Connection);
$query_Reference = "SELECT Id FROM pocustomer ORDER BY Id DESC";
$Reference = mysql_query($query_Reference, $Connection) or die(mysql_error());
$row_Reference = mysql_fetch_assoc($Reference);
$totalRows_Reference = mysql_num_rows($Reference);
?>


<?php
$PAGE="Insert PO";
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
			<small>Insert</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
			<li><a href="../POCustomer/POCustomer.php">Purchase Order</a></li>
			<li class="active">Insert PO</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<!-- general form elements -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">PO Detail</h3>
					</div>
					<!-- /.box-header -->
					
					<!-- form start -->
					<form action="<?php echo $editFormAction; ?>" id="fm_insertpocustomer_form1" name="fm_insertpocustomer_form1" method="POST">
						<div class="box-body">
							<div class="form-group">
								<label>Reference</label>
								<input name="tx_insertpocustomer_Reference" type="text" class="form-control" id="tx_insertpocustomer_Reference" onKeyUp="capital()" value="<?php echo str_pad($row_Reference['Id']+1, 5, "0", STR_PAD_LEFT); ?>/<?php echo date("dmy") ?>" readonly>
							</div>
							<div class="form-group">
								<label>Date</label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input name="tx_insertpocustomer_Tgl" type="text" class="form-control pull-right date" id="tx_insertpocustomer_Tgl" autocomplete="off" required>
								</div>
							</div>
							<div class="form-group">
								<label>Project Code</label>
								<input name="tx_insertpocustomer_PCode" type="text" class="form-control" id="tx_insertpocustomer_PCode" autocomplete="off" onKeyUp="capital()" placeholder="ABC01" maxlength="5" required>
								<p class="help-block">Enter the beginning of the Project Code, then pick from the dropdown</p>
							</div>
						</div>
						<!-- /.box-body -->
						
						<div class="box-footer">
							<a href="POCustomer.php"><button type="button" class="btn btn-default pull-left">Cancel</button></a> 
							<button type="submit" id="bt_insertpocustomer_submit" class="btn btn-primary pull-right">Insert</button>
						</div>
						<input type="hidden" name="MM_insert" value="form1">
					</form>
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
  $('#tx_insertpocustomer_Tgl').datepicker({
	  format: "dd/mm/yyyy",
	  orientation: "bottom left",
	  todayHighlight: true,
	  autoclose: true,
	  startDate: '-7d',
	  endDate: '+7d'
  }); 
</script>
<script>
$(function() {
  var availableTags = <?php include ("../autocomplete.php");?>;
  $( "#tx_insertpocustomer_PCode" ).autocomplete({
	source: availableTags,
	autoFocus: true
  });
});
</script>
<script>
function capital() {
	var x = document.getElementById("tx_insertpocustomer_PCode");
    x.value = x.value.toUpperCase();
}
</script>

<?php
  mysql_free_result($Reference);
?>