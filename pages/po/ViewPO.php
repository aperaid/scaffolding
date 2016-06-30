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

//Set Variable POCode
$pocode=$_GET['POCode'];

//Get the data for PO Detail Box
mysql_select_db($database_Connection, $Connection);
$query_po = sprintf("SELECT po.POCode AS pocode, po.Tgl AS tgl, po.PPN AS ppn, po.Transport AS transport, po.Catatan AS catatan FROM po WHERE po.POCode='$pocode'");
$po = mysql_query($query_po, $Connection) or die(mysql_error());
$row_po = mysql_fetch_assoc($po);

//Get the data for Input PO Item
mysql_select_db($database_Connection, $Connection);
$query_poitem = sprintf("SELECT transaksi.Barang AS barang, transaksi.JS AS js, transaksi.Quantity AS quantity, transaksi.Amount AS price FROM transaksi WHERE transaksi.POCode='$pocode'");
$poitem = mysql_query($query_poitem, $Connection) or die(mysql_error());
$row_poitem = mysql_fetch_assoc($poitem);

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
		<form action="" id="fm_insertpocustomerbarang_form1" name="fm_insertpocustomerbarang_form1" method="POST">	
			<div class="row">
				<div class="col-xs-12">
					<div class="box box-primary">
						<div class="box-body">
							<a href=""><button type="button" class="btn btn-default pull-left">Cancel</button></a>
							<a href=""><button type="submit" id="bt_insertpocustomerbarang_submit" class="btn btn-primary pull-right">Edit</button></a>
							<a href=""><button type="submit" id="bt_insertpocustomerbarang_submit" class="btn btn-danger pull-right" style="margin-right: 5px;">Delete</button></a>
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
								<input name="tx_viewpo_POCode" type="text" class="form-control" id="tx_viewpo_POCode" placeholder="Input PO Number" value="<?php echo $row_po['pocode']?>" readonly>
							</div>
							<div class="form-group">
								<label for="exampleInputPassword1">Tanggal</label>
								<input name="tx_viewpo_Tgl" type="text" id="tx_viewpo_Tgl" class="form-control" autocomplete="off" placeholder="Date" value="<?php echo $row_po['tgl']?>" readonly>
							</div>
							<div class="checkbox">
									<label>
										<input name="tx_viewpo_PPN" class="minimal" type="checkbox" id="tx_viewpo_PPN" <?php if ($row_po['ppn'] == 1) { ?> checked <?php } ?> disabled>
										PPN
									</label>
								</div>
							<div class="form-group">
								<label for="exampleInputPassword1">Transport</label>
								<input name="tx_viewpo_Transport" type="text" id="tx_viewpo_Transport" class="form-control" autocomplete="off" placeholder="0" value="<?php echo $row_po['transport']?>" readonly>
							</div>
							<div class="form-group">
								<label for="exampleInputPassword1">Catatan</label>
								<textarea name="tx_viewpo_Catatan" type="textarea" id="tx_viewpo_Catatan" class="form-control" autocomplete="off" placeholder="Catatan" rows="5" readonly><?php echo $row_po['catatan']?></textarea>
							</div>
							<input name="hd_inputpocustomerbarang_Reference" type="hidden" id="hd_inputpocustomerbarang_Reference" value="">
							<input type="hidden" name="MM_insert" value="form1">
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
									<tr>
										<th>Barang</th>
										<th>J/S</th>
										<th>Quantity</th>
										<th>Price</th>
									</tr>
								</thead>
								<tbody>
									<?php do { ?>
									<tr>
										<td><?php echo $row_poitem['barang'] ?></td>
										<td><?php echo $row_poitem['js'] ?></td>
										<td><?php echo $row_poitem['quantity'] ?></td>
										<td><?php echo $row_poitem['price'] ?></td>
									</tr>
									<?php } while ($row_poitem = mysql_fetch_assoc($poitem)); ?>
								</tbody>
							</table>
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</div>
			<!-- /.row -->
		</form>
	</section>
	<!-- /.section -->
</div>
<!-- /.content-wrapper -->
  
<!-- Footer Wrapper -->
<?php include_once($ROOT . 'pages/footer.php'); ?>
<!-- /.footer-wrapper -->

<script>
    $(document).ready(function(){
		//Mask Transport
		$("#tx_viewpo_Transport").maskMoney({prefix:'Rp ', allowZero: true, allowNegative: false, thousands:'.', decimal:',', affixesStay: true, precision: 0});
		//Mask Price
		$("#tx_viewpo_Amount").maskMoney({prefix:'Rp ', allowZero: true, allowNegative: false, thousands:'.', decimal:',', affixesStay: true, precision: 0});
		
	});
</script>