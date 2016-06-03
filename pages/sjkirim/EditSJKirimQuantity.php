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
$query_View = sprintf("SELECT SJKir FROM sjkirim WHERE SJKir = %s", GetSQLValueString($colname_View, "text"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);

$colname_EditIsiSJKirim = "-1";
if (isset($_GET['SJKir'])) {
  $colname_EditIsiSJKirim = $_GET['SJKir'];
}
mysql_select_db($database_Connection, $Connection);
$query_Tgl = sprintf("SELECT Tgl FROM sjkirim WHERE SJKir = %s", GetSQLValueString($colname_EditIsiSJKirim, "text"));
$Tgl = mysql_query($query_Tgl, $Connection) or die(mysql_error());
$row_Tgl = mysql_fetch_assoc($Tgl);
$totalRows_Tgl = mysql_num_rows($Tgl);

mysql_select_db($database_Connection, $Connection);
$query_EditIsiSJKirim = sprintf("SELECT isisjkirim.*, transaksi.Barang, transaksi.JS, transaksi.QSisaKir, project.Project FROM isisjkirim INNER JOIN transaksi ON isisjkirim.Purchase=transaksi.Purchase INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode WHERE isisjkirim.SJKir = %s ORDER BY isisjkirim.Id ASC", GetSQLValueString($colname_EditIsiSJKirim, "text"));
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

//Code Update QTTD
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	for ($i=0;$i<$totalRows_EditIsiSJKirim;$i++){
		mysql_select_db($database_Connection, $Connection);
		$updateSQL = sprintf ("SELECT edit_sjkirimquantity(%s, %s, %s)",
						GetSQLValueString($_POST['hd_editsjkirimquantity_IsiSJKir'][$i]	, "text"), //isisjkirim
						GetSQLValueString($_POST['tx_editsjkirimquantity_QTertanda'][$i]	, "int"), //qttd
						GetSQLValueString($_POST['tx_editsjkirimquantity_S']				, "text")); //start date
		$Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
	}
	$updateGoTo = "ViewSJKirim.php";
	if (isset($_SERVER['QUERY_STRING'])) {
		$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
		$updateGoTo .= $_SERVER['QUERY_STRING'];
	}
	header(sprintf("Location: %s", $updateGoTo));
}
//Code Update QTTD END

/*
for($i=0;$i<$totalRows_EditIsiSJKirim;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	$QTertanda = GetSQLValueString($_POST['tx_editsjkirimquantity_QTertanda2'][$i], "int");
    $updateSQL = sprintf("UPDATE transaksi SET QSisaKir=QSisaKir+$QTertanda-%s, QSisaKem=QSisaKem-$QTertanda+%s WHERE Purchase=%s",
                       GetSQLValueString($_POST['tx_editsjkirimquantity_QTertanda'][$i], "int"),
					   GetSQLValueString($_POST['tx_editsjkirimquantity_QTertanda'][$i], "int"),
                       GetSQLValueString($_POST['hd_editsjkirimquantity_Purchase'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE isisjkirim SET QTertanda=%s, QSisaKemInsert=%s, QSisaKem=%s WHERE Id=%s",
                       GetSQLValueString($_POST['tx_editsjkirimquantity_QTertanda'][$i], "int"),
					   GetSQLValueString($_POST['tx_editsjkirimquantity_QTertanda'][$i], "int"),
					   GetSQLValueString($_POST['tx_editsjkirimquantity_QTertanda'][$i], "int"),
                       GetSQLValueString($_POST['hd_editsjkirimquantity_Id'][$i], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE transaksi SET QSisaKem=0 WHERE JS='Jual'");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}


if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE periode SET S=%s, E=%s WHERE IsiSJKir=%s AND Deletes='Sewa' OR  Deletes='Jual'",
                       GetSQLValueString($_POST['tx_editsjkirimquantity_S'], "text"),
                       GetSQLValueString($_POST['hd_editsjkirimquantity_E'], "text"),
					   GetSQLValueString($_POST['hd_editsjkirimquantity_IsiSJKir'][$i], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE isisjkirim LEFT JOIN transaksi ON isisjkirim.Purchase=transaksi.Purchase SET isisjkirim.QSisaKemInsert=0, isisjkirim.QSisaKem=0 WHERE transaksi.JS='Jual'");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());

  $updateGoTo = "ViewSJKirim.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  
  header(sprintf("Location: %s", $updateGoTo));
}
*/
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>BDN ERP | Edit SJ Kirim Quantity</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="../../library/bootstrap/css/bootstrap.min.css">
  <!-- jQueryUI -->
  <link rel="stylesheet" href="../../library/jQueryUI/jquery-ui.css" >
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../library/font-awesome-4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../library/ionicons-2.0.1/css/ionicons.min.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../library/datatables/dataTables.bootstrap.css">
  <!-- datepicker -->
  <link rel="stylesheet" href="../../library/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../library/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../library/dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue fixed sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="../../index.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>BDN</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">PT. <b>BDN</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Buka/Tutup</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="../../library/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo "Welcome ".$_SESSION['MM_Username']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="../../library/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                  <?php echo $_SESSION['MM_Username']; ?> - <?php echo $row_User['Name']; ?>
                  <small>Super Profile</small>
                </p>
              </li>
              
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-right">
                  <a href="<?php echo $logoutAction ?>" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          
        </ul>
      </div>
    </nav>
  </header>
  
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MENU</li>
        <?php do { ?>
        <li><a href="../../<?php echo $row_Menu['link']; ?>"><i class="<?php echo $row_Menu['icon']; ?>"></i> <span><?php echo $row_Menu['nama']; ?></span></a></li>
        <?php } while ($row_Menu = mysql_fetch_assoc($Menu)); ?>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
  
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
        <li class="active">Edit SJ Kirim Quantity</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
		<form action="<?php echo $editFormAction; ?>" id="fm_editsjkirimquantity_form1" name="fm_editsjkirimquantity_form1" method="POST">
          <div class="box box-primary">
            <div class="box-body no-padding">
				<table id="tb_editsjkirimquantity_example1" class="table table-bordered">
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
                    <?php
					$Tgl2 = $row_Tgl['Tgl'];
					$Convert = str_replace('/', '-', $Tgl2);
					$date = new DateTime($Convert);
					$Today = new DateTime();
					$diff=date_diff($Today,$date);
					$Min = $diff->format("%a");
				  ?>
                    	<?php $x=1; ?>
						<?php do { ?>
						  <tr>
                          	<input name="hd_editsjkirimquantity_Id[]" type="hidden" id="hd_editsjkirimquantity_Id" value="<?php echo $row_EditIsiSJKirim['Id']; ?>">
							<input name="hd_editsjkirimquantity_QSisaKir2" type="hidden" id="hd_editsjkirimquantity_QSisaKir2<?php echo $x; ?>" value="<?php echo $row_EditIsiSJKirim['QSisaKir']; ?>">
                            <input name="hd_editsjkirimquantity_IsiSJKir[]" type="hidden" class="textview" id="hd_editsjkirimquantity_IsiSJKir" value="<?php echo $row_EditIsiSJKirim['IsiSJKir']; ?>">
                            <input name="hd_editsjkirimquantity_Purchase[]" type="hidden" class="textview" id="hd_editsjkirimquantity_Purchase" value="<?php echo $row_EditIsiSJKirim['Purchase']; ?>">
                            <input name="tx_editsjkirimquantity_QTertanda2[]" type="hidden" class="form-control" id="tx_editsjkirimquantity_QTertanda2" autocomplete="off" value="<?php echo $row_EditIsiSJKirim['QTertanda']; ?>">
							<td><input name="tx_editsjkirimquantity_JS" type="text" class="form-control" id="tx_editsjkirimquantity_JS" value="<?php echo $row_EditIsiSJKirim['JS']; ?>" readonly></td>
							<td><input name="tx_editsjkirimquantity_Barang" type="text" class="form-control" id="tx_editsjkirimquantity_Barang" value="<?php echo $row_EditIsiSJKirim['Barang']; ?>" readonly></td>
							<td><input name="hd_editsjkirimquantity_Warehouse[]" type="text" class="form-control" id="hd_editsjkirimquantity_Warehouse" value="<?php echo $row_EditIsiSJKirim['Warehouse']; ?>" readonly></td>
							<td><input name="tx_editsjkirimquantity_QKirim[]" type="text" class="form-control" id="tx_editsjkirimquantity_QKirim" autocomplete="off" value="<?php echo $row_EditIsiSJKirim['QKirim']; ?>" readonly></td>
                            <input name="tx_editsjkirimquantity_QSisaKir[]" type="hidden" class="form-control" id="tx_editsjkirimquantity_QSisaKir<?php echo $x; ?>" value="<?php echo $row_EditIsiSJKirim['QSisaKir']; ?>" readonly>
                            <td><input name="tx_editsjkirimquantity_QTertanda[]" type="text" class="form-control" id="tx_editsjkirimquantity_QTertanda<?php echo $x; ?>" autocomplete="off" onkeyup="this.value = minmax(this.value, 0, <?php echo $row_EditIsiSJKirim['QKirim']; ?>)" onKeyUp="sisa();" value="<?php echo $row_EditIsiSJKirim['QTertanda']; ?>" required></td>
                          </tr>
                          <?php $x++; ?>
						<?php } while ($row_EditIsiSJKirim = mysql_fetch_assoc($EditIsiSJKirim)); ?>
					</tbody>
				  </table>
					
			</div>
			
            <!-- /.box-body -->
            <div class="box-footer">
					<label>Tanggal Barang Sampai Tujuan/Tanggal Mulai Penghitungan</label>
					<div class="input-group">
					<div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                    </div>
					<input name="tx_editsjkirimquantity_S" type="text" class="form-control" id="tx_editsjkirimquantity_S" autocomplete="off" required>
					</div>
					<input name="hd_editsjkirimquantity_E" type="hidden" id="hd_editsjkirimquantity_E">
				<br>
				<a href="ViewSJKirim.php?SJKir=<?php echo $row_View['SJKir']; ?>"><button type="button" class="btn btn-default pull-left">Cancel</button></a>
				<button type="submit" name="bt_editsjkirimquantity_submit" id="bt_editsjkirimquantity_submit" class="btn btn-success pull-right">Update</button>
				
			</div>
          </div>
          <!-- /.box -->
		<input type="hidden" name="MM_update" value="form1">
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
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> `1.0.0
    </div>
    <strong>Copyright &copy; 2015 <a href="http://apera.id">Apera Indonesia</a>.</strong> All rights
    reserved.
  </footer>
  <!-- /.footer-wrapper -->
  
  <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>

<!-- jQuery 2.1.4 -->
<script src="../../library/jQuery/jQuery-2.1.4.min.js"></script>
<!-- jQuery UI -->
<script src="../../library/jQueryUI/jquery-ui.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="../../library/bootstrap/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="../../library/datatables/jquery.dataTables.min.js"></script>
<script src="../../library/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../../library/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../../library/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../library/dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../library/dist/js/demo.js"></script>
<!-- datepicker -->
<script src="../../library/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<!-- page script -->

<script language="javascript">
  function sisa() {
  for(x = 1; x < 11; x++){
    var txtFirstNumberValue = document.getElementById('hd_editsjkirimquantity_QSisaKir2'+x).value;
    var txtSecondNumberValue = document.getElementById('tx_editsjkirimquantity_QTertanda'+x).value;
	var result = parseInt(txtFirstNumberValue) - parseInt(txtSecondNumberValue);
	  if (!isNaN(result)) {
		document.getElementById('tx_editsjkirimquantity_QSisaKir'+x).value = result;
      }
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
<script>
var Min = <?php echo $Min ?>;
  $('#tx_editsjkirimquantity_S').datepicker({
	  format: "dd/mm/yyyy",
	  startDate: '+'+Min+'d',
	  orientation: "bottom left",
	  todayHighlight: true,
	  autoclose: true
  }); 
</script>

<script>
function tgl(text){

	var date = text.value.substr(0, 2);
	var month = text.value.substr(3, 2)-1;
	var year = text.value.substr(6, 5);
	
	var test = new Date(year, month, date);
	var test2 = new Date(new Date(test).setMonth(test.getMonth()+1));
	var test3 = new Date(new Date(test2).setDate(test2.getDate()-1));
	document.getElementById('hd_editsjkirimquantity_E').value = ('0' + test3.getDate()).slice(-2) + "/" + ('0' + (test3.getMonth()+1)).slice(-2) + "/" + test3.getFullYear();
	
}
</script>

</body>
</html>
<?php
  mysql_free_result($Menu);
  mysql_free_result($EditIsiSJKirim);
  mysql_free_result($User);
  mysql_free_result($View);
?>