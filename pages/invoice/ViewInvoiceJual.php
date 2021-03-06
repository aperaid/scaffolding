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
$query_View = sprintf("SELECT invoice.*, project.Project, customer.Company FROM invoice INNER JOIN pocustomer ON invoice.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode INNER JOIN customer ON project.CCode=customer.CCode WHERE invoice.Reference = %s AND invoice.Invoice = %s", GetSQLValueString($colname_View, "text"), GetSQLValueString($colname_ViewInvoice, "text"));
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
}

// Get product list table
mysql_select_db($database_Connection, $Connection);
$query_View2 = sprintf("SELECT isisjkirim.QKirim, sjkirim.SJKir, transaksi.Purchase, transaksi.Barang, sjkirim.Tgl AS S, transaksi.Amount, transaksi.POCode, po.Transport FROM transaksi LEFT JOIN po ON transaksi.POCode=po.POCode RIGHT JOIN isisjkirim ON isisjkirim.Purchase = transaksi.Purchase LEFT JOIN sjkirim ON sjkirim.SJKir = isisjkirim.SJKir WHERE transaksi.Reference = %s AND transaksi.JS = %s", GetSQLValueString($colname_View2, "text"),
GetSQLValueString($colname_ViewJS, "text"));
$View2 = mysql_query($query_View2, $Connection) or die(mysql_error());
$row_View2 = mysql_fetch_assoc($View2);

// Get POCode
mysql_select_db($database_Connection, $Connection);
$query_pocode = sprintf("SELECT DISTINCT transaksi.POCode
	FROM transaksi 
	LEFT JOIN po ON transaksi.POCode=po.POCode
	RIGHT JOIN isisjkirim ON isisjkirim.Purchase = transaksi.Purchase
	LEFT JOIN sjkirim ON sjkirim.SJKir = isisjkirim.SJKir
	WHERE transaksi.Reference = %s AND transaksi.JS = %s", 
	GetSQLValueString($colname_View2, "text"),
	GetSQLValueString($colname_ViewJS, "text"));
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
                       GetSQLValueString(str_replace(".","",substr($_POST['tx_viewinvoicejual_Transport'], 3)), "text"),
					   GetSQLValueString($_POST['hd_viewinvoicejual_POCode'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE invoice SET PPN=%s, Discount=%s, Catatan=%s WHERE Invoice=%s",
					   GetSQLValueString($_POST['tx_viewinvoicejual_PPN'], "int"),
					   GetSQLValueString(str_replace(".","",substr($_POST['tx_viewinvoicejual_Discount'], 3)), "float"),
					   GetSQLValueString($_POST['tx_viewinvoicejual_Catatan'], "text"),
					   GetSQLValueString($_POST['tx_viewinvoicejual_Invoice'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());

  $updateGoTo = "ViewInvoiceJual.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

?>
 
<?php
$PAGE="Invoice Jual";
$top_menu_sel="menu_invoice";
include_once($ROOT . 'pages/html_header.php');
include_once($ROOT . 'pages/html_main_header.php');
?>
  
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Invoice Jual
			<small>View</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
			<li class="active">View Invoice Jual</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
			<!-- Horizontal Form -->
				<div class="box box-info">
					<!-- box-header -->
					<div class="box-header with-border">
						<h3 class="box-title">Invoice Detail</h3>
					</div>
					<!-- /.box-header -->
					
					<!-- form start -->
					<form action="<?php echo $editFormAction; ?>" id="fm_viewinvoicejual_form1" name="fm_viewinvoicejual_form1" method="POST" class="form-horizontal">
						<div class="box-body with-border">
							<div class="col-md-9">
								<div class="form-group">
									<label class="col-sm-4 control-label">No. Invoice</label>
									<div class="col-sm-8">
										<input id="tx_viewinvoicejual_Invoice" name="tx_viewinvoicejual_Invoice" type="text" class="form-control" value="<?php echo $row_View['Invoice']; ?>"  readonly>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label">Project</label>
									<div class="col-sm-8">
										<input id="tx_viewinvoicejual_Project" name="tx_viewinvoicejual_Project" type="text" class="form-control" value="<?php echo $row_View['Project']; ?>"  readonly>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label">Company</label>
									<div class="col-sm-8">
										<input id="tx_viewinvoicejual_Company" name="tx_viewinvoicejual_Company" type="text" class="form-control" value="<?php echo $row_View['Company']; ?>"  readonly>
									</div>
								</div>
							</div>
							<div class="col-md-3">
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
							<table id="tb_viewinvoicejual_example1" name="tb_viewinvoicejual_example1" class="table table-bordered table-striped table-responsive">
								<thead>
									<tr>
										<th align="center">SJ Kirim</th>
										<th align="center">Item</th>
										<th>Quantity Kirim</th>
										<th>Price</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$total = 0;
									do { ?>
									<tr>
										<?php $Transport = $row_View2['Transport']; ?>
										<input name="hd_viewinvoicejual_POCode" type="hidden" id="hd_viewinvoicejual_POCode" value="<?php echo $row_View2['POCode']; ?>">
										<td><?php echo $row_View2['SJKir']; ?></td>
										<td><?php echo $row_View2['Barang']; ?></td>
										<td><?php echo $row_View2['QKirim']; ?></td>
										<td>Rp <?php echo number_format($row_View2['Amount'], 2, ',', '.'); ?></td>
										<?php $test = $row_View2['QKirim']* $row_View2['Amount']; $total += $test ?>
										<td>Rp <?php echo number_format($test, 2,',','.') ?></td>
									</tr>
									<?php } while ($row_View2 = mysql_fetch_assoc($View2)); ?>
								</tbody>
							</table>
							<div class="form-group">
								<label class="col-sm-2 control-label">Pajak 10%</label>
								<div class="col-sm-6">
									<input name="tx_viewinvoicejual_PPN" type="hidden" id="tx_viewinvoicejual_PPN" value="0">
									<input name="tx_viewinvoicejual_PPN" type="checkbox" id="tx_viewinvoicejual_PPN" value="1" <?php if ($row_View['PPN'] == 1){ ?> checked <?php } ?>>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Transport</label>
								<div class="col-sm-6">
									<input id="tx_viewinvoicejual_Transport" name="tx_viewinvoicejual_Transport" type="text" class="form-control" value="<?php echo 'Rp ', number_format($Transport,0,',','.'); ?>" onKeyUp="tot()">
									<input id="hd_viewinvoicejual_Transport2" name="hd_viewinvoicejual_Transport2" type="hidden" autocomplete="off" value="<?php echo $Transport; ?>">
								</div>
							</div>
							<!-- Discount Input -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Discount</label>
								<div class="col-sm-6">
									<input id="tx_viewinvoicejual_Discount" name="tx_viewinvoicejual_Discount" type="text" class="form-control" value="<?php echo 'Rp ', number_format($row_View['Discount'],0,',','.'); ?>" onKeyUp="tot()" >
								</div>
							</div>
							<!-- Catatan Input -->
							<div class="form-group">
								<label class="col-sm-2  control-label" >Catatan</label>
								<div class="col-sm-6">
									<textarea name="tx_viewinvoicejual_Catatan" type="textarea" id="tx_viewinvoicejual_Catatan" class="form-control" autocomplete="off" placeholder="Catatan" rows=5><?php echo $row_View['Catatan']; ?></textarea>
								</div>
							</div>
							<!-- Total Text -->
							<div class="form-group">
								<label class="col-sm-2 control-label">Total</label>
								<div class="col-sm-6">
									<input name="tx_viewinvoicejual_Totals" type="text" class="form-control" id="tx_viewinvoicejual_Totals" value="Rp <?php echo number_format(($total*$row_View['PPN']*0.1)+$total+$Transport-$row_View['Discount'], 2,',','.'); ?>" readonly>
									<input name="hd_viewinvoicejual_Totals2" type="hidden" id="hd_viewinvoicejual_Totals2" value="<?php echo round($total, 2); ?>" >
								</div>
							</div>
							<div class="box-footer">
								<button type="submit" name="bt_viewinvoicejual_submit" id="bt_viewinvoicejual_submit" class="btn btn-info pull-right">Update</button>
								<div class="btn-group">
									<a href="Invoice.php"><button type="button" class="btn btn-default pull-left">Back</button></a>
								</div>
								<div class="btn-group" >
									<a href="#" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
								</div>
							</div>
							<input type="hidden" name="MM_update" value="form1">
							<!-- /.box-footer -->
						</div>
						<!-- /.box-body -->
					</form>
					<!-- /.form -->
				</div>
				<!-- /.box -->
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

<script language="javascript">
  function tot() {
    var txtFirstNumberValue = document.getElementById('hd_viewinvoicejual_Totals2').value;
    var txtSecondNumberValue = document.getElementById('tx_viewinvoicejual_PPN').value;
	var txtThirdNumberValue = document.getElementById('tx_viewinvoicejual_Transport').value;
	var txtFourthNumberValue = document.getElementById('tx_viewinvoicejual_Discount').value;
	var result = (parseFloat(txtFirstNumberValue) * parseFloat(txtSecondNumberValue)*0.1)+parseFloat(txtFirstNumberValue) + parseFloat(txtThirdNumberValue) - parseFloat(txtFourthNumberValue);
	  if (!isNaN(result)) {
		document.getElementById('tx_viewinvoicejual_Totals').value = result;
      }
   }
   
$(document).ready(function(){
	//Mask Transport
	$("#tx_viewinvoicejual_Transport").maskMoney({prefix:'Rp ', allowZero: true, allowNegative: false, thousands:'.', decimal:',', affixesStay: true, precision: 0});
	$("#tx_viewinvoicejual_Discount").maskMoney({prefix:'Rp ', allowZero: true, allowNegative: false, thousands:'.', decimal:',', affixesStay: true, precision: 0});
});
</script>

<?php
  mysql_free_result($View);
  mysql_free_result($View2);
?>
