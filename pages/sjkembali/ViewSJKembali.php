<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

$colname_ViewIsiSJKembali = "-1";
if (isset($_GET['SJKem'])) {
  $colname_ViewIsiSJKembali = $_GET['SJKem'];
}
mysql_select_db($database_Connection, $Connection);
$query_ViewIsiSJKembali = sprintf("SELECT isisjkembali.*, SUM(isisjkembali.QTertanda) AS QTertanda2, SUM(isisjkembali.QTerima) AS QTerima2, isisjkirim.QSisaKem, sjkirim.Tgl, transaksi.Barang, sjkirim.Reference, project.Project, customer.* FROM isisjkembali INNER JOIN isisjkirim ON isisjkembali.IsiSJKir=isisjkirim.IsiSJKir INNER JOIN sjkirim ON isisjkirim.SJKir=sjkirim.SJKir INNER JOIN transaksi ON isisjkembali.Purchase=transaksi.Purchase INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode INNER JOIN customer ON project.CCode=customer.CCode WHERE isisjkembali.SJKem = %s GROUP BY isisjkembali.Purchase ORDER BY isisjkembali.Id ASC", GetSQLValueString($colname_ViewIsiSJKembali, "text"));
$ViewIsiSJKembali = mysql_query($query_ViewIsiSJKembali, $Connection) or die(mysql_error());
$row_ViewIsiSJKembali = mysql_fetch_assoc($ViewIsiSJKembali);
$totalRows_ViewIsiSJKembali = mysql_num_rows($ViewIsiSJKembali);

$colname_View = "-1";
if (isset($_GET['SJKem'])) {
  $colname_View = $_GET['SJKem'];
}
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT SJKem, Tgl FROM sjkembali WHERE SJKem = %s", GetSQLValueString($colname_View, "text"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);

//disabled edit button if qterima exists
mysql_select_db($database_Connection, $Connection);
$query_check = sprintf("SELECT check_sjkem(%s) AS result", GetSQLValueString($colname_ViewIsiSJKembali, "text"));
$check = mysql_query($query_check, $Connection) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
//disabled end

?>

<?php
$PAGE="View Detail";
$top_menu_sel="menu_sjkembali";
include_once($ROOT . 'pages/html_header.php');
include_once($ROOT . 'pages/html_main_header.php');
?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Surat Jalan Kembali
        <small>View</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="../SJKembali/SJKembali.php">SJ Kembali</a></li>
        <li class="active">View SJ Kembali</li>
      </ol>
    </section>

    <!-- Main content -->
	<section class="content">
    <section class="invoice">
		
		<div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> SJ Kembali | <?php echo $row_View['SJKem']; ?>
			<small class="pull-right">Date: <?php echo $row_View['Tgl']; ?></small>
		  </h2>
        </div>
        <!-- /.col -->
		</div>
		
		<!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          Company
          <address>
            <strong><?php echo $row_ViewIsiSJKembali['Company']; ?></strong><br>
            <?php echo $row_ViewIsiSJKembali['Alamat']; ?><br>
            <?php echo $row_ViewIsiSJKembali['Kota']; ?>,  <?php echo $row_ViewIsiSJKembali['Zip']; ?><br>
            Phone: <?php echo $row_ViewIsiSJKembali['CompPhone']; ?><br>
            Email: <?php echo $row_ViewIsiSJKembali['CompEmail']; ?>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          Project
          <address>
            <strong><?php echo $row_ViewIsiSJKembali['Project']; ?></strong><br>
            <?php echo $row_ViewIsiSJKembali['Alamat']; ?><br>
            <?php echo $row_ViewIsiSJKembali['Kota']; ?>,  <?php echo $row_ViewIsiSJKembali['Zip']; ?><br>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          Contact Person
          <address>
            <strong><?php echo $row_ViewIsiSJKembali['Customer']; ?></strong><br>
            Phone: <?php echo $row_ViewIsiSJKembali['CustPhone']; ?><br>
            Email: <?php echo $row_ViewIsiSJKembali['CustEmail']; ?>
          </address>
        </div>
        <!-- /.col -->
      </div>
	  
		
     

          <div class="row">
            <div class="col-xs-12 table-responsive">
              <table id="tb_viewsjkembali_example1" class="table table-striped">
                <thead>
                <tr>
					<th>Tanggal Kirim</th>
					<th>Barang</th>
					<th>Warehouse</th>
					<th>Q Pengambilan</th>
					<th>Q Terima</th>
                </tr>
                </thead>
                <tbody>
                <?php $Reference = $row_ViewIsiSJKembali['Reference']; ?>
					<?php do { ?>
					<tr>
						<td><input	 name="tx_viewsjkembali_Tgl" type="text" class="form-control" id="tx_viewsjkembali_Tgl" value="<?php echo $row_ViewIsiSJKembali['Tgl']; ?>" readonly></td>
						<td><input name="tx_viewsjkembali_Barang" type="text" class="form-control" id="tx_viewsjkembali_Barang" value="<?php echo $row_ViewIsiSJKembali['Barang']; ?>" readonly></td>
						<td><input name="tx_viewsjkembali_Warehouse" type="text" class="form-control" id="tx_viewsjkembali_Warehouse" value="<?php echo $row_ViewIsiSJKembali['Warehouse']; ?>" readonly></td>
						<td><input name="tx_viewsjkembali_QTertanda" type="text" class="form-control" id="tx_viewsjkembali_QTertanda" value="<?php echo $row_ViewIsiSJKembali['QTertanda2']; ?>" readonly></td>
						<td><input name="tx_viewsjkembali_QTerima" type="text" class="form-control" id="tx_viewsjkembali_QTerima" value="<?php echo $row_ViewIsiSJKembali['QTerima2']; ?>" readonly></td
					></tr>
					<?php } while ($row_ViewIsiSJKembali = mysql_fetch_assoc($ViewIsiSJKembali)); ?>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
            
            <div class="box-footer">
				<a href="SJKembali.php"><button type="button" class="btn btn-default">Back</button></a>
				<a href="SJKembali.php"><button type="button" class="btn btn-default">Print</button></a>
				<div class="btn-group pull-right">
					<a href="EditSJKembali.php?SJKem=<?php echo $row_View['SJKem']; ?>&Reference=<?php echo $Reference; ?>"><button type="button" <?php if($row_check['result'] == 0) { ?> class="btn btn-primary" <?php } else { ?> class="btn btn-default" disabled <?php } ?>>Edit Pengembalian</button></a>
					<a href="EditSJKembaliQuantity.php?SJKem=<?php echo $row_View['SJKem']; ?>"><button type="button" class="btn btn-success">Quantity Terima</button></a>
				</div>
			</div>
          </div>
          <!-- /.box -->
       
    </section>
    </section>
	<!-- /.content -->
    
  </div>
  <!-- /.content-wrapper -->
  
<!-- Footer Wrapper -->
<?php include_once($ROOT . 'pages/footer.php'); ?>
<!-- /.footer-wrapper -->
<?php
  mysql_free_result($ViewIsiSJKembali);
  mysql_free_result($View);
  mysql_free_result($check);
?>