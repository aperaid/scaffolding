<?php require_once('../../connections/Connection.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "../login/Login.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../login/Login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

// Query Menu
mysql_select_db($database_Connection, $Connection);
$query_Menu = "SELECT * FROM menu";
$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
$row_Menu = mysql_fetch_assoc($Menu);
$totalRows_Menu = mysql_num_rows($Menu);

// Ambil Reference & Periode dari URI
$colname_InsertTransaksiClaim = "-1";
if (isset($_GET['Reference'])) {
  $colname_InsertTransaksiClaim = $_GET['Reference'];
  $colname_Periode = $_GET['Periode'];
}

$checkbox = $_SESSION['cb_inserttransaksiclaimbarang_checkbox'];
$remove = preg_replace("/[^0-9,.]/", "", $checkbox);
$test_count=count($remove);
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

// Ambil ID dari transaksi claim
mysql_select_db($database_Connection, $Connection);
$query_LastClaim = "SELECT MAX(Id) AS Id FROM transaksiclaim";
$LastClaim = mysql_query($query_LastClaim, $Connection) or die(mysql_error());
$row_LastClaim = mysql_fetch_assoc($LastClaim);
$totalRows_LastClaim = mysql_num_rows($LastClaim);

mysql_select_db($database_Connection, $Connection);
$query_GetId = sprintf("SELECT MAX(Id) AS Id FROM periode WHERE Reference = %s AND (Deletes = 'Sewa' OR Deletes = 'Extend') GROUP BY IsiSJKir ORDER BY Id DESC", GetSQLValueString($_GET['Reference'], "text"));
$GetId = mysql_query($query_GetId, $Connection) or die(mysql_error());
$row_GetId = mysql_fetch_assoc($GetId);
$totalRows_GetId = mysql_num_rows($GetId);

$Id = array();
do{
	$Id[] = $row_GetId['Id'];
} while ($row_GetId = mysql_fetch_assoc($GetId));
$Id2 = join(',',$Id);

// Ambil ID isisjkirim, purchase, qsisakem, barang, id periode dkk berdasarkan reference, periode, sewa/extend, dan isisjkir
mysql_select_db($database_Connection, $Connection);
$query_InsertTransaksiClaim = sprintf("SELECT isisjkirim.Purchase, SUM(isisjkirim.QSisaKem) AS QSisaKem, transaksi.Barang, periode.Periode, periode.IsiSJKir, periode.S, periode.E FROM isisjkirim LEFT JOIN periode ON isisjkirim.IsiSJKir = periode.IsiSJKir LEFT JOIN transaksi ON periode.Purchase=transaksi.Purchase LEFT JOIN pocustomer ON transaksi.Reference=pocustomer.Reference WHERE transaksi.Reference = %s AND periode.Periode = %s AND (Deletes = 'Sewa' OR Deletes = 'Extend') AND isisjkirim.Purchase IN ($Purchase) GROUP BY isisjkirim.Purchase ORDER BY periode.Id ASC", GetSQLValueString($colname_InsertTransaksiClaim, "text"), GetSQLValueString($colname_Periode, "text"));
$InsertTransaksiClaim = mysql_query($query_InsertTransaksiClaim, $Connection) or die(mysql_error());
$row_InsertTransaksiClaim = mysql_fetch_assoc($InsertTransaksiClaim);
$totalRows_InsertTransaksiClaim = mysql_num_rows($InsertTransaksiClaim);

mysql_select_db($database_Connection, $Connection);
$query_InsertTransaksiClaim2 = sprintf("SELECT periode.Quantity, periode.IsiSJKir, periode.Purchase FROM periode WHERE periode.Id IN ($Id2) AND periode.Reference=%s ORDER BY periode.Id ASC", GetSQLValueString($colname_InsertTransaksiClaim, "text"));
$InsertTransaksiClaim2 = mysql_query($query_InsertTransaksiClaim2, $Connection) or die(mysql_error());
$row_InsertTransaksiClaim2 = mysql_fetch_assoc($InsertTransaksiClaim2);
$totalRows_InsertTransaksiClaim2 = mysql_num_rows($InsertTransaksiClaim2);

$Quantity = array();
$IsiSJKir = array();
$Purchase2 = array();
$Claim = array();
$x = 1;
do{
	$Quantity[]=$row_InsertTransaksiClaim2['Quantity'];
	$IsiSJKir[]=$row_InsertTransaksiClaim2['IsiSJKir'];
	$Purchase2[]=$row_InsertTransaksiClaim2['Purchase'];
	$Claim[]=$row_LastClaim['Id']+$x;
	$x++;
} while ($row_InsertTransaksiClaim2 = mysql_fetch_assoc($InsertTransaksiClaim2));
$Claim2 = join(',',$Claim);

// Ambil reference dari URI masukin ke $colname_Reference
$colname_Reference = "-1";
if (isset($_GET['Reference'])) {
  $colname_Reference = $_GET['Reference'];
}

// Ambil reference dari URI mauskin ke $colname_Periode
$colname_Periode = "-1";
if (isset($_GET['Reference'])) {
  $colname_Periode = $_GET['Reference'];
}

// Ambil max periode dari periode parameter: reference, sewa/extend
mysql_select_db($database_Connection, $Connection);
$query_Periode = sprintf("SELECT MAX(Periode) AS Periode FROM periode WHERE Reference = %s AND (Deletes = 'Sewa' OR Deletes = 'Extend')", GetSQLValueString($colname_Periode, "text"));
$Periode = mysql_query($query_Periode, $Connection) or die(mysql_error());
$row_Periode = mysql_fetch_assoc($Periode);
$totalRows_Periode = mysql_num_rows($Periode);

// Ambil ID dari invoice
mysql_select_db($database_Connection, $Connection);
$query_LastId = "SELECT MAX(Id) AS Id FROM invoice";
$LastId = mysql_query($query_LastId, $Connection) or die(mysql_error());
$row_LastId = mysql_fetch_assoc($LastId);
$totalRows_LastId = mysql_num_rows($LastId);

//Insert Periode
for ($i=0;$i<$totalRows_InsertTransaksiClaim2;$i++){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO periode (Periode, S, E, Quantity, IsiSJKir, Reference, Purchase, Deletes) VALUES (%s, %s, %s, %s, %s, %s, %s, 'Claim')",
                       GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_Periode'], "int"),
                       GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_S'], "text"),
                       GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_E'], "text"),
                       GetSQLValueString($Quantity[$i], "int"),
                       GetSQLValueString($IsiSJKir[$i], "text"),
					   GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_Reference'], "text"),
					   GetSQLValueString($Purchase2[$i], "text"));
					   
  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
}
}

//Insert Invoice
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO invoice (Invoice, JSC, Tgl, PPN, Reference, Periode) VALUES (%s, 'Claim', %s, %s, %s, %s)",
                       GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_Invoice'], "text"),
                       GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_E'], "text"),
                       GetSQLValueString($_POST['tx_inserttransaksiclaimbarang2_PPN'], "int"),
                       GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_Reference'], "text"),
                       GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_Periode'], "int"));
  $deleteSQL = sprintf("DELETE FROM invoice WHERE invoice.Periode NOT IN (SELECT periode.Periode FROM periode WHERE Reference=%s) AND Reference=%s",
                       GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_Reference'], "text"),
					   GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_Reference'], "text"));
  $alterSQL = sprintf("ALTER TABLE invoice AUTO_INCREMENT = 1");
  
  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
}

for ($i=0;$i<$totalRows_InsertTransaksiClaim2;$i++){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $_SESSION['tx_inserttransaksiclaimbarang2_Amount'][$i] = sprintf("%s", GetSQLValueString($_POST['tx_inserttransaksiclaimbarang2_Amount'][$i], "int"));
  $_SESSION['hd_inserttransaksiclaimbarang2_E'] = sprintf("%s", GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_E'], "text"));
}
}

for($i=0;$i<$totalRows_InsertTransaksiClaim;$i++){
//Update transaksi, kurangin qsisakembali dengan jumlah yg diclaim
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {

  $updateSQL = sprintf("UPDATE transaksi SET QSisaKem=QSisaKem-%s WHERE Purchase=%s",
                       GetSQLValueString($_POST['tx_inserttransaksiclaimbarang2_QClaim'][$i], "int"),
                       GetSQLValueString($_POST['tx_inserttransaksiclaimbarang2_Purchase'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	$updateSQL = sprintf("CALL insert_claim(%s, %s, %s, %s)",
                       GetSQLValueString($_POST['tx_inserttransaksiclaimbarang2_QClaim'][$i], "int"),
                       GetSQLValueString($_POST['tx_inserttransaksiclaimbarang2_Purchase'][$i], "text"),
					   GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_Periode'], "int"),
					   GetSQLValueString($_POST['tx_inserttransaksiclaimbarang2_Claim'][$i], "text"));
	
  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());

  $insertGoTo = "InsertTransaksiClaimBarang3.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
}

//Kalau klik submit, hapus data2 dari session sebelumnya
//if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
//	unset($_SESSION['cb_inserttransaksiclaimbarang_checkbox']);
//	unset($_SESSION['tx_inserttransaksiclaim_Tgl']);
//	unset($_SESSION['tx_inserttransaksiclaim_Reference']);
//}

/* -------- USERNAME PRIVILEGE ---------- */
$colname_User = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_User = $_SESSION['MM_Username'];
}

mysql_select_db($database_Connection, $Connection);
$query_User = sprintf("SELECT Name FROM users WHERE Username = %s", GetSQLValueString($colname_User, "text"));
$User = mysql_query($query_User, $Connection) or die(mysql_error());
$row_User = mysql_fetch_assoc($User);
$totalRows_User = mysql_num_rows($User);
/*--------- END ---------------- */
?>

<?php
// Declare Root directory
$ROOT="../../";
$PAGE="Insert Q";
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
        <small>Item</small>
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
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Input Claim Item</h3>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
              <form action="<?php echo $editFormAction; ?>" id="fm_inserttransaksiclaimbarang2_form1" name="fm_inserttransaksiclaimbarang2_form1" method="POST">
                <div class="box-body">
                  <table class="table table-hover table-bordered" id="tb_inserttransaksiclaimbarang2_example1" name="tb_inserttransaksiclaimbarang2_example1">
    <thead>
      <tr>
		<th>No. Claim</th>
		<th>Barang</th>
		<th>Quantity Ditempat</th>
		<th>Quantity Claim</th>
		<th>Price</th>
		<th>No. Purchase</th>
      </tr>
    </thead>
    <tbody>
    
    <?php
	/*
	$TanggalS = $row_InsertTransaksiClaim['S'];
	$Convert = str_replace('/', '-', $TanggalS);
	$date = new DateTime($Convert);
	$TanggalE = $row_InsertTransaksiClaim['E'];
	$Convert2 = str_replace('/', '-', $TanggalE);
	$date2 = new DateTime($Convert2);
	$diff=date_diff($date2,$date);
	$Min = $diff->format("%a");
	*/
	?>
    
    <?php 
	$tx_inserttransaksiclaimbarang2_Tgl = substr($_SESSION['tx_inserttransaksiclaim_Tgl'], 1, -1);
	$tx_inserttransaksiclaimbarang2_Reference = substr($_SESSION['tx_inserttransaksiclaim_Reference'], 1, -1);
 	?> 
    
    <?php $increment = 1; ?>
	<?php do { ?>
    <?php
	
    $tgl = $tx_inserttransaksiclaimbarang2_Tgl;
	
	?>
    
	<tr>
    <input name="hd_inserttransaksiclaimbarang2_S" type="hidden" id="hd_inserttransaksiclaimbarang2_S" value="<?php echo $row_InsertTransaksiClaim['S']; ?>">
    <input name="hd_inserttransaksiclaimbarang2_E" type="hidden" id="hd_inserttransaksiclaimbarang2_E" value="<?php echo $tgl; ?>">
	<input name="hd_inserttransaksiclaimbarang2_IsiSJKir[]" type="hidden" id="hd_inserttransaksiclaimbarang2_IsiSJKir" value="<?php echo $row_InsertTransaksiClaim['IsiSJKir']; ?>">
	<input name="hd_inserttransaksiclaimbarang2_Reference" type="hidden" id="hd_inserttransaksiclaimbarang2_Reference" value="<?php echo $tx_inserttransaksiclaimbarang2_Reference; ?>">
	<input name="hd_inserttransaksiclaimbarang2_Periode" type="hidden" id="hd_inserttransaksiclaimbarang2_Periode" value="<?php echo $row_InsertTransaksiClaim['Periode']; ?>">
	<input name="hd_inserttransaksiclaimbarang2_Invoice" type="hidden" id="hd_inserttransaksiclaimbarang2_Invoice" value="<?php echo str_pad($row_LastId['Id'] + 1, 5, "0", STR_PAD_LEFT); ?>">
	<td><input name="tx_inserttransaksiclaimbarang2_Claim[]" type="text" class="form-control" id="tx_inserttransaksiclaimbarang2_Claim" value="<?php echo $row_LastClaim['Id'] + $increment; ?>" readonly></td>
	<td><input name="tx_inserttransaksiclaimbarang2_Barang" type="text" class="form-control" id="tx_inserttransaksiclaimbarang2_Barang" value="<?php echo $row_InsertTransaksiClaim['Barang']; ?>" readonly></td>
	<td><input name="tx_inserttransaksiclaimbarang2_QSisaKem" type="text" class="form-control" id="tx_inserttransaksiclaimbarang2_QSisaKem" value="<?php echo $row_InsertTransaksiClaim['QSisaKem']; ?>" readonly></td>
	<td><input name="tx_inserttransaksiclaimbarang2_QClaim[]" type="text" class="form-control" id="tx_inserttransaksiclaimbarang2_QClaim" autocomplete="off" onkeyup="this.value = minmax(this.value, 0, <?php echo $row_InsertTransaksiClaim['QSisaKem']; ?>)" required></td>
	<td><input name="tx_inserttransaksiclaimbarang2_Amount[]" type="text" class="form-control" id="tx_inserttransaksiclaimbarang2_Amount" autocomplete="off" required></td>
	<td><input name="tx_inserttransaksiclaimbarang2_Purchase[]" type="text" class="form-control" id="tx_inserttransaksiclaimbarang2_Purchase[]" value=<?php echo $row_InsertTransaksiClaim['Purchase']; ?> readonly></td>
	</tr>
	<?php $increment++; ?>
	<?php } while ($row_InsertTransaksiClaim = mysql_fetch_assoc($InsertTransaksiClaim)); ?>
    </table>
    </div>
                <!-- /.box-body -->
                <div class="box-footer">
                <table class="table table-hover table-bordered" id="tb_inserttransaksiclaimbarang2_example2" name="tb_inserttransaksiclaimbarang2_example2">
                    <thead>
                      <th>PPN</th>
                    </thead>
                    <tbody>
                      <tr>

          <td>
          <input name="tx_inserttransaksiclaimbarang2_PPN" type="hidden" id="tx_inserttransaksiclaimbarang2_PPN" value="0">
          <input name="tx_inserttransaksiclaimbarang2_PPN" type="checkbox" id="tx_inserttransaksiclaimbarang2_PPN" value="1"></td>
                      </tr>
    				</tbody>
                </table>
                <a href="InsertTransaksiClaimBarang.php?Reference=<?php echo $tx_inserttransaksiclaimbarang2_Reference; ?>&Periode=<?php echo $row_Periode['Periode']; ?>"><button type="button" class="btn btn-default pull-left">Cancel</button></a>
		   <button type="submit" name="bt_inserttransaksiclaimbarang2_submit" id="bt_inserttransaksiclaimbarang2_submit" class="btn btn-success pull-right">Insert</button>
                </div>
  <input type="hidden" name="MM_insert" value="form1">
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="MM_delete" value="form1">
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
function limit(text){
	var check = <?php echo $month ?>;
	var test = text.value; 
	var tgl = test.substr(3,2);
	var TanggalS = <?php echo json_encode($S) ?>;
	var TanggalS2 = <?php echo json_encode($S2) ?>;
	
    if (tgl < check) { 
		document.getElementById('hd_inserttransaksiclaimbarang2_S2').value = text.value;
    }
    else { 
		document.getElementById('hd_inserttransaksiclaimbarang2_S2').value = TanggalS2;
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
<?php
  mysql_free_result($Menu);
  mysql_free_result($InsertTransaksiClaim);
  mysql_free_result($LastClaim);
  mysql_free_result($Periode);
  mysql_free_result($LastId);
  mysql_free_result($User);
?>

<?php
if ((isset($_POST["MM_delete"])) && ($_POST["MM_delete"] == "form1")) {
	$deleteSQL = "CALL insert_claim2";

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
}
?>