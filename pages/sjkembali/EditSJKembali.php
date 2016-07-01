<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

$Reference = $_GET['Reference'];

// Tanggal Start & End
mysql_select_db($database_Connection, $Connection);
$query_TanggalMin = "SELECT S FROM periode WHERE Reference = '$Reference' AND Deletes = 'Sewa'";
$TanggalMin = mysql_query($query_TanggalMin, $Connection) or die(mysql_error());
$row_TanggalMin = mysql_fetch_assoc($TanggalMin);
$totalRows_TanggalMin = mysql_num_rows($TanggalMin);

mysql_select_db($database_Connection, $Connection);
$query_TanggalMax = "SELECT E FROM periode WHERE Reference = '$Reference' AND (Deletes = 'Sewa' OR Deletes = 'Extend') ORDER BY Id DESC";
$TanggalMax = mysql_query($query_TanggalMax, $Connection) or die(mysql_error());
$row_TanggalMax = mysql_fetch_assoc($TanggalMax);
$totalRows_TanggalMax = mysql_num_rows($TanggalMax);
// Tanggal Start & End

$colname_View = "-1";
if (isset($_GET['SJKem'])) {
  $colname_View = $_GET['SJKem'];
}

mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT SJKem, Tgl FROM sjkembali WHERE SJKem = %s", GetSQLValueString($colname_View, "text"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);

$colname_EditIsiSJKembali = "-1";
if (isset($_GET['SJKem'])) {
  $colname_EditIsiSJKembali = $_GET['SJKem'];
}
// Table MYSQL Fetch
mysql_select_db($database_Connection, $Connection);
$query_EditIsiSJKembali = sprintf("SELECT isisjkembali.*, isisjkirim.IsiSJKir, isisjkirim.QKirim, isisjkirim.QSisaKem, sjkirim.Tgl, transaksi.Barang, project.Project FROM isisjkembali INNER JOIN isisjkirim ON isisjkembali.IsiSJKir=isisjkirim.IsiSJKir INNER JOIN sjkirim ON isisjkirim.SJKir=sjkirim.SJKir INNER JOIN transaksi ON isisjkembali.Purchase=transaksi.Purchase INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode WHERE isisjkembali.SJKem = %s ORDER BY isisjkembali.Id ASC", GetSQLValueString($colname_EditIsiSJKembali, "text"));
$EditIsiSJKembali = mysql_query($query_EditIsiSJKembali, $Connection) or die(mysql_error());
$row_EditIsiSJKembali = mysql_fetch_assoc($EditIsiSJKembali);
$totalRows_EditIsiSJKembali = mysql_num_rows($EditIsiSJKembali);
// Table MYSQL Fetch

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

// Safety Net
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  for($i=0;$i<$totalRows_EditIsiSJKembali;$i++){
  $updateSQL = sprintf("SELECT edit_sjkembali(%s,%s,%s, %s)",
					   GetSQLValueString($_POST['hd_editsjkembali_IsiSJKem'][$i], "text"),
                       GetSQLValueString($_POST['tx_editsjkembali_QTertanda'][$i], "int"),
					   GetSQLValueString($_POST['tx_editsjkembali_Warehouse'][$i], "text"),
					   GetSQLValueString($_POST['tx_editsjkembali_Tgl2'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
  }

  $updateGoTo = "ViewSJKembali.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
// Safety Net

/* Rumus Awal
$QTertanda = $row_EditIsiSJKembali['QTertanda'];

$SJKem = $_GET['SJKem'];


if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE sjkembali SET Tgl=%s WHERE SJKem = '$SJKem'",
 					   GetSQLValueString($_POST['tx_editsjkembali_Tgl2'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}

for($i=0;$i<$totalRows_EditIsiSJKembali;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE isisjkirim SET QSisaKemInsert=QSisaKemInsert+$QTertanda-%s WHERE IsiSJKir=%s",
                       GetSQLValueString($_POST['tx_editsjkembali_QTertanda'][$i], "int"),
                       GetSQLValueString($_POST['hd_editsjkembali_IsiSJKir'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
	
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE isisjkembali SET Warehouse=%s, QTertanda=%s WHERE Id=%s",
                       GetSQLValueString($_POST['tx_editsjkembali_Warehouse'][$i], "text"),
					   GetSQLValueString($_POST['tx_editsjkembali_QTertanda'][$i], "int"),
                       GetSQLValueString($_POST['hd_editsjkembali_Id'][$i], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());

  $updateGoTo = "ViewSJKembali.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  
  header(sprintf("Location: %s", $updateGoTo));
  }
}
*/
?>

<?php
// Declare Root directory
$ROOT="../../";
$PAGE="Edit SJ Kembali";
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
        <small>Edit</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="../SJKembali/SJKembali.php">SJ Kembali</a></li>
        <li class="active">Edit SJ Kembali</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
		<form action="<?php echo $editFormAction; ?>" id="fm_editsjkembali_form1" name="fm_editsjkembali_form1" method="POST">
          <div class="box box-primary">
            <div class="box-body no-padding">
				  <table id="tb_editsjkembali_example1" name="tb_editsjkembali_example1" class="table table-bordered">
					<thead>
					<tr>
						<th>Tanggal Kirim</th>
						<th>Barang</th>
						<th>Warehouse</th>
						<th>Q di Proyek</th>
                        <th>Q Pengambilan</th>
					</tr>
					</thead>
					<tbody>
                    
                    <?php
					$Min = $row_TanggalMin['S'];
					
					$Max = $row_TanggalMax['E'];
				  ?>
                    
						<?php do { ?>
                        <tr>
							<input name="hd_editsjkembali_IsiSJKem[]" type="hidden" id="hd_editsjkembali_IsiSJKem" value="<?php echo $row_EditIsiSJKembali['IsiSJKem']; ?>">
                            <input name="hd_editsjkembali_IsiSJKir[]" type="hidden" id="hd_editsjkembali_IsiSJKir" value="<?php echo $row_EditIsiSJKembali['IsiSJKir']; ?>">
							<td><input name="tx_editsjkembali_Tgl" type="text" class="form-control" id="tx_editsjkembali_Tgl" value="<?php echo $row_EditIsiSJKembali['Tgl']; ?>" readonly></td>
							<td><input name="tx_editsjkembali_Barang" type="text" class="form-control" id="tx_editsjkembali_Barang" value="<?php echo $row_EditIsiSJKembali['Barang']; ?>" readonly></td>
							<td><input name="tx_editsjkembali_Warehouse[]" type="text" class="form-control" id="tx_editsjkembali_Warehouse" autocomplete="off" value="<?php echo $row_EditIsiSJKembali['Warehouse']; ?>"></td>
                            <td><input name="tx_editsjkembali_QSisaKem[]" type="text" class="form-control" id="tx_editsjkembali_QSisaKem" autocomplete="off" value="<?php echo $row_EditIsiSJKembali['QSisaKem']; ?>" readonly></td>
							<td><input name="tx_editsjkembali_QTertanda[]" type="text" class="form-control" id="tx_editsjkembali_QTertanda" autocomplete="off" value="<?php echo $row_EditIsiSJKembali['QTertanda']; ?>" onkeyup="this.value = minmax(this.value, 0, <?php echo $row_EditIsiSJKembali['QKirim']; ?>)" required></td>
							<input name="tx_editsjkembali_QTerima" type="hidden" class="form-control" id="tx_editsjkembali_QTerima" autocomplete="off" value="<?php echo $row_EditIsiSJKembali['QTerima']; ?>" readonly>
						</tr>
						<?php } while ($row_EditIsiSJKembali = mysql_fetch_assoc($EditIsiSJKembali)); ?>
					</tbody>
				  </table>
				<input type="hidden" name="MM_update" value="form1">
			</div>
            <!-- /.box-body -->
            <div class="box-footer">
            <label>Tanggal SJ Kembali</label>
					<div class="input-group">
					<div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                    </div>
					<input name="tx_editsjkembali_Tgl2" type="text" class="form-control" id="tx_editsjkembali_Tgl2" autocomplete="off" value="<?php echo $row_View['Tgl'] ?>" required>
					</div>
				<br>
            <div class="box-footer">
				<a href="ViewSJKembali.php?SJKem=<?php echo $row_View['SJKem']; ?>"><button type="button" class="btn btn-default">Cancel</button></a>
				<button type="submit" name="bt_editsjkembali_submit" id="bt_editsjkembali_submit" class="btn btn-success pull-right">Update</button>
			</div>
          </div>
          <!-- /.box -->
        </form>
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

<script>
var Min = <?php echo json_encode($Min) ?>;
var Max = <?php echo json_encode($Max) ?>;
  $('#tx_editsjkembali_Tgl2').datepicker({
	  format: "dd/mm/yyyy",
	  startDate: Min,
	  endDate: Max,
	  orientation: "bottom left",
	  todayHighlight: true,
	  autoclose: true
  }); 
</script>

<?php
  mysql_free_result($EditIsiSJKembali);
  mysql_free_result($View);
  mysql_free_result($TanggalMin);
  mysql_free_result($TanggalMax);
?>
