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

$colname_InsertSJKembali = "-1";
if (isset($_GET['Reference'])) {
  $colname_InsertSJKembali = $_GET['Reference'];
}

mysql_select_db($database_Connection, $Connection);
$query_GetId = sprintf("SELECT MAX(Id) AS Id FROM periode WHERE Reference = %s AND (Deletes = 'Sewa' OR Deletes = 'Extend') GROUP BY IsiSJKir ORDER BY Id ASC", GetSQLValueString($colname_InsertSJKembali, "text"));
$GetId = mysql_query($query_GetId, $Connection) or die(mysql_error());
$row_GetId = mysql_fetch_assoc($GetId);
$totalRows_GetId = mysql_num_rows($GetId);

$query = mysql_query($query_GetId, $Connection) or die(mysql_error());
$Id2 = array();
while($row = mysql_fetch_assoc($query)){
	$Id2[] = $row['Id'];
}
$Id3 = join(',',$Id2); 

mysql_select_db($database_Connection, $Connection);
$query_InsertSJKembali = sprintf("SELECT isisjkirim.IsiSJKir, isisjkirim.QSisaKemInsert, periode.S, periode.E,  sjkirim.SJKir, sjkirim.Tgl, transaksi.Barang FROM isisjkirim LEFT JOIN periode ON isisjkirim.IsiSJKir=periode.IsiSJKir INNER JOIN sjkirim ON isisjkirim.SJKir=sjkirim.SJKir INNER JOIN transaksi ON isisjkirim.Purchase=transaksi.Purchase WHERE sjkirim.Reference = %s AND transaksi.JS = 'Sewa' AND periode.Id IN ($Id3) ORDER BY periode.Id ASC", GetSQLValueString($colname_InsertSJKembali, "text"));
$InsertSJKembali = mysql_query($query_InsertSJKembali, $Connection) or die(mysql_error());
$row_InsertSJKembali = mysql_fetch_assoc($InsertSJKembali);
$totalRows_InsertSJKembali = mysql_num_rows($InsertSJKembali);

mysql_select_db($database_Connection, $Connection);
$query_LastIsiSJKembali = "SELECT IsiSJKem FROM isisjkembali ORDER BY Id DESC";
$LastIsiSJKembali = mysql_query($query_LastIsiSJKembali, $Connection) or die(mysql_error());
$row_LastIsiSJKembali = mysql_fetch_assoc($LastIsiSJKembali);
$totalRows_LastIsiSJKembali = mysql_num_rows($LastIsiSJKembali);

mysql_select_db($database_Connection, $Connection);
$query_LastId = "SELECT Id FROM sjkembali ORDER BY Id DESC";
$LastId = mysql_query($query_LastId, $Connection) or die(mysql_error());
$row_LastId = mysql_fetch_assoc($LastId);
$totalRows_LastId = mysql_num_rows($LastId);

for($i=0;$i<$totalRows_InsertSJKembali;$i++){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $_SESSION['cb_insertsjkembalibarang_checkbox'][$i] = sprintf("%s", GetSQLValueString($_POST['cb_insertsjkembalibarang_checkbox'][$i], "int"));
  
  $insertGoTo = "InsertSJKembaliBarang2.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
  }
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

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>BDN ERP | Insert SJ Kembali Barang</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="../../library/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../library/font-awesome-4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../library/ionicons-2.0.1/css/ionicons.min.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../library/datatables/dataTables.bootstrap.css">
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
        Surat Jalan Kembali
        <small>Pilih</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="../SJKembali/SJKembali.php">SJ Kembali</a></li>
        <li class="active">Insert SJ Kembali Barang</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <form action="<?php echo $editFormAction; ?>" id="fm_insertsjkembalibarang_form1" name="fm_insertsjkembalibarang_form1" method="POST">
            <div class="box box-primary">
              <div class="box-body">
                <table id="tb_insertsjkembalibarang_example1" class="table table-bordered table-striped table-responsive">
                  <thead>
                  <tr>
                    <th>Pilih</th>
                    <th>Tgl Extend</th>
                    <th>Barang</th>
                    <th>Quantity Sisa Kembali</th>
                    <th>SJ Code</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php $increment = 1; ?>
                    <?php do { ?>
                    <?php 
					
					$tx_insertsjkembalibarang_Tgl = substr($_SESSION['tx_insertsjkembali_Tgl'], 1, -1);
					
					$tgl = $tx_insertsjkembalibarang_Tgl;
					$convert = str_replace('/', '-', $tgl);
					$tgls = $row_InsertSJKembali['S'];
					$converts = str_replace('/', '-', $tgls);
					$tgle = $row_InsertSJKembali['E'];
					$converte = str_replace('/', '-', $tgle);
					
					$check = strtotime($convert);
					$checks = strtotime($converts);
					$checke = strtotime($converte);
					
					?>
                      <tr>
                        <td><input type="checkbox" name="cb_insertsjkembalibarang_checkbox[]" id="cb_insertsjkembalibarang_checkbox" value="<?php echo $row_InsertSJKembali['IsiSJKir']; ?>" <?php if ($check < $checks){ ?> disabled <?php }elseif ($check > $checke){ ?> disabled <?php } ?>></td>
                        <td><input name="tx_insertsjkembalibarang_Tgl[]" type="text" class="form-control" id="tx_insertsjkembalibarang_Tgl" value="<?php echo $row_InsertSJKembali['S']; ?>" readonly></td>
                        <td><input name="tx_insertsjkembalibarang_Barang[]" type="text" class="form-control" id="tx_insertsjkembalibarang_Barang" value="<?php echo $row_InsertSJKembali['Barang']; ?>" readonly></td>
                        <td><input name="tx_insertsjkembalibarang_QSisaKemInsert[]" type="text" class="form-control" id="tx_insertsjkembalibarang_QSisaKemInsert" value="<?php echo $row_InsertSJKembali['QSisaKemInsert']; ?>" readonly></td>
                        <td><input name="tx_insertsjkembalibarang_SJKir[]" type="text" class="form-control" id="tx_insertsjkembalibarang_SJKir" value="<?php echo $row_InsertSJKembali['SJKir']; ?>" readonly></td>
                        </tr>
                      <?php $increment++; ?>
                      <?php } while ($row_InsertSJKembali = mysql_fetch_assoc($InsertSJKembali)); ?>
                  </tbody>
                </table>
              </div>
              <!-- /.box-body -->
               <div class="box-footer">
                <input type="submit" name="bt_insertsjkembalibarang_submit" id="bt_insertsjkembalibarang_submit" class="btn btn-primary pull-right" value="Choose" disabled>
                <a href="InsertSJKembali.php?Reference=<?php echo $_GET['Reference'] ?>"><button type="button" class="btn btn-default">Cancel</button></a>
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
<!-- page script -->
<script>
var checkboxes = $("input[type='checkbox']"),
    submitButt = $("input[type='submit']");

checkboxes.click(function() {
    submitButt.attr("disabled", !checkboxes.is(":checked"));
});
</script>

</body>
</html>
<?php
  mysql_free_result($Menu);
  mysql_free_result($LastIsiSJKembali);
  mysql_free_result($LastId);
  mysql_free_result($InsertSJKembali);
?>
