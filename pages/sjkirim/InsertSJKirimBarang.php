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

mysql_select_db($database_Connection, $Connection);
$query_Menu = "SELECT * FROM menu";
$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
$row_Menu = mysql_fetch_assoc($Menu);
$totalRows_Menu = mysql_num_rows($Menu);

$colname_InsertSJKirim = "-1";
if (isset($_GET['Reference'])) {
  $colname_InsertSJKirim = $_GET['Reference'];
}

mysql_select_db($database_Connection, $Connection);
$query_InsertSJKirim = sprintf("SELECT transaksi.Purchase, transaksi.Barang, transaksi.JS, transaksi.QSisaKirInsert, project.Project FROM transaksi INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference  INNER JOIN project ON pocustomer.PCode=project.PCode WHERE transaksi.Reference = %s ORDER BY transaksi.Id ASC", GetSQLValueString($colname_InsertSJKirim, "text"));
$InsertSJKirim = mysql_query($query_InsertSJKirim, $Connection) or die(mysql_error());
$row_InsertSJKirim = mysql_fetch_assoc($InsertSJKirim);
$totalRows_InsertSJKirim = mysql_num_rows($InsertSJKirim);

mysql_select_db($database_Connection, $Connection);
$query_LastIsiSJKirim = "SELECT IsiSJKir FROM isisjkirim ORDER BY Id DESC";
$LastIsiSJKirim = mysql_query($query_LastIsiSJKirim, $Connection) or die(mysql_error());
$row_LastIsiSJKirim = mysql_fetch_assoc($LastIsiSJKirim);
$totalRows_LastIsiSJKirim = mysql_num_rows($LastIsiSJKirim);

mysql_select_db($database_Connection, $Connection);
$query_LastId = "SELECT Id FROM sjkirim ORDER BY Id DESC";
$LastId = mysql_query($query_LastId, $Connection) or die(mysql_error());
$row_LastId = mysql_fetch_assoc($LastId);
$totalRows_LastId = mysql_num_rows($LastId);

$colname_User = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_User = $_SESSION['MM_Username'];
}
mysql_select_db($database_Connection, $Connection);
$query_User = sprintf("SELECT Name FROM users WHERE Username = %s", GetSQLValueString($colname_User, "text"));
$User = mysql_query($query_User, $Connection) or die(mysql_error());
$row_User = mysql_fetch_assoc($User);
$totalRows_User = mysql_num_rows($User);

for($i=0;$i<$totalRows_InsertSJKirim;$i++){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $_SESSION['cb_insertsjkirimbarang_checkbox'][$i] = sprintf("%s", GetSQLValueString($_POST['cb_insertsjkirimbarang_checkbox'][$i], "int"));
  
  $insertGoTo = "InsertSJKirimBarang2.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
  }
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
        <small>Select</small>
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
          <form action="<?php echo $editFormAction; ?>" id="fm_insertsjkirimbarang_form1" name="fm_insertsjkirimbarang_form1" method="POST">
            <div class="box box-primary">
              <div class="box-body">
                <table id="tb_insertsjkirimbarang_example1" name="tb_insertsjkirimbarang_example1" class="table table-bordered table-striped table-responsive">
                  <thead>
                  <tr>
                    <th>Pilih</th>
                    <th>J/S</th>
                    <th>Barang</th>
                    <th>Quantity Sisa Kirim</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php $increment = 1; ?>
                    <?php do { ?>
                      <tr>
                        <td><input type="checkbox" name="cb_insertsjkirimbarang_checkbox[]" id="cb_insertsjkirimbarang_checkbox" value="<?php echo $row_InsertSJKirim['Purchase']; ?>" <?php if ($row_InsertSJKirim['QSisaKirInsert'] == 0){ ?> disabled <?php } ?>></td>
                        <td><input name="tx_insertsjkirimbarang_JS[]" type="text" class="form-control" id="tx_insertsjkirimbarang_JS" value="<?php echo $row_InsertSJKirim['JS']; ?>" readonly></td>
                        <td><input name="tx_insertsjkirimbarang_Barang[]" type="text" class="form-control" id="tx_insertsjkirimbarang_Barang" value="<?php echo $row_InsertSJKirim['Barang']; ?>" readonly></td>
                        <td><input name="tx_insertsjkirimbarang_QSisaKir[]" type="text" class="form-control" id="tx_insertsjkirimbarang_QSisaKir" value="<?php echo $row_InsertSJKirim['QSisaKirInsert']; ?>" readonly></td>
                        </tr>
                      <?php $increment++; ?>
                      <?php } while ($row_InsertSJKirim = mysql_fetch_assoc($InsertSJKirim)); ?>
                      <p><label><input type="checkbox" id="SelectAll"/> Check all</label></p>
                  </tbody>
                </table>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <input type="submit" name="bt_insertsjkirimbarang_submit" id="bt_insertsjkirimbarang_submit" class="btn btn-primary pull-right" value="Choose" disabled>
                <a href="InsertSJKirim.php?Reference=<?php echo $_GET['Reference']; ?>"><button type="button" class="btn btn-default">Cancel</button></a>
              </div>
            </div>
            <!-- /.box -->
            <input type="hidden" name="MM_insert" value="form1">
          </form>
          <!-- /.col -->
        </div>
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
var checkboxes = $("input[type='checkbox']"),
    submitButt = $("input[type='submit']");

checkboxes.click(function() {
    submitButt.attr("disabled", !checkboxes.is(":checked"));
});
</script>

<script>
$('#SelectAll').click(function () {
    var checked_status = this.checked;

    $('input[type=checkbox]').not(":disabled").prop('checked', checked_status);
});
</script>

<?php
  mysql_free_result($Menu);
  mysql_free_result($LastIsiSJKirim);
  mysql_free_result($LastId);
  mysql_free_result($User);
  mysql_free_result($InsertSJKirim);
?>
