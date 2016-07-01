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
	
	//Get pocode
	$pocode= $_GET['POCode'];
	
	//Get Reference for inserting
	mysql_select_db($database_Connection, $Connection);
	$query_reference = sprintf("SELECT DISTINCT transaksi.Reference AS reference FROM transaksi WHERE transaksi.POCode='$pocode'");
	$reference = mysql_query($query_reference, $Connection) or die(mysql_error());
	$row_reference = mysql_fetch_assoc($reference);
	$ref = $row_reference['reference'];

	//Get the data for PO Detail Box
	mysql_select_db($database_Connection, $Connection);
	$query_po = sprintf("SELECT po.POCode AS pocode, po.Tgl AS tgl, po.Transport AS transport, po.Catatan AS catatan FROM po WHERE po.POCode='$pocode'");
	$po = mysql_query($query_po, $Connection) or die(mysql_error());
	$row_po = mysql_fetch_assoc($po);
	
	//Get the data for Input PO Item
	mysql_select_db($database_Connection, $Connection);
	$query_poitem = sprintf("SELECT transaksi.Barang AS barang, transaksi.JS AS js, transaksi.Quantity AS quantity, transaksi.Amount AS price FROM transaksi WHERE transaksi.POCode='$pocode'");
	$poitem = mysql_query($query_poitem, $Connection) or die(mysql_error());
	$row_poitem = mysql_fetch_assoc($poitem);
	
	//Get last purchase id for po item table
	mysql_select_db($database_Connection, $Connection);
	$query_purchase = sprintf("SELECT max(transaksi.ID) AS purchase FROM transaksi");
	$purchase = mysql_query($query_purchase, $Connection) or die(mysql_error());
	$row_purchase = mysql_fetch_assoc($purchase);
	$last_purchase = $row_purchase['purchase'];
	
	//Set link to current php file for submit form
	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
	  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}
	
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
		//1. Update po Table
		mysql_select_db($database_Connection, $Connection);
		$query_updatepo = sprintf("UPDATE po SET po.POCode=%s, po.Tgl=%s, po.Transport=%s, po.Catatan=%s WHERE po.POCode='$pocode'", 
									GetSQLValueString($_POST['tx_editpo_POCode'], "text"),
									GetSQLValueString($_POST['tx_editpo_Tgl'], "text"),
									GetSQLValueString($_POST['tx_editpo_Transport'], "text"),
									GetSQLValueString($_POST['tx_editpo_Catatan'], "text"));
		$updatepo = mysql_query($query_updatepo, $Connection) or die(mysql_error());
		
		
		//2. Update Transaksi Table
		//2-1 Delete all in transaction
		mysql_select_db($database_Connection, $Connection);
		$query_deletetransaksi = sprintf("DELETE FROM transaksi WHERE POCode='$pocode'");
		$deletetransaksi = mysql_query($query_deletetransaksi, $Connection) or die(mysql_error());
		//2-2 Insert again to Transaksi Table
		
		//Set pocode variable again for inserting
		$pocode2= $_POST['tx_editpo_POCode'];
		//Set reference variable again for inserting
		$ref = $_POST['tx_editpo_reference'];
		//Inserting
		for($i=0;$i<15;$i++){
			if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
				mysql_select_db($database_Connection, $Connection);
				$insertSQL = sprintf("INSERT INTO transaksi (Purchase, JS, Barang, Quantity, QSisaKirInsert, QSisaKir, Amount, Reference, POCode) VALUES (%s, %s, %s, %s, %s, %s, %s, '$ref', '$pocode2')",
							   GetSQLValueString($_POST['tx_editpo_Purchase'][$i], "text"),
							   GetSQLValueString($_POST['db_editpo_JS'][$i], "text"),
							   GetSQLValueString($_POST['tx_editpo_Barang'][$i], "text"),
							   GetSQLValueString($_POST['tx_editpo_Quantity'][$i], "text"),
							   GetSQLValueString($_POST['tx_editpo_Quantity'][$i], "text"),
							   GetSQLValueString($_POST['tx_editpo_Quantity'][$i], "text"),
							   GetSQLValueString($_POST['tx_editpo_Amount'][$i], "text"));
				$Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
			}
			
			//Redirect
			$insertGoTo = "ViewPO.php?POCode=$pocode";
			header(sprintf("Location: %s", $insertGoTo));
		}
	}
?>

<?php
// Declare Root directory
$ROOT="../../";
$PAGE="Edit PO";
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
			<small>Edit</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
			<li><a href="../POCustomer/POCustomer.php">Purchase Order</a></li>
			<li class="active">Edit PO</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<form action="<?php echo $editFormAction; ?>" id="fm_editpo" name="fm_editpo" method="POST">
			
			<!-- BUTTON ROW -->
			<div class="row">
				<div class="col-xs-12">
					<div class="box box-primary">
						<div class="box-body">
							<a href="ViewPO.php?POCode=<?php echo $pocode ?>"><button type="button" class="btn btn-default pull-left">Cancel</button></a>
							<button type="submit" id="bt_insertpocustomerbarang_submit" class="btn btn-success pull-right">Update</button>
						</div>
					</div>
				</div>
			</div>
			
			<!-- CONTENT ROW -->
			<div class="row">
				<!-- PO INFO BOX -->
				<div class="col-md-3">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">PO Detail</h3>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label>Nomor PO</label>
								<input name="tx_editpo_POCode" type="text" class="form-control" id="tx_editpo_POCode" placeholder="Input PO Number" value="<?php echo $row_po['pocode']?>">
							</div>
							<div class="form-group">
								<label>Tanggal</label>
								<input name="tx_editpo_Tgl" type="text" id="tx_editpo_Tgl" class="form-control" autocomplete="off" placeholder="Date" value="<?php echo $row_po['tgl']?>">
							</div>
							<div class="form-group">
								<label>Transport</label>
								<input name="tx_editpo_Transport" type="text" id="tx_editpo_Transport" class="form-control" autocomplete="off" placeholder="0" value="<?php echo $row_po['transport']?>">
							</div>
							<div class="form-group">
								<label>Catatan</label>
								<textarea name="tx_editpo_Catatan" type="textarea" id="tx_editpo_Catatan" class="form-control" autocomplete="off" placeholder="Catatan" rows="5"><?php echo $row_po['catatan']?></textarea>
							</div>
						</div>
					</div>        
				</div>
				<!-- POITEM BOX -->
				<div class="col-md-9">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">PO Item</h3>
						</div>
						<div class="box-body">
							<table class="table table-hover table-bordered" id="tb_editpo">
								<thead>
									<th><a id="hf_editpo_addCF" class=" glyphicon glyphicon-plus"></a></th>
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
		<input type="hidden" name="tx_editpo_reference" value="<?php echo $ref?>">
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
		var max_fields      = 15; //maximum input boxes allowed
		
		var x = 0; //initial text box count
		var y = <?php echo $last_purchase ?>;
		var z = y;
		
		<?php do { ?> 
			if(x < max_fields){ //max input box allowed
				x++; //text box count increment
				z++;
				$("#tb_editpo").append('<tr><td><a class="remCF glyphicon glyphicon-remove"></a></td><td class="hidden"><input type="hidden" name="tx_editpo_Purchase[]" class="textbox" id="tx_editpo_Purchase" value="'+ z +'"></td><td><input type="text" name="tx_editpo_Barang[]" id="tx_editpo_Barang" autocomplete="off" class="form-control" value="<?php echo $row_poitem['barang'] ?>" required></td><td><select name="db_editpo_JS[]" id="db_editpo_JS" class="form-control"><option <?php if($row_poitem['js']=="Jual") {?>selected<?php }?>>Jual</option><option <?php if($row_poitem['js']=="Sewa") {?>selected<?php }?>>Sewa</option></select></td><td><input type="number" name="tx_editpo_Quantity[]" id="tx_editpo_Quantity" autocomplete="off" class="form-control" value="<?php echo $row_poitem['quantity']?>"required></td><td><input type="number" name="tx_editpo_Amount[]" autocomplete="off" id="tx_editpo_Amount" class="form-control" value="<?php echo $row_poitem['price']?>"required></td></tr>');
			}
		<?php } while ($row_poitem = mysql_fetch_assoc($poitem)); ?>
		
		$("#hf_editpo_addCF").click(function(){
			if(x < max_fields){ //max input box allowed
				x++; //text box count increment
				z++;
				$("#tb_editpo").append('<tr><td><a class="remCF glyphicon glyphicon-remove"></a></td><td class="hidden"><input type="hidden" name="tx_editpo_Purchase[]" class="textbox" id="tx_editpo_Purchase" value="'+ z +'"></td><td><input type="text" name="tx_editpo_Barang[]" id="tx_editpo_Barang" autocomplete="off" class="form-control" required></td><td><select name="db_editpo_JS[]" id="db_editpo_JS" class="form-control"><option>Jual</option><option>Sewa</option></select></td><td><input type="number" name="tx_editpo_Quantity[]" id="tx_editpo_Quantity" autocomplete="off" class="form-control" required></td><td><input type="number" name="tx_editpo_Amount[]" autocomplete="off" id="tx_editpo_Amount" class="form-control" required></td></tr>');
			}
		});
		
		$("#tb_editpo").on('click','.remCF',function(){
			$(this).parent().parent().remove();
			x--;
		});
		
		//Mask Transport
		$("#tx_insertpocustomerbarang_Transport").maskMoney({prefix:'Rp ', allowZero: true, allowNegative: false, thousands:'.', decimal:',', affixesStay: true, precision: 0});
		//Mask Price
		$("#tx_insertpocustomerbarang_Amount").maskMoney({prefix:'Rp ', allowZero: true, allowNegative: false, thousands:'.', decimal:',', affixesStay: true, precision: 0});
		
	});
</script>
