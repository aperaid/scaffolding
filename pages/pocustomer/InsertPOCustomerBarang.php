<?php require_once('../../connections/Connection.php'); ?>
<?php date_default_timezone_set("Asia/Krasnoyarsk"); ?>
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

/*mysql_select_db($database_Connection, $Connection);
$query_POCode = sprintf("SELECT MAX(POCode) AS POCode FROM transaksi WHERE Reference=%s", GetSQLValueString($_GET['Reference'], "text"));
$POCode = mysql_query($query_POCode, $Connection) or die(mysql_error());
$row_POCode = mysql_fetch_assoc($POCode);
$totalRows_POCode = mysql_num_rows($POCode);

if($row_POCode['POCode'] == NULL){
	$POCode = 0;
}else
{
	$POCode = $row_POCode['POCode'];
}*/

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $_SESSION['tx_insertpocustomerbarang_Tgl'] = sprintf("%s", GetSQLValueString($_POST['tx_insertpocustomerbarang_Tgl'], "text"));
  $_SESSION['tx_insertpocustomerbarang_Discount'] = sprintf("%s", GetSQLValueString($_POST['tx_insertpocustomerbarang_Discount'], "text"));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO po (POCode, Tgl, Catatan, Transport) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['tx_insertpocustomerbarang_POCode'], "text"),
					   GetSQLValueString($_POST['tx_insertpocustomerbarang_Tgl'], "text"),
					   GetSQLValueString($_POST['tx_insertpocustomerbarang_Catatan'], "text"),
					   GetSQLValueString(str_replace(".","",substr($_POST['tx_insertpocustomerbarang_Transport'], 3)), "float"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
}

for($i=0;$i<10;$i++){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO transaksi (Purchase, JS, Barang, Quantity, QSisaKirInsert, QSisaKir, Amount, Reference, POCode) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['hd_insertpocustomerbarang_Purchase'][$i], "text"),
                       GetSQLValueString($_POST['db_insertpocustomerbarang_JS'][$i], "text"),
                       GetSQLValueString($_POST['tx_inputpocustomerbarang_Barang'][$i], "text"),
                       GetSQLValueString($_POST['tx_insertpocustomerbarang_Quantity'][$i], "int"),
					   GetSQLValueString($_POST['tx_insertpocustomerbarang_Quantity'][$i], "int"),
					   GetSQLValueString($_POST['tx_insertpocustomerbarang_Quantity'][$i], "int"),
                       GetSQLValueString($_POST['tx_insertpocustomerbarang_Amount'][$i], "text"),
                       GetSQLValueString($_POST['hd_inputpocustomerbarang_Reference'], "text"),
					   GetSQLValueString($_POST['tx_insertpocustomerbarang_POCode'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "InsertPOCustomerBarang2.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}}

mysql_select_db($database_Connection, $Connection);
$query_Purchase = "SELECT Id FROM transaksi ORDER BY Id DESC";
$Purchase = mysql_query($query_Purchase, $Connection) or die(mysql_error());
$row_Purchase = mysql_fetch_assoc($Purchase);
$totalRows_Purchase = mysql_num_rows($Purchase);

$colname_User = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_User = $_SESSION['MM_Username'];
}
mysql_select_db($database_Connection, $Connection);
$query_User = sprintf("SELECT Name FROM users WHERE Username = %s", GetSQLValueString($colname_User, "text"));
$User = mysql_query($query_User, $Connection) or die(mysql_error());
$row_User = mysql_fetch_assoc($User);
$totalRows_User = mysql_num_rows($User);
?>


<?php
// Declare Root directory
$ROOT="../../";
$PAGE="Insert Barang";
$top_menu_sel="menu_po";
include_once($ROOT . 'pages/html_header.php');
include_once($ROOT . 'pages/html_main_header.php');
?>
  
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Purchase Order
			<small>Item</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
			<li><a href="../POCustomer/POCustomer.php">Purchase Order</a></li>
			<li class="active">Insert PO Barang</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<form action="<?php echo $editFormAction; ?>" id="fm_insertpocustomerbarang_form1" name="fm_insertpocustomerbarang_form1" method="POST">	
			<div class="row">
				<div class="col-xs-12">
					<div class="box box-primary">
						<div class="box-body">
							<a href="ViewTransaksi.php?Reference=<?php echo $_GET['Reference']; ?>"><button type="button" class="btn btn-default pull-left">Cancel</button></a>
							<button type="submit" id="bt_insertpocustomerbarang_submit" class="btn btn-success pull-right">Insert</button>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-3">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">PO Detail</h3>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label for="exampleInputEmail1">Nomor PO</label>
								<input name="tx_insertpocustomerbarang_POCode" type="text" class="form-control" id="tx_insertpocustomerbarang_POCode" autocomplete="off" placeholder="Input PO Number" required>
							</div>
							<div class="form-group">
								<label for="exampleInputPassword1">Tanggal</label>
								<input name="tx_insertpocustomerbarang_Tgl" type="text" id="tx_insertpocustomerbarang_Tgl" class="form-control" autocomplete="off" placeholder="Date" required>
							</div>
							<div class="form-group">
								<label for="exampleInputPassword1">Transport</label>
								<input name="tx_insertpocustomerbarang_Transport" type="text" id="tx_insertpocustomerbarang_Transport" class="form-control" autocomplete="off" placeholder="Transport Fee" required>
							</div>
							<div class="form-group">
								<label>Discount</label>
								<input name="tx_insertpocustomerbarang_Discount" type="text" id="tx_insertpocustomerbarang_Discount" class="form-control" autocomplete="off" placeholder="Discount" required>
							</div>
							<div class="form-group">
								<label>Catatan</label>
								<textarea name="tx_insertpocustomerbarang_Catatan" type="textarea" id="tx_insertpocustomerbarang_Catatan" class="form-control" autocomplete="off" placeholder="Catatan" rows=5></textarea>
							</div>
							<input name="hd_inputpocustomerbarang_Reference" type="hidden" id="hd_inputpocustomerbarang_Reference" value="<?php echo $_GET['Reference']; ?>">
						</div>
					</div>        
				</div>
				<div class="col-md-9">
					<!-- general form elements -->
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Input PO Item</h3>
						</div>
						<div class="box-body">
							<table class="table table-hover table-bordered" id="tb_insertpocustomerbarang_customFields">
								<thead>
									<th><a href="javascript:void(0);" id="hf_insertpocustomerbarang_addCF" class=" glyphicon glyphicon-plus"></a></th>
									<th>Barang</th>
									<th>J/S</th>
									<th>Quantity</th>
									<th>Price</th>
								</thead>
							</table>
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</div>
			<!-- /.row -->
		<input type="hidden" name="MM_insert" value="form1">
		</form>
	</section>
	<!-- /.section -->
</div>
<!-- /.content-wrapper -->
  
<!-- Footer Wrapper -->
<?php include_once($ROOT . 'pages/footer.php'); ?>
<!-- /.footer-wrapper -->
<script>
  $('#tx_insertpocustomerbarang_Tgl').datepicker({
	  format: "dd/mm/yyyy",
	  orientation: "bottom left",
	  todayHighlight: true,
	  autoclose: true,
	  startDate: '-7d',
	  endDate: '+7d'
  }); 
</script>

<script>
    $(document).ready(function(){
		var max_fields      = 10; //maximum input boxes allowed
		
		var x = 0; //initial text box count
		var y = <?php echo $row_Purchase['Id']; ?>;
		var z = y;
		
		$("#hf_insertpocustomerbarang_addCF").click(function(){
			if(x < max_fields){ //max input box allowed
				x++; //text box count increment
				z++;
			$("#tb_insertpocustomerbarang_customFields").append('<tr><td><a href="javascript:void(0);" class="remCF glyphicon glyphicon-remove"></a></td><td class="hidden"><input type="hidden" name="hd_insertpocustomerbarang_Purchase[]" class="textbox" id="hd_insertpocustomerbarang_Purchase" value="'+ z +'"></td><td><input type="text" name="tx_inputpocustomerbarang_Barang[]" id="tx_inputpocustomerbarang_Barang" autocomplete="off" class="form-control" required></td><td><select name="db_insertpocustomerbarang_JS[]" id="db_insertpocustomerbarang_JS" class="form-control"><option>Jual</option><option>Sewa</option></select></td><td><input type="number" name="tx_insertpocustomerbarang_Quantity[]" id="tx_insertpocustomerbarang_Quantity" autocomplete="off" class="form-control" required></td><td><input type="number" name="tx_insertpocustomerbarang_Amount[]" autocomplete="off" id="tx_insertpocustomerbarang_Amount" class="form-control" required></td></tr>');
			}
		});
		
		$("#tb_insertpocustomerbarang_customFields").on('click','.remCF',function(){
			$(this).parent().parent().remove();
			x--;
		});
		
		//Mask Transport
		$("#tx_insertpocustomerbarang_Transport").maskMoney({prefix:'Rp ', allowZero: true, allowNegative: false, thousands:'.', decimal:',', affixesStay: true, precision: 0});
		//Mask Price
		$("#tx_insertpocustomerbarang_Amount").maskMoney({prefix:'Rp ', allowZero: true, allowNegative: false, thousands:'.', decimal:',', affixesStay: true, precision: 0});
		$("#tx_insertpocustomerbarang_Discount").maskMoney({prefix:'Rp ', allowZero: true, allowNegative: false, thousands:'.', decimal:',', affixesStay: true, precision: 0});
		
	});
</script>

<?php
  mysql_free_result($Purchase);
  mysql_free_result($User);
?>