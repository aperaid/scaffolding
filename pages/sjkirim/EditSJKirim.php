<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

$colname_View = "-1";
if (isset($_GET['SJKir'])) {
  $colname_View = $_GET['SJKir'];
}
mysql_select_db($database_Connection, $Connection);
$query_TanggalMin = sprintf("SELECT pocustomer.Tgl FROM pocustomer LEFT JOIN sjkirim ON pocustomer.Reference=sjkirim.Reference WHERE sjkirim.SJKir = %s", GetSQLValueString($colname_View, "text"));
$TanggalMin = mysql_query($query_TanggalMin, $Connection) or die(mysql_error());
$row_TanggalMin = mysql_fetch_assoc($TanggalMin);
$totalRows_TanggalMin = mysql_num_rows($TanggalMin);

$colname_EditIsiSJKirim = "-1";
if (isset($_GET['SJKir'])) {
  $colname_EditIsiSJKirim = $_GET['SJKir'];
}
mysql_select_db($database_Connection, $Connection);
$query_EditIsiSJKirim = sprintf("SELECT isisjkirim.*, sjkirim.Tgl, transaksi.Barang, transaksi.JS, transaksi.Quantity, transaksi.QSisaKir, project.Project FROM isisjkirim LEFT JOIN sjkirim ON isisjkirim.SJKir=sjkirim.SJKir INNER JOIN transaksi ON isisjkirim.Purchase=transaksi.Purchase INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode WHERE isisjkirim.SJKir = %s ORDER BY isisjkirim.Id ASC", GetSQLValueString($colname_EditIsiSJKirim, "text"));
$EditIsiSJKirim = mysql_query($query_EditIsiSJKirim, $Connection) or die(mysql_error());
$row_EditIsiSJKirim = mysql_fetch_assoc($EditIsiSJKirim);
$totalRows_EditIsiSJKirim = mysql_num_rows($EditIsiSJKirim);


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$SJKir = $_GET['SJKir'];

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE sjkirim SET Tgl=%s WHERE SJKir = '$SJKir'",
 					   GetSQLValueString($_POST['tx_editsjkirim_Tgl'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}

for($i=0;$i<$totalRows_EditIsiSJKirim;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("SELECT edit_sjkirim(%s,%s,%s)",
                       GetSQLValueString($_POST['hd_editsjkirim_IsiSJKir'][$i], "int"),
                       GetSQLValueString($_POST['tx_editsjkirim_QKirim'][$i], "int"),
                       GetSQLValueString($_POST['tx_editsjkirim_Warehouse'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());

  $updateGoTo = "ViewSJKirim.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  
  header(sprintf("Location: %s", $updateGoTo));
}

}

?>

<?php
$PAGE="Edit SJ Kirim";
$top_menu_sel="menu_sjkirim";
include_once($ROOT . 'pages/html_header.php');
include_once($ROOT . 'pages/html_main_header.php');
?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Surat Jalan Kirim
        <small>Edit</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="../SJKirim/SJKirim.php">SJ Kirim</a></li>
        <li class="active">Edit SJ Kirim</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
		<form action="<?php echo $editFormAction; ?>" id="fm_editsjkirim_form1" name="fm_editsjkirim_form1" method="POST">
          <div class="box box-primary">
            <div class="box-body no-padding">
				  <table id="tb_editsjkirim_example1" class="table table-bordered">
					<thead>
					<tr>
						<th>J/S</th>
						<th>Barang</th>
						<th>Warehouse</th>
                        <th>Q Kirim</th>
						<th>Q Tertanda</th>
					</tr>
					</thead>
					<tbody>
                    <?php $Tgl = $row_EditIsiSJKirim['Tgl']; ?>
                    <?php
					$Min = $row_TanggalMin['Tgl'];
				  ?>
						<?php do { ?>
						<tr>
							<input name="hd_editsjkirim_Id[]" type="hidden" id="hd_editsjkirim_Id" value="<?php echo $row_EditIsiSJKirim['Id']; ?>">
                            <input name="hd_editsjkirim_Purchase[]" type="hidden" id="hd_editsjkirim_Purchase" value="<?php echo $row_EditIsiSJKirim['Purchase']; ?>">
                            <input name="hd_editsjkirim_IsiSJKir[]" type="hidden" id="hd_editsjkirim_IsiSJKir" value="<?php echo $row_EditIsiSJKirim['IsiSJKir']; ?>">
							<td><input name="tx_editsjkirim_JS" type="text" class="form-control" id="tx_editsjkirim_JS" value="<?php echo $row_EditIsiSJKirim['JS']; ?>" readonly></td>
							<td><input name="tx_editsjkirim_Barang" type="text" class="form-control" id="tx_editsjkirim_Barang" value="<?php echo $row_EditIsiSJKirim['Barang']; ?>" readonly></td>
							<td><input name="tx_editsjkirim_Warehouse[]" type="text" class="form-control" id="tx_editsjkirim_Warehouse" autocomplete="off" value="<?php echo $row_EditIsiSJKirim['Warehouse']; ?>"></td>
							<td><input name="tx_editsjkirim_QKirim[]" type="number" class="form-control" id="tx_editsjkirim_QKirim" autocomplete="off" value="<?php echo $row_EditIsiSJKirim['QKirim']; ?>" onkeyup="this.value = minmax(this.value, 0, <?php echo $row_EditIsiSJKirim['Quantity']; ?>)" required></td>
							<td><input name="tx_editsjkirim_QTertanda[]" type="text" class="form-control" id="tx_editsjkirim_QTertanda[]" autocomplete="off" value="<?php echo $row_EditIsiSJKirim['QTertanda']; ?>" readonly></td>
						</tr>
						<?php } while ($row_EditIsiSJKirim = mysql_fetch_assoc($EditIsiSJKirim)); ?>
					</tbody>
				  </table>
				<input type="hidden" name="MM_update" value="form1">
			</div>
            <!-- /.box-body -->
            <div class="box-footer">
            <label>Send Date</label>
					<div class="input-group">
					<div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                    </div>
					<input name="tx_editsjkirim_Tgl" type="text" class="form-control" id="tx_editsjkirim_Tgl" autocomplete="off" value="<?php echo $Tgl; ?>" required>
					</div>
				<br>
				<a href="ViewSJKirim.php?SJKir=<?php echo $_GET['SJKir']; ?>"><button type="button" class="btn btn-default">Cancel</button></a>
				<button type="submit" name="bt_editsjkirim_submit" id="bt_editsjkirim_submit" class="btn btn-success pull-right">Update</button>
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

<script>
var Min = <?php echo json_encode($Min) ?>;
  $('#tx_editsjkirim_Tgl').datepicker({
	  format: "dd/mm/yyyy",
	  startDate: Min,
	  orientation: "bottom left",
	  todayHighlight: true,
	  autoclose: true
  }); 
</script>
<?php
  mysql_free_result($EditIsiSJKirim);
  mysql_free_result($TanggalMin);
?>