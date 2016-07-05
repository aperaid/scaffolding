<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");
	
$colname_TransaksiClaim = "-1";
if (isset($_GET['Reference'])) {
  $colname_TransaksiClaim = $_GET['Reference'];
}
	
mysql_select_db($database_Connection, $Connection);
$query_TransaksiClaim = "SELECT transaksiclaim.*, periode.Reference, transaksi.Barang, transaksi.QSisaKem, project.Project, customer.Customer FROM transaksiclaim LEFT JOIN periode ON transaksiclaim.Claim=periode.Claim INNER JOIN transaksi ON transaksiclaim.Purchase=transaksi.Purchase INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode LEFT JOIN customer ON project.CCode=customer.CCode GROUP BY transaksiclaim.Periode ORDER BY transaksiclaim.Id ASC";
$TransaksiClaim = mysql_query($query_TransaksiClaim, $Connection) or die(mysql_error());
$row_TransaksiClaim = mysql_fetch_assoc($TransaksiClaim);
$totalRows_TransaksiClaim = mysql_num_rows($TransaksiClaim);
?>

<?php
$PAGE="Transaksi Claim";
$top_menu_sel="menu_claim";
include_once($ROOT . 'pages/html_header.php');
include_once($ROOT . 'pages/html_main_header.php');
?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Transaksi Claim
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">Transaksi Claim</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-body">
              <table id="tb_transaksiclaim_example1" name="tb_transaksiclaim_example1" class="table table-bordered table-striped table-responsive">
				<thead>
					<tr>
					<th>Reference</th>
                    <th>Periode</th>
					<th>Tanggal Claim</th>
					<th>Project</th>
					<th>Customer</th>
                    <th>View</th>
					<th>Delete</th>
                    </tr>
				</thead>
				<tbody>
					<?php do { ?>
                    
                    <?php 
					
					mysql_select_db($database_Connection, $Connection);
					$query_PerClaim = sprintf("SELECT MAX(periode.Periode) AS Periode FROM periode WHERE Deletes='Claim' AND Reference=%s AND Claim=%s AND Periode=%s", GetSQLValueString($row_TransaksiClaim['Reference'], "text"), GetSQLValueString($row_TransaksiClaim['Claim'], "text"), GetSQLValueString($row_TransaksiClaim['Periode'], "text"));
					$PerClaim = mysql_query($query_PerClaim, $Connection) or die(mysql_error());
					$row_PerClaim = mysql_fetch_assoc($PerClaim);
					$totalRows_PerClaim = mysql_num_rows($PerClaim);

					mysql_select_db($database_Connection, $Connection);
					$query_PerExtend = sprintf("SELECT MAX(periode.Periode) AS Periode FROM periode WHERE (Deletes='Extend' OR Deletes='Sewa') AND Reference=%s", GetSQLValueString($row_TransaksiClaim['Reference'], "text"));
					$PerExtend = mysql_query($query_PerExtend, $Connection) or die(mysql_error());
					$row_PerExtend = mysql_fetch_assoc($PerExtend);
					$totalRows_PerExtend = mysql_num_rows($PerExtend);
					
					$Claim = $row_PerClaim['Periode'];
					$Extend = $row_PerExtend['Periode'];
					
					?>
                    
					<tr>
						<td><?php echo $row_TransaksiClaim['Reference']; ?></td>
                        <td><?php echo $row_TransaksiClaim['Periode']; ?></td>
						<td><?php echo $row_TransaksiClaim['Tgl']; ?></td>
						<td><?php echo $row_TransaksiClaim['Project']; ?></td>
						<td><?php echo $row_TransaksiClaim['Customer']; ?></td>
                        <td><a href="ViewTransaksiClaim.php?Reference=<?php echo $row_TransaksiClaim['Reference']; ?>&Periode=<?php echo $row_TransaksiClaim['Periode']; ?>"><button type="button" class="btn btn-block btn-primary btn-sm">View</button></a></td>
					  <td><a href="DeleteTransaksiClaim.php?Reference=<?php echo $row_TransaksiClaim['Reference']; ?>&Periode=<?php echo $row_TransaksiClaim['Periode']; ?>" onclick="return confirm('Delete Claim Barang?')"><button type="button" <?php if ($Claim = $Extend) { ?> class="btn btn-block btn-sm btn-danger" <?php } else { ?> class="btn btn-block btn-sm btn-default" disabled <?php } ?>>Batal</button></a></td>
					</tr>
					<?php } while ($row_TransaksiClaim = mysql_fetch_assoc($TransaksiClaim)); ?>
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

<script>
  $(function () {
    $("#tb_transaksiclaim_example1").DataTable();
  });
</script>
<?php
  mysql_free_result($TransaksiClaim);
?>