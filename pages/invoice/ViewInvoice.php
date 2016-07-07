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

$colname_View = "-1";
if (isset($_GET['Reference'])) {
  $colname_View = $_GET['Reference'];
  $colname_ViewInvoice = $_GET['Invoice'];
}
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("
	SELECT invoice.*, project.Project, customer.Company
	FROM invoice INNER JOIN pocustomer ON invoice.Reference=pocustomer.Reference
	INNER JOIN project ON pocustomer.PCode=project.PCode
	INNER JOIN customer ON project.CCode=customer.CCode
	WHERE invoice.Reference = %s AND invoice.Invoice = %s", GetSQLValueString($colname_View, "text"), GetSQLValueString($colname_ViewInvoice, "text"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);

$colname_Count = "-1";
if (isset($_GET['Reference'])) {
  $colname_Count = $_GET['Reference'];
  $colname_ViewJS = $_GET['JS'];
  $colname_ViewInvoice = $_GET['Invoice'];
}

$colname_View2 = "-1";
if (isset($_GET['JS'])) {
  $colname_View2 = $_GET['Reference'];
  $colname_ViewJS = $_GET['JS'];
  $colname_ViewPeriode = $_GET['Periode'];
}

//Get product detail table
mysql_select_db($database_Connection, $Connection);
$query_View2 = sprintf("
	SELECT sjkirim.SJKir, transaksi.Purchase, transaksi.Barang, periode.S, periode.E, SUM(periode.Quantity) AS Quantity, transaksi.Amount, transaksi.POCode, po.Transport,  periode.SJKem, periode.Deletes, periode.Periode
	FROM periode 
	LEFT JOIN isisjkirim ON periode.IsiSJKir=isisjkirim.IsiSJKir 
	LEFT JOIN transaksi ON isisjkirim.Purchase=transaksi.Purchase
	LEFT JOIN po ON transaksi.POCode=po.POCode
	LEFT JOIN sjkirim ON isisjkirim.SJKir=sjkirim.SJKir
	WHERE transaksi.Reference = %s AND transaksi.JS = %s AND periode.Periode = %s AND periode.Quantity != 0
	GROUP BY periode.Purchase, periode.S, periode.Deletes
	ORDER BY periode.Id ASC", GetSQLValueString($colname_View2, "text"),
GetSQLValueString($colname_ViewJS, "text"),
GetSQLValueString($colname_ViewPeriode, "text"));
$View2 = mysql_query($query_View2, $Connection) or die(mysql_error());
$row_View2 = mysql_fetch_assoc($View2);

// Get pocode
mysql_select_db($database_Connection, $Connection);
$query_pocode = sprintf("SELECT DISTINCT transaksi.POCode
	FROM periode 
	LEFT JOIN transaksi ON Periode.Purchase=Transaksi.Purchase
	WHERE transaksi.Reference = %s AND transaksi.JS = %s AND periode.Periode = %s AND periode.Quantity != 0
	GROUP BY periode.Purchase, periode.S, periode.Deletes
	ORDER BY periode.Id ASC", GetSQLValueString($colname_View2, "text"),
GetSQLValueString($colname_ViewJS, "text"),
GetSQLValueString($colname_ViewPeriode, "text"));
$pocode = mysql_query($query_pocode, $Connection) or die(mysql_error());
$row_pocode = mysql_fetch_assoc($pocode);

if (isset($_GET['totalRows_View2'])) {
  $totalRows_View2 = $_GET['totalRows_View2'];
} else {
  $all_View2 = mysql_query($query_View2);
  $totalRows_View2 = mysql_num_rows($all_View2);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE po SET Transport=%s WHERE POCode=%s",
                       GetSQLValueString(str_replace(".","",substr($_POST['tx_viewinvoice_Transport'], 3)), "text"),
					   GetSQLValueString($_POST['hd_viewinvoice_POCode'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE invoice SET PPN=%s, Discount=%s, Catatan=%s WHERE Invoice=%s",
					   GetSQLValueString($_POST['tx_viewinvoice_PPN'], "int"),
					   GetSQLValueString(str_replace(".","",substr($_POST['tx_viewinvoice_Discount'], 3)), "float"),
					   GetSQLValueString($_POST['tx_viewinvoice_Catatan'], "text"),
					   GetSQLValueString($_POST['tx_viewinvoice_Invoice'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
  
  // Redirect
  $updateGoTo = "ViewInvoice.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

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
			Invoice Sewa
			<small>View</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
			<li><a href="Invoice.php">Invoice Sewa</a></li>
			<li class="active">View Invoice Sewa</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<!-- Horizontal Form -->
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title">Invoice Detail</h3>
					</div>
					<!-- /.box-header -->
					<!-- form start -->
					<form action="<?php echo $editFormAction; ?>" id="fm_viewinvoice_form1" name="fm_viewinvoice_form1" method="POST" class="form-horizontal">
						<div class="box-body with-border">
							<div class="col-sm-9">
								<div class="form-group">
									<label class="col-sm-4 control-label">No. Invoice</label>
									<div class="col-sm-8">
										<input id="tx_viewinvoice_Invoice" name="tx_viewinvoice_Invoice" type="text" class="form-control" value="<?php echo $row_View['Invoice']; ?>"  readonly>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label">Project</label>
									<div class="col-sm-8">
										<input id="tx_viewinvoice_Project" name="tx_viewinvoice_Project" type="text" class="form-control" value="<?php echo $row_View['Project']; ?>"  readonly>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label">Company</label>
									<div class="col-sm-8">
										<input id="tx_viewinvoice_Company" name="tx_viewinvoice_Company" type="text" class="form-control" value="<?php echo $row_View['Company']; ?>"  readonly>
									</div>
								</div>
							</div>
							<div class="col-sm-3">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th>Nomor PO</th>
										</tr>
									</thead>
									<tbody>
										<?php do { ?>
										<tr>
											<td><?php echo $row_pocode['POCode']  ?></td>
										</tr>
										<?php } while ($row_pocode = mysql_fetch_assoc($pocode)); ?>	
									</tbody>
								</table>
							</div>
							<table id="tb_viewinvoice_example1" name="tb_viewinvoice_example1" class="table table-bordered table-striped table-responsive">
								<thead>
									<tr>
										<th align="center">SJ Kirim</th>
										<th align="center">SJ Kembali</th>
										<th align="center">Item</th>
										<th>S</th>
										<th>E</th>
										<th>S-E</th>
										<th>Periode</th>
										<th>I</th>
										<th>Quantity</th>
										<th>Price</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$total = 0;
									do { ?>

									<tr>
										<input name="hd_viewinvoice_POCode" type="hidden" id="hd_viewinvoice_POCode" value="<?php echo $row_View2['POCode']; ?>">
										<td><?php echo $row_View2['SJKir']; ?></td>
										<td><?php echo $row_View2['SJKem']; ?></td>
										<td><?php echo $row_View2['Barang']; ?></td>

										<?php 
										$start = $row_View2['S'];
										$end = $row_View2['E'];

										$start2 = str_replace('/', '-', $start);
										$end2 = str_replace('/', '-', $end);
										$start3 = strtotime($start2);
										$end3 = strtotime($end2);

										$SE = round((($end3 - $start3) / 86400),1)+1;

										$Days = str_replace('/', ',', $start);
										$M = substr($Days, 3, -5);
										$Y = substr($Days, 6);
										$Days2 = cal_days_in_month(CAL_GREGORIAN, $M, $Y);

										$PPN = $row_View['PPN'];
										$Transport = $row_View2['Transport'];
										?>

										<td><?php echo $row_View2['S']; ?></td>
										<td><?php echo $row_View2['E']; ?></td>
										<td><?php echo $SE; ?></td>
										<td><?php echo $Days2 ?></td>
										<td><?php echo round(((($end3 - $start3) / 86400)+1)/$Days2, 4) ?></td>
										<td><?php echo $row_View2['Quantity']; ?></td>
										<td>Rp <?php echo number_format($row_View2['Amount'], 2, ',', '.'); ?></td>
										<?php $total2 = ((($end3 - $start3) / 86400)+1)/$Days2*$row_View2['Quantity']* $row_View2['Amount']; $total += $total2 ?>
										<td>Rp <?php echo number_format($total2, 2, ',', '.'); ?></td>
									</tr>
									<?php } while ($row_View2 = mysql_fetch_assoc($View2)); ?>
								</tbody>
							</table>
							
							<!-- PPN checkbox -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Pajak 10%</label>
								<div class="col-sm-6">
									<input name="tx_viewinvoice_PPN" type="hidden" id="tx_viewinvoice_PPN" value="0">
									<input name="tx_viewinvoice_PPN" type="checkbox" id="tx_viewinvoice_PPN" value="1" <?php if ($PPN == 1){ ?> checked <?php } ?>>
								</div>
							</div>
							<!-- Transport Input -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Transport</label>
								<div class="col-sm-6">
									<input id="tx_viewinvoice_Transport" name="tx_viewinvoice_Transport" type="text" class="form-control" value="<?php if ($row_View['Periode'] == 1){ echo 'Rp ', number_format($Transport,0,',','.'); }?>" onKeyUp="tot()" <?php if($row_View['Periode'] > 1) { ?> disabled <?php } ?>>
								</div>
							</div>
							<!-- Discount Input -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Discount</label>
								<div class="col-sm-6">
									<input id="tx_viewinvoice_Discount" name="tx_viewinvoice_Discount" type="text" class="form-control" value="<?php echo 'Rp ', number_format($row_View['Discount'],0,',','.'); ?>" onKeyUp="tot()" value="" onKeyUp="tot()" >
								</div>
							</div>
							<!-- Catatan Input -->
							<div class="form-group">
								<label class="col-sm-2  control-label" >Catatan</label>
								<div class="col-sm-6">
									<textarea name="tx_viewinvoice_Catatan" type="textarea" id="tx_viewinvoice_Catatan" class="form-control" autocomplete="off" placeholder="Catatan" rows=5><?php echo $row_View['Catatan']; ?></textarea>
								</div>
							</div>
							<!-- Total Text -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Total</label>
								<div class="col-sm-6">
									<input id="tx_viewinvoice_Totals" name="tx_viewinvoice_Totals" type="text" class="form-control" value="Rp <?php if ($row_View['Periode'] == 1){$toss = $Transport; } 
									else $toss = 0;
									echo number_format(($total*$PPN*0.1)+$total+$toss-$row_View['Discount'], 2, ',','.');?>"  readonly>
									<input id="hd_viewinvoice_Totals2" name="hd_viewinvoice_Totals2" type="hidden" value="<?php echo round($total, 2); ?>" >
								</div>
							</div>
							<!-- Footer Box -->
							<div class="box-footer">
								<!-- Back Button -->	<a href="Invoice.php">	<button type="button" class="btn btn-default">Back</button></a>
								<!-- Print Button -->	<a href="#">			<button type="button" class="btn btn-default"><i class="fa fa-print"></i> Print</button></a>
								<!-- Submit Button -->							<button type="submit" name="tx_viewinvoice_submit" id="tx_viewinvoice_submit"  class="btn btn-info pull-right">Update</button>
							</div>
							<!-- /.box-footer -->
							<input type="hidden" name="MM_update" value="form1">
						</div>
						<!-- /.box-body -->
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
function tot() {
    var txtFirstNumberValue = document.getElementById('hd_viewinvoice_Totals2').value;
    var txtSecondNumberValue = document.getElementById('tx_viewinvoice_PPN').value;
	var txtThirdNumberValue = document.getElementById('tx_viewinvoice_Transport').value;
	var txtFourthNumberValue = document.getElementById('tx_viewinvoice_Discount').value;
	var result = (parseFloat(txtFirstNumberValue) * parseFloat(txtSecondNumberValue)*0.1)+parseFloat(txtFirstNumberValue) + parseFloat(txtThirdNumberValue) - parseFloat(txtFourthNumberValue);
	if (!isNaN(result)) {
		document.getElementById('tx_viewinvoice_Totals').value = result;
    }
}
$(document).ready(function(){
	//Mask Transport
	$("#tx_viewinvoice_Transport").maskMoney({prefix:'Rp ', allowZero: true, allowNegative: false, thousands:'.', decimal:',', affixesStay: true, precision: 0});
	$("#tx_viewinvoice_Discount").maskMoney({prefix:'Rp ', allowZero: true, allowNegative: false, thousands:'.', decimal:',', affixesStay: true, precision: 0});
});
</script>
<?php
  mysql_free_result($View);
  mysql_free_result($View2);
?>
