<?php require_once('../../connections/Connection.php'); ?>
<?php date_default_timezone_set("Asia/Krasnoyarsk"); ?>
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
$query_View = sprintf("SELECT SJKir FROM sjkirim WHERE SJKir = %s", GetSQLValueString($colname_View, "text"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);

$colname_EditIsiSJKirim = "-1";
if (isset($_GET['SJKir'])) {
  $colname_EditIsiSJKirim = $_GET['SJKir'];
  $colname_Periode = $_GET['Periode'];
  $colname_Reference = $_GET['Reference'];
}
//tanggal min
mysql_select_db($database_Connection, $Connection);
$query_TanggalMin = sprintf("SELECT Tgl FROM sjkirim WHERE SJKir = %s", GetSQLValueString($colname_EditIsiSJKirim, "text"));
$TanggalMin = mysql_query($query_TanggalMin, $Connection) or die(mysql_error());
$row_TanggalMin = mysql_fetch_assoc($TanggalMin);
$totalRows_TanggalMin = mysql_num_rows($TanggalMin);

//tanggal max
mysql_select_db($database_Connection, $Connection);
$query_TanggalMax = sprintf("SELECT periode.E FROM periode LEFT JOIN isisjkirim ON periode.IsiSJKir=isisjkirim.IsiSJKir WHERE Reference = %s AND Periode = %s", GetSQLValueString($colname_Reference, "text"), GetSQLValueString($colname_Periode, "text"));
$TanggalMax = mysql_query($query_TanggalMax, $Connection) or die(mysql_error());
$row_TanggalMax = mysql_fetch_assoc($TanggalMax);
$totalRows_TanggalMax = mysql_num_rows($TanggalMax);

mysql_select_db($database_Connection, $Connection);
$query_TglValue = sprintf("SELECT periode.S FROM periode LEFT JOIN isisjkirim ON periode.IsiSJKir=isisjkirim.IsiSJKir WHERE Reference = %s AND Periode = %s AND SJKir = %s", GetSQLValueString($colname_Reference, "text"), GetSQLValueString($colname_Periode, "text"), GetSQLValueString($colname_EditIsiSJKirim, "text"));
$TglValue = mysql_query($query_TglValue, $Connection) or die(mysql_error());
$row_TglValue = mysql_fetch_assoc($TglValue);
$totalRows_TglValue = mysql_num_rows($TglValue);

mysql_select_db($database_Connection, $Connection);
$query_EditIsiSJKirim = sprintf("SELECT isisjkirim.*, transaksi.Barang, transaksi.JS, transaksi.QSisaKir, project.Project FROM isisjkirim INNER JOIN transaksi ON isisjkirim.Purchase=transaksi.Purchase INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode WHERE isisjkirim.SJKir = %s ORDER BY isisjkirim.Id ASC", GetSQLValueString($colname_EditIsiSJKirim, "text"));
$EditIsiSJKirim = mysql_query($query_EditIsiSJKirim, $Connection) or die(mysql_error());
$row_EditIsiSJKirim = mysql_fetch_assoc($EditIsiSJKirim);
$totalRows_EditIsiSJKirim = mysql_num_rows($EditIsiSJKirim);

for($i=0;$i<$totalRows_EditIsiSJKirim;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE periode SET E=%s WHERE IsiSJKir=%s AND (Deletes='Sewa' OR  Deletes='Jual')",
                       GetSQLValueString($row_TanggalMax['E'], "text"),
					   GetSQLValueString($_POST['hd_editsjkirimquantity_IsiSJKir'][$i], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

for($i=0;$i<$totalRows_EditIsiSJKirim;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	$QTertanda = GetSQLValueString($_POST['tx_editsjkirimquantity_QTertanda2'][$i], "int");
    $updateSQL = sprintf("UPDATE transaksi SET QSisaKir=QSisaKir+$QTertanda-%s, QSisaKem=QSisaKem-$QTertanda+%s WHERE Purchase=%s",
                       GetSQLValueString($_POST['tx_editsjkirimquantity_QTertanda'][$i], "int"),
					   GetSQLValueString($_POST['tx_editsjkirimquantity_QTertanda'][$i], "int"),
                       GetSQLValueString($_POST['hd_editsjkirimquantity_Purchase'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE isisjkirim SET QTertanda=%s, QSisaKemInsert=%s, QSisaKem=%s WHERE Id=%s",
                       GetSQLValueString($_POST['tx_editsjkirimquantity_QTertanda'][$i], "int"),
					   GetSQLValueString($_POST['tx_editsjkirimquantity_QTertanda'][$i], "int"),
					   GetSQLValueString($_POST['tx_editsjkirimquantity_QTertanda'][$i], "int"),
                       GetSQLValueString($_POST['hd_editsjkirimquantity_Id'][$i], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE transaksi SET QSisaKem=0 WHERE JS='Jual'");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}


if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE periode SET S=%s WHERE IsiSJKir=%s AND (Deletes='Sewa' OR  Deletes='Jual')",
                       GetSQLValueString($_POST['tx_editsjkirimquantity_S'], "text"),
					   GetSQLValueString($_POST['hd_editsjkirimquantity_IsiSJKir'][$i], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE isisjkirim LEFT JOIN transaksi ON isisjkirim.Purchase=transaksi.Purchase SET isisjkirim.QSisaKemInsert=0, isisjkirim.QSisaKem=0 WHERE transaksi.JS='Jual'");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());

  $updateGoTo = "ViewSJKirim.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  
  header(sprintf("Location: %s", $updateGoTo));
}

?>

<?php
$PAGE="Verifikasi Pengiriman";
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
        <li class="active">Edit SJ Kirim Quantity</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
		<form action="<?php echo $editFormAction; ?>" id="fm_editsjkirimquantity_form1" name="fm_editsjkirimquantity_form1" method="POST">
          <div class="box box-primary">
            <div class="box-body no-padding">
				<table id="tb_editsjkirimquantity_example1" class="table table-bordered">
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
                    <?php
					$Min = $row_TanggalMin['Tgl'];
					$Max = $row_TanggalMax['E'];
				  ?>
                    	<?php $x=1; ?>
						<?php do { ?>
						  <tr>
                          	<input name="hd_editsjkirimquantity_Id[]" type="hidden" id="hd_editsjkirimquantity_Id" value="<?php echo $row_EditIsiSJKirim['Id']; ?>">
							<input name="hd_editsjkirimquantity_QSisaKir2" type="hidden" id="hd_editsjkirimquantity_QSisaKir2<?php echo $x; ?>" value="<?php echo $row_EditIsiSJKirim['QSisaKir']; ?>">
                            <input name="hd_editsjkirimquantity_IsiSJKir[]" type="hidden" class="textview" id="hd_editsjkirimquantity_IsiSJKir" value="<?php echo $row_EditIsiSJKirim['IsiSJKir']; ?>">
                            <input name="hd_editsjkirimquantity_Purchase[]" type="hidden" class="textview" id="hd_editsjkirimquantity_Purchase" value="<?php echo $row_EditIsiSJKirim['Purchase']; ?>">
                            <input name="tx_editsjkirimquantity_QTertanda2[]" type="hidden" class="form-control" id="tx_editsjkirimquantity_QTertanda2" autocomplete="off" value="<?php echo $row_EditIsiSJKirim['QTertanda']; ?>">
							<td><input name="tx_editsjkirimquantity_JS" type="text" class="form-control" id="tx_editsjkirimquantity_JS" value="<?php echo $row_EditIsiSJKirim['JS']; ?>" readonly></td>
							<td><input name="tx_editsjkirimquantity_Barang" type="text" class="form-control" id="tx_editsjkirimquantity_Barang" value="<?php echo $row_EditIsiSJKirim['Barang']; ?>" readonly></td>
							<td><input name="hd_editsjkirimquantity_Warehouse[]" type="text" class="form-control" id="hd_editsjkirimquantity_Warehouse" value="<?php echo $row_EditIsiSJKirim['Warehouse']; ?>" readonly></td>
							<td><input name="tx_editsjkirimquantity_QKirim[]" type="text" class="form-control" id="tx_editsjkirimquantity_QKirim" autocomplete="off" value="<?php echo $row_EditIsiSJKirim['QKirim']; ?>" readonly></td>
                            <input name="tx_editsjkirimquantity_QSisaKir[]" type="hidden" class="form-control" id="tx_editsjkirimquantity_QSisaKir<?php echo $x; ?>" value="<?php echo $row_EditIsiSJKirim['QSisaKir']; ?>" readonly>
                            <td><input name="tx_editsjkirimquantity_QTertanda[]" type="text" class="form-control" id="tx_editsjkirimquantity_QTertanda<?php echo $x; ?>" autocomplete="off" onkeyup="this.value = minmax(this.value, 0, <?php echo $row_EditIsiSJKirim['QKirim']; ?>)" onKeyUp="sisa();" value="<?php echo $row_EditIsiSJKirim['QTertanda']; ?>" required></td>
                          </tr>
                          <?php $x++; ?>
						<?php } while ($row_EditIsiSJKirim = mysql_fetch_assoc($EditIsiSJKirim)); ?>
					</tbody>
				  </table>
					
			</div>
			
            <!-- /.box-body -->
            <div class="box-footer">
					<label>Tanggal Barang Sampai Tujuan/Tanggal Mulai Penghitungan</label>
					<div class="input-group">
					<div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                    </div>
					<input name="tx_editsjkirimquantity_S" type="text" class="form-control" id="tx_editsjkirimquantity_S" autocomplete="off" value="<?php echo $row_TglValue['S']; ?>" required>
					</div>
				<br>
				<a href="ViewSJKirim.php?SJKir=<?php echo $row_View['SJKir']; ?>"><button type="button" class="btn btn-default pull-left">Cancel</button></a>
				<button type="submit" name="bt_editsjkirimquantity_submit" id="bt_editsjkirimquantity_submit" class="btn btn-success pull-right">Update</button>
				
			</div>
          </div>
          <!-- /.box -->
		<input type="hidden" name="MM_update" value="form1">
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
<script language="javascript">
  function sisa() {
  for(x = 1; x < 11; x++){
    var txtFirstNumberValue = document.getElementById('hd_editsjkirimquantity_QSisaKir2'+x).value;
    var txtSecondNumberValue = document.getElementById('tx_editsjkirimquantity_QTertanda'+x).value;
	var result = parseInt(txtFirstNumberValue) - parseInt(txtSecondNumberValue);
	  if (!isNaN(result)) {
		document.getElementById('tx_editsjkirimquantity_QSisaKir'+x).value = result;
      }
   }
   }
</script>

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
  $('#tx_editsjkirimquantity_S').datepicker({
	  format: "dd/mm/yyyy",
	  startDate: Min,
	  endDate: Max,
	  orientation: "bottom left",
	  todayHighlight: true,
	  autoclose: true
  }); 
</script>
<?php
  mysql_free_result($EditIsiSJKirim);
  mysql_free_result($View);
?>