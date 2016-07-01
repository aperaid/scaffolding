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

$colname_InsertSJKirim = "-1";
if (isset($_GET['Reference'])) {
  $colname_InsertSJKirim = $_GET['Reference'];
}

$checkbox = $_SESSION['cb_insertsjkirimbarang_checkbox'];
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
$query_InsertSJKirim = sprintf("SELECT transaksi.Id, transaksi.Purchase, transaksi.Barang, transaksi.JS, transaksi.QSisaKirInsert, transaksi.Reference, project.Project FROM transaksi LEFT JOIN pocustomer ON transaksi.Reference=pocustomer.Reference LEFT JOIN project ON pocustomer.PCode=project.PCode WHERE transaksi.Reference = %s AND Purchase IN ($Purchase) ORDER BY transaksi.Id ASC",
						GetSQLValueString($colname_InsertSJKirim, "text"));
						
$InsertSJKirim = mysql_query($query_InsertSJKirim, $Connection) or die(mysql_error());
$row_InsertSJKirim = mysql_fetch_assoc($InsertSJKirim);
$totalRows_InsertSJKirim = mysql_num_rows($InsertSJKirim);

mysql_select_db($database_Connection, $Connection);
$query_LastIsiSJKirim = "SELECT Id FROM isisjkirim ORDER BY Id DESC";
$LastIsiSJKirim = mysql_query($query_LastIsiSJKirim, $Connection) or die(mysql_error());
$row_LastIsiSJKirim = mysql_fetch_assoc($LastIsiSJKirim);
$totalRows_LastIsiSJKirim = mysql_num_rows($LastIsiSJKirim);

$colname_Reference = "-1";
if (isset($_GET['Reference'])) {
  $colname_Reference = $_GET['Reference'];
}
mysql_select_db($database_Connection, $Connection);
$query_Reference = sprintf("SELECT Reference FROM transaksi WHERE Reference = %s ORDER BY Id DESC", GetSQLValueString($colname_Reference, "text"));
$Reference = mysql_query($query_Reference, $Connection) or die(mysql_error());
$row_Reference = mysql_fetch_assoc($Reference);
$totalRows_Reference = mysql_num_rows($Reference);

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO sjkirim (SJKir, Tgl, Reference, NoPolisi, Sopir, Kenek) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['hd_insertsjkirimbarang2_SJKir'], "text"),
                       GetSQLValueString($_POST['hd_insertsjkirimbarang2_Tgl'], "text"),
                       GetSQLValueString($_POST['hd_insertsjkirimbarang2_Reference'], "text"),
					   GetSQLValueString($_POST['tx_insertsjkirimbarang2_NoPolisi'], "text"),
                       GetSQLValueString($_POST['tx_insertsjkirimbarang2_Sopir'], "text"),
                       GetSQLValueString($_POST['tx_insertsjkirimbarang2_Kenek'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
}

for($i=0;$i<$totalRows_InsertSJKirim;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE transaksi SET QSisaKirInsert=QSisaKirInsert-%s WHERE Purchase=%s",
                       GetSQLValueString($_POST['tx_insertsjkirimbarang2_QKirim'][$i], "int"),
                       GetSQLValueString($_POST['hd_insertsjkirimbarang2_Purchase'][$i], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
}

for($i=0;$i<$totalRows_InsertSJKirim;$i++){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO isisjkirim (IsiSJKir, Warehouse, QKirim, Purchase, SJKir) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['hd_insertsjkirimbarang2_IsiSJKir'][$i], "text"),
                       GetSQLValueString($_POST['tx_insertsjkirimbarang2_Warehouse'][$i], "text"),
                       GetSQLValueString($_POST['tx_insertsjkirimbarang2_QKirim'][$i], "int"),
                       GetSQLValueString($_POST['hd_insertsjkirimbarang2_Purchase'][$i], "text"),
                       GetSQLValueString($_POST['hd_insertsjkirimbarang2_SJKir'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
  
    $insertSQL = sprintf("INSERT INTO periode (Periode, S, E, Quantity, IsiSJKir, Reference, Purchase, Deletes) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
					   GetSQLValueString($_POST['hd_insertsjkirimbarang2_Periode'], "text"),
					   GetSQLValueString($_POST['hd_insertsjkirimbarang2_Tgl'], "text"),
					   GetSQLValueString($_POST['hd_insertsjkirimbarang2_TglE'], "text"),
                       GetSQLValueString($_POST['tx_insertsjkirimbarang2_QKirim'][$i], "int"),
                       GetSQLValueString($_POST['hd_insertsjkirimbarang2_IsiSJKir'][$i], "text"),
					   GetSQLValueString($_POST['hd_insertsjkirimbarang2_Reference2'][$i], "text"),
					   GetSQLValueString($_POST['hd_insertsjkirimbarang2_Purchase'][$i], "text"),
					   GetSQLValueString($_POST['tx_insertsjkirimbarang2_JS'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "SJKirim.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
  }
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	unset($_SESSION['cb_insertsjkirimbarang_checkbox']);
	unset($_SESSION['tx_insertsjkirim_SJKir']);
	unset($_SESSION['tx_insertsjkirim_Tgl']);
	unset($_SESSION['tx_insertsjkirim_Reference']);
}

?>

<?php
// Declare Root directory
$ROOT="../../";
$PAGE="Insert Barang";
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
        <small>Item</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="../SJKirim/SJKirim.php">SJ Kirim</a></li>
        <li class="active">Insert SJ Kirim Barang</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
              <form action="<?php echo $editFormAction; ?>" id="fm_insertsjkirimbarang2_form1" name="fm_insertsjkirimbarang2_form1" method="POST">
              <div class="box box-primary">
            	<div class="box-body">
                  <table id="tb_insertsjkirimbarang2_example1" name="tb_insertsjkirimbarang2_example1" class="table table-bordered table-striped table-responsive">
                    <thead>
					  <tr>
					    <th>J/S</th>
					    <th>Barang</th>
					    <th>Warehouse</th>
					    <th>Q Sisa Kirim</th>
					    <th>Q Kirim</th>
					  </tr>
                    </thead>
                	<tbody>
	<?php 
	$tx_insertsjkirimbarang2_SJKir = substr($_SESSION['tx_insertsjkirim_SJKir'], 1, -1);
	$tx_insertsjkirimbarang2_Tgl = substr($_SESSION['tx_insertsjkirim_Tgl'], 1, -1);
	$tx_insertsjkirimbarang2_Reference = substr($_SESSION['tx_insertsjkirim_Reference'], 1, -1);
	
	mysql_select_db($database_Connection, $Connection);
	$query_ECont = sprintf("SELECT E, Reference, Periode FROM periode WHERE Reference = %s AND Periode = (SELECT MAX(Periode) FROM periode WHERE Reference = %s)", GetSQLValueString($tx_insertsjkirimbarang2_Reference, "text"), GetSQLValueString($tx_insertsjkirimbarang2_Reference, "text"));
	$ECont = mysql_query($query_ECont, $Connection) or die(mysql_error());
	$row_ECont = mysql_fetch_assoc($ECont);
	
	$end = str_replace('/', '-', $tx_insertsjkirimbarang2_Tgl);
	$end2 = strtotime("-1 day +1 month", strtotime($end));
	$end3 = date("d/m/Y", $end2);
	
	if($tx_insertsjkirimbarang2_Reference == $row_ECont['Reference']){
	
	$tglE = $row_ECont['E'];
	$periode = $row_ECont['Periode'];
	
	}else{
	$tglE = $end3;
	$periode = 1;
	}

 	?>
    <?php $increment = 1; ?>
	<?php do { ?>
	  <tr>
      	  <input name="hd_insertsjkirimbarang2_SJKir" type="hidden" id="hd_insertsjkirimbarang2_SJKir" value="<?php echo $tx_insertsjkirimbarang2_SJKir; ?>">
          <input name="hd_insertsjkirimbarang2_Tgl" type="hidden" id="hd_insertsjkirimbarang2_Tgl" value="<?php echo $tx_insertsjkirimbarang2_Tgl; ?>">
          <input name="hd_insertsjkirimbarang2_TglE" type="hidden" id="hd_insertsjkirimbarang2_TglE" value="<?php echo $tglE; ?>">
          <input name="hd_insertsjkirimbarang2_Reference" type="hidden" id="hd_insertsjkirimbarang2_Reference" value="<?php echo $tx_insertsjkirimbarang2_Reference; ?>">
	      <input name="hd_insertsjkirimbarang2_Id[]" type="hidden" id="hd_insertsjkirimbarang2_Id" value="<?php echo $row_InsertSJKirim['Id']; ?>">
          <input name="hd_insertsjkirimbarang2_Periode" type="hidden" id="hd_insertsjkirimbarang2_Periode" value="<?php echo $periode; ?>">
	      <input name="hd_insertsjkirimbarang2_Reference2[]" type="hidden" id="hd_insertsjkirimbarang2_Reference2" value="<?php echo $row_InsertSJKirim['Reference']; ?>">
	      <input name="hd_insertsjkirimbarang2_IsiSJKir[]" type="hidden" id="hd_insertsjkirimbarang2_IsiSJKir" value="<?php echo $row_LastIsiSJKirim['Id'] + $increment; ?>">
          <input name="hd_insertsjkirimbarang2_Purchase[]" type="hidden" id="hd_insertsjkirimbarang2_Purchase" value="<?php echo $row_InsertSJKirim['Purchase']; ?>">
	    <td><input name="tx_insertsjkirimbarang2_JS[]" type="text" class="form-control" id="tx_insertsjkirimbarang2_JS" value="<?php echo $row_InsertSJKirim['JS']; ?>" readonly></td>
	    <td><input name="tx_insertsjkirimbarang2_Barang[]" type="text" class="form-control" id="tx_insertsjkirimbarang2_Barang" value="<?php echo $row_InsertSJKirim['Barang']; ?>" readonly></td>
	    <td><input name="tx_insertsjkirimbarang2_Warehouse[]" type="text" class="form-control" id="tx_insertsjkirimbarang2_Warehouse" autocomplete="off"></td>
	    <td><input name="tx_insertsjkirimbarang2_QSisaKirInsert[]" type="text" class="form-control" id="tx_insertsjkirimbarang2_QSisaKirInsert<?php echo $increment; ?>" value="<?php echo $row_InsertSJKirim['QSisaKirInsert']; ?>" readonly></td>
	    <td><input name="tx_insertsjkirimbarang2_QKirim[]" type="text" class="form-control" id="tx_insertsjkirimbarang2_QKirim" autocomplete="off" value="<?php echo $row_InsertSJKirim['QSisaKirInsert']; ?>" onkeyup="this.value = minmax(this.value, 0, <?php echo $row_InsertSJKirim['QSisaKirInsert']; ?>)" required></td>
	    </tr>
	  <?php $increment++; ?>
	  <?php } while ($row_InsertSJKirim = mysql_fetch_assoc($InsertSJKirim)); ?>
    				</tbody>
                </table>
                <input type="hidden" name="MM_insert" value="form1">
  			    <input type="hidden" name="MM_update" value="form1">
                </div>
				<table id="tb_insertsjkirimbarang2_example2" name="tb_insertsjkirimbarang2_example2" class="table table-bordered table-striped table-responsive">
				<thead>
				<tr>
				<th>No Polisi</th>
				<th>Sopir</th>
				<th>Kenek</th>
				</tr>
				</thead>
				<tbody>
				<tr>
				<td><input name="tx_insertsjkirimbarang2_NoPolisi" type="text" class="form-control" id="tx_insertsjkirimbarang2_NoPolisi" autocomplete="off" required></td>
	    <td><input name="tx_insertsjkirimbarang2_Sopir" type="text" class="form-control" id="tx_insertsjkirimbarang2_Sopir" autocomplete="off" required></td>
	    <td><input name="tx_insertsjkirimbarang2_Kenek" type="text" class="form-control" id="tx_insertsjkirimbarang2_Kenek" autocomplete="off" required></td>
				</tr>
				</tbody>
				</table>
            <!-- /.box-body -->
            <div class="box-footer">
                  <a href="InsertSJKirimBarang.php?Reference=<?php echo $row_Reference['Reference']; ?>"><button type="button" class="btn btn-default pull-left">Cancel</button></a>
                  <button type="submit" id="bt_insertsjkirimbarang2_submit" name="bt_insertsjkirimbarang2_submit" class="btn btn-success pull-right">Insert</button>
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
  mysql_free_result($LastIsiSJKirim);
  mysql_free_result($Reference);
  mysql_free_result($InsertSJKirim);
?>
