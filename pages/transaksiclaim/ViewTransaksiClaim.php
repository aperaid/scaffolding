<?php require_once('../../connections/Connection.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}
// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF'] . "?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")) {
  $logoutAction .= "&" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_GET['doLogout'])) && ($_GET['doLogout'] == "true")) {
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username']  = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl']      = NULL;
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
$MM_authorizedUsers  = "Admin";
$MM_donotCheckaccess = "true";
// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup)
{
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False;
  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) {
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers  = Explode(",", $strUsers);
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
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("", $MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {
  $MM_qsChar   = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?"))
    $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0)
    $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo . $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: " . $MM_restrictGoTo);
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
	
$colname_TransaksiClaim = "-1";
if (isset($_GET['Reference'])) {
  $colname_TransaksiClaim = $_GET['Reference'];
  $colname_Periode = $_GET['Periode'];
}
	
mysql_select_db($database_Connection, $Connection);
$query_TransaksiClaim = sprintf("SELECT transaksiclaim.*, periode.Reference, transaksi.Barang, transaksi.QSisaKem, project.Project, customer.* FROM transaksiclaim LEFT JOIN periode ON transaksiclaim.IsiSJKir=periode.IsiSJKir INNER JOIN transaksi ON transaksiclaim.Purchase=transaksi.Purchase INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode INNER JOIN customer ON project.CCode=customer.CCode WHERE periode.Reference=%s AND transaksiclaim.Periode=%s GROUP BY transaksiclaim.Id ORDER BY transaksiclaim.Id ASC", GetSQLValueString($colname_TransaksiClaim, "text"), GetSQLValueString($colname_Periode, "text"));
$TransaksiClaim = mysql_query($query_TransaksiClaim, $Connection) or die(mysql_error());
$row_TransaksiClaim = mysql_fetch_assoc($TransaksiClaim);
$totalRows_TransaksiClaim = mysql_num_rows($TransaksiClaim);
	
mysql_select_db($database_Connection, $Connection);
$query_Menu = "SELECT * FROM menu";
$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
$row_Menu = mysql_fetch_assoc($Menu);
$totalRows_Menu = mysql_num_rows($Menu);

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
  <title>BDN ERP | Transaksi Claim</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- bootstrap-3.3.6-dist 3.3.5 -->
  <link rel="stylesheet" href="../../library/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../library/font-awesome-4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../library/ionicons-2.0.1/css/ionicons.min.css">
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
        Transaksi Claim
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">Transaksi Claim</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <section class="invoice">
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> Transaksi Claim | <?php echo $row_TransaksiClaim['Reference']; ?>
			<small class="pull-right">Date: <?php echo $row_TransaksiClaim['Tgl']; ?></small>
		  </h2>
        </div>
        <!-- /.col -->
      </div>
	  
	  <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          Company
          <address>
            <strong><?php echo $row_TransaksiClaim['Company']; ?></strong><br>
            <?php echo $row_TransaksiClaim['Alamat']; ?><br>
            <?php echo $row_TransaksiClaim['Kota']; ?>,  <?php echo $row_TransaksiClaim['Zip']; ?><br>
            Phone: <?php echo $row_TransaksiClaim['CompPhone']; ?><br>
            Email: <?php echo $row_TransaksiClaim['CompEmail']; ?>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          Project
          <address>
            <strong><?php echo $row_TransaksiClaim['Project']; ?></strong><br>
            <?php echo $row_TransaksiClaim['Alamat']; ?><br>
            <?php echo $row_TransaksiClaim['Kota']; ?>,  <?php echo $row_TransaksiClaim['Zip']; ?><br>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          Contact Person
          <address>
            <strong><?php echo $row_TransaksiClaim['Customer']; ?></strong><br>
            Phone: <?php echo $row_TransaksiClaim['CustPhone']; ?><br>
            Email: <?php echo $row_TransaksiClaim['CustEmail']; ?>
          </address>
        </div>
        <!-- /.col -->
      </div>
    
    <div class="row">
        <div class="col-xs-12 table-responsive">
              <table id="tb_viewsjkirim_example1" class="table table-striped">
                <thead>
					<th>No. Claim</th>
                    <th>Periode</th>
                    <th>Barang</th>
					<th>Tanggal Claim</th>
					<th>Project</th>
					<th>Amount</th>
					<th>Quantity Claim</th>
                </thead>
                <tbody>
					<?php do { ?>
                    
                    <?php 
					
					mysql_select_db($database_Connection, $Connection);
					$query_PerClaim = sprintf("SELECT MAX(periode.Periode) AS Periode FROM periode WHERE Deletes='ClaimS' AND Reference=%s AND IsiSJKir=%s AND Periode=%s", GetSQLValueString($row_TransaksiClaim['Reference'], "text"), GetSQLValueString($row_TransaksiClaim['IsiSJKir'], "text"), GetSQLValueString($row_TransaksiClaim['Periode'], "text"));
					$PerClaim = mysql_query($query_PerClaim, $Connection) or die(mysql_error());
					$row_PerClaim = mysql_fetch_assoc($PerClaim);
					$totalRows_PerClaim = mysql_num_rows($PerClaim);

					mysql_select_db($database_Connection, $Connection);
					$query_PerExtend = sprintf("SELECT MAX(periode.Periode) AS Periode FROM periode WHERE (Deletes='Extend' OR Deletes='Sewa') AND Reference=%s AND IsiSJKir=%s", GetSQLValueString($row_TransaksiClaim['Reference'], "text"), GetSQLValueString($row_TransaksiClaim['IsiSJKir'], "text"));
					$PerExtend = mysql_query($query_PerExtend, $Connection) or die(mysql_error());
					$row_PerExtend = mysql_fetch_assoc($PerExtend);
					$totalRows_PerExtend = mysql_num_rows($PerExtend);
					
					$Claim = $row_PerClaim['Periode'];
					$Extend = $row_PerExtend['Periode'];
					
					?>
					  <tr>
						<td><?php echo $row_TransaksiClaim['Claim']; ?></td>
                        <td><?php echo $row_TransaksiClaim['Periode']; ?></td>
                        <td><?php echo $row_TransaksiClaim['Barang']; ?></td>
						<td><?php echo $row_TransaksiClaim['Tgl']; ?></td>
						<td><?php echo $row_TransaksiClaim['Project']; ?></td>
						<td><?php echo number_format($row_TransaksiClaim['Amount'], 2); ?></td>
						<td><?php echo $row_TransaksiClaim['QClaim']; ?></td>
					  </tr>
                      <?php } while ($row_TransaksiClaim = mysql_fetch_assoc($TransaksiClaim)); ?>
                 </tbody>
              </table>
              </div>
            <!-- /.box-body -->
            <div class="box-footer">
				<a href="TransaksiClaim.php"><button type="button" class="btn btn-default">Back</button></a>
				<a href="#"><button type="button" class="btn btn-default">Print</button></a>
				
				<div class="btn-group pull-right">
				<a href="EditTransaksiClaim.php?Reference=<?php echo $_GET['Reference']; ?>&Periode=<?php echo $_GET['Periode']; ?>"><button type="button" <?php if ($Claim >= $Extend) { ?> class="btn btn-primary" <?php } else { ?> class="btn btn-default" disabled <?php } ?> >Edit Claim </button>
                </a>
                </div>
			  </div>
                <!-- /.box -->
        	</div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      </section>
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
  $(function () {
    $("#tb_transaksiclaim_example1").DataTable();
  });
</script>
</body>
</html>
<?php
  mysql_free_result($Menu);
  mysql_free_result($TransaksiClaim);
?>