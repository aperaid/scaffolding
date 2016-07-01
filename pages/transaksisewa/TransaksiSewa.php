<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT periode.Id, periode.Periode, periode.S, periode.E, periode.IsiSJKir, periode.Reference, periode.Deletes, isisjkirim.SJKir, customer.Customer, project.Project, periode.Reference FROM periode LEFT JOIN isisjkirim ON periode.IsiSJKir=isisjkirim.IsiSJKir LEFT JOIN pocustomer ON periode.Reference=pocustomer.Reference LEFT JOIN project ON pocustomer.PCode=project.PCode LEFT JOIN customer ON project.CCode=customer.CCode WHERE periode.Deletes = 'Sewa' OR periode.Deletes = 'Extend' GROUP BY periode.Reference, periode.Periode");
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);

/*$query_LastPeriode = sprintf("SELECT MAX(Periode) AS Periode FROM periode WHERE Reference = %s", GetSQLValueString($colname_View, "text"));
 $LastPeriode = mysql_query($query_LastPeriode, $Connection) or die(mysql_error());
 $row_LastPeriode = mysql_fetch_assoc($LastPeriode);
 $totalRows_LastPeriode = mysql_num_rows($LastPeriode);*/
?>

<?php
$PAGE="Transaksi Sewa";
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
        <small>View</small>
        <!--<large><a href="ExtendTransaksiSewa.php?Reference=<?php echo $row_View['Reference']; ?>&Periode=<?php echo $row_LastPeriode['Periode']; ?>&SJKir=<?php echo $row_View['SJKir']; ?>" onclick="return confirm('Extend Sewa hanya boleh dilakukan di akhir periode dan sudah ada konfirmasi dari customer. Lanjutkan?')"><button type="button" class="btn btn-success btn-sm">Extend</button></a></large>-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="TransaksiSewa.php">Transaksi Sewa</a></li>
        <li class="active">View Transaksi Sewa</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-body">
              <table id="tb_viewtransaksisewa_example1" class="table table-bordered table-striped table-responsive">
                <thead>
                <tr>
				  <th>Reference</th>
                  <th>Periode</th>
                  <th>Start</th>
                  <th>End</th>
                  <th>Customer</th>
                  <th>Project</th>
                  <th>View</th>
				  <th>Extend</th>
                </tr>
                </thead>
				<tbody>
					<?php do { ?>
                    
					<?php
					mysql_select_db($database_Connection, $Connection);
					$query_Periode = sprintf("SELECT MAX(Id) AS Id FROM periode WHERE Reference = %s AND IsiSJKir = %s AND (Deletes = 'Sewa' OR Deletes = 'Extend') GROUP BY IsiSJKir ORDER BY Id ASC", GetSQLValueString($row_View['Reference'], "text"), GetSQLValueString($row_View['IsiSJKir'], "text"));
					$Periode = mysql_query($query_Periode, $Connection) or die(mysql_error());
					$row_Periode = mysql_fetch_assoc($Periode);
					$totalRows_Periode = mysql_num_rows($Periode);
					
					$Id = $row_Periode['Id'];
					?>
					
					<tr>
						<td><?php echo $row_View['Reference']; ?></td>
						<td><?php echo $row_View['Periode']; ?></td>
						<td><?php echo $row_View['S']; ?></td>
						<td><?php echo $row_View['E']; ?></td>
						<td><?php echo $row_View['Customer']; ?></td>
						<td><?php echo $row_View['Project']; ?></td>
						<td><a href="ViewTransaksiSewa.php?Reference=<?php echo $row_View['Reference']; ?>&Periode=<?php echo $row_View['Periode']; ?>"><button type="button" class="btn btn-block btn-primary btn-sm">View Detail</button></a></td>
						<td><a href="ExtendTransaksiSewa.php?Reference=<?php echo $row_View['Reference']; ?>&Periode=<?php echo $row_View['Periode']; ?>&SJKir=<?php echo $row_View['SJKir']; ?>" onclick="return confirm('Extend Sewa hanya boleh dilakukan di akhir periode dan sudah ada konfirmasi dari customer. Lanjutkan?')"><button type="button" name="bt_viewtransaksisewa_extend" id="bt_viewtransaksisewa_extend" <?php if (($row_Periode['Id'] != $row_View['Id'])){ ?> class="btn btn-block btn-default btn-sm" disabled <?php   } else { ?> class="btn btn-block btn-primary btn-sm" <?php } ?>>Extend</button></a></td>
					</tr>
					<?php } while ($row_View = mysql_fetch_assoc($View)); ?>
				</tbody>
              </table>
            </div>
            <!-- /.box-body -->
            <!-- <div class="box-footer">
                  <a href="TransaksiSewa.php"><button type="button" class="btn btn-default pull-left">Back</button></a>
			</div> -->
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
    $("#tb_viewtransaksisewa_example1").DataTable();
  });
</script>

<?php
mysql_free_result($View);
?>