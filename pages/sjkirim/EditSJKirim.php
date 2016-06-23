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

mysql_select_db($database_Connection, $Connection);
$query_Menu = "SELECT * FROM menu";
$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
$row_Menu = mysql_fetch_assoc($Menu);
$totalRows_Menu = mysql_num_rows($Menu);

$colname_View = "-1";
if (isset($_GET['SJKir'])) {
  $colname_View = $_GET['SJKir'];
}
mysql_select_db($database_Connection, $Connection);
$query_TanggalMin = sprintf("SELECT pocustomer.Tgl FROM pocustomer LEFT JOIN sjkirim ON pocustomer.Reference=sjkirim.Reference WHERE sjkirim.SJKir = %s", GetSQLValueString($colname_View, "text"));
$TanggalMin = mysql_query($query_TanggalMin, $Connection) or die(mysql_error());
$row_TanggalMin = mysql_fetch_assoc($TanggalMin);
$totalRows_TanggalMin = mysql_num_rows($TanggalMin);

$colname_EditIsiSJKirim = "-1";
if (isset($_GET['SJKir'])) {
  $colname_EditIsiSJKirim = $_GET['SJKir'];
}
mysql_select_db($database_Connection, $Connection);
$query_EditIsiSJKirim = sprintf("SELECT isisjkirim.*, sjkirim.Tgl, transaksi.Barang, transaksi.JS, transaksi.Quantity, transaksi.QSisaKir, project.Project FROM isisjkirim LEFT JOIN sjkirim ON isisjkirim.SJKir=sjkirim.SJKir INNER JOIN transaksi ON isisjkirim.Purchase=transaksi.Purchase INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode WHERE isisjkirim.SJKir = %s ORDER BY isisjkirim.Id ASC", GetSQLValueString($colname_EditIsiSJKirim, "text"));
$EditIsiSJKirim = mysql_query($query_EditIsiSJKirim, $Connection) or die(mysql_error());
$row_EditIsiSJKirim = mysql_fetch_assoc($EditIsiSJKirim);
$totalRows_EditIsiSJKirim = mysql_num_rows($EditIsiSJKirim);

$colname_User = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_User = $_SESSION['MM_Username'];
}
mysql_select_db($database_Connection, $Connection);
$query_User = sprintf("SELECT Name FROM users WHERE Username = %s", GetSQLValueString($colname_User, "text"));
$User = mysql_query($query_User, $Connection) or die(mysql_error());
$row_User = mysql_fetch_assoc($User);
$totalRows_User = mysql_num_rows($User);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$SJKir = $_GET['SJKir'];

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE sjkirim SET Tgl=%s WHERE SJKir = '$SJKir'",
 					   GetSQLValueString($_POST['tx_editsjkirim_Tgl'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}

for($i=0;$i<$totalRows_EditIsiSJKirim;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("SELECT edit_sjkirim(%s,%s,%s)",
                       GetSQLValueString($_POST['hd_editsjkirim_IsiSJKir'][$i], "int"),
                       GetSQLValueString($_POST['tx_editsjkirim_QKirim'][$i], "int"),
                       GetSQLValueString($_POST['tx_editsjkirim_Warehouse'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());

  $updateGoTo = "ViewSJKirim.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  
  header(sprintf("Location: %s", $updateGoTo));
}

}

?>

<?php
// Declare Root directory
$ROOT="../../";
$PAGE="Edit SJ Kirim";
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
        <small>Edit</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="../SJKirim/SJKirim.php">SJ Kirim</a></li>
        <li class="active">Edit SJ Kirim</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
		<form action="<?php echo $editFormAction; ?>" id="fm_editsjkirim_form1" name="fm_editsjkirim_form1" method="POST">
          <div class="box box-primary">
            <div class="box-body no-padding">
				  <table id="tb_editsjkirim_example1" class="table table-bordered">
					<thead>
					<tr>
						<th>J/S</th>
						<th>Barang</th>
						<th>Warehouse</th>
                        <th>Q Kirim</th>
						<th>Q Tertanda</th>
					</tr>
					</thead>
					<tbody>
                    <?php $Tgl = $row_EditIsiSJKirim['Tgl']; ?>
                    <?php
					$Min = $row_TanggalMin['Tgl'];
				  ?>
						<?php do { ?>
						<tr>
							<input name="hd_editsjkirim_Id[]" type="hidden" id="hd_editsjkirim_Id" value="<?php echo $row_EditIsiSJKirim['Id']; ?>">
                            <input name="hd_editsjkirim_Purchase[]" type="hidden" id="hd_editsjkirim_Purchase" value="<?php echo $row_EditIsiSJKirim['Purchase']; ?>">
                            <input name="hd_editsjkirim_IsiSJKir[]" type="hidden" id="hd_editsjkirim_IsiSJKir" value="<?php echo $row_EditIsiSJKirim['IsiSJKir']; ?>">
							<td><input name="tx_editsjkirim_JS" type="text" class="form-control" id="tx_editsjkirim_JS" value="<?php echo $row_EditIsiSJKirim['JS']; ?>" readonly></td>
							<td><input name="tx_editsjkirim_Barang" type="text" class="form-control" id="tx_editsjkirim_Barang" value="<?php echo $row_EditIsiSJKirim['Barang']; ?>" readonly></td>
							<td><input name="tx_editsjkirim_Warehouse[]" type="text" class="form-control" id="tx_editsjkirim_Warehouse" autocomplete="off" value="<?php echo $row_EditIsiSJKirim['Warehouse']; ?>"></td>
							<td><input name="tx_editsjkirim_QKirim[]" type="number" class="form-control" id="tx_editsjkirim_QKirim" autocomplete="off" value="<?php echo $row_EditIsiSJKirim['QKirim']; ?>" onkeyup="this.value = minmax(this.value, 0, <?php echo $row_EditIsiSJKirim['Quantity']; ?>)" required></td>
							<td><input name="tx_editsjkirim_QTertanda[]" type="text" class="form-control" id="tx_editsjkirim_QTertanda[]" autocomplete="off" value="<?php echo $row_EditIsiSJKirim['QTertanda']; ?>" readonly></td>
						</tr>
						<?php } while ($row_EditIsiSJKirim = mysql_fetch_assoc($EditIsiSJKirim)); ?>
					</tbody>
				  </table>
				<input type="hidden" name="MM_update" value="form1">
			</div>
            <!-- /.box-body -->
            <div class="box-footer">
            <label>Send Date</label>
					<div class="input-group">
					<div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                    </div>
					<input name="tx_editsjkirim_Tgl" type="text" class="form-control" id="tx_editsjkirim_Tgl" autocomplete="off" value="<?php echo $Tgl; ?>" required>
					</div>
				<br>
				<a href="ViewSJKirim.php?SJKir=<?php echo $_GET['SJKir']; ?>"><button type="button" class="btn btn-default">Cancel</button></a>
				<button type="submit" name="bt_editsjkirim_submit" id="bt_editsjkirim_submit" class="btn btn-success pull-right">Update</button>
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

<script>
var Min = <?php echo json_encode($Min) ?>;
  $('#tx_editsjkirim_Tgl').datepicker({
	  format: "dd/mm/yyyy",
	  startDate: Min,
	  orientation: "bottom left",
	  todayHighlight: true,
	  autoclose: true
  }); 
</script>
<?php
  mysql_free_result($Menu);
  mysql_free_result($EditIsiSJKirim);
  mysql_free_result($User);
  mysql_free_result($TanggalMin);
?>