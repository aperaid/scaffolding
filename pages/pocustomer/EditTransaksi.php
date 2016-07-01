<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

$colname_Purchase = "-1";
if (isset($_GET['Reference'])) {
  $colname_Purchase = $_GET['Reference'];
}
mysql_select_db($database_Connection, $Connection);
$query_Purchase = sprintf("SELECT transaksi.*, pocustomer.Tgl, project.Project, customer.Company, customer.Alamat, customer.Zip, customer.Kota, customer.CompPhone, customer.CompEmail, customer.Customer, customer.CustPhone, customer.CustEmail FROM transaksi INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode INNER JOIN customer ON project.CCode=customer.CCode WHERE transaksi.Reference = %s ORDER BY transaksi.Id ASC", GetSQLValueString($colname_Purchase, "text"));
$Purchase = mysql_query($query_Purchase, $Connection) or die(mysql_error());
$row_Purchase = mysql_fetch_assoc($Purchase);
$totalRows_Purchase = mysql_num_rows($Purchase);

$colname_View = "-1";
if (isset($_GET['Reference'])) {
  $colname_View = $_GET['Reference'];
}
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT Reference FROM transaksi WHERE Reference = %s", GetSQLValueString($colname_View, "text"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

for($i=0;$i<$totalRows_Purchase;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("SELECT edit_transaksi(%s,%s,%s,%s,%s,%s)",
                       GetSQLValueString($_POST['tx_edittransaksi_Quantity'][$i], "int"),
					   GetSQLValueString($_POST['tx_edittransaksi_Quantity'][$i], "int"),
					   GetSQLValueString($_POST['tx_edittransaksi_Quantity'][$i], "int"),
                       GetSQLValueString($_POST['tx_edittransaksi_Amount'][$i], "text"),
                       GetSQLValueString($_POST['hd_edittransaksi_Id'][$i], "int"),
					   GetSQLValueString($colname_View, "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());



  $updateGoTo = "ViewTransaksi.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  
  header(sprintf("Location: %s", $updateGoTo));
}
}
?>


<?php
$PAGE="Edit Isi PO";
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
        <small>Edit</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="../POCustomer/POCustomer.php">Purchase Order</a></li>
        <li class="active">Edit PO Item</li>
      </ol>
    </section>

	<!-- Main content -->
    <section class="invoice">
	  <form action="<?php echo $editFormAction; ?>" id="fm_edittransaksi_form1" name="fm_edittransaksi_form1" method="POST">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> PT. BDN | 
			<?php echo $row_Purchase['Reference']; ?>
			<small class="pull-right">Date: <?php echo $row_Purchase['Tgl']; ?></small>
		  </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          Company
          <address>
            <strong><?php echo $row_Purchase['Company']; ?></strong><br>
            <?php echo $row_Purchase['Alamat']; ?><br>
            <?php echo $row_Purchase['Kota']; ?>,  <?php echo $row_Purchase['Zip']; ?><br>
            Phone: <?php echo $row_Purchase['CompPhone']; ?><br>
            Email: <?php echo $row_Purchase['CompEmail']; ?>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          Project
          <address>
            <strong><?php echo $row_Purchase['Project']; ?></strong><br>
            <?php echo $row_Purchase['Alamat']; ?><br>
            <?php echo $row_Purchase['Kota']; ?>,  <?php echo $row_Purchase['Zip']; ?><br>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          Contact Person
          <address>
            <strong><?php echo $row_Purchase['Customer']; ?></strong><br>
            Phone: <?php echo $row_Purchase['CustPhone']; ?><br>
            Email: <?php echo $row_Purchase['CustEmail']; ?>
          </address>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-striped">
            <thead>
            <tr>
                <th>J/S</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            </thead>
            <tbody>
				<?php do { ?>
                    <tr>
                      <td class="hidden"><input name="hd_edittransaksi_Id[]" id="hd_edittransaksi_Id" value="<?php echo $row_Purchase['Id']; ?>"></td>
                      <td><?php echo $row_Purchase['JS']; ?></td>
                      <td><?php echo $row_Purchase['Barang']; ?></td>
                      <td><input name="tx_edittransaksi_Quantity[]" type="text" class="form-control" id="tx_edittransaksi_Quantity" value="<?php echo $row_Purchase['Quantity']; ?>" autocomplete="off" required></td>
                      <td><input name="tx_edittransaksi_Amount[]" type="text" class="form-control" id="tx_edittransaksi_Amount" value="<?php echo $row_Purchase['Amount']; ?>" autocomplete="off" required></td>
                    </tr>
                  <?php } while ($row_Purchase = mysql_fetch_assoc($Purchase)); ?>
            </tbody>
          </table>
            <input type="hidden" name="MM_update" value="form1">
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
            <button type="submit" name="bt_edittransaksi_submit" id="bt_edittransaksi_submit" class="btn btn-success pull-right">Update</button> 
            <a href="ViewTransaksi.php?Reference=<?php echo $row_View['Reference']; ?>"><button type="button" class="btn btn-default pull-left">Cancel</button></a>
        </div>
      </div>
	  </form>
	</section>
    <!-- /.content -->
	<div class="clearfix"></div>
	
  </div>
  <!-- /.content-wrapper -->
  
<!-- Footer Wrapper -->
<?php include_once($ROOT . 'pages/footer.php'); ?>
<!-- /.footer-wrapper -->
<?php
  mysql_free_result($Purchase);
  mysql_free_result($View);
?>