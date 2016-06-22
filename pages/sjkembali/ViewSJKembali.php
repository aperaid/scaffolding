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

$colname_ViewIsiSJKembali = "-1";
if (isset($_GET['SJKem'])) {
  $colname_ViewIsiSJKembali = $_GET['SJKem'];
}
mysql_select_db($database_Connection, $Connection);
$query_ViewIsiSJKembali = sprintf("SELECT isisjkembali.*, isisjkirim.QSisaKem, sjkirim.Tgl, transaksi.Barang, sjkirim.Reference, project.Project, customer.* FROM isisjkembali INNER JOIN isisjkirim ON isisjkembali.IsiSJKir=isisjkirim.IsiSJKir INNER JOIN sjkirim ON isisjkirim.SJKir=sjkirim.SJKir INNER JOIN transaksi ON isisjkembali.Purchase=transaksi.Purchase INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode INNER JOIN customer ON project.CCode=customer.CCode WHERE isisjkembali.SJKem = %s ORDER BY isisjkembali.Id ASC", GetSQLValueString($colname_ViewIsiSJKembali, "text"));
$ViewIsiSJKembali = mysql_query($query_ViewIsiSJKembali, $Connection) or die(mysql_error());
$row_ViewIsiSJKembali = mysql_fetch_assoc($ViewIsiSJKembali);
$totalRows_ViewIsiSJKembali = mysql_num_rows($ViewIsiSJKembali);

$colname_View = "-1";
if (isset($_GET['SJKem'])) {
  $colname_View = $_GET['SJKem'];
}
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT SJKem, Tgl FROM sjkembali WHERE SJKem = %s", GetSQLValueString($colname_View, "text"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);

//disabled edit button if qterima exists
mysql_select_db($database_Connection, $Connection);
$query_check = sprintf("SELECT check_sjkem(%s) AS result", GetSQLValueString($colname_ViewIsiSJKembali, "text"));
$check = mysql_query($query_check, $Connection) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
//disabled end

$colname_User = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_User = $_SESSION['MM_Username'];
}
mysql_select_db($database_Connection, $Connection);
$query_User = sprintf("SELECT Name FROM users WHERE Username = %s", GetSQLValueString($colname_User, "text"));
$User = mysql_query($query_User, $Connection) or die(mysql_error());
$row_User = mysql_fetch_assoc($User);
$totalRows_User = mysql_num_rows($User);
// Declare Root directory
$ROOT="../../";
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>BDN ERP | View SJ Kembali</title>
  <!-- css include -->
  <?php include_once('../../pages/cssinclude.php'); ?>
</head>
<body class="hold-transition skin-blue fixed sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <?php include_once('../../pages/logo.php'); ?>
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
      <!-- Sidebar Menu -->
		<?php
			$top_menu_sel="menu_sjkembali";
			include_once('../../pages/menu.php');
		?>
    </section>
    <!-- /.sidebar -->
  </aside>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Surat Jalan Kembali
        <small>View</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="../SJKembali/SJKembali.php">SJ Kembali</a></li>
        <li class="active">View SJ Kembali</li>
      </ol>
    </section>

    <!-- Main content -->
	<section class="content">
    <section class="invoice">
		
		<div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> SJ Kembali | <?php echo $row_View['SJKem']; ?>
			<small class="pull-right">Date: <?php echo $row_View['Tgl']; ?></small>
		  </h2>
        </div>
        <!-- /.col -->
		</div>
		
		<!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          Company
          <address>
            <strong><?php echo $row_ViewIsiSJKembali['Company']; ?></strong><br>
            <?php echo $row_ViewIsiSJKembali['Alamat']; ?><br>
            <?php echo $row_ViewIsiSJKembali['Kota']; ?>,  <?php echo $row_ViewIsiSJKembali['Zip']; ?><br>
            Phone: <?php echo $row_ViewIsiSJKembali['CompPhone']; ?><br>
            Email: <?php echo $row_ViewIsiSJKembali['CompEmail']; ?>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          Project
          <address>
            <strong><?php echo $row_ViewIsiSJKembali['Project']; ?></strong><br>
            <?php echo $row_ViewIsiSJKembali['Alamat']; ?><br>
            <?php echo $row_ViewIsiSJKembali['Kota']; ?>,  <?php echo $row_ViewIsiSJKembali['Zip']; ?><br>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          Contact Person
          <address>
            <strong><?php echo $row_ViewIsiSJKembali['Customer']; ?></strong><br>
            Phone: <?php echo $row_ViewIsiSJKembali['CustPhone']; ?><br>
            Email: <?php echo $row_ViewIsiSJKembali['CustEmail']; ?>
          </address>
        </div>
        <!-- /.col -->
      </div>
	  
		
     

          <div class="row">
            <div class="col-xs-12 table-responsive">
              <table id="tb_viewsjkembali_example1" class="table table-striped">
                <thead>
                <tr>
					<th>Tanggal Kirim</th>
					<th>Barang</th>
					<th>Warehouse</th>
					<th>Q Pengambilan</th>
					<th>Q Terima</th>
                </tr>
                </thead>
                <tbody>
                <?php $Reference = $row_ViewIsiSJKembali['Reference']; ?>
					<?php do { ?>
					<tr>
						<td><input	 name="tx_viewsjkembali_Tgl" type="text" class="form-control" id="tx_viewsjkembali_Tgl" value="<?php echo $row_ViewIsiSJKembali['Tgl']; ?>" readonly></td>
						<td><input name="tx_viewsjkembali_Barang" type="text" class="form-control" id="tx_viewsjkembali_Barang" value="<?php echo $row_ViewIsiSJKembali['Barang']; ?>" readonly></td>
						<td><input name="tx_viewsjkembali_Warehouse" type="text" class="form-control" id="tx_viewsjkembali_Warehouse" value="<?php echo $row_ViewIsiSJKembali['Warehouse']; ?>" readonly></td>
						<td><input name="tx_viewsjkembali_QTertanda" type="text" class="form-control" id="tx_viewsjkembali_QTertanda" value="<?php echo $row_ViewIsiSJKembali['QTertanda']; ?>" readonly></td>
						<td><input name="tx_viewsjkembali_QTerima" type="text" class="form-control" id="tx_viewsjkembali_QTerima" value="<?php echo $row_ViewIsiSJKembali['QTerima']; ?>" readonly></td
					></tr>
					<?php } while ($row_ViewIsiSJKembali = mysql_fetch_assoc($ViewIsiSJKembali)); ?>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
            
            <div class="box-footer">
				<a href="SJKembali.php"><button type="button" class="btn btn-default">Back</button></a>
				<a href="SJKembali.php"><button type="button" class="btn btn-default">Print</button></a>
				<div class="btn-group pull-right">
					<a href="EditSJKembali.php?SJKem=<?php echo $row_View['SJKem']; ?>&Reference=<?php echo $Reference; ?>"><button type="button" <?php if($row_check['result'] == 0) { ?> class="btn btn-primary" <?php } else { ?> class="btn btn-default" disabled <?php } ?>>Edit Pengembalian</button></a>
					<a href="EditSJKembaliQuantity.php?SJKem=<?php echo $row_View['SJKem']; ?>"><button type="button" class="btn btn-success">Quantity Terima</button></a>
				</div>
			</div>
          </div>
          <!-- /.box -->
       
    </section>
    </section>
	<!-- /.content -->
    
  </div>
  <!-- /.content-wrapper -->
  
  <!-- Footer Wrapper -->
  <footer class="main-footer">
    <?php include_once('../../pages/footer.php'); ?>
  </footer>
  <!-- /.footer-wrapper -->
  
  <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>

<!-- jsinclude -->
<?php include_once('../../pages/jsinclude.php'); ?>
</body>
</html>
<?php
  mysql_free_result($Menu);
  mysql_free_result($ViewIsiSJKembali);
  mysql_free_result($View);
  mysql_free_result($User);
  mysql_free_result($check);
?>