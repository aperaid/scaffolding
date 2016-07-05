<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

$colname_View = "-1";
if (isset($_GET['Reference'])) {
  $colname_View = $_GET['Reference'];
  $colname_SJKir = $_GET['SJKir'];
}

mysql_select_db($database_Connection, $Connection);
$query_IsiSJKir = sprintf("SELECT IsiSJKir FROM isisjkirim WHERE SJKir = %s", GetSQLValueString($colname_SJKir, "text"));
$IsiSJKir = mysql_query($query_IsiSJKir, $Connection) or die(mysql_error());
$row_IsiSJKir = mysql_fetch_assoc($IsiSJKir);
$totalRows_IsiSJKir = mysql_num_rows($IsiSJKir);

$IsiSJKir2 = $row_IsiSJKir['IsiSJKir'];

mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT periode.S, transaksi.Id, transaksi.Barang, customer.Company, project.Project, transaksi.Quantity, transaksi.Amount FROM periode LEFT JOIN isisjkirim ON periode.IsiSJKir=isisjkirim.IsiSJKir LEFT JOIN sjkirim ON isisjkirim.SJKir=sjkirim.SJKir LEFT JOIN transaksi ON sjkirim.Reference=transaksi.Reference LEFT JOIN pocustomer ON transaksi.Reference=pocustomer.Reference LEFT JOIN project ON pocustomer.PCode = project.PCode LEFT JOIN customer ON project.CCode = customer.CCode WHERE periode.Deletes = 'Jual' AND transaksi.JS = 'Jual' AND periode.IsiSJKir IN ($IsiSJKir2)");
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);
?>

<?php
$PAGE="Transaksi Jual";
$top_menu_sel="menu_jual";
include_once($ROOT . 'pages/html_header.php');
include_once($ROOT . 'pages/html_main_header.php');
?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Transaksi Jual
        <small>View</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="../transaksisewa/TransaksiSewa.php">Transaksi Jual</a></li>
        <li class="active">View Transaksi Jual</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-body">
              <table id="tb_viewtransaksijual_example1" class="table table-bordered table-striped table-responsive">
                <thead>
                <tr>
                  <th>Barang</th>
                  <th>Tanggal Jual</th>
                  <th>Company</th>
                  <th>Project</th>
                  <th>Quantity</th>
                  <th>Price</th>
                </tr>
                </thead>
				<tbody>
					<?php do { ?>
					<tr>
                    	<td><?php echo $row_View['Barang']; ?></td>
                        <td><?php echo $row_View['S']; ?></td>
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
                  <a href="../transaksisewa/TransaksiSewa.php"><button type="button" class="btn btn-default pull-left">Back</button></a>
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

<!-- page script -->
<script>
  $(function () {
    $("#tb_viewtransaksijual_example1").DataTable();
  });
</script>
<?php
  mysql_free_result($View);
?>