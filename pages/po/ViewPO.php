<?php require_once('../../connections/Connection.php'); ?>
<?php date_default_timezone_set("Asia/Krasnoyarsk"); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

//Set Variable POCode
$pocode=$_GET['POCode'];

//Get the data for PO Detail Box
mysql_select_db($database_Connection, $Connection);
$query_po = sprintf("SELECT po.POCode AS pocode, po.Tgl AS tgl, po.Transport AS transport, po.Catatan AS catatan FROM po WHERE po.POCode='$pocode'");
$po = mysql_query($query_po, $Connection) or die(mysql_error());
$row_po = mysql_fetch_assoc($po);

//Get the data for Input PO Item
mysql_select_db($database_Connection, $Connection);
$query_poitem = sprintf("SELECT transaksi.Barang AS barang, transaksi.JS AS js, transaksi.Quantity AS quantity, transaksi.Amount AS price FROM transaksi WHERE transaksi.POCode='$pocode'");
$poitem = mysql_query($query_poitem, $Connection) or die(mysql_error());
$row_poitem = mysql_fetch_assoc($poitem);

//Get Reference for cancel button link
mysql_select_db($database_Connection, $Connection);
$query_reference = sprintf("SELECT DISTINCT transaksi.Reference AS reference FROM transaksi WHERE transaksi.POCode='$pocode'");
$reference = mysql_query($query_reference, $Connection) or die(mysql_error());
$row_reference = mysql_fetch_assoc($reference);

//Delete and Edit button check
mysql_select_db($database_Connection, $Connection);
$query_checkpo = sprintf("SELECT check_pobutton('$pocode') AS checkpo");
$checkpo = mysql_query($query_checkpo, $Connection) or die(mysql_error());
$row_checkpo = mysql_fetch_assoc($checkpo);

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
			Purchase Order
		</h1>
		<ol class="breadcrumb">
			<li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
			<li><a href="../POCustomer/POCustomer.php">Purchase Order</a></li>
			<li class="active">Insert PO Barang</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-body">
						<a href="../pocustomer/ViewTransaksi.php?Reference=<?php echo $row_reference['reference']?>"><button type="button" class="btn btn-default pull-left">Cancel</button></a>
						<a href="EditPO.php?POCode=<?php echo $pocode ?>"><button type="submit" id="bt_insertpocustomerbarang_submit" class="btn btn-primary pull-right" <?php if ($row_checkpo['checkpo']==1) { ?> disabled <?php } ?>>Edit</button></a>
						<a href="DeletePO.php?POCode=<?php echo $pocode ?>"><button type="button" id="bt_insertpocustomerbarang_submit" class="btn btn-danger pull-right" style="margin-right: 5px;" <?php if ($row_checkpo['checkpo']==1) { ?> disabled <?php } ?>>Delete</button></a>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-3">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">PO Detail</h3>
					</div>
					<div class="box-body">
						<div class="form-group">
							<label>Nomor PO</label>
							<input name="tx_viewpo_POCode" type="text" class="form-control" id="tx_viewpo_POCode" placeholder="Input PO Number" value="<?php echo $row_po['pocode']?>" readonly>
						</div>
						<div class="form-group">
							<label>Tanggal</label>
							<input name="tx_viewpo_Tgl" type="text" id="tx_viewpo_Tgl" class="form-control" autocomplete="off" placeholder="Date" value="<?php echo $row_po['tgl']?>" readonly>
						</div>
						<div class="form-group">
							<label>Transport</label>
							<input name="tx_viewpo_Transport" type="text" id="tx_viewpo_Transport" class="form-control" autocomplete="off" placeholder="0" value="<?php echo $row_po['transport']?>" readonly>
						</div>
						<div class="form-group">
							<label>Catatan</label>
							<textarea name="tx_viewpo_Catatan" type="textarea" id="tx_viewpo_Catatan" class="form-control" autocomplete="off" placeholder="Catatan" rows="5" readonly><?php echo $row_po['catatan']?></textarea>
						</div>
						<input name="hd_inputpocustomerbarang_Reference" type="hidden" id="hd_inputpocustomerbarang_Reference" value="">
					</div>
				</div>        
			</div>
			<div class="col-md-9">
				<!-- general form elements -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">PO Item</h3>
					</div>
					<div class="box-body">
						<table class="table table-hover table-bordered" id="tb_insertpocustomerbarang_customFields">
							<thead>
								<tr>
									<th>Barang</th>
									<th>J/S</th>
									<th>Quantity</th>
									<th>Price</th>
								</tr>
							</thead>
							<tbody>
								<?php do { ?>
								<tr>
									<td><?php echo $row_poitem['barang'] ?></td>
									<td><?php echo $row_poitem['js'] ?></td>
									<td><?php echo $row_poitem['quantity'] ?></td>
									<td><?php echo $row_poitem['price'] ?></td>
								</tr>
								<?php } while ($row_poitem = mysql_fetch_assoc($poitem)); ?>
							</tbody>
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.col -->
			</div>
			<!-- /.row -->
		</div>
		<!-- /.row -->
	</section>
	<!-- /.section -->
</div>
<!-- /.content-wrapper -->
  
<!-- Footer Wrapper -->
<?php include_once($ROOT . 'pages/footer.php'); ?>
<!-- /.footer-wrapper -->

<script>
    $(document).ready(function(){
		//Mask Transport
		$("#tx_viewpo_Transport").maskMoney({prefix:'Rp ', allowZero: true, allowNegative: false, thousands:'.', decimal:',', affixesStay: true, precision: 0});
		//Mask Price
		$("#tx_viewpo_Amount").maskMoney({prefix:'Rp ', allowZero: true, allowNegative: false, thousands:'.', decimal:',', affixesStay: true, precision: 0});
		
	});
</script>