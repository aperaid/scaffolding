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

$colname_Purchase = "-1";
if (isset($_GET['Reference'])) {
  $colname_Purchase = $_GET['Reference'];
}

//Company Details TAB
mysql_select_db($database_Connection, $Connection);
$query_detail = sprintf("SELECT project.*, customer.*, pocustomer.Tgl as tgl FROM project INNER JOIN customer ON project.CCode=customer.CCode INNER JOIN pocustomer ON pocustomer.PCode=project.PCode WHERE pocustomer.Reference = %s", GetSQLValueString($colname_Purchase, "text"));
$detail = mysql_query($query_detail, $Connection) or die(mysql_error());
$row_detail = mysql_fetch_assoc($detail);
$totalRows_detail = mysql_num_rows($detail);
//Company Details End

//Overview table Tab
mysql_select_db($database_Connection, $Connection);
$query_Purchase = sprintf("SELECT transaksi.JS AS js, transaksi.Barang AS barang, transaksi.Quantity AS quantity, transaksi.Amount AS price, transaksi.QSisaKir AS qsisakirim, transaksi.QSisaKem AS qsisakembali FROM transaksi INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference WHERE transaksi.Reference = %s ORDER BY transaksi.Id", GetSQLValueString($colname_Purchase, "text"));
$Purchase = mysql_query($query_Purchase, $Connection) or die(mysql_error());
$row_Purchase = mysql_fetch_assoc($Purchase);
$totalRows_Purchase = mysql_num_rows($Purchase);
//Overview table Tab End

$colname_View = "-1";
if (isset($_GET['Reference'])) {
  $colname_View = $_GET['Reference'];
}

//Untuk Ambil reference doang
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT Reference FROM transaksi WHERE Reference = %s", GetSQLValueString($colname_View, "text"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);
//Untuk ambil reference doang

//SJKirim Tab
mysql_select_db($database_Connection, $Connection);
$query_sjkirim = sprintf("SELECT sjkirim.SJKir AS sjkirim_id, sjkirim.Tgl AS tgl, sum(isisjkirim.QTertanda) AS qtertanda FROM isisjkirim LEFT JOIN sjkirim ON isisjkirim.SJKir=sjkirim.SJKir WHERE sjkirim.Reference=%s GROUP BY sjkirim.SJKir", GetSQLValueString($colname_View, "text"));
$view_sjkirim = mysql_query ($query_sjkirim, $Connection) or die(mysql_error());
$row_sjkirim = mysql_fetch_assoc($view_sjkirim);
//SJKirim TAB End

//SJKembali Tab
mysql_select_db($database_Connection, $Connection);
$query_sjkembali = sprintf("SELECT sjkembali.SJKem AS sjkembali_id, sjkembali.Tgl AS tgl, sum(isisjkembali.QTerima) AS qterima FROM isisjkembali LEFT JOIN sjkembali ON isisjkembali.SJKem=sjkembali.SJKem WHERE sjkembali.Reference=%s GROUP BY sjkembali.SJKem", GetSQLValueString($colname_View, "text"));
$view_sjkembali = mysql_query ($query_sjkembali, $Connection) or die(mysql_error());
$row_sjkembali = mysql_fetch_assoc($view_sjkembali);
//SJKirim TAB End

//FUNCTION BUTTON DISABLE
mysql_select_db($database_Connection, $Connection);
$query_check = sprintf("SELECT check_POCustomer('$colname_View') AS result");
$check = mysql_query($query_check, $Connection) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);
//Function button disable end

//SJkirim buttton disabled
mysql_select_db($database_Connection, $Connection);
$query_sjkirimcheck = sprintf("SELECT check_sjkirimbutton('$colname_View') AS result");
$sjkirimcheck = mysql_query($query_sjkirimcheck, $Connection) or die(mysql_error());
$row_sjkirimcheck = mysql_fetch_assoc($sjkirimcheck);
//sjkirim button disabled end

//SJkembali buttton disabled
mysql_select_db($database_Connection, $Connection);
$query_sjkembalicheck = sprintf("SELECT check_sjkembalibutton('$colname_View') AS result");
$sjkembalicheck = mysql_query($query_sjkembalicheck, $Connection) or die(mysql_error());
$row_sjkembalicheck = mysql_fetch_assoc($sjkembalicheck);
//sjkembali button disabled end

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
  <title>BDN ERP | View PO</title>
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
        Purchase Order
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="../POCustomer/POCustomer.php">Purchase Order</a></li>
        <li class="active">View PO</li>
      </ol>
    </section>

	<!-- Main content -->
    
    <!-- /.content -->
	
	<!-- Tab Part -->
	<section class="content">
	<div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#overall_tab" data-toggle="tab">Overall</a></li>
              <li><a href="#sjkirim_tab" data-toggle="tab">SJKirim</a></li>
              <li><a href="#sjkembali_tab" data-toggle="tab">SJKembali</a></li>
              <li><a href="#sjclaim_tab" data-toggle="tab">Claim</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="overall_tab">
                <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> PT. BDN | 
			<?php echo $colname_View; ?>
			<small class="pull-right">Date: <?php echo $row_detail['tgl']; ?></small>
		  </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          Company
          <address>
            <strong><?php echo $row_detail['Company']; ?></strong><br>
            <?php echo $row_detail['Alamat']; ?><br>
            <?php echo $row_detail['Kota']; ?>,  <?php echo $row_detail['Zip']; ?><br>
            Phone: <?php echo $row_detail['CompPhone']; ?><br>
            Email: <?php echo $row_detail['CompEmail']; ?>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          Project
          <address>
            <strong><?php echo $row_detail['Project']; ?></strong><br>
            <?php echo $row_detail['Alamat']; ?><br>
            <?php echo $row_detail['Kota']; ?>,  <?php echo $row_detail['Zip']; ?><br>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          Contact Person
          <address>
            <strong><?php echo $row_detail['Customer']; ?></strong><br>
            Phone: <?php echo $row_detail['CustPhone']; ?><br>
            Email: <?php echo $row_detail['CustEmail']; ?>
          </address>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-striped">
            <thead>
            <tr>
                <th>J/S</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Price</th>
				<th>Progress</th>
				<th>Status</th>
            </tr>
            </thead>
            <tbody>
				<?php do { ?>
                  <tr>
                    <td><?php echo $row_Purchase['js']; ?></td>
                    <td><?php echo $row_Purchase['barang']; ?></td>
                    <td><?php echo $row_Purchase['quantity']; ?></td>
                    <td>Rp <?php echo number_format($row_Purchase['price'], 2,',', '.'); ?></td>
					<?php /* Kalau SEWA */ if ($row_Purchase['js'] == "Sewa") { ?>
						<?php /* belum dikirim */ if ($row_Purchase['qsisakirim'] == $row_Purchase['quantity'] && $row_Purchase['qsisakembali'] == 0){ ?>
						<td>
						<div class="progress progress-xs">
						  <div class="progress-bar progress-bar-red" style="width:10%"></div>
						</div>
						</td>
						<td><span class="badge bg-red">Belum Dikirim</span></td>
						
						<?php } /* setengah dikirim */ elseif (($row_Purchase['qsisakirim'] < $row_Purchase['quantity']) && $row_Purchase['qsisakirim'] != 0){ ?>
						<td>
						<div class="progress progress-xs">
						  <div class="progress-bar progress-bar-yellow" style="width:25%"></div>
						</div>
						</td>
						<td><span class="badge bg-yellow">Separuh Terkirim</span></td>
						
						<?php } /* pengiriman selesai, dalam proses penyewaan */elseif ($row_Purchase['qsisakirim'] == 0 && $row_Purchase['qsisakembali'] == $row_Purchase['quantity']){ ?>
						<td>
						<div class="progress progress-xs">
						  <div class="progress-bar progress-bar-blue" style="width:50%"></div>
						</div>
						</td>
						<td><span class="badge bg-blue">Pengiriman Selesai, dalam penyewaan</span></td>
						
						<?php } /* setengah dikembalikan */ elseif (($row_Purchase['qsisakembali'] < $row_Purchase['quantity']) && $row_Purchase['qsisakembali'] != 0){ ?>
						<td>
						<div class="progress progress-xs">
						  <div class="progress-bar progress-bar-yellow" style="width:75%"></div>
						</div>
						</td>
						<td><span class="badge bg-yellow">Separuh Kembali</span></td>
						
						<?php } /* selesai dikembalikan */ elseif ($row_Purchase['qsisakembali'] == 0 && $row_Purchase['qsisakirim'] == 0){ ?>
						<td>
						<div class="progress progress-xs">
						  <div class="progress-bar progress-bar-green" style="width:100%"></div>
						</div>
						</td>
						<td><span class="badge bg-green">Semua Kembali/Claimed, Transaksi Selesai</span></td>
						<?php } ?>
                    <?php } /* kalau JUAL */ elseif($row_Purchase['js'] == "Jual") { ?>
						<?php /* belum dikirim */ if ($row_Purchase['qsisakirim'] == $row_Purchase['quantity']){ ?>
						<td>
						<div class="progress progress-xs">
						  <div class="progress-bar progress-bar-red" style="width:10%"></div>
						</div>
						</td>
						<td><span class="badge bg-red">Belum Dikirim</span></td>
						
						<?php } /* setengah dikirim */ elseif ($row_Purchase['qsisakirim'] < $row_Purchase['quantity'] && $row_Purchase['qsisakirim'] != 0){ ?>
						<td>
						<div class="progress progress-xs">
						  <div class="progress-bar progress-bar-yellow" style="width:50%"></div>
						</div>
						</td>
						<td><span class="badge bg-yellow">Separuh Terkirim</span></td>
						
						<?php } /* pengiriman selesai, dalam proses penyewaan */elseif ($row_Purchase['qsisakirim'] == 0){ ?>
						<td>
						<div class="progress progress-xs">
						  <div class="progress-bar progress-bar-green" style="width:100%"></div>
						</div>
						</td>
						<td><span class="badge bg-green">Selesai Dikirim, Penjualan Selesai</span></td>
					<?php } } ?>
				  </tr>
                <?php } while ($row_Purchase = mysql_fetch_assoc($Purchase)); ?>
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
          <a href="#" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
		  <a href="POCustomer.php">																				<button 						type="button" 	class="btn btn-default pull-left" style="margin-right: 5px;">Back</button></a>
          <a href="../sjkirim/InsertSJKirim.php?Reference=<?php echo $row_View['Reference']; ?>">				<button id="SJKirim_button" 	type="button"  	style="margin-right: 5px;"	<?php if ($row_sjkirimcheck['result'] == 0)		{ ?>	class="btn btn-default pull-right" disabled	<?php } else {?>	class="btn btn-success pull-right"	<?php }?>>SJ Kirim</button></a>
		  <a href="../sjkembali/InsertSJKembali.php?Reference=<?php echo $row_View['Reference']; ?>">			<button id="SJKembali_button" 	type="button"  	style="margin-right: 5px;"	<?php if ($row_sjkembalicheck['result'] == 0) 	{ ?>	class="btn btn-default pull-right" disabled	<?php } else {?>	class="btn btn-warning pull-right"	<?php }?>>SJ Kembali</button></a>
		  <a href="../transaksiclaim/inserttransaksiclaim.php?Reference=<?php echo $row_View['Reference']; ?>">	<button id="claim_button" 		type="button"  	style="margin-right: 5px;"	<?php if ($row_sjkembalicheck['result'] == 0) 	{ ?>	class="btn btn-default pull-right" disabled	<?php } else {?>	class="btn btn-info pull-right"		<?php }?>>Claim</button></a>
          <a href="EditTransaksi.php?Reference=<?php echo $row_View['Reference']; ?>">							<button id="edit_button"		type="button"  	style="margin-right: 5px;"	<?php if ($row_check['result'] == 1) 			{ ?>	class="btn btn-default pull-right" disabled	<?php } else {?>	class="btn btn-primary pull-right"	<?php }?>>Edit</button></a>
          <a href="DeletePOCustomer.php?Reference=<?php echo $row_View['Reference']; ?>">						<button id="delete_button" 		type="button"  	style="margin-right: 5px;"	<?php if ($row_check['result'] == 1) 			{ ?>	class="btn btn-default pull-right" disabled	<?php } else {?>	class="btn btn-danger pull-right"	<?php }?> onclick="return confirm('Delete PO Customer?')">Delete</button></a>
        </div>
      </div>
    </section>
              </div>
              <!-- /.tab-pane -->
			  <div class="tab-pane" id="sjkirim_tab">
                
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-condensed">
                <tr>
                  <th>SJKir</th>
                  <th>Tanggal</th>
                  <th>Progress</th>
                  <th>Status</th>
				  <th>View</th>
                </tr>
                <?php do { ?>
                  <tr>
				    <td><?php echo $row_sjkirim['sjkirim_id']; ?></td>
                    <td><?php echo $row_sjkirim['tgl']; ?></td>
                    <?php if($row_sjkirim['qtertanda'] != 0 ){ ?>
					<td>
                    <div class="progress progress-xs">
                      <div class="progress-bar progress-bar-success" style="width: 100%"></div>
                    </div>
                    </td>                    
					<td><span class="badge bg-green">Selesai Dikirim</span></td>
				    <?php }
					else { ?>
						<td>
                    <div class="progress progress-xs">
                      <div class="progress-bar progress-bar-yellow" style="width: 50%"></div>
                    </div>
                    </td>                    
					<td><span class="badge bg-yellow">Dalam Pengiriman</span></td>
					<?php } ?>
					<td><a href="../sjkirim/ViewSJKirim.php?SJKir=<?php echo $row_sjkirim['sjkirim_id']; ?>"><button class="btn btn-primary">View</button></a></td>
                  </tr>
                <?php } while ($row_sjkirim = mysql_fetch_assoc($view_sjkirim)); ?>
            </table>
            </div>
            <!-- /.box-body -->
              </div>
              <!-- /.tab-pane -->
			  
			  <!-- /.tab-pane -->
			  <div class="tab-pane" id="sjkembali_tab">
                
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-condensed">
                <tr>
                  <th>SJKem</th>
                  <th>Tanggal</th>
                  <th>Progress</th>
                  <th>Status</th>
				  <th>View</th>
                </tr>
                <?php do { ?>
                  <tr>
				    <td><?php echo $row_sjkembali['sjkembali_id']; ?></td>
                    <td><?php echo $row_sjkembali['tgl']; ?></td>
                    <?php if($row_sjkembali['qterima'] != 0 ){ ?>
					<td>
                    <div class="progress progress-xs">
                      <div class="progress-bar progress-bar-success" style="width: 100%"></div>
                    </div>
                    </td>                    
					<td><span class="badge bg-green">Selesai Dikembalikan</span></td>
				    <?php }
					else { ?>
						<td>
                    <div class="progress progress-xs">
                      <div class="progress-bar progress-bar-yellow" style="width: 50%"></div>
                    </div>
                    </td>                    
					<td><span class="badge bg-yellow">Dalam Pengambilan</span></td>
					<?php } ?>
					<td><a href="../sjKembali/ViewSJKembali.php?SJKem=<?php echo $row_sjkembali['sjkembali_id']; ?>"><button class="btn btn-primary">View</button></a></td>
                  </tr>
                <?php } while ($row_sjkembali = mysql_fetch_assoc($view_sjkembali)); ?>
            </table>
            </div>
            <!-- /.box-body -->
              </div>
              <!-- /.tab-pane -->
              
            </div>
			<!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
    
	<!-- /.col -->
	<!-- Tab Part End -->
	
	<div class="clearfix"></div>
	
  </div>
    </section>
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
</body>
</html>
<?php
  mysql_free_result($Menu);
  mysql_free_result($detail);
  mysql_free_result($Purchase);
  mysql_free_result($View);
  mysql_free_result($view_sjkirim);
  mysql_free_result($view_sjkembali);
  mysql_free_result($check);
  mysql_free_result($sjkirimcheck);
  mysql_free_result($sjkembalicheck);
  mysql_free_result($User);
?>