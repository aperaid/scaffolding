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

// Ambil Nomor Reference & Periode dari URI masukin ke variable
$colname_InsertTransaksiClaim = "-1";
if (isset($_GET['Reference'])) {
  // Reference dari URI masukin ke $colname_InsertTransaksiClaim
  $colname_InsertTransaksiClaim = $_GET['Reference'];
  // Periode dari URI masukin ke $colname_Periode
  $colname_Periode = $_GET['Periode'];
}

// Ambil max ID dari PERIODE parameter: Reference, Deletes= Sewa/Extend (karena mau ambil periode paling terakhir) berdasarkan isisjkir nya
mysql_select_db($database_Connection, $Connection);
$query_GetId = sprintf("SELECT MAX(Id) AS Id FROM periode WHERE Reference = %s AND (Deletes = 'Sewa' OR Deletes = 'Extend') GROUP BY IsiSJKir ORDER BY Id ASC", GetSQLValueString($colname_InsertTransaksiClaim, "text"));
$GetId = mysql_query($query_GetId, $Connection) or die(mysql_error());
$row_GetId = mysql_fetch_assoc($GetId);
$totalRows_GetId = mysql_num_rows($GetId);

// Ambil ID paling besar setiap isisjkir extend/sewa doang
$query = mysql_query($query_GetId, $Connection) or die(mysql_error());
$Id2 = array();
while($row = mysql_fetch_assoc($query)){
	$Id2[] = $row['Id'];
}
$Id3 = join(',',$Id2); 

// Ambil isisjkir, purchase, qsisakeminsert, S, E, Barang, JS dari isisjkirim, periode, & sjkirim parameter: reference, js: sewa, dan periode ID=yg udah diambil di atas
mysql_select_db($database_Connection, $Connection);
$query_InsertTransaksiClaim = sprintf("SELECT isisjkirim.IsiSJKir, isisjkirim.Purchase, SUM(isisjkirim.QSisaKem) AS QSisaKem, periode.S, periode.E, transaksi.Barang, transaksi.JS FROM isisjkirim LEFT JOIN periode ON isisjkirim.IsiSJKir=periode.IsiSJKir INNER JOIN sjkirim ON isisjkirim.SJKir=sjkirim.SJKir INNER JOIN transaksi ON isisjkirim.Purchase=transaksi.Purchase WHERE sjkirim.Reference = %s AND transaksi.JS = 'Sewa' AND periode.Id IN ($Id3) GROUP BY isisjkirim.Purchase ORDER BY periode.Id ASC", GetSQLValueString($colname_InsertTransaksiClaim, "text"), GetSQLValueString($colname_InsertTransaksiClaim, "text"));
$InsertTransaksiClaim = mysql_query($query_InsertTransaksiClaim, $Connection) or die(mysql_error());
$row_InsertTransaksiClaim = mysql_fetch_assoc($InsertTransaksiClaim);
$totalRows_InsertTransaksiClaim = mysql_num_rows($InsertTransaksiClaim);

// ambil ID dari transaksi claim
mysql_select_db($database_Connection, $Connection);
$query_LastId = "SELECT Id FROM transaksiclaim ORDER BY Id DESC";
$LastId = mysql_query($query_LastId, $Connection) or die(mysql_error());
$row_LastId = mysql_fetch_assoc($LastId);
$totalRows_LastId = mysql_num_rows($LastId);

// 
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  for($i=0;$i<$totalRows_InsertTransaksiClaim;$i++){  
  // masukin ke array checkbox barang apa aja yg dipilih
  $_SESSION['cb_inserttransaksiclaimbarang_checkbox'][$i] = sprintf("%s", GetSQLValueString($_POST['cb_inserttransaksiclaimbarang_checkbox'][$i], "int"));
  
  // redirect setelah selesai ke inserttransaksiclaimbarang2
  $insertGoTo = "InsertTransaksiClaimBarang2.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
  }
}
?>

<?php
$PAGE="Insert Barang";
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
        <small>Select</small>
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
        <div class="col-xs-12">
          <form action="<?php echo $editFormAction; ?>" id="fm_inserttransaksiclaimbarang_form1" name="fm_inserttransaksiclaimbarang_form1" method="POST">
            <div class="box box-primary">
              <div class="box-body">
                <table id="tb_inserttransaksiclaimbarang_example1" class="table table-bordered table-striped table-responsive">
    <thead>
      <tr>
		<th>Pilih</th>
		<th>J/S</th>
		<th>Barang</th>
		<th>Quantity Ditempat</th>
		<th>No. Purchase</th>
      </tr>
    </thead>
    <tbody>
    <?php $increment = 1; ?>
	<?php do { ?>
    <?php 
					
					$tx_inserttransaksiclaimbarang_Tgl = substr($_SESSION['tx_inserttransaksiclaim_Tgl'], 1, -1);
					
					$tgl = $tx_inserttransaksiclaimbarang_Tgl;
					$convert = str_replace('/', '-', $tgl);
					$tgls = $row_InsertTransaksiClaim['S'];
					$converts = str_replace('/', '-', $tgls);
					$tgle = $row_InsertTransaksiClaim['E'];
					$converte = str_replace('/', '-', $tgle);
					
					$check = strtotime($convert);
					$checks = strtotime($converts);
					$checke = strtotime($converte);
					
					?>
	  <tr>
	    <td align="center"><input type="checkbox" name="cb_inserttransaksiclaimbarang_checkbox[]" id="cb_inserttransaksiclaimbarang_checkbox" value="<?php echo $row_InsertTransaksiClaim['Purchase']; ?>" <?php if ($check < $checks){ ?> disabled <?php }elseif ($check > $checke){ ?> disabled <?php }elseif ($row_InsertTransaksiClaim['QSisaKem'] == 0){ ?> disabled <?php } ?>></td>
	    <td><input name="tx_inserttransaksiclaimbarang_JS[]" type="text" class="form-control" id="tx_inserttransaksiclaimbarang_JS" value="<?php echo $row_InsertTransaksiClaim['JS']; ?>" readonly></td>
	    <td><input name="tx_inserttransaksiclaimbarang_Barang[]" type="text" class="form-control" id="tx_inserttransaksiclaimbarang_Barang" value="<?php echo $row_InsertTransaksiClaim['Barang']; ?>" readonly></td>
	    <td><input name="tx_inserttransaksiclaimbarang_Quantity[]" type="text" class="form-control" id="tx_inserttransaksiclaimbarang_Quantity" value="<?php echo $row_InsertTransaksiClaim['QSisaKem']; ?>" readonly></td>
	    <td><input name="tx_inserttransaksiclaimbarang_Purchase[]" type="text" class="form-control" id="tx_inserttransaksiclaimbarang_Purchase" value=<?php echo $row_InsertTransaksiClaim['Purchase']; ?> readonly></td>
	    </tr>
	  <?php $increment++; ?>
	  <?php } while ($row_InsertTransaksiClaim = mysql_fetch_assoc($InsertTransaksiClaim)); ?>
      <p><label><input type="checkbox" id="SelectAll"/> Check all</label></p>
	</tbody>
                </table>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <input type="submit" name="bt_inserttransaksiclaimbarang_submit" id="bt_inserttransaksiclaimbarang_submit" class="btn btn-primary pull-right" value="Choose" disabled>
                <a href="InsertTransaksiClaim.php?Reference=<?php echo $_GET['Reference'] ?>"><button type="button" class="btn btn-default">Cancel</button></a>
              </div>
            </div>
            <!-- /.box -->
  <input type="hidden" name="MM_insert" value="form1">
  <input type="hidden" name="MM_update" value="form1">
</form>
          <!-- /.col -->
        </div>
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
var checkboxes = $("input[type='checkbox']"),
    submitButt = $("input[type='submit']");

checkboxes.click(function() {
    submitButt.attr("disabled", !checkboxes.is(":checked"));
});
</script>

<script>
$('#SelectAll').click(function () {
    var checked_status = this.checked;

    $('input[type=checkbox]').not(":disabled").prop('checked', checked_status);
});
</script>


<?php
  mysql_free_result($LastId);
  mysql_free_result($InsertTransaksiClaim);
?>
