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
	$_SESSION['tx_insertsjkembali_SJKem'] = sprintf("%s", GetSQLValueString($_POST['tx_insertsjkembali_SJKem'], "text"));
	$_SESSION['tx_insertsjkembali_Tgl'] = sprintf("%s", GetSQLValueString($_POST['tx_insertsjkembali_Tgl'], "text"));
	$_SESSION['tx_insertsjkembali_Reference'] = sprintf("%s", GetSQLValueString($_POST['tx_insertsjkembali_Reference'], "text"));

  $insertGoTo = "InsertSJKembaliBarang.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_Connection, $Connection);
$query_Menu = "SELECT * FROM menu";
$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
$row_Menu = mysql_fetch_assoc($Menu);
$totalRows_Menu = mysql_num_rows($Menu);

mysql_select_db($database_Connection, $Connection);
$query_NoSJ = "SELECT Id FROM sjkembali ORDER BY Id DESC";
$NoSJ = mysql_query($query_NoSJ, $Connection) or die(mysql_error());
$row_NoSJ = mysql_fetch_assoc($NoSJ);
$totalRows_NoSJ = mysql_num_rows($NoSJ);

$Reference = $_GET['Reference'];

mysql_select_db($database_Connection, $Connection);
$query_Periode = "SELECT MAX(Periode) AS Periode FROM periode WHERE Reference = '$Reference' AND (Deletes = 'Sewa' OR Deletes = 'Extend')";
$Periode = mysql_query($query_Periode, $Connection) or die(mysql_error());
$row_Periode = mysql_fetch_assoc($Periode);
$totalRows_Periode = mysql_num_rows($Periode);

$Periode2 = $row_Periode['Periode'];

mysql_select_db($database_Connection, $Connection);
$query_TanggalMin = "SELECT S FROM periode WHERE Reference = '$Reference' AND Periode = '$Periode2' AND (Deletes = 'Sewa' OR Deletes = 'Extend') ORDER BY Id ASC";
$TanggalMin = mysql_query($query_TanggalMin, $Connection) or die(mysql_error());
$row_TanggalMin = mysql_fetch_assoc($TanggalMin);
$totalRows_TanggalMin = mysql_num_rows($TanggalMin);

mysql_select_db($database_Connection, $Connection);
$query_TanggalMax = "SELECT E FROM periode WHERE Reference = '$Reference' AND Periode = '$Periode2' AND Deletes = 'Sewa' OR Deletes = 'Extend' ORDER BY Id DESC";
$TanggalMax = mysql_query($query_TanggalMax, $Connection) or die(mysql_error());
$row_TanggalMax = mysql_fetch_assoc($TanggalMax);
$totalRows_TanggalMax = mysql_num_rows($TanggalMax);
?>

<?php
$PAGE="Insert SJ Kembali";
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
        <small>Insert</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="../SJKembali/SJKembali.php">SJ Kembali</a></li>
        <li class="active">Insert SJ Kembali</li>
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
              <form action="<?php echo $editFormAction; ?>" id="fm_insertsjkembali_form1" name="fm_insertsjkembali_form1" method="POST">
                <div class="box-body">
                  <div class="form-group">
                  
                  <?php
				    $Min = $row_TanggalMin['S'];
					$Max = $row_TanggalMax['E'];
				  ?>
                  
                    <label>No. Surat Jalan</label>
                    <input name="tx_insertsjkembali_SJKem" type="text" class="form-control" id="tx_insertsjkembali_SJKem" onKeyUp="capital()" value="<?php echo str_pad($row_NoSJ['Id']+1, 3, "0", STR_PAD_LEFT); ?>/SI/<?php echo date("mY"); ?>" readonly>
                  </div>
                  <div class="form-group">
                    <label>Return Date</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="tx_insertsjkembali_Tgl" type="text" autocomplete="off" class="form-control pull-right date" id="tx_insertsjkembali_Tgl" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Reference Code</label>
                    <input name="tx_insertsjkembali_Reference" type="text" class="form-control" id="tx_insertsjkembali_Reference" autocomplete="off" onKeyUp="capital()" placeholder="00001/010116" value="<?php echo $_GET['Reference']; ?>" readonly>
                    <p class="help-block">Enter the beginning of the Reference Code, then pick from the dropdown</p>
                  </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                  <a href="../POCustomer/ViewTransaksi.php?Reference=<?php echo $_GET['Reference'] ?>"><button type="button" class="btn btn-default pull-left">Cancel</button></a> 
                  <button type="submit" name="bt_insertsjkembali_submit" id="bt_insertsjkembali_submit" class="btn btn-primary pull-right">Insert</button>
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
<script>
var Min = <?php echo json_encode($Min) ?>;
var Max = <?php echo json_encode($Max) ?>;
  $('#tx_insertsjkembali_Tgl').datepicker({
	  format: "dd/mm/yyyy",
	  startDate: Min,
	  endDate: Max,
	  orientation: "bottom left",
	  todayHighlight: true,
	  autoclose: true
  }); 
</script>
<script type="text/javascript">
$(function() {
    var availableTags = <?php include ("../autocomplete3.php");?>;
    $( "#tx_insertsjkembali_Reference" ).autocomplete({
      source: availableTags
    });
  });
</script>
<?php
  mysql_free_result($TanggalMin);
  mysql_free_result($TanggalMax);
  mysql_free_result($NoSJ);
?>
