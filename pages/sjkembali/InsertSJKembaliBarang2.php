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

$checkbox = $_SESSION['cb_insertsjkembalibarang_checkbox'];
$remove = preg_replace("/[^0-9,.]/", "", $checkbox);

error_reporting(E_ERROR); // bagian di ilangin error
$array = array();
    for ($i = 0; $i < 10; ++$i) { // krn bagian sini ga ngerti untuk count sesuai byk array
        $array[$i] = $remove[$i];
}
$count = count(array_filter($array));

$arrayaftercount = array();
    for ($i = 0; $i < $count; ++$i) {
        $arrayaftercount[$i] = $remove[$i];
}
	
$Purchase = join(',',$arrayaftercount); 

mysql_select_db($database_Connection, $Connection);
$query_LastIsiSJKembali = "SELECT MAX(Id) AS Id FROM isisjkembali";
$LastIsiSJKembali = mysql_query($query_LastIsiSJKembali, $Connection) or die(mysql_error());
$row_LastIsiSJKembali = mysql_fetch_assoc($LastIsiSJKembali);
$totalRows_LastIsiSJKembali = mysql_num_rows($LastIsiSJKembali);

$colname_GetId = "-1";
if (isset($_GET['Reference'])) {
  $colname_GetId = $_GET['Reference'];
}
mysql_select_db($database_Connection, $Connection);
$query_GetId = sprintf("SELECT MAX(Id) AS Id FROM periode WHERE Reference = %s AND (Deletes = 'Sewa' OR Deletes = 'Extend') GROUP BY IsiSJKir ORDER BY Id DESC", GetSQLValueString($colname_GetId, "text"));
$GetId = mysql_query($query_GetId, $Connection) or die(mysql_error());
$row_GetId = mysql_fetch_assoc($GetId);
$totalRows_GetId = mysql_num_rows($GetId);

$Id = array();
do{
	$Id[] = $row_GetId['Id'];
} while ($row_GetId = mysql_fetch_assoc($GetId));
$Id2 = join(',',$Id);

mysql_select_db($database_Connection, $Connection);
$query_InsertSJKembali = sprintf("SELECT isisjkirim.*, SUM(isisjkirim.QSisaKemInsert) AS QSisaKemInsert, periode.Periode, periode.S, sjkirim.SJKir, sjkirim.Tgl, transaksi.Barang, transaksi.Purchase, transaksi.Reference FROM isisjkirim LEFT JOIN periode ON isisjkirim.IsiSJKir=periode.IsiSJKir INNER JOIN sjkirim ON isisjkirim.SJKir=sjkirim.SJKir INNER JOIN transaksi ON isisjkirim.Purchase=transaksi.Purchase WHERE isisjkirim.Purchase IN ($Purchase) AND periode.Id IN ($Id2) AND transaksi.Reference=%s GROUP BY isisjkirim.Purchase ORDER BY periode.Id ASC", GetSQLValueString($colname_GetId, "text"));
$InsertSJKembali = mysql_query($query_InsertSJKembali, $Connection) or die(mysql_error());
$row_InsertSJKembali = mysql_fetch_assoc($InsertSJKembali);
$totalRows_InsertSJKembali = mysql_num_rows($InsertSJKembali);

mysql_select_db($database_Connection, $Connection);
$query_InsertSJKembali2 = sprintf("SELECT periode.Quantity, periode.IsiSJKir, periode.Purchase FROM periode WHERE periode.Id IN ($Id2) AND periode.Reference=%s ORDER BY periode.Id ASC", GetSQLValueString($colname_GetId, "text"));
$InsertSJKembali2 = mysql_query($query_InsertSJKembali2, $Connection) or die(mysql_error());
$row_InsertSJKembali2 = mysql_fetch_assoc($InsertSJKembali2);
$totalRows_InsertSJKembali2 = mysql_num_rows($InsertSJKembali2);

$Quantity = array();
$IsiSJKir = array();
$Purchase2 = array();
$IsiSJKem = array();
$x = 1;
do{
	$Quantity[]=$row_InsertSJKembali2['Quantity'];
	$IsiSJKir[]=$row_InsertSJKembali2['IsiSJKir'];
	$Purchase2[]=$row_InsertSJKembali2['Purchase'];
	$IsiSJKem[]=$row_LastIsiSJKembali['Id']+$x;
	$x++;
} while ($row_InsertSJKembali2 = mysql_fetch_assoc($InsertSJKembali2));
$Quantity2 = join(',',$Quantity);
$IsiSJKem2 = join(',',$IsiSJKem);

$query = mysql_query($query_InsertSJKembali, $Connection) or die(mysql_error());
$Periode = array();
while($row = mysql_fetch_assoc($query)){
	$Periode[] = $row['Periode'];
}
$Periode2 = join(',',$Periode);

mysql_select_db($database_Connection, $Connection);
$query_Invoice = sprintf("SELECT * FROM invoice WHERE Reference = %s AND Periode IN ($Periode2) GROUP BY Periode ORDER BY Id DESC", GetSQLValueString($colname_GetId, "text"));
$Invoice = mysql_query($query_Invoice, $Connection) or die(mysql_error());
$row_Invoice = mysql_fetch_assoc($Invoice);
$totalRows_Invoice = mysql_num_rows($Invoice);

for($i=0;$i<$totalRows_InsertSJKembali;$i++){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO sjkembali (SJKem, Tgl, Reference) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_SJKem'], "text"),
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_E'], "text"),
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_Reference'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
}

/*if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE periode SET Quantity=Quantity-%s WHERE Periode=%s AND IsiSJKir = %s AND Deletes !='KembaliS' AND Deletes != 'KembaliE' AND Deletes != 'ClaimS' AND Deletes != 'ClaimE' AND Deletes != 'Jual'",
                       GetSQLValueString($_POST['tx_insertsjkembalibarang2_QTertanda'][$i], "int"),
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_Periode'][$i], "int"),
					   GetSQLValueString($_POST['hd_insertsjkembalibarang2_IsiSJKir'][$i], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}*/

for ($i=0;$i<$totalRows_InsertSJKembali2;$i++){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO periode (Periode, S, E, Quantity, IsiSJKir, SJKem, Reference, Purchase, Deletes) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, 'Kembali')",
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_Periode'], "int"),
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_S'], "text"),
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_E'], "text"),
                       GetSQLValueString($Quantity[$i], "int"),
                       GetSQLValueString($IsiSJKir[$i], "text"),
					   GetSQLValueString($_POST['hd_insertsjkembalibarang2_SJKem'], "text"),
					   GetSQLValueString($_POST['hd_insertsjkembalibarang2_Reference'], "text"),
					   GetSQLValueString($Purchase2[$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
}
}

for ($i=0;$i<$totalRows_InsertSJKembali2;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	$updateSQL = sprintf("CALL insert_sjkembali(%s, %s, %s, %s)",
                       GetSQLValueString($_POST['tx_insertsjkembalibarang2_QTertanda'][$i], "int"),
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_Purchase'][$i], "text"),
					   GetSQLValueString($_POST['hd_insertsjkembalibarang2_Periode'], "int"),
					   GetSQLValueString($_POST['hd_insertsjkembalibarang2_SJKem'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
  
  $insertGoTo = "SJKembali.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
  }
} 
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	unset($_SESSION['cb_insertsjkembalibarang_checkbox']);
	unset($_SESSION['tx_insertsjkembali_SJKem']);
	unset($_SESSION['tx_insertsjkembali_Tgl']);
	unset($_SESSION['tx_insertsjkembali_Reference']);
}
?>

<?php
$PAGE="Insert Quantity";
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
        <small>Item</small>
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
              <form action="<?php echo $editFormAction; ?>" id="fm_insertsjkembalibarang2_form1" name="fm_insertsjkembalibarang2_form1" method="POST">
              <div class="box box-primary">
            	<div class="box-body">
                  <table id="tb_insertsjkembalibarang2_example1" name="tb_insertsjkiribarang2_example1" class="table table-bordered table-striped table-responsive">
                  <thead>
					  <tr>
					    <th>Tgl Extend</th>
					    <th>Barang</th>
					    <th>Warehouse</th>
					    <th>Q Sisa Kembali</th>
					    <th>Q Pengambilan</th>
					  </tr>
                    </thead>
                    <tbody>
           
	<?php 
	$tx_insertsjkembalibarang2_SJKem = substr($_SESSION['tx_insertsjkembali_SJKem'], 1, -1);
	$tx_insertsjkembalibarang2_Tgl = substr($_SESSION['tx_insertsjkembali_Tgl'], 1, -1);
	$tx_insertsjkembalibarang2_Reference = substr($_SESSION['tx_insertsjkembali_Reference'], 1, -1);
 	?>     
	
	<?php do { ?>
    <?php 

	$tgl = $tx_insertsjkembalibarang2_Tgl;

	?>
	  <tr>
      <!--<?php $FirstDate = substr($tx_insertsjkembalibarang2_Tgl, 2); ?>-->
      	  <input name="hd_insertsjkembalibarang2_SJKem" type="hidden" id="hd_insertsjkembalibarang2_SJKem" value="<?php echo $tx_insertsjkembalibarang2_SJKem; ?>">
          <input name="hd_insertsjkembalibarang2_Reference" type="hidden" id="hd_insertsjkembalibarang2_Reference" value="<?php echo $tx_insertsjkembalibarang2_Reference; ?>">
	      <input name="hd_insertsjkembalibarang2_Id[]" type="hidden" id="hd_insertsjkembalibarang2_Id" value="<?php echo $row_InsertSJKembali['Id']; ?>">
          <input name="hd_insertsjkembalibarang2_IsiSJKir[]" type="hidden" id="hd_insertsjkembalibarang2_IsiSJKir" value="<?php echo $row_InsertSJKembali['IsiSJKir']; ?>">
	      <input name="hd_insertsjkembalibarang2_Purchase[]" type="hidden" id="hd_insertsjkembalibarang2_Purchase" value="<?php echo $row_InsertSJKembali['Purchase']; ?>">
	        <input name="hd_insertsjkembalibarang2_Periode" type="hidden" id="hd_insertsjkembalibarang2_Periode" value="<?php echo $row_InsertSJKembali['Periode']; ?>">
	        <input name="hd_insertsjkembalibarang2_S" type="hidden" id="hd_insertsjkembalibarang2_S" value="<?php echo $row_InsertSJKembali['S']; ?>">
            <input name="hd_insertsjkembalibarang2_E" type="hidden" id="hd_insertsjkembalibarang2_E" value="<?php echo $tx_insertsjkembalibarang2_Tgl; ?>">
        <td><input name="tx_insertsjkembalibarang2_Tgl[]" type="text" class="form-control" id="tx_insertsjkembalibarang2_Tgl" value="<?php echo $row_InsertSJKembali['S']; ?>" readonly></td>
	    <td><input name="tx_insertsjkembalibarang2_Barang[]" type="text" class="form-control" id="tx_insertsjkembalibarang2_Barang" value="<?php echo $row_InsertSJKembali['Barang']; ?>" readonly></td>
	    <td><input name="tx_insertsjkembalibarang2_Warehouse[]" type="text" class="form-control" id="tx_insertsjkembalibarang2_Warehouse" autocomplete="off"></td>
	    <td><input name="tx_insertsjkembalibarang2_QSisaKem[]" type="text" class="form-control" id="tx_insertsjkembalibarang2_QSisaKem" value="<?php echo $row_InsertSJKembali['QSisaKemInsert']; ?>" readonly></td>
	    <td><input name="tx_insertsjkembalibarang2_QTertanda[]" type="text" class="form-control" id="tx_insertsjkembalibarang2_QTertanda" autocomplete="off" value="<?php echo $row_InsertSJKembali['QSisaKemInsert']; ?>" onkeyup="this.value = minmax(this.value, 0, <?php echo $row_InsertSJKembali['QSisaKemInsert']; ?>)" required></td>
	    </tr>
	  <?php } while ($row_InsertSJKembali = mysql_fetch_assoc($InsertSJKembali)); ?>
    				</tbody>
                </table>
                <input type="hidden" name="MM_insert" value="form1">
  			    <input type="hidden" name="MM_update" value="form1">
				<input type="hidden" name="MM_delete" value="form1">
                </div>
            <!-- /.box-body -->
            <div class="box-footer">
                  <a href="InsertSJKembaliBarang.php?Reference=<?php echo $_GET['Reference']; ?>"><button type="button" class="btn btn-default pull-left">Cancel</button></a>
                  <button type="submit" id="bt_insertsjkembalibarang2_submit" name="bt_insertsjkembalibarang2_submit" class="btn btn-success pull-right">Insert</button>
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

<?php
  mysql_free_result($Select);
  mysql_free_result($LastIsiSJKembali);
  mysql_free_result($Reference);
  mysql_free_result($InsertSJKembali);
  mysql_free_result($LastTglS);
?>

<?php
if ((isset($_POST["MM_delete"])) && ($_POST["MM_delete"] == "form1")) {
  $deleteSQL = "CALL insert_sjkembali2";

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
}
  
for ($i=0;$i<$totalRows_InsertSJKembali2;$i++){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO isisjkembali (IsiSJKem, QTertanda, Purchase, SJKem, Periode, IsiSJKir) SELECT %s, periode.Quantity, periode.Purchase, periode.SJKem, periode.Periode, periode.IsiSJKir FROM periode WHERE periode.SJKem = %s AND periode.IsiSJKir = %s",
                       GetSQLValueString($IsiSJKem[$i], "text"),
					   GetSQLValueString($_POST['hd_insertsjkembalibarang2_SJKem'], "text"),
					   GetSQLValueString($IsiSJKir[$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
}
}
?>

<?php
for ($i=0;$i<$totalRows_InsertSJKembali2;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	$updateSQL = sprintf("UPDATE isisjkembali SET isisjkembali.Warehouse = %s WHERE isisjkembali.periode = %s AND isisjkembali.IsiSJKem = %s AND isisjkembali.Warehouse IS NULL",
					   GetSQLValueString($_POST['tx_insertsjkembalibarang2_Warehouse'][$i], "text"),
					   GetSQLValueString($_POST['hd_insertsjkembalibarang2_Periode'], "text"),
					   GetSQLValueString($IsiSJKem[$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
}
?>