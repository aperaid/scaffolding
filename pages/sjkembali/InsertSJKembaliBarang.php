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

$colname_InsertSJKembali = "-1";
if (isset($_GET['Reference'])) {
  $colname_InsertSJKembali = $_GET['Reference'];
}

mysql_select_db($database_Connection, $Connection);
$query_GetId = sprintf("SELECT MAX(Id) AS Id FROM periode WHERE Reference = %s AND (Deletes = 'Sewa' OR Deletes = 'Extend') GROUP BY IsiSJKir ORDER BY Id ASC", GetSQLValueString($colname_InsertSJKembali, "text"));
$GetId = mysql_query($query_GetId, $Connection) or die(mysql_error());
$row_GetId = mysql_fetch_assoc($GetId);
$totalRows_GetId = mysql_num_rows($GetId);

$query = mysql_query($query_GetId, $Connection) or die(mysql_error());
$Id2 = array();
while($row = mysql_fetch_assoc($query)){
	$Id2[] = $row['Id'];
}
$Id3 = join(',',$Id2); 

mysql_select_db($database_Connection, $Connection);
$query_InsertSJKembali = sprintf("SELECT isisjkirim.Purchase, SUM(isisjkirim.QSisaKemInsert) AS QSisaKemInsert, periode.S, periode.E,  sjkirim.SJKir, sjkirim.Tgl, transaksi.Barang FROM isisjkirim LEFT JOIN periode ON isisjkirim.IsiSJKir=periode.IsiSJKir INNER JOIN sjkirim ON isisjkirim.SJKir=sjkirim.SJKir INNER JOIN transaksi ON isisjkirim.Purchase=transaksi.Purchase WHERE sjkirim.Reference = %s AND transaksi.JS = 'Sewa' AND periode.Id IN ($Id3) GROUP BY isisjkirim.Purchase ORDER BY periode.Id ASC", GetSQLValueString($colname_InsertSJKembali, "text"));
$InsertSJKembali = mysql_query($query_InsertSJKembali, $Connection) or die(mysql_error());
$row_InsertSJKembali = mysql_fetch_assoc($InsertSJKembali);
$totalRows_InsertSJKembali = mysql_num_rows($InsertSJKembali);

mysql_select_db($database_Connection, $Connection);
$query_LastIsiSJKembali = "SELECT IsiSJKem FROM isisjkembali ORDER BY Id DESC";
$LastIsiSJKembali = mysql_query($query_LastIsiSJKembali, $Connection) or die(mysql_error());
$row_LastIsiSJKembali = mysql_fetch_assoc($LastIsiSJKembali);
$totalRows_LastIsiSJKembali = mysql_num_rows($LastIsiSJKembali);

mysql_select_db($database_Connection, $Connection);
$query_LastId = "SELECT Id FROM sjkembali ORDER BY Id DESC";
$LastId = mysql_query($query_LastId, $Connection) or die(mysql_error());
$row_LastId = mysql_fetch_assoc($LastId);
$totalRows_LastId = mysql_num_rows($LastId);

for($i=0;$i<$totalRows_InsertSJKembali;$i++){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $_SESSION['cb_insertsjkembalibarang_checkbox'][$i] = sprintf("%s", GetSQLValueString($_POST['cb_insertsjkembalibarang_checkbox'][$i], "int"));
  
  $insertGoTo = "InsertSJKembaliBarang2.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
  }
}
?>

<?php
$PAGE="Pick Product";
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
        <small>Pilih</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="../SJKembali/SJKembali.php">SJ Kembali</a></li>
        <li class="active">Insert SJ Kembali Barang</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <form action="<?php echo $editFormAction; ?>" id="fm_insertsjkembalibarang_form1" name="fm_insertsjkembalibarang_form1" method="POST">
            <div class="box box-primary">
              <div class="box-body">
                <table id="tb_insertsjkembalibarang_example1" class="table table-bordered table-striped table-responsive">
                  <thead>
                  <tr>
                    <th>Pilih</th>
                    <th>Tgl Extend</th>
                    <th>Barang</th>
                    <th>Quantity Sisa Kembali</th>
                    <th>SJ Code</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php $increment = 1; ?>
                    <?php do { ?>
                    <?php 
					
					$tx_insertsjkembalibarang_Tgl = substr($_SESSION['tx_insertsjkembali_Tgl'], 1, -1);
					
					$tgl = $tx_insertsjkembalibarang_Tgl;
					$convert = str_replace('/', '-', $tgl);
					$tgls = $row_InsertSJKembali['S'];
					$converts = str_replace('/', '-', $tgls);
					$tgle = $row_InsertSJKembali['E'];
					$converte = str_replace('/', '-', $tgle);
					
					$check = strtotime($convert);
					$checks = strtotime($converts);
					$checke = strtotime($converte);
					
					?>
                      <tr>
                        <td><input type="checkbox" name="cb_insertsjkembalibarang_checkbox[]" id="cb_insertsjkembalibarang_checkbox" value="<?php echo $row_InsertSJKembali['Purchase']; ?>" <?php if ($check < $checks){ ?> disabled <?php }elseif ($check > $checke){ ?> disabled <?php }elseif ($row_InsertSJKembali['QSisaKemInsert'] == 0){ ?> disabled <?php } ?>></td>
                        <td><input name="tx_insertsjkembalibarang_Tgl[]" type="text" class="form-control" id="tx_insertsjkembalibarang_Tgl" value="<?php echo $row_InsertSJKembali['S']; ?>" readonly></td>
                        <td><input name="tx_insertsjkembalibarang_Barang[]" type="text" class="form-control" id="tx_insertsjkembalibarang_Barang" value="<?php echo $row_InsertSJKembali['Barang']; ?>" readonly></td>
                        <td><input name="tx_insertsjkembalibarang_QSisaKemInsert[]" type="text" class="form-control" id="tx_insertsjkembalibarang_QSisaKemInsert" value="<?php echo $row_InsertSJKembali['QSisaKemInsert']; ?>" readonly></td>
                        <td><input name="tx_insertsjkembalibarang_SJKir[]" type="text" class="form-control" id="tx_insertsjkembalibarang_SJKir" value="<?php echo $row_InsertSJKembali['SJKir']; ?>" readonly></td>
                        </tr>
                      <?php $increment++; ?>
                      <?php } while ($row_InsertSJKembali = mysql_fetch_assoc($InsertSJKembali)); ?>
                      <p><label><input type="checkbox" id="SelectAll"/> Check all</label></p>
                  </tbody>
                </table>
              </div>
              <!-- /.box-body -->
               <div class="box-footer">
                <input type="submit" name="bt_insertsjkembalibarang_submit" id="bt_insertsjkembalibarang_submit" class="btn btn-primary pull-right" value="Choose" disabled>
                <a href="InsertSJKembali.php?Reference=<?php echo $_GET['Reference'] ?>"><button type="button" class="btn btn-default">Cancel</button></a>
              </div>
            </div>
            <!-- /.box -->
            <input type="hidden" name="MM_insert" value="form1">
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
  mysql_free_result($LastIsiSJKembali);
  mysql_free_result($LastId);
  mysql_free_result($InsertSJKembali);
?>
