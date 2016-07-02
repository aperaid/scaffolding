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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE pocustomer SET Reference=%s, Tgl=%s, PCode=%s WHERE Id=%s",
                       GetSQLValueString($_POST['tx_editpocustomer_Reference'], "text"),
                       GetSQLValueString($_POST['tx_editpocustomer_Tgl'], "text"),
                       GetSQLValueString($_POST['tx_editpocustomer_PCode'], "text"),
                       GetSQLValueString($_POST['hd_editpocustomer_Id'], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());

  $updateGoTo = "POCustomer.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_Edit = "-1";
if (isset($_GET['Id'])) {
  $colname_Edit = $_GET['Id'];
}
mysql_select_db($database_Connection, $Connection);
$query_Edit = sprintf("SELECT pocustomer.*, project.Project, customer.Company FROM pocustomer INNER JOIN project ON pocustomer.PCode=project.PCode INNER JOIN customer ON project.CCode=customer.CCode WHERE pocustomer.Id = %s", GetSQLValueString($colname_Edit, "int"));
$Edit = mysql_query($query_Edit, $Connection) or die(mysql_error());
$row_Edit = mysql_fetch_assoc($Edit);
$totalRows_Edit = mysql_num_rows($Edit);
?>


<?php
$PAGE="Edit PO";
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
        <li class="active">Edit PO</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <!-- general form elements -->
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">PO Detail</h3>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
              <form action="<?php echo $editFormAction; ?>" id="fm_editpocustomer_form1" name="fm_editpocustomer_form1" method="POST">
                <div class="box-body">
                <input name="hd_editpocustomer_Id" type="hidden" id="hd_editpocustomer_Id" value="<?php echo $row_Edit['Id']; ?>">
                  <div class="form-group">
                    <label>Reference</label>
                    <input name="tx_editpocustomer_Reference" type="text" class="form-control" id="tx_editpocustomer_Reference" value="<?php echo $row_Edit['Reference']; ?>" readonly>
                  </div>
                  <div class="form-group">
                    <label>Date</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="tx_editpocustomer_Tgl" type="text" class="form-control pull-right date" id="tx_editpocustomer_Tgl" value="<?php echo $row_Edit['Tgl']; ?>" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Project Code</label>
                    <input name="tx_editpocustomer_PCode" type="text" class="form-control" id="tx_editpocustomer_PCode" autocomplete="off" onKeyUp="capital()" placeholder="ABC01" value="<?php echo $row_Edit['PCode']; ?>" maxlength="5" required>
                    <p class="help-block">Enter the beginning of the Project Code, then pick from the dropdown</p>
                  </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                  <a href="POCustomer.php"><button type="button" class="btn btn-default pull-left">Cancel</button></a> 
                  <button name="bt_editpocustomer_submit" type="submit" id="bt_editpocustomer_submit" class="btn btn-primary pull-right">Update</button>
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
<script>
  $('#tx_editpocustomer_Tgl').datepicker({
	  format: "dd/mm/yyyy",
	  orientation: "bottom left",
	  todayHighlight: true,
	  autoclose: true,
	  startDate: '-7d',
	  endDate: '+7d'
  }); 
</script>
<script>
$(function() {
  var availableTags = <?php include ("../autocomplete.php");?>;
  $( "#tx_editpocustomer_PCode" ).autocomplete({
	source: availableTags
	autoFocus: true
  });
});
</script>
<script>
function capital() {
	var x = document.getElementById("tx_editpocustomer_PCode");
    x.value = x.value.toUpperCase();
}
</script>

<?php
  mysql_free_result($Edit);
?>