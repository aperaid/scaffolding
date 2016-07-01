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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	$_SESSION['tx_insertsjkirim_SJKir'] = sprintf("%s", GetSQLValueString($_POST['tx_insertsjkirim_SJKir'], "text"));
	$_SESSION['tx_insertsjkirim_Tgl'] = sprintf("%s", GetSQLValueString($_POST['tx_insertsjkirim_Tgl'], "text"));
	$_SESSION['tx_insertsjkirim_Reference'] = sprintf("%s", GetSQLValueString($_POST['tx_insertsjkirim_Reference'], "text"));

  $insertGoTo = "InsertSJKirimBarang.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$Reference = $_GET['Reference'];

mysql_select_db($database_Connection, $Connection);
$query_TanggalMin = "SELECT Tgl FROM pocustomer WHERE Reference = '$Reference'";
$TanggalMin = mysql_query($query_TanggalMin, $Connection) or die(mysql_error());
$row_TanggalMin = mysql_fetch_assoc($TanggalMin);
$totalRows_TanggalMin = mysql_num_rows($TanggalMin);

mysql_select_db($database_Connection, $Connection);
$query_NoSJ = "SELECT Id FROM sjkirim ORDER BY Id DESC";
$NoSJ = mysql_query($query_NoSJ, $Connection) or die(mysql_error());
$row_NoSJ = mysql_fetch_assoc($NoSJ);
$totalRows_NoSJ = mysql_num_rows($NoSJ);
?>

<?php
$PAGE="Insert SJ Kirim";
$top_menu_sel="menu_sjkirim";
include_once($ROOT . 'pages/html_header.php');
include_once($ROOT . 'pages/html_main_header.php');
?>
      
<?php
$Min = $row_TanggalMin['Tgl'];
?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Surat Jalan Kirim
        <small>Insert</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="../SJKirim/SJKirim.php">SJ Kirim</a></li>
        <li class="active">Insert SJ Kirim</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <!-- general form elements -->
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">SJ Detail</h3>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
              <form action="<?php echo $editFormAction; ?>" id="fm_insertsjkirim_form1" name="fm_insertsjkirim_form1" method="POST">
                <div class="box-body">
                  <div class="form-group">
                    <label>No. Surat Jalan</label>
                    <input name="tx_insertsjkirim_SJKir" type="text" class="form-control" id="tx_insertsjkirim_SJKir" onKeyUp="capital()" value="<?php echo str_pad($row_NoSJ['Id']+1, 3, "0", STR_PAD_LEFT); ?>/SI/<?php echo date("mY") ?>" readonly>
                  </div>
                  <div class="form-group">
                    <label>Send Date</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="tx_insertsjkirim_Tgl" type="text" autocomplete="off" class="form-control pull-right date" id="tx_insertsjkirim_Tgl" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Reference Code</label>
                    <input name="tx_insertsjkirim_Reference" type="text" class="form-control" id="tx_insertsjkirim_Reference" autocomplete="off" onKeyUp="capital()" placeholder="00001/010116" value="<?php echo $_GET['Reference'] ?>" readonly>
                    <p class="help-block">Enter the beginning of the Reference Code, then pick from the dropdown</p>
                  </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                  <a href="../POCustomer/ViewTransaksi.php?Reference=<?php echo $_GET['Reference'] ?>"><button type="button" class="btn btn-default pull-left">Cancel</button></a> 
                  <button type="submit" name="bt_insertsjkirim_submit" id="bt_insertsjkirim_submit" class="btn btn-primary pull-right">Insert</button>
                </div>
              <input type="hidden" name="MM_insert" value="form1">
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
var Min = <?php echo json_encode($Min) ?>;
  $('#tx_insertsjkirim_Tgl').datepicker({
	  format: "dd/mm/yyyy",
	  startDate: Min,
	  orientation: "bottom left",
	  todayHighlight: true,
	  autoclose: true
  }); 
</script>
<script type="text/javascript">
$(function() {
    var availableTags = <?php include ("../autocomplete3.php");?>;
    $( "#tx_insertsjkirim_Reference" ).autocomplete({
      source: availableTags
    });
  });
</script>

<?php
  mysql_free_result($TanggalMin);
  mysql_free_result($NoSJ);
?>
