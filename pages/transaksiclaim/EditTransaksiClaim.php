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

$colname_Edit = "-1";
if (isset($_GET['Reference'])) {
  $colname_Edit = $_GET['Reference'];
  $colname_Periode = $_GET['Periode'];
}
mysql_select_db($database_Connection, $Connection);
$query_Edit = sprintf("SELECT transaksiclaim.*, periode.Reference, transaksi.Barang, isisjkirim.QSisaKem FROM transaksiclaim LEFT JOIN periode ON transaksiclaim.IsiSJKir=periode.IsiSJKir LEFT JOIN isisjkirim ON transaksiclaim.IsiSJKir=isisjkirim.IsiSJKir LEFT JOIN transaksi ON periode.Purchase = transaksi.Purchase WHERE periode.Reference=%s AND transaksiclaim.Periode=%s GROUP BY transaksiclaim.Id", GetSQLValueString($colname_Edit, "text"), GetSQLValueString($colname_Periode, "text"));
$Edit = mysql_query($query_Edit, $Connection) or die(mysql_error());
$row_Edit = mysql_fetch_assoc($Edit);
$totalRows_Edit = mysql_num_rows($Edit);

$query = mysql_query($query_Edit, $Connection) or die(mysql_error());
$IsiSJKir = array();
while($row = mysql_fetch_assoc($query)){
	$IsiSJKir[] = $row['IsiSJKir'];
}
$IsiSJKir2 = join(',', $IsiSJKir);

$query = mysql_query($query_Edit, $Connection) or die(mysql_error());
$QClaim = array();
while($row = mysql_fetch_assoc($query)){
	$QClaim[] = $row['QClaim'];
}

for($i=0;$i<$totalRows_Edit;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE transaksi SET QSisaKem=QSisaKem+%s-%s WHERE Reference=%s AND Purchase=%s",
  					   GetSQLValueString($QClaim[$i], "int"),
                       GetSQLValueString($_POST['tx_edittransaksiclaim_QClaim'][$i], "int"),
                       GetSQLValueString($_POST['hd_edittransaksiclaim_Reference'], "text"),
					   GetSQLValueString($_POST['tx_edittransaksiclaim_Purchase'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE isisjkirim SET QSisaKemInsert=QSisaKemInsert+%s-%s, QSisaKem=QSisaKem+%s-%s WHERE Purchase=%s AND IsiSJKir=%s",
  					   GetSQLValueString($QClaim[$i], "int"),
                       GetSQLValueString($_POST['tx_edittransaksiclaim_QClaim'][$i], "int"),
					   GetSQLValueString($QClaim[$i], "int"),
					   GetSQLValueString($_POST['tx_edittransaksiclaim_QClaim'][$i], "int"),
					   GetSQLValueString($_POST['tx_edittransaksiclaim_Purchase'][$i], "text"),
                       GetSQLValueString($_POST['hd_edittransaksiclaim_IsiSJKir'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE periode SET Quantity=Quantity+%s-%s WHERE Periode=%s AND IsiSJKir=%s AND (Deletes ='Extend' OR Deletes = 'Sewa')",
  					   GetSQLValueString($QClaim[$i], "int"),
                       GetSQLValueString($_POST['tx_edittransaksiclaim_QClaim'][$i], "int"),
					   GetSQLValueString($_POST['hd_edittransaksiclaim_Periode'], "int"),
                       GetSQLValueString($_POST['hd_edittransaksiclaim_IsiSJKir'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE periode SET Quantity=%s WHERE Periode=%s AND Purchase=%s AND Claim=%s AND Deletes='ClaimS'",
                       GetSQLValueString($_POST['tx_edittransaksiclaim_QClaim'][$i], "int"),
					   GetSQLValueString($_POST['hd_edittransaksiclaim_Periode'], "int"),
                       GetSQLValueString($_POST['tx_edittransaksiclaim_Purchase'][$i], "text"),
					   GetSQLValueString($_POST['tx_edittransaksiclaim_Claim'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE periode SET Quantity=%s WHERE Periode=%s+1 AND Purchase=%s AND Claim=%s AND Deletes='ClaimE'",
                       GetSQLValueString($_POST['tx_edittransaksiclaim_QClaim'][$i], "int"),
					   GetSQLValueString($_POST['hd_edittransaksiclaim_Periode'], "int"),
                       GetSQLValueString($_POST['tx_edittransaksiclaim_Purchase'][$i], "text"),
					   GetSQLValueString($_POST['tx_edittransaksiclaim_Claim'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE transaksiclaim SET QClaim=%s, Amount=%s WHERE Id=%s",
                       GetSQLValueString($_POST['tx_edittransaksiclaim_QClaim'][$i], "int"),
                       GetSQLValueString($_POST['tx_edittransaksiclaim_Amount'][$i], "text"),
                       GetSQLValueString($_POST['hd_edittransaksiclaim_Id'][$i], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());

  $updateGoTo = "TransaksiClaim.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
}
?>

<?php
$PAGE="Edit Claim";
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
        <small>Item</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="../TransaksiClaim/TransaksiClaim.php">Transaksi Claim</a></li>
        <li class="active">Insert Transaksi Claim Barang</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Input Claim Item</h3>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
              <form action="<?php echo $editFormAction; ?>" id="fm_edittransaksiclaim_form1" name="fm_edittransaksiclaim_form1" method="POST">
           
           <div class="box-body">
				<table class="table table-hover table-bordered" id="tb_edittransaksiclaim_example1" name="tb_edittransaksiclaim_example1">
    <thead>
      <tr>
		<th>No. Claim</th>
		<th>Barang</th>
		<th>Quantity Ditempat</th>
		<th>Quantity Claim</th>
		<th>Price</th>
		<th>No. Purchase</th>
      </tr>
    </thead>
    <tbody>
	<?php do { ?>
	  <tr>
      <input name="hd_edittransaksiclaim_Id[]" type="hidden" class="form-control" id="hd_edittransaksiclaim_Id" value="<?php echo $row_Edit['Id']; ?>" readonly>
      <input name="hd_edittransaksiclaim_Reference" type="hidden" class="form-control" id="hd_edittransaksiclaim_Reference" value="<?php echo $row_Edit['Reference']; ?>" readonly>
      <input name="hd_edittransaksiclaim_Periode" type="hidden" class="form-control" id="hd_edittransaksiclaim_Periode" value="<?php echo $row_Edit['Periode']; ?>" readonly>
      <input name="hd_edittransaksiclaim_IsiSJKir[]" type="hidden" class="form-control" id="hd_edittransaksiclaim_IsiSJKir" value="<?php echo $row_Edit['IsiSJKir']; ?>" readonly>
	    <td><input name="tx_edittransaksiclaim_Claim[]" type="text" class="form-control" id="tx_edittransaksiclaim_Claim" value="<?php echo $row_Edit['Claim']; ?>" readonly></td>
	    <td><input name="tx_edittransaksiclaim_Barang[]" type="text" class="form-control" id="tx_edittransaksiclaim_Barang" value="<?php echo $row_Edit['Barang']; ?>" readonly></td>
	    <td><input name="tx_edittransaksiclaim_QSisaKem[]" type="text" class="form-control" id="tx_edittransaksiclaim_QSisaKem" value="<?php echo $row_Edit['QSisaKem']; ?>" readonly></td>
	    <td><input name="tx_edittransaksiclaim_QClaim[]" type="text" class="form-control" id="tx_edittransaksiclaim_QClaim" autocomplete="off" value="<?php echo $row_Edit['QClaim']; ?>" onkeyup="this.value = minmax(this.value, 0, <?php echo $row_Edit['QSisaKem']; ?>)"></td>
	    <td><input name="tx_edittransaksiclaim_Amount[]" type="text" class="form-control" id="tx_edittransaksiclaim_Amount" autocomplete="off" value="<?php echo $row_Edit['Amount']; ?>"></td>
	    <td><input name="tx_edittransaksiclaim_Purchase[]" type="text" class="form-control" id="tx_edittransaksiclaim_Purchase" value="<?php echo $row_Edit['Purchase']; ?>" readonly></td>
	    </tr>
            <?php } while ($row_Edit = mysql_fetch_assoc($Edit)); ?>
            </tbody>
            </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
	<a href="TransaksiClaim.php"><button type="button" class="btn btn-default pull-left">Cancel</button></a>
	<button type="submit" name="bt_edittransaksiclaim_submit" id="bt_edittransaksiclaim_submit" class="btn btn-success pull-right">Update</button>
                      </div>
                
  <input type="hidden" name="MM_update" value="form1">
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
  
 <!-- Footer Wrapper -->
<?php include_once($ROOT . 'pages/footer.php'); ?>
<!-- /.footer-wrapper -->
<!-- page script -->
<script type="text/javascript">
function minmax(value, min, max) 
{
	if(parseInt(value) < min || isNaN(value)) 
        return 0; 
    if(parseInt(value) > max) 
        return parseInt(max); 
    else return value;
}
</script>
<?php
  mysql_free_result($Edit);
?>
