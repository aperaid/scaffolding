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

// Ambil value dari URI
$colname_View2 = "-1";
if (isset($_GET['JS'])) {
  // Ambil Reference
  $colname_View2 = $_GET['Reference'];
  // Ambil Periode
  $colname_ViewPeriode = $_GET['Periode'];
}

// Ambil detail transaksi claim berdasarkan reference & periode
mysql_select_db($database_Connection, $Connection);
$query_View2 = sprintf("SELECT isisjkirim.SJKir, transaksiclaim.*, SUM(transaksiclaim.QClaim) QClaim2, transaksi.Reference, transaksi.Barang, transaksi.QSisaKem, project.Project, customer.* FROM transaksiclaim LEFT JOIN isisjkirim ON transaksiclaim.IsiSJKir=isisjkirim.IsiSJKir LEFT JOIN transaksi ON transaksiclaim.Purchase=transaksi.Purchase INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode INNER JOIN customer ON project.CCode=customer.CCode WHERE transaksi.Reference=%s AND transaksiclaim.Periode=%s GROUP BY transaksiclaim.Claim, transaksiclaim.Tgl, transaksiclaim.Claim ORDER BY transaksiclaim.Id ASC", GetSQLValueString($colname_View2, "text"), GetSQLValueString($colname_ViewPeriode, "text"));
$View2 = mysql_query($query_View2, $Connection) or die(mysql_error());
$row_View2 = mysql_fetch_assoc($View2);
$totalRows_View2 = mysql_num_rows($View2);

/*if (isset($_GET['totalRows_View2'])) {
  $totalRows_View2 = $_GET['totalRows_View2'];
} else {
  $all_View2 = mysql_query($query_View2);
  $totalRows_View2 = mysql_num_rows($all_View2);
}*/

for($i=0;$i<$totalRows_View2;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE transaksiclaim SET PPN=%s WHERE Claim=%s",
                       GetSQLValueString($_POST['tx_viewinvoiceclaim_PPN'], "int"),
					   GetSQLValueString($_POST['hd_viewinvoiceclaim_Claim'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
}

// Update Discount berdasarkan nomor invoice
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE invoice SET Discount=%s, Catatan=%s WHERE Invoice=%s",
					   GetSQLValueString(str_replace(".","",substr($_POST['tx_viewinvoiceclaim_Discount'], 3)), "float"),
					   GetSQLValueString($_POST['tx_viewinvoiceclaim_Catatan'], "text"),
					   GetSQLValueString($_POST['tx_viewinvoiceclaim_Invoice'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
  
  // Redirect
  $updateGoTo = "ViewInvoiceClaim.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
?>

<?php
$PAGE="Invoice Claim";
$top_menu_sel="menu_invoice";
include_once($ROOT . 'pages/html_header.php');
include_once($ROOT . 'pages/html_main_header.php');
?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Invoice Claim
        <small>View</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="InvoiceClaim.php">Invoice Claim</a></li>
        <li class="active">View Invoice Claim</li>
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
            <form action="<?php echo $editFormAction; ?>" id="fm_viewinvoiceclaim_form1" name="fm_viewinvoiceclaim_form1" method="POST" class="form-horizontal">

            <div class="box-body with-border">
                <div class="form-group">
                  <label class="col-sm-2 control-label">No. Invoice</label>
                  <div class="col-sm-6">
                    <input id="tx_viewinvoiceclaim_Invoice" name="tx_viewinvoiceclaim_Invoice" type="text" class="form-control" value="<?php echo $row_View['Invoice']; ?>"  readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Project</label>
                  <div class="col-sm-6">
                    <input id="tx_viewinvoiceclaim_Project" name="tx_viewinvoiceclaim_Project" type="text" class="form-control" value="<?php echo $row_View['Project']; ?>"  readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Company</label>
                  <div class="col-sm-6">
                    <input id="tx_viewinvoiceclaim_Company" name="tx_viewinvoiceclaim_Company" type="text" class="form-control" value="<?php echo $row_View['Company']; ?>"  readonly>
                  </div>
                </div>
            <div>

  <table id="tb_viewinvoicejual_example1" name="tb_viewinvoicejual_example1" class="table table-bordered table-striped table-responsive">

	<thead>
      <tr>
	    <th align="center">SJKir</th>
        <th align="center">Item</th>
        <th>Tgl Claim</th>
        <th>Quantity Claim</th>
        <th>Price</th>
        <th>Total</th>
        </tr>
      </thead>
      <tbody>
	  <?php 
	  $total = 0;
	  $PPN = $row_View2['PPN'];
	  do { ?>
      
        <tr>
		<input name="hd_viewinvoiceclaim_Claim[]" type="hidden" id="hd_viewinvoiceclaim_Claim" value="<?php echo $row_View2['Claim']; ?>">
          <td><?php echo $row_View2['SJKir']; ?></td>
          <td><?php echo $row_View2['Barang']; ?></td>
          <td><?php echo $row_View2['Tgl']; ?></td>
          <td><?php echo $row_View2['QClaim']; ?></td>
          <td>Rp <?php echo number_format($row_View2['Amount'], 2,',','.'); ?></td>
          <?php $test = $row_View2['QClaim']* $row_View2['Amount']; $total += $test ?>
          <td>Rp <?php echo number_format($test, 2,',','.') ?></td>
        </tr>
	  <?php } while ($row_View2 = mysql_fetch_assoc($View2)); ?>
    </tbody>
</table>
    </div>

    <div class="form-group">
                  <label class="col-sm-2 control-label">Pajak 10%</label>
                  <div class="col-sm-6">
                    <input name="tx_viewinvoiceclaim_PPN" type="hidden" id="tx_viewinvoiceclaim_PPN" value="0">
					<input name="tx_viewinvoiceclaim_PPN" type="checkbox" id="tx_viewinvoiceclaim_PPN" value="1" <?php if ($PPN == 1){ ?> checked <?php } ?>>
                  </div>
                </div>
				<!-- Discount Input -->
				<div class="form-group">
					<label class="col-sm-2 control-label">Discount</label>
					<div class="col-sm-6">
						<input id="tx_viewinvoiceclaim_Discount" name="tx_viewinvoiceclaim_Discount" type="text" class="form-control" autocomplete="off" value="<?php echo 'Rp ', number_format($row_View['Discount'],0,',','.'); ?>" onKeyUp="tot()" >
					</div>
                </div>
				<!-- Catatan Input -->
				<div class="form-group">
					<label class="col-sm-2  control-label" >Catatan</label>
					<div class="col-sm-6">
						<textarea name="tx_viewinvoiceclaim_Catatan" type="textarea" id="tx_viewinvoiceclaim_Catatan" class="form-control" autocomplete="off" placeholder="Catatan" rows=5><?php echo $row_View['Catatan']; ?></textarea>
					</div>
				</div>
				<!-- Total Text -->
                <div class="form-group">
                  <label class="col-sm-2 control-label">Total</label>
                  <div class="col-sm-6">
                    <input name="tx_viewinvoiceclaim_Totals" type="text" class="form-control" id="tx_viewinvoiceclaim_Totals" value="Rp <?php echo number_format(($total*$PPN*0.1)+$total-$row_View['Discount'], 2,',','.'); ?>" readonly>
                    <input name="hd_viewinvoiceclaim_Totals2" type="hidden" id="hd_viewinvoiceclaim_Totals2" value="<?php echo round($total, 2); ?>" >
                  </div>
                </div>
                
                <div class="box-footer">
                <button type="submit" name="bt_viewinvoiceclaim_submit" id="bt_viewinvoiceclaim_submit" class="btn btn-info pull-right">Update</button>
                <div class="btn-group"><a href="InvoiceClaim.php"><button type="button" class="btn btn-default pull-left">Back</button></a></div>
                <div class="btn-group" ><a href="#" class="btn btn-default"><i class="fa fa-print"></i> Print</a></div>
              </div>
              <input type="hidden" name="MM_update" value="form1">
<!-- /.box-footer -->
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
</div>

<!-- Footer Wrapper -->
<?php include_once($ROOT . 'pages/footer.php'); ?>
<!-- /.footer-wrapper -->

<script language="javascript">
  function tot() {
    var txtFirstNumberValue = document.getElementById('hd_viewinvoiceclaim_Totals2').value;
    var txtSecondNumberValue = document.getElementById('tx_viewinvoiceclaim_PPN').value;
	var txtThirdNumberValue = document.getElementById('tx_viewinvoicejual_Discount').value;
	var result = (parseFloat(txtFirstNumberValue) * parseFloat(txtSecondNumberValue)*0.1 - parseFloat(txtThirdNumberValue));
	  if (!isNaN(result)) {
		document.getElementById('tx_viewinvoiceclaim_Totals').value = result;
      }
   }
$(document).ready(function(){
	//Mask Transport
	$("#tx_viewinvoiceclaim_Discount").maskMoney({prefix:'Rp ', allowZero: true, allowNegative: false, thousands:'.', decimal:',', affixesStay: true, precision: 0});
});
</script>
<?php
  mysql_free_result($View);
  mysql_free_result($View2);
?>
