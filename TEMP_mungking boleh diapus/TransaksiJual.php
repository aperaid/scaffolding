<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

mysql_select_db($database_Connection, $Connection);
$query_TransaksiJual = "SELECT periode.S, isisjkirim.SJKir, pocustomer.Reference, project.Project, customer.Company, project.Project, customer.Customer FROM periode LEFT JOIN isisjkirim ON periode.IsiSJKir = isisjkirim.IsiSJKir LEFT JOIN sjkirim ON isisjkirim.SJKir=sjkirim.SJKir LEFT JOIN transaksi ON sjkirim.Reference=transaksi.Reference LEFT JOIN pocustomer ON transaksi.Reference=pocustomer.Reference LEFT JOIN project ON pocustomer.PCode=project.PCode LEFT JOIN customer ON project.CCode=customer.CCode WHERE periode.Deletes = 'Jual' GROUP BY sjkirim.SJKir";
$TransaksiJual = mysql_query($query_TransaksiJual, $Connection) or die(mysql_error());
$row_TransaksiJual = mysql_fetch_assoc($TransaksiJual);
$totalRows_TransaksiJual = mysql_num_rows($TransaksiJual);
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
        <small>All</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">Transaksi Jual</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-body">
              <table id="tb_transaksijual_example1" class="table table-bordered table-striped table-responsive">
                <thead>
                <tr>
                  <th>Reference</th>
                  <th>Tgl Kirim</th>
                  <th>Project</th>
                  <th>Customer</th>
                  <th>View</th>
                </tr>
                </thead>
				<tbody>
					<?php do { ?>
					<tr>
						<td><?php echo $row_TransaksiJual['Reference']; ?></td>
                        <td><?php echo $row_TransaksiJual['S']; ?></td>
						<td><?php echo $row_TransaksiJual['Project']; ?></td>
						<td><?php echo $row_TransaksiJual['Customer']; ?></td>
						<td><a href="ViewTransaksiJual.php?Reference=<?php echo $row_TransaksiJual['Reference'] ?>&SJKir=<?php echo $row_TransaksiJual['SJKir']; ?>"><button type="button" class="btn btn-block btn-primary btn-sm">View</button></a></td>
					</tr>
					<?php } while ($row_TransaksiJual = mysql_fetch_assoc($TransaksiJual)); ?>
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
  
<!-- Footer Wrapper -->
<?php include_once($ROOT . 'pages/footer.php'); ?>
<!-- /.footer-wrapper -->
<!-- page script -->
<script>
  $(function () {
    $("#tb_transaksijual_example1").DataTable();
  });
</script>
<?php
  mysql_free_result($TransaksiJual);
?>