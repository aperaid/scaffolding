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
  $_SESSION['Reference'] = sprintf("%s", GetSQLValueString($_POST['tx_inserttransaksiclaim_Reference'], "text"));

  $insertGoTo = "InsertTransaksiClaim2.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

// Ambil nomor reference dari URI masukin ke variable $Reference
$Reference = $_GET['Reference'];

// Query utk ambil Periode Maximum dari periode dgn parameter: reference, Delete Sewa/Extend
mysql_select_db($database_Connection, $Connection);
$query_Periode = "SELECT MAX(Periode) AS Periode FROM periode WHERE Reference = '$Reference' AND (Deletes = 'Sewa' OR Deletes = 'Extend')";
$Periode = mysql_query($query_Periode, $Connection) or die(mysql_error());
$row_Periode = mysql_fetch_assoc($Periode);
$totalRows_Periode = mysql_num_rows($Periode);
// Dari query diatas, masukin ke Variable $Periode2
$Periode2 = $row_Periode['Periode'];

// Query utk ambil tanggal S dari periode dengan parameter: Reference, Periode, Deletes Sewa/Extend
mysql_select_db($database_Connection, $Connection);
$query_TanggalMin = "SELECT S FROM periode WHERE Reference = '$Reference' AND Periode = '$Periode2' AND (Deletes = 'Sewa' OR Deletes = 'Extend') ORDER BY Id ASC";
$TanggalMin = mysql_query($query_TanggalMin, $Connection) or die(mysql_error());
$row_TanggalMin = mysql_fetch_assoc($TanggalMin);
$totalRows_TanggalMin = mysql_num_rows($TanggalMin);

// Query untuk ambil tanggal E dari periode dengan parameter: Reference, Periode, Deletes=sewa/exend
mysql_select_db($database_Connection, $Connection);
$query_TanggalMax = "SELECT E FROM periode WHERE Reference = '$Reference' AND Periode = '$Periode2' AND (Deletes = 'Sewa' OR Deletes = 'Extend') ORDER BY Id DESC";
$TanggalMax = mysql_query($query_TanggalMax, $Connection) or die(mysql_error());
$row_TanggalMax = mysql_fetch_assoc($TanggalMax);
$totalRows_TanggalMax = mysql_num_rows($TanggalMax);

// Rumus insert kalau udah di klik insert
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	// Masukin Tgl transaksi claim ke session
	$_SESSION['tx_inserttransaksiclaim_Tgl'] = sprintf("%s", GetSQLValueString($_POST['tx_inserttransaksiclaim_Tgl'], "text"));
	// Masukkin No.Ref ke Session
	$_SESSION['tx_inserttransaksiclaim_Reference'] = sprintf("%s", GetSQLValueString($_POST['tx_inserttransaksiclaim_Reference'], "text"));
  
  //Redirect after klik insert
  $insertGoTo = "InsertTransaksiClaimBarang.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>

<?php
$PAGE="Insert Claim";
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
        <small>Insert</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="../TransaksiClaim/TransaksiClaim.php">Transaksi Claim</a></li>
        <li class="active">Insert Transaksi Claim</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <!-- general form elements -->
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Transaksi Claim Detail</h3>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
              <form action="<?php echo $editFormAction; ?>&Periode=<?php echo $row_Periode['Periode']; ?>" id="fm_inserttransaksiclaim_form1" name="fm_inserttransaksiclaim_form1" method="POST">
                <div class="box-body">

                  <?php
	
					  $Min = $row_TanggalMin['S'];
					  $Max = $row_TanggalMax['E'];
					
				  ?>
                  <div class="form-group">
                    <label>Return Date</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="tx_inserttransaksiclaim_Tgl" type="text" autocomplete="off" class="form-control pull-right date" id="tx_inserttransaksiclaim_Tgl" required>
                    </div>
                  </div>
                  <div class="form-group">
                  <label>Reference Code</label>
                    <input name="tx_inserttransaksiclaim_Reference" type="text" class="form-control" id="tx_inserttransaksiclaim_Reference" autocomplete="off" onKeyUp="capital()" placeholder="00001/010116" value="<?php echo $_GET['Reference'] ?>" readonly>
                  </div>
                <!-- /.box-body -->
                <div class="box-footer">
                  <a href="../POCustomer/ViewTransaksi.php?Reference=<?php echo $_GET['Reference'] ?>"><button type="button" class="btn btn-default pull-left">Back</button></a> 
                  <button type="submit" name="bt_inserttransaksiclaim_submit" id="bt_inserttransaksiclaim_submit" class="btn btn-primary pull-right">Insert</button>
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
</div>

<!-- Footer Wrapper -->
<?php include_once($ROOT . 'pages/footer.php'); ?>
<!-- /.footer-wrapper -->
<script>
var Min = <?php echo json_encode($Min) ?>;
var Max = <?php echo json_encode($Max) ?>;
  $('#tx_inserttransaksiclaim_Tgl').datepicker({
	  format: "dd/mm/yyyy",
	  startDate: Min,
	  endDate: Max,
	  orientation: "bottom left",
	  todayHighlight: true,
	  autoclose: true
  }); 
</script>

<script>
function capital() {
	var x = document.getElementById("tx_inserttransaksiclaim_Reference");
    x.value = x.value.toUpperCase();
}
</script>

<script type="text/javascript">
$(function() {
    var availableTags = <?php include ("../autocomplete3.php");?>;
    $( "#tx_inserttransaksiclaim_Reference" ).autocomplete({
      source: availableTags
    });
  });
</script>
