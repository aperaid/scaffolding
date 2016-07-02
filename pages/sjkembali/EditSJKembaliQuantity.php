<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

//Assign SJKEM dari URL
$colname_View = "-1"; //assign default value auto dari dreamweaver (masya ollo)
if (isset($_GET['SJKem'])) {
  $colname_View = $_GET['SJKem'];
}

//Ambil Kode SJKEM lagi
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT SJKem FROM sjkembali WHERE SJKem = %s", GetSQLValueString($colname_View, "text"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);

//Assign SJKEM dari URL lagi
$colname_EditIsiSJKembali = "-1"; //assign default value auto dari dreamweaver (masya ollo)
if (isset($_GET['SJKem'])) {
  $colname_EditIsiSJKembali = $_GET['SJKem'];
}

//Query buat nunjukin isisjkembali
mysql_select_db($database_Connection, $Connection);
$query_EditIsiSJKembali = sprintf("SELECT isisjkembali.*, SUM(isisjkembali.QTertanda) AS QTertanda2, isisjkirim.QSisaKem, sjkirim.Tgl, transaksi.Barang, project.Project FROM isisjkembali INNER JOIN isisjkirim ON isisjkembali.IsiSJKir=isisjkirim.IsiSJKir INNER JOIN sjkirim ON isisjkirim.SJKir=sjkirim.SJKir INNER JOIN transaksi ON isisjkembali.Purchase=transaksi.Purchase INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode WHERE isisjkembali.SJKem = %s GROUP BY isisjkembali.Purchase ORDER BY isisjkembali.Id ASC", GetSQLValueString($colname_EditIsiSJKembali, "text"));
$EditIsiSJKembali = mysql_query($query_EditIsiSJKembali, $Connection) or die(mysql_error());
$row_EditIsiSJKembali = mysql_fetch_assoc($EditIsiSJKembali);
$totalRows_EditIsiSJKembali = mysql_num_rows($EditIsiSJKembali);

mysql_select_db($database_Connection, $Connection);
$query_EditIsiSJKembali2 = sprintf("SELECT isisjkembali.*, isisjkirim.QSisaKem, sjkirim.Tgl, transaksi.Barang, project.Project FROM isisjkembali INNER JOIN isisjkirim ON isisjkembali.IsiSJKir=isisjkirim.IsiSJKir INNER JOIN sjkirim ON isisjkirim.SJKir=sjkirim.SJKir INNER JOIN transaksi ON isisjkembali.Purchase=transaksi.Purchase INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode WHERE isisjkembali.SJKem = %s ORDER BY isisjkembali.Id ASC", GetSQLValueString($colname_EditIsiSJKembali, "text"));
$EditIsiSJKembali2 = mysql_query($query_EditIsiSJKembali2, $Connection) or die(mysql_error());
$row_EditIsiSJKembali2 = mysql_fetch_assoc($EditIsiSJKembali2);
$totalRows_EditIsiSJKembali2 = mysql_num_rows($EditIsiSJKembali2);

//Query khusus untuk ambil IsiSJKir apa aja yg ada di sjkembali ini 
$query = mysql_query($query_EditIsiSJKembali2, $Connection) or die(mysql_error());
$IsiSJKir = array();
while($row = mysql_fetch_assoc($query)){
	$IsiSJKir[] = $row['IsiSJKir'];
}
$IsiSJKir2 = join(',',$IsiSJKir); 

mysql_select_db($database_Connection, $Connection);
$query_Tgl = sprintf("SELECT Tgl FROM sjkembali WHERE SJKem = %s", GetSQLValueString($colname_EditIsiSJKembali, "text"));
$Tgl = mysql_query($query_Tgl, $Connection) or die(mysql_error());
$row_Tgl = mysql_fetch_assoc($Tgl);
$totalRows_Tgl = mysql_num_rows($Tgl);

//Query ambil tanggal start & end kembali S&E yg udah ada 
mysql_select_db($database_Connection, $Connection);
$query_Tanggal = sprintf("SELECT E FROM periode WHERE IsiSJKir IN ($IsiSJKir2) AND Deletes='Kembali'", GetSQLValueString($colname_EditIsiSJKembali, "text"));
$Tanggal = mysql_query($query_Tanggal, $Connection) or die(mysql_error());
$row_Tanggal = mysql_fetch_assoc($Tanggal);
$totalRows_Tanggal = mysql_num_rows($Tanggal);

//Query ambil E untuk enddate datepicker
/*	Rumusnya:
	Di dalam satu sjkembali, ada banyak isisjkir. Masing2 isisjkir, punya periodenya masing-masing.
	Masing-masing periode dari isisjkir tersebut, punya S dan E yg berbeda.
	Sehingga datepicker harus menunjukkan tanggal maksimal dengan tanggal E yg paling pertama
	Contoh, ada 2 isisjkir di satu sjkembali:
	#############################################################################################
	#	isisjkir	Q		S		E		Deletes												#
	#	--------	---		---		---		-------												#
	#	1			50		10/1	9/2		Extend	<-ini yg diambil E nya jadi maks datepicker	#
	#	2			50		15/1	14/2	Extend												#
	#############################################################################################
*/
//BINGUNG, masih blom dikerjain

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

// update overall qsisakembali di transaksi diupdate
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
for($i=0;$i<$totalRows_EditIsiSJKembali;$i++){
	$QTerima = GetSQLValueString($_POST['tx_editsjkembaliquantity_QTerima2'][$i], "int");
  $updateSQL = sprintf("UPDATE transaksi SET QSisaKem=QSisaKem+$QTerima-%s WHERE Purchase=%s",
                       GetSQLValueString($_POST['tx_editsjkembaliquantity_QTerima'][$i], "int"),
                       GetSQLValueString($_POST['hd_editsjkembaliquantity_Purchase'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
}

// jumlah qsisakembaliquantity di isisjkirim diupdate
for($i=0;$i<$totalRows_EditIsiSJKembali;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("CALL edit_sjkembaliquantity(%s, %s, %s)",
                       GetSQLValueString($_POST['tx_editsjkembaliquantity_QTerima'][$i], "int"),
                       GetSQLValueString($_POST['hd_editsjkembaliquantity_Purchase'][$i], "text"),
					   GetSQLValueString($_GET['SJKem'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
}

//update qterima di sjkembali
/*if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
for($i=0;$i<$totalRows_EditIsiSJKembali;$i++){
  $updateSQL = sprintf("UPDATE isisjkembali SET QTerima=%s WHERE Id=%s",
                       GetSQLValueString($_POST['tx_editsjkembaliquantity_QTerima'][$i], "int"),
                       GetSQLValueString($_POST['hd_editsjkembaliquantity_Id'][$i], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());

}
}*/

//update tanggal di periode
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE periode SET E=%s WHERE IsiSJKir IN ($IsiSJKir2) AND Deletes='Kembali' AND SJKem=%s",
                       GetSQLValueString($_POST['tx_editsjkembaliquantity_E'], "text"),
					   GetSQLValueString($colname_EditIsiSJKembali, "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());

// Redirect ke sjkembali
$updateGoTo = "ViewSJKembali.php";
if (isset($_SERVER['QUERY_STRING'])) {
	$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
	$updateGoTo .= $_SERVER['QUERY_STRING'];
}  
header(sprintf("Location: %s", $updateGoTo));

}
?>

<?php
$PAGE="Verifikasi Terima";
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
        <li class="active">Edit SJ Kembali Quantity</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
		<form action="<?php echo $editFormAction; ?>" id="fm_editsjkembaliquantity_form1" name="fm_editsjkembaliquantity_form1" method="POST">
          <div class="box box-primary">
            <div class="box-body no-padding">
				  <table id="tb_editsjkembaliquantity_example1" name="tb_editsjkembaliquantity_example1" class="table table-bordered">
					<thead>
					<tr>
					  <th>Tanggal Kirim</th>
					  <th>Barang</th>
					  <th>Warehouse</th>
                      <th>Q Pengambilan</th>
					  <th>Q Terima</th>
                	</tr>
					</thead>
					<tbody>
					  <?php 
					  $Min = $row_Tgl['Tgl'];
					  $Tgl = $row_Tanggal['E'];
					  $x=1; 
					  ?>
					  <?php do { ?>
						<tr>
									<input name="hd_editsjkembaliquantity_Id[]"			id="hd_editsjkembaliquantity_Id"							type="hidden"	value="<?php echo $row_EditIsiSJKembali['Id']; ?>">
									<input name="hd_editsjkembaliquantity_QSisaKem2"	id="hd_editsjkembaliquantity_QSisaKem2<?php echo $x; ?>"	type="hidden"	value="<?php echo $row_EditIsiSJKembali['QTertanda']; ?>">
									<input name="hd_editsjkembaliquantity_IsiSJKir[]"	id="hd_editsjkembaliquantity_IsiSJKir"						type="hidden"	class="textview"		value="<?php echo $row_EditIsiSJKembali['IsiSJKir']; ?>">
									<input name="hd_editsjkembaliquantity_Purchase[]"	id="hd_editsjkembaliquantity_Purchase"						type="hidden"	class="textview"		value="<?php echo $row_EditIsiSJKembali['Purchase']; ?>">
									<input name="tx_editsjkembaliquantity_QTerima2[]"	id="tx_editsjkembaliquantity_QTerima2"						type="hidden"	class="form-control"	autocomplete="off" value="<?php echo $row_EditIsiSJKembali['QTerima']; ?>">
							<td>	<input name="tx_editsjkembaliquantity_Tgl"			id="tx_editsjkembaliquantity_Tgl"							type="text"		class="form-control"	value="<?php echo $row_EditIsiSJKembali['Tgl']; ?>" readonly></td>
							<td>	<input name="tx_editsjkembaliquantity_Barang"		id="tx_editsjkembaliquantity_Barang"						type="text"		class="form-control"	value="<?php echo $row_EditIsiSJKembali['Barang']; ?>" readonly></td>
							<td>	<input name="tx_editsjkembaliquantity_Warehouse[]"	id="tx_editsjkembaliquantity_Warehouse"						type="text"		class="form-control"	value="<?php echo $row_EditIsiSJKembali['Warehouse']; ?>" readonly></td>
							<td>	<input name="tx_editsjkembaliquantity_QTertanda[]"	id="tx_editsjkembaliquantity_QTertanda"						type="text"		class="form-control"	value="<?php echo $row_EditIsiSJKembali['QTertanda2']; ?>"autocomplete="off"  readonly></td>
									<input name="tx_editsjkembaliquantity_QSisaKem[]"	id="tx_editsjkembaliquantity_QSisaKem<?php echo $x; ?>"		type="hidden"	class="form-control"	value="<?php echo $row_EditIsiSJKembali['QSisaKem']; ?>" readonly>
                            <td>	<input name="tx_editsjkembaliquantity_QTerima[]"	id="tx_editsjkembaliquantity_QTerima<?php echo $x; ?>"		type="text"		class="form-control"	value="<?php echo $row_EditIsiSJKembali['QTerima']; ?>" autocomplete="off" onkeyup="this.value = minmax(this.value, 0, <?php echo $row_EditIsiSJKembali['QTertanda2']; ?>)" onKeyUp="sisa();"  required></td>
                          </tr>
						<?php $x++; ?>
						<?php } while ($row_EditIsiSJKembali = mysql_fetch_assoc($EditIsiSJKembali)); ?>
					</tbody>
				  </table>
			</div>
            <!-- /.box-body -->
            <div class="box-footer">
				<label>Tanggal Selesai Penghitungan</label>
				<div class="input-group">
				<div class="input-group-addon">
                <i class="fa fa-calendar"></i>
                </div>
					<input name="tx_editsjkembaliquantity_E" type="text" class="form-control" id="tx_editsjkembaliquantity_E" autocomplete="off" value="<?php echo $Tgl; ?>" required>
				</div>
				<br>
				<a href="ViewSJKembali.php?SJKem=<?php echo $row_View['SJKem']; ?>"><button type="button" class="btn btn-default">Cancel</button></a>
				<button type="submit" name="bt_editsjkembaliquantity_submit" id="bt_editsjkembaliquantity_submit" class="btn btn-success pull-right">Update</button>
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
<script language="javascript">
  function sisa() {
  for(x = 1; x < 11; x++){
    var txtFirstNumberValue = document.getElementById('hd_editsjkembaliquantity_QSisaKem2'+x).value;
    var txtSecondNumberValue = document.getElementById('tx_editsjkembaliquantity_QTerima'+x).value;
	var result = parseInt(txtFirstNumberValue) - parseInt(txtSecondNumberValue);
	  if (!isNaN(result)) {
		document.getElementById('tx_editsjkembaliquantity_QSisaKem'+x).value = result;
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
var Min = <?php echo json_encode($Min); ?>;
  $('#tx_editsjkembaliquantity_E').datepicker({
	  format: "dd/mm/yyyy",
	  startDate: Min,
	  orientation: "bottom left",
	  todayHighlight: true,
	  autoclose: true
  }); 
</script>

<?php
  mysql_free_result($EditIsiSJKembali);
?>
