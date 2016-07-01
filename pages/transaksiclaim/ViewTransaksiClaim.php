<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");
	
$colname_TransaksiClaim = "-1";
if (isset($_GET['Reference'])) {
  $colname_TransaksiClaim = $_GET['Reference'];
  $colname_Periode = $_GET['Periode'];
}
	
mysql_select_db($database_Connection, $Connection);
$query_TransaksiClaim = sprintf("SELECT transaksiclaim.*, SUM(transaksiclaim.QClaim) QClaim2, transaksi.Reference, transaksi.Barang, transaksi.QSisaKem, project.Project, customer.* FROM transaksiclaim LEFT JOIN transaksi ON transaksiclaim.Purchase=transaksi.Purchase INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode INNER JOIN customer ON project.CCode=customer.CCode WHERE transaksi.Reference=%s AND transaksiclaim.Periode=%s GROUP BY transaksiclaim.Claim, transaksiclaim.Tgl, transaksiclaim.Claim ORDER BY transaksiclaim.Id ASC", GetSQLValueString($colname_TransaksiClaim, "text"), GetSQLValueString($colname_Periode, "text"));
$TransaksiClaim = mysql_query($query_TransaksiClaim, $Connection) or die(mysql_error());
$row_TransaksiClaim = mysql_fetch_assoc($TransaksiClaim);
$totalRows_TransaksiClaim = mysql_num_rows($TransaksiClaim);
?>

<?php
$PAGE="View Claim";
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
    <section class="invoice">
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> Transaksi Claim | <?php echo $row_TransaksiClaim['Reference']; ?>
			<small class="pull-right">Date: <?php echo $row_TransaksiClaim['Tgl']; ?></small>
		  </h2>
        </div>
        <!-- /.col -->
      </div>
	  
	  <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          Company
          <address>
            <strong><?php echo $row_TransaksiClaim['Company']; ?></strong><br>
            <?php echo $row_TransaksiClaim['Alamat']; ?><br>
            <?php echo $row_TransaksiClaim['Kota']; ?>,  <?php echo $row_TransaksiClaim['Zip']; ?><br>
            Phone: <?php echo $row_TransaksiClaim['CompPhone']; ?><br>
            Email: <?php echo $row_TransaksiClaim['CompEmail']; ?>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          Project
          <address>
            <strong><?php echo $row_TransaksiClaim['Project']; ?></strong><br>
            <?php echo $row_TransaksiClaim['Alamat']; ?><br>
            <?php echo $row_TransaksiClaim['Kota']; ?>,  <?php echo $row_TransaksiClaim['Zip']; ?><br>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          Contact Person
          <address>
            <strong><?php echo $row_TransaksiClaim['Customer']; ?></strong><br>
            Phone: <?php echo $row_TransaksiClaim['CustPhone']; ?><br>
            Email: <?php echo $row_TransaksiClaim['CustEmail']; ?>
          </address>
        </div>
        <!-- /.col -->
      </div>
    
    <div class="row">
        <div class="col-xs-12 table-responsive">
              <table id="tb_viewsjkirim_example1" class="table table-striped">
                <thead>
					<th>No. Claim</th>
                    <th>Periode</th>
                    <th>Barang</th>
					<th>Tanggal Claim</th>
					<th>Project</th>
					<th>Price</th>
					<th>Quantity Claim</th>
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
						<td><?php echo $row_TransaksiClaim['Claim']; ?></td>
                        <td><?php echo $row_TransaksiClaim['Periode']; ?></td>
                        <td><?php echo $row_TransaksiClaim['Barang']; ?></td>
						<td><?php echo $row_TransaksiClaim['Tgl']; ?></td>
						<td><?php echo $row_TransaksiClaim['Project']; ?></td>
						<td><?php echo number_format($row_TransaksiClaim['Amount'], 2); ?></td>
						<td><?php echo $row_TransaksiClaim['QClaim2']; ?></td>
					  </tr>
                      <?php } while ($row_TransaksiClaim = mysql_fetch_assoc($TransaksiClaim)); ?>
                 </tbody>
              </table>
              </div>
            <!-- /.box-body -->
            <div class="box-footer">
				<a href="TransaksiClaim.php"><button type="button" class="btn btn-default">Back</button></a>
				<a href="#"><button type="button" class="btn btn-default">Print</button></a>
				
				<div class="btn-group pull-right">
				<a href="EditTransaksiClaim.php?Reference=<?php echo $_GET['Reference']; ?>&Periode=<?php echo $_GET['Periode']; ?>"><button type="button" <?php if ($Claim = $Extend) { ?> class="btn btn-primary" <?php } else { ?> class="btn btn-default" disabled <?php } ?> >Edit Claim </button>
                </a>
                </div>
			  </div>
                <!-- /.box -->
        	</div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      </section>
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
    $("#tb_transaksiclaim_example1").DataTable();
  });
</script>

<?php
  mysql_free_result($TransaksiClaim);
?>