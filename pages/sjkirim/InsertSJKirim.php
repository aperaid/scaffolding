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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	$_SESSION['tx_insertsjkirim_SJKir'] = sprintf("%s", GetSQLValueString($_POST['tx_insertsjkirim_SJKir'], "text"));
	$_SESSION['tx_insertsjkirim_Tgl'] = sprintf("%s", GetSQLValueString($_POST['tx_insertsjkirim_Tgl'], "text"));
	$_SESSION['tx_insertsjkirim_Reference'] = sprintf("%s", GetSQLValueString($_POST['tx_insertsjkirim_Reference'], "text"));

  $insertGoTo = "InsertSJKirimBarang.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_Connection, $Connection);
$query_Menu = "SELECT * FROM menu";
$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
$row_Menu = mysql_fetch_assoc($Menu);
$totalRows_Menu = mysql_num_rows($Menu);

$Reference = $_GET['Reference'];

mysql_select_db($database_Connection, $Connection);
$query_TanggalMin = "SELECT Tgl FROM pocustomer WHERE Reference = '$Reference'";
$TanggalMin = mysql_query($query_TanggalMin, $Connection) or die(mysql_error());
$row_TanggalMin = mysql_fetch_assoc($TanggalMin);
$totalRows_TanggalMin = mysql_num_rows($TanggalMin);

mysql_select_db($database_Connection, $Connection);
$query_NoSJ = "SELECT Id FROM sjkirim ORDER BY Id DESC";
$NoSJ = mysql_query($query_NoSJ, $Connection) or die(mysql_error());
$row_NoSJ = mysql_fetch_assoc($NoSJ);
$totalRows_NoSJ = mysql_num_rows($NoSJ);

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
$PAGE="Insert SJ Kirim";
$top_menu_sel="menu_sjkirim";
include_once($ROOT . 'pages/html_header.php');
include_once($ROOT . 'pages/html_main_header.php');
?>
      
<?php
$Min = $row_TanggalMin['Tgl'];
?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Surat Jalan Kirim
        <small>Insert</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="../SJKirim/SJKirim.php">SJ Kirim</a></li>
        <li class="active">Insert SJ Kirim</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <!-- general form elements -->
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">SJ Detail</h3>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
              <form action="<?php echo $editFormAction; ?>" id="fm_insertsjkirim_form1" name="fm_insertsjkirim_form1" method="POST">
                <div class="box-body">
                  <div class="form-group">
                    <label>No. Surat Jalan</label>
                    <input name="tx_insertsjkirim_SJKir" type="text" class="form-control" id="tx_insertsjkirim_SJKir" onKeyUp="capital()" value="<?php echo str_pad($row_NoSJ['Id']+1, 3, "0", STR_PAD_LEFT); ?>/SI/<?php echo date("mY") ?>" readonly>
                  </div>
                  <div class="form-group">
                    <label>Send Date</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="tx_insertsjkirim_Tgl" type="text" autocomplete="off" class="form-control pull-right date" id="tx_insertsjkirim_Tgl" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Reference Code</label>
                    <input name="tx_insertsjkirim_Reference" type="text" class="form-control" id="tx_insertsjkirim_Reference" autocomplete="off" onKeyUp="capital()" placeholder="00001/010116" value="<?php echo $_GET['Reference'] ?>" readonly>
                    <p class="help-block">Enter the beginning of the Reference Code, then pick from the dropdown</p>
                  </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                  <a href="../POCustomer/ViewTransaksi.php?Reference=<?php echo $_GET['Reference'] ?>"><button type="button" class="btn btn-default pull-left">Cancel</button></a> 
                  <button type="submit" name="bt_insertsjkirim_submit" id="bt_insertsjkirim_submit" class="btn btn-primary pull-right">Insert</button>
                </div>
              <input type="hidden" name="MM_insert" value="form1">
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
  
<!-- Footer Wrapper -->
<?php include_once($ROOT . 'pages/footer.php'); ?>
<!-- /.footer-wrapper -->

<!-- page script -->
<script>
var Min = <?php echo json_encode($Min) ?>;
  $('#tx_insertsjkirim_Tgl').datepicker({
	  format: "dd/mm/yyyy",
	  startDate: Min,
	  orientation: "bottom left",
	  todayHighlight: true,
	  autoclose: true
  }); 
</script>
<script type="text/javascript">
$(function() {
    var availableTags = <?php include ("../autocomplete3.php");?>;
    $( "#tx_insertsjkirim_Reference" ).autocomplete({
      source: availableTags
    });
  });
</script>

<?php
  mysql_free_result($TanggalMin);
  mysql_free_result($Menu);
  mysql_free_result($NoSJ);
  mysql_free_result($User);
?>
