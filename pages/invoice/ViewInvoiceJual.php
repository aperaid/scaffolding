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

$colname_View = "-1";
if (isset($_GET['Reference'])) {
  $colname_View = $_GET['Reference'];
  $colname_ViewInvoice = $_GET['Invoice'];
}
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT invoice.*, pocustomer.PPN, project.Project, customer.Company FROM invoice INNER JOIN pocustomer ON invoice.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode INNER JOIN customer ON project.CCode=customer.CCode WHERE invoice.Reference = %s AND invoice.Invoice = %s", GetSQLValueString($colname_View, "text"), GetSQLValueString($colname_ViewInvoice, "text"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);

$colname_Count = "-1";
if (isset($_GET['Reference'])) {
  $colname_Count = $_GET['Reference'];
  $colname_ViewJS = $_GET['JS'];
  $colname_ViewInvoice = $_GET['Invoice'];
}

$colname_View2 = "-1";
if (isset($_GET['JS'])) {
  $colname_View2 = $_GET['Reference'];
  $colname_ViewJS = $_GET['JS'];
}
mysql_select_db($database_Connection, $Connection);
$query_View2 = sprintf("SELECT isisjkirim.QKirim, sjkirim.SJKir, transaksi.Purchase, transaksi.Barang, sjkirim.Tgl AS S, transaksi.Amount FROM transaksi RIGHT JOIN isisjkirim ON isisjkirim.Purchase = transaksi.Purchase LEFT JOIN sjkirim ON sjkirim.SJKir = isisjkirim.SJKir WHERE transaksi.Reference = %s AND transaksi.JS = %s", GetSQLValueString($colname_View2, "text"),
GetSQLValueString($colname_ViewJS, "text"));
$View2 = mysql_query($query_View2, $Connection) or die(mysql_error());
$row_View2 = mysql_fetch_assoc($View2);

if (isset($_GET['totalRows_View2'])) {
  $totalRows_View2 = $_GET['totalRows_View2'];
} else {
  $all_View2 = mysql_query($query_View2);
  $totalRows_View2 = mysql_num_rows($all_View2);
}

mysql_select_db($database_Connection, $Connection);
$query_Menu = "SELECT * FROM menu";
$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
$row_Menu = mysql_fetch_assoc($Menu);
$totalRows_Menu = mysql_num_rows($Menu);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE invoice SET PPN=%s, Transport=%s, Discount=%s, Catatan=%s WHERE Invoice=%s",
                       GetSQLValueString($_POST['tx_viewinvoicejual_PPN'], "int"),
                       GetSQLValueString(str_replace(".","",substr($_POST['tx_viewinvoicejual_Transport'], 3)), "text"),
					   GetSQLValueString(str_replace(".","",substr($_POST['tx_viewinvoicejual_Discount'], 3)), "text"),
					   GetSQLValueString($_POST['tx_viewinvoicejual_Catatan'], "text"),
					   GetSQLValueString($_POST['tx_viewinvoicejual_Invoice'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());

  $updateGoTo = "ViewInvoiceJual.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

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
$PAGE="Invoice Jual";
$top_menu_sel="menu_invoice";
include_once($ROOT . 'pages/html_header.php');
include_once($ROOT . 'pages/html_main_header.php');
?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Invoice Jual
        <small>View</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="InvoiceJual.php">Invoice Jual</a></li>
        <li class="active">View Invoice Jual</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Invoice Detail</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="<?php echo $editFormAction; ?>" id="fm_viewinvoicejual_form1" name="fm_viewinvoicejual_form1" method="POST" class="form-horizontal">
            
            <div class="box-body with-border">
                <div class="form-group">
                  <label class="col-sm-2 control-label">No. Invoice</label>
                  <div class="col-sm-6">
                    <input id="tx_viewinvoicejual_Invoice" name="tx_viewinvoicejual_Invoice" type="text" class="form-control" value="<?php echo $row_View['Invoice']; ?>"  readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Project</label>
                  <div class="col-sm-6">
                    <input id="tx_viewinvoicejual_Project" name="tx_viewinvoicejual_Project" type="text" class="form-control" value="<?php echo $row_View['Project']; ?>"  readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Company</label>
                  <div class="col-sm-6">
                    <input id="tx_viewinvoicejual_Company" name="tx_viewinvoicejual_Company" type="text" class="form-control" value="<?php echo $row_View['Company']; ?>"  readonly>
                  </div>
                </div>
            <div>
  <table id="tb_viewinvoicejual_example1" name="tb_viewinvoicejual_example1" class="table table-bordered table-striped table-responsive">

	<thead>
      <tr>
        <th align="center">SJ Kirim</th>
        <th align="center">Item</th>
        <th>Quantity Kirim</th>
        <th>Price</th>
        <th>Total</th>
        </tr>
      </thead>
      <tbody>
	  <?php 
	  $total = 0;
	  do { ?>
      
        <tr>
          <td><?php echo $row_View2['SJKir']; ?></td>
          <td><?php echo $row_View2['Barang']; ?></td>
          <td><?php echo $row_View2['QKirim']; ?></td>
          <td>Rp <?php echo number_format($row_View2['Amount'], 2, ',', '.'); ?></td>
          <?php $test = $row_View2['QKirim']* $row_View2['Amount']; $total += $test ?>
          <td>Rp <?php echo number_format($test, 2,',','.') ?></td>
        </tr>
	  <?php } while ($row_View2 = mysql_fetch_assoc($View2)); ?>
    </tbody>
</table>
    </div>

    <div class="form-group">
                  <label class="col-sm-2 control-label">Pajak 10%</label>
                  <div class="col-sm-6">
                    <input name="tx_viewinvoicejual_PPN" type="hidden" id="tx_viewinvoicejual_PPN" value="0">
					<input name="tx_viewinvoicejual_PPN" type="checkbox" id="tx_viewinvoicejual_PPN" value="1" <?php if ($row_View['PPN'] == 1){ ?> checked <?php } ?>>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Transport</label>
                  <div class="col-sm-6">
                    <input id="tx_viewinvoicejual_Transport" name="tx_viewinvoicejual_Transport" type="text" class="form-control" value="<?php echo 'Rp ', number_format($row_View['Transport'],0,',','.'); ?>" onKeyUp="tot()">
                    <input id="hd_viewinvoicejual_Transport2" name="hd_viewinvoicejual_Transport2" type="hidden" autocomplete="off" value="<?php echo $row_View['Transport']; ?>">
                  </div>
                </div>
				<!-- Discount Input -->
				<div class="form-group">
					<label class="col-sm-2 control-label">Discount</label>
					<div class="col-sm-6">
						<input id="tx_viewinvoicejual_Discount" name="tx_viewinvoicejual_Discount" type="text" class="form-control" value="<?php echo 'Rp ', number_format($row_View['Discount'],0,',','.'); ?>" onKeyUp="tot()" >
					</div>
                </div>
				<!-- Catatan Input -->
				<div class="form-group">
					<label class="col-sm-2  control-label" >Catatan</label>
					<div class="col-sm-6">
						<textarea name="tx_viewinvoicejual_Catatan" type="textarea" id="tx_viewinvoicejual_Catatan" class="form-control" autocomplete="off" placeholder="Catatan" rows=5><?php echo $row_View['Catatan']; ?></textarea>
					</div>
				</div>
				<!-- Total Text -->
                <div class="form-group">
                  <label class="col-sm-2 control-label">Total</label>
                  <div class="col-sm-6">
                    <input name="tx_viewinvoicejual_Totals" type="text" class="form-control" id="tx_viewinvoicejual_Totals" value="Rp <?php echo number_format(($total*$row_View['PPN']*0.1)+$total+$row_View['Transport']-$row_View['Discount'], 2,',','.'); ?>" readonly>
                    <input name="hd_viewinvoicejual_Totals2" type="hidden" id="hd_viewinvoicejual_Totals2" value="<?php echo round($total, 2); ?>" >
                  </div>
                </div>
                
                <div class="box-footer">
                <button type="submit" name="bt_viewinvoicejual_submit" id="bt_viewinvoicejual_submit" class="btn btn-info pull-right">Update</button>
                <div class="btn-group"><a href="InvoiceJual.php"><button type="button" class="btn btn-default pull-left">Back</button></a></div>
                <div class="btn-group" ><a href="#" class="btn btn-default"><i class="fa fa-print"></i> Print</a></div>
              </div>
              <input type="hidden" name="MM_update" value="form1">
<!-- /.box-footer -->
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

<script language="javascript">
  function tot() {
    var txtFirstNumberValue = document.getElementById('hd_viewinvoicejual_Totals2').value;
    var txtSecondNumberValue = document.getElementById('tx_viewinvoicejual_PPN').value;
	var txtThirdNumberValue = document.getElementById('tx_viewinvoicejual_Transport').value;
	var txtFourthNumberValue = document.getElementById('tx_viewinvoicejual_Discount').value;
	var result = (parseFloat(txtFirstNumberValue) * parseFloat(txtSecondNumberValue)*0.1)+parseFloat(txtFirstNumberValue) + parseFloat(txtThirdNumberValue) - parseFloat(txtFourthNumberValue);
	  if (!isNaN(result)) {
		document.getElementById('tx_viewinvoicejual_Totals').value = result;
      }
   }
   
$(document).ready(function(){
	//Mask Transport
	$("#tx_viewinvoicejual_Transport").maskMoney({prefix:'Rp ', allowZero: true, allowNegative: false, thousands:'.', decimal:',', affixesStay: true, precision: 0});
	$("#tx_viewinvoicejual_Discount").maskMoney({prefix:'Rp ', allowZero: true, allowNegative: false, thousands:'.', decimal:',', affixesStay: true, precision: 0});
});
</script>

<?php
  mysql_free_result($View);
  mysql_free_result($View2);
?>
