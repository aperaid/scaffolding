<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

$colname_View = "-1";
if (isset($_GET['Reference'])) {
  $colname_View = $_GET['Reference'];
}

$colname_View2 = "-1";
if (isset($_GET['Periode'])) {
  $colname_View2 = $_GET['Periode'];
}

$colname_View3 = "-1";
if (isset($_GET['SJKir'])) {
  $colname_View3 = $_GET['SJKir'];
}
$colname_View4 = "-1";
if (isset($_GET['Deletes'])) {
  $colname_View4 = $_GET['Deletes'];
}
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT periode.Id, periode.Periode, periode.IsiSJKir, transaksi.Barang, periode.S, periode.E, periode.Deletes, customer.Company, project.Project, SUM(periode.Quantity) AS Quantity, transaksi.Amount FROM periode LEFT JOIN isisjkirim ON periode.IsiSJKir=isisjkirim.IsiSJKir LEFT JOIN transaksi ON isisjkirim.Purchase=transaksi.Purchase LEFT JOIN pocustomer ON transaksi.Reference=pocustomer.Reference LEFT JOIN project ON pocustomer.PCode=project.PCode LEFT JOIN customer ON project.CCode=customer.CCode WHERE periode.Reference=%s AND periode.Periode=%s AND (periode.Deletes = 'Sewa' OR periode.Deletes = 'Extend' OR periode.Deletes='Kembali') GROUP BY periode.Purchase, periode.S, periode.Deletes ORDER BY periode.Id ASC", GetSQLValueString($colname_View, "text"),GetSQLValueString($colname_View2, "text"),GetSQLValueString($colname_View3, "text"),GetSQLValueString($colname_View4, "text"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);
?>

<?php
$PAGE="View Detail";
$top_menu_sel="menu_sewa";
include_once($ROOT . 'pages/html_header.php');
include_once($ROOT . 'pages/html_main_header.php');
?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Transaksi Sewa
        <small>View Detail</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="TransaksiSewa.php">Transaksi Sewa</a></li>
        <li class="active">View Transaksi Sewa Detail</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-body">
              <table id="tb_viewtransaksisewa2_example1" class="table table-bordered table-striped table-responsive">
                <thead>
                <tr>
                  <th>Periode</th>
                  <th>Barang</th>
                  <th>Start</th>
                  <th>End</th>
                  <th>Company</th>
                  <th>Project</th>
                  <th>Quantity</th>
                  <th>Price</th>
                </tr>
                </thead>
				<tbody>
					<?php do { ?>
					<tr>
						<td><?php echo $row_View['Periode']; ?></td>
                        <td><?php echo $row_View['Barang']; ?></td>
						<td><?php echo $row_View['S']; ?></td>
						<td><?php echo $row_View['E']; ?></td>
						<td><?php echo $row_View['Company']; ?></td>
						<td><?php echo $row_View['Project']; ?></td>
            <td><?php echo $row_View['Quantity']; ?></td>
            <td>Rp <?php echo number_format($row_View['Amount'], 2,',', '.'); ?></td>
					</tr>
					<?php } while ($row_View = mysql_fetch_assoc($View)); ?>
				</tbody>
              </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                  <a href="TransaksiSewa.php"><button type="button" class="btn btn-default pull-left">Back</button></a>
			</div>
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

<?php
mysql_free_result($View);
	?>