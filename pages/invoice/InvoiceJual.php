<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");
	
mysql_select_db($database_Connection, $Connection);
$query_Invoice = "SELECT invoice.*, project.Project, customer.Company FROM invoice INNER JOIN pocustomer ON invoice.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode INNER JOIN customer ON project.CCode=customer.CCode WHERE JSC = 'Jual' AND EXISTS (SELECT periode.Reference FROM periode WHERE invoice.Reference = periode.Reference AND Deletes='Jual') GROUP BY invoice.Reference, invoice.Periode";
$Invoice = mysql_query($query_Invoice, $Connection) or die(mysql_error());
$row_Invoice = mysql_fetch_assoc($Invoice);
$totalRows_Invoice = mysql_num_rows($Invoice);
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
        <small>All</small>
        <large><a href="InvoiceJual.php"><button id="bt_invoicejual_invoicejual" name="bt_invoicejual_invoicejual" type="button" class="btn btn-success btn-sm" style="margin-right: 5px;">Invoice Jual</button></a><a href="Invoice.php"><button id="bt_invoicejual_invoicesewa" name="bt_invoicejual_invoicesewa" type="button" class="btn btn-success btn-sm" style="margin-right: 5px;">Invoice Sewa</button></a><a href="InvoiceClaim.php"><button id="bt_invoicejual_invoiceclaim" name="bt_invoicejual_invoiceclaim" type="button" class="btn btn-success btn-sm">Invoice Claim</button></a></large>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">Invoice Jual</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-body">
              <table id="tb_invoicejual_example1" name="tb_invoicejual_example1" class="table table-bordered table-striped table-responsive">
              <thead>
					<tr>
					<th>No. Invoice</th>
					<th>Project</th>
					<th>J/S/C</th>
					<th>Perusahaan</th>
					<th>Total</th>
					<th>Tanggal Invoice</th>
					<th>View</th>
				</thead>
				<tbody>
					<?php do { ?>
					<tr>
						<td><?php echo $row_Invoice['Invoice']; ?></td>
						<td><?php echo $row_Invoice['Project']; ?></td>
						<td><?php echo $row_Invoice['JSC']; ?></td>
						<td><?php echo $row_Invoice['Company']; ?></td>
						<td>&nbsp;</td>
						<td><?php echo $row_Invoice['Tgl']; ?></td>
					  <td align="center"><a href="ViewInvoiceJual.php?Reference=<?php echo $row_Invoice['Reference']; ?>&JS=<?php echo $row_Invoice['JSC']; ?>&Invoice=<?php echo $row_Invoice['Invoice']; ?>">
					  <button type="button" class="btn btn-block btn-primary btn-sm">View</button></a></td>
					</tr>
					<?php } while ($row_Invoice = mysql_fetch_assoc($Invoice)); ?>
				</tbody>
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
</div>

<!-- Footer Wrapper -->
<?php include_once($ROOT . 'pages/footer.php'); ?>
<!-- /.footer-wrapper -->

<script>
  $(function () {
    $("#tb_invoicejual_example1").DataTable();
  });
</script>

<?php
  mysql_free_result($Invoice);
?>