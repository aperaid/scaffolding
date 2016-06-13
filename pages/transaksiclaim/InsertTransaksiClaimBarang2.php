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

$colname_InsertTransaksiClaim = "-1";
if (isset($_GET['Reference'])) {
  $colname_InsertTransaksiClaim = $_GET['Reference'];
  $colname_Periode = $_GET['Periode'];
}

$checkbox = $_SESSION['cb_inserttransaksiclaimbarang_checkbox'];
$remove = preg_replace("/[^0-9,.]/", "", $checkbox);

error_reporting(E_ERROR); // bagian di ilangin error
$array = array();
    for ($i = 0; $i < 10; ++$i) { // krn bagian sini ga ngerti untuk count sesuai byk array
        $array[$i] = $remove[$i];
}
$count = count(array_filter($array));

$arrayaftercount = array();
    for ($i = 0; $i < $count; ++$i) {
        $arrayaftercount[$i] = $remove[$i];
}
	
$IsiSJKir = join(',',$arrayaftercount);  

mysql_select_db($database_Connection, $Connection);
$query_InsertTransaksiClaim = sprintf("SELECT isisjkirim.Id AS Id1, isisjkirim.Purchase, isisjkirim.QSisaKem, transaksi.Barang, periode.Id AS Id2, periode.Periode, periode.IsiSJKir, periode.S, periode.E FROM isisjkirim LEFT JOIN periode ON isisjkirim.IsiSJKir = periode.IsiSJKir LEFT JOIN transaksi ON periode.Purchase=transaksi.Purchase LEFT JOIN pocustomer ON transaksi.Reference=pocustomer.Reference WHERE transaksi.Reference = %s AND periode.Periode = %s AND (Deletes = 'Sewa' OR Deletes = 'Extend') AND isisjkirim.IsiSJKir IN ($IsiSJKir) ORDER BY transaksi.Id ASC", GetSQLValueString($colname_InsertTransaksiClaim, "text"), GetSQLValueString($colname_Periode, "text"));
$InsertTransaksiClaim = mysql_query($query_InsertTransaksiClaim, $Connection) or die(mysql_error());
$row_InsertTransaksiClaim = mysql_fetch_assoc($InsertTransaksiClaim);
$totalRows_InsertTransaksiClaim = mysql_num_rows($InsertTransaksiClaim);

mysql_select_db($database_Connection, $Connection);
$query_LastClaim = "SELECT Id FROM transaksiclaim ORDER BY Id DESC";
$LastClaim = mysql_query($query_LastClaim, $Connection) or die(mysql_error());
$row_LastClaim = mysql_fetch_assoc($LastClaim);
$totalRows_LastClaim = mysql_num_rows($LastClaim);

$colname_Reference = "-1";
if (isset($_GET['Reference'])) {
  $colname_Reference = $_GET['Reference'];
}

$colname_Periode = "-1";
if (isset($_GET['Reference'])) {
  $colname_Periode = $_GET['Reference'];
}
mysql_select_db($database_Connection, $Connection);
$query_Periode = sprintf("SELECT MAX(Periode) AS Periode FROM periode WHERE Reference = %s AND (Deletes = 'Sewa' OR Deletes = 'Extend')", GetSQLValueString($colname_Periode, "text"));
$Periode = mysql_query($query_Periode, $Connection) or die(mysql_error());
$row_Periode = mysql_fetch_assoc($Periode);
$totalRows_Periode = mysql_num_rows($Periode);

mysql_select_db($database_Connection, $Connection);
$query_LastId = "SELECT Id FROM invoice ORDER BY Id DESC";
$LastId = mysql_query($query_LastId, $Connection) or die(mysql_error());
$row_LastId = mysql_fetch_assoc($LastId);
$totalRows_LastId = mysql_num_rows($LastId);

for($i=0;$i<$totalRows_InsertTransaksiClaim;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE transaksi SET QSisaKem=QSisaKem-%s WHERE Purchase=%s",
                       GetSQLValueString($_POST['tx_inserttransaksiclaimbarang2_QClaim'][$i], "int"),
                       GetSQLValueString($_POST['tx_inserttransaksiclaimbarang2_Purchase'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
}

for($i=0;$i<$totalRows_InsertTransaksiClaim;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE isisjkirim SET QSisaKem=QSisaKem-%s, QSisaKemInsert=QSisaKemInsert-%s WHERE IsiSJKir=%s AND Id=%s",
                       GetSQLValueString($_POST['tx_inserttransaksiclaimbarang2_QClaim'][$i], "int"),
					   GetSQLValueString($_POST['tx_inserttransaksiclaimbarang2_QClaim'][$i], "int"),
                       GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_IsiSJKir'][$i], "text"),
					   GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_Id'][$i], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
}


for($i=0;$i<$totalRows_InsertTransaksiClaim;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE periode SET Quantity=Quantity-%s WHERE IsiSJKir=%s AND Reference=%s AND Periode=%s AND Id=%s AND SJKem IS NULL AND (Deletes = 'Sewa' OR Deletes = 'Extend')",
                       GetSQLValueString($_POST['tx_inserttransaksiclaimbarang2_QClaim'][$i], "int"),
					   GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_IsiSJKir'][$i], "text"),
                       GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_Reference'][$i], "text"),
					   GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_Periode'][$i], "text"),
					   GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_Id2'][$i], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO periode (Periode, S, E, Quantity, IsiSJKir, Reference, Purchase, Claim, Deletes) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, 'ClaimS')",
                       GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_Periode'][$i], "int"),
                       GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_S'][$i], "text"),
                       GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_E'], "text"),
                       GetSQLValueString($_POST['tx_inserttransaksiclaimbarang2_QClaim'][$i], "int"),
                       GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_IsiSJKir'][$i], "text"),
					   GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_Reference'][$i], "text"),
					   GetSQLValueString($_POST['tx_inserttransaksiclaimbarang2_Purchase'][$i], "text"),
					   GetSQLValueString($_POST['tx_inserttransaksiclaimbarang2_Claim'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO periode (Periode, S, E, Quantity, IsiSJKir, Reference, Purchase, Claim, Deletes) VALUES (%s+1, %s, %s, %s, %s, %s, %s, %s, 'ClaimE')",
                       GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_Periode'][$i], "int"),
                       GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_S2'], "text"),
                       GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_E'], "text"),
                       GetSQLValueString($_POST['tx_inserttransaksiclaimbarang2_QClaim'][$i], "int"),
                       GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_IsiSJKir'][$i], "text"),
					   GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_Reference'][$i], "text"),
					   GetSQLValueString($_POST['tx_inserttransaksiclaimbarang2_Purchase'][$i], "text"),
					   GetSQLValueString($_POST['tx_inserttransaksiclaimbarang2_Claim'][$i], "text"));
$deleteSQL = sprintf("DELETE FROM periode WHERE S=E AND Deletes = 'ClaimE'");
$alterSQL = sprintf("ALTER TABLE periode AUTO_INCREMENT = 1");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());
  
}
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO invoice (Invoice, JSC, Tgl, PPN, Reference, Periode) VALUES (%s, 'Claim', %s, %s, %s, %s)",
                       GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_Invoice2'], "text"),
                       GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_E2'], "text"),
                       GetSQLValueString($_POST['tx_inserttransaksiclaimbarang2_PPN'], "int"),
                       GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_Reference2'], "text"),
                       GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_Periode2'], "int"));
  
  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
}

  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO invoice (Invoice, JSC, Tgl, PPN, Reference, Periode) VALUES (%s, 'Sewa', %s, %s, %s, %s+1)",
                       GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_Invoice3'], "text"),
                       GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_E2'], "text"),
                       GetSQLValueString($_POST['tx_inserttransaksiclaimbarang2_PPN'], "int"),
                       GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_Reference2'], "text"),
                       GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_Periode2'], "int"));
  $deleteSQL = "DELETE FROM invoice WHERE invoice.Periode NOT IN (SELECT periode.Periode FROM periode)";
  $alterSQL = sprintf("ALTER TABLE invoice AUTO_INCREMENT = 1");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());
}

for($i=0;$i<$totalRows_InsertTransaksiClaim;$i++){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO transaksiclaim (Claim, Tgl, QClaim, Amount, Purchase, Periode, IsiSJKir) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tx_inserttransaksiclaimbarang2_Claim'][$i], "text"),
                       GetSQLValueString($_POST['tx_inserttransaksiclaimbarang2_Tgl'], "text"),
                       GetSQLValueString($_POST['tx_inserttransaksiclaimbarang2_QClaim'][$i], "int"),
					   GetSQLValueString($_POST['tx_inserttransaksiclaimbarang2_Amount'][$i], "int"),
                       GetSQLValueString($_POST['tx_inserttransaksiclaimbarang2_Purchase'][$i], "text"),
					   GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_Periode'][$i], "int"),
					   GetSQLValueString($_POST['hd_inserttransaksiclaimbarang2_IsiSJKir'][$i], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "TransaksiClaim.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	unset($_SESSION['cb_inserttransaksiclaimbarang_checkbox']);
	unset($_SESSION['tx_inserttransaksiclaim_Tgl']);
	unset($_SESSION['tx_inserttransaksiclaim_Reference']);
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
  <title>BDN ERP | Insert Transaksi Claim Barang</title>
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
                  <?php echo $_SESSION['MM_Username']; ?> - <?php echo $row_User['Name']; ?>s
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
        <small>Item</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="../TransaksiClaim/TransaksiClaim.php">Transaksi Claim</a></li>
        <li class="active">Insert Transaksi Claim Barang</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Input Claim Item</h3>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
              <form action="<?php echo $editFormAction; ?>" id="fm_inserttransaksiclaimbarang2_form1" name="fm_inserttransaksiclaimbarang2_form1" method="POST">
                <div class="box-body">
                  <table class="table table-hover table-bordered" id="tb_inserttransaksiclaimbarang2_example1" name="tb_inserttransaksiclaimbarang2_example1">
    <thead>
      <tr>
		<th>No. Claim</th>
		<th>Barang</th>
		<th>Quantity Ditempat</th>
		<th>Quantity Claim</th>
		<th>Amount</th>
		<th>No. Purchase</th>
      </tr>
    </thead>
    <tbody>
    
    <?php
	/*
	$TanggalS = $row_InsertTransaksiClaim['S'];
	$Convert = str_replace('/', '-', $TanggalS);
	$date = new DateTime($Convert);
	$TanggalE = $row_InsertTransaksiClaim['E'];
	$Convert2 = str_replace('/', '-', $TanggalE);
	$date2 = new DateTime($Convert2);
	$diff=date_diff($date2,$date);
	$Min = $diff->format("%a");
	*/
	?>
    
    <?php 
	$tx_inserttransaksiclaimbarang2_Tgl = substr($_SESSION['tx_inserttransaksiclaim_Tgl'], 1, -1);
	$tx_inserttransaksiclaimbarang2_Reference = substr($_SESSION['tx_inserttransaksiclaim_Reference'], 1, -1);
 	?> 
    
    <?php $increment = 1; ?>
	<?php do { ?>
    <?php
	
    /* --- Tanggal yang diinput sama user --- */
	// 1. Input tgl claim di masukin user ke variable $tgl
	$tgl = $tx_inserttransaksiclaimbarang2_Tgl;
	// 2. Abis itu di convert dalam bentuk tanggal yg bisa dicompare system trus dimasukkin ke variable $convert
	$convert = str_replace('/', '-', $tgl);
	// 3. convert ke bentuk comparable
	$check = strtotime($convert);
	
	/* --- Tanggal yang diinput sama user, ambil tanggal 01 nya dan akhir nya --- */
	// 4. Tanggal yg diinput sama user, ambil tanggal 01 nya, masukin ke $S2
	$S2 = date('01/m/Y', strtotime($convert));
	// 5. Tanggal yg diinput sama user, ambil tanggal akhirnya (29, 30, atau 31),  masukin ke $E2 Untuk Invoice
	$E2 = date('t/m/Y', strtotime($convert));
	
	/* --- Tanggal start sewa atau extend --- */
	// 1. Tanggal Start (S) di periode, dimasukkin ke variable $tgl2
	$tgl2 = $row_InsertTransaksiClaim['S'];
	// 2. Abis itu diconvert dalam bentuk tanggal yg bisa dicompare system trus dimasukkin ke variable $convert2
	$convert2 = str_replace('/', '-', $tgl2);
	// 3. Tanggal start sewa/extend, ambil tanggal akhirnya, masukin ke $last
	$last = date('t-m-Y', strtotime($convert2));
	// 4. convert ke bentuk comparable
	$E = strtotime($last);
	
	// 9. bandingin apakah tgl yg diinput user lebih kecil atau sama dengan tanggal akhir dari bulan sewa/extendnya
	if($check <= $E)
	{
		// 10. kalau lebih kecil/sama dengan, set $S dengan tanggal yg diinput
		$S = $tx_inserttransaksiclaimbarang2_Tgl;
	}
	else
	{
		// 11. kalau lebih besar, set $S dengan tanggal 01 dari bulan yg diinput sama user
		$S = $S2;			
	}
	?>
    
	  <tr>
      <input name="hd_inserttransaksiclaimbarang2_Id[]" type="hidden" id="hd_inserttransaksiclaimbarang2_Id" value="<?php echo $row_InsertTransaksiClaim['Id1']; ?>">
      <input name="hd_inserttransaksiclaimbarang2_Id2[]" type="hidden" id="hd_inserttransaksiclaimbarang2_Id2" value="<?php echo $row_InsertTransaksiClaim['Id2']; ?>">
      <input name="hd_inserttransaksiclaimbarang2_S[]" type="text" id="hd_inserttransaksiclaimbarang2_S" value="<?php echo $row_InsertTransaksiClaim['S']; ?>">
      <input name="hd_inserttransaksiclaimbarang2_E" type="text" id="hd_inserttransaksiclaimbarang2_E" value="<?php echo $tx_inserttransaksiclaimbarang2_Tgl; ?>">
      <input name="hd_inserttransaksiclaimbarang2_S2" type="text" id="hd_inserttransaksiclaimbarang2_S2" value="<?php echo $S ?>">
      <input name="hd_inserttransaksiclaimbarang2_E2" type="text" id="hd_inserttransaksiclaimbarang2_E2" value="<?php echo $E2; ?>">
	    <input name="hd_inserttransaksiclaimbarang2_IsiSJKir[]" type="hidden" id="hd_inserttransaksiclaimbarang2_IsiSJKir" value="<?php echo $row_InsertTransaksiClaim['IsiSJKir']; ?>">
	      <input name="hd_inserttransaksiclaimbarang2_Reference[]" type="hidden" id="hd_inserttransaksiclaimbarang2_Reference" value="<?php echo $tx_inserttransaksiclaimbarang2_Reference; ?>">
<input name="hd_inserttransaksiclaimbarang2_Periode[]" type="hidden" id="hd_inserttransaksiclaimbarang2_Periode" value="<?php echo $row_InsertTransaksiClaim['Periode']; ?>">
	      <input name="hd_inserttransaksiclaimbarang2_Invoice[]" type="hidden" id="hd_inserttransaksiclaimbarang2_Invoice" value="<?php echo str_pad($row_LastId['Id'] + $increment, 5, "0", STR_PAD_LEFT); ?>">
	    <td><input name="tx_inserttransaksiclaimbarang2_Claim[]" type="text" class="form-control" id="tx_inserttransaksiclaimbarang2_Claim" value="<?php echo $row_LastClaim['Id'] + $increment; ?>" readonly></td>
	    <td><input name="tx_inserttransaksiclaimbarang2_Barang" type="text" class="form-control" id="tx_inserttransaksiclaimbarang2_Barang" value="<?php echo $row_InsertTransaksiClaim['Barang']; ?>" readonly></td>
	    <td><input name="tx_inserttransaksiclaimbarang2_QSisaKem" type="text" class="form-control" id="tx_inserttransaksiclaimbarang2_QSisaKem" value="<?php echo $row_InsertTransaksiClaim['QSisaKem']; ?>" readonly></td>
	    <td><input name="tx_inserttransaksiclaimbarang2_QClaim[]" type="text" class="form-control" id="tx_inserttransaksiclaimbarang2_QClaim" autocomplete="off" onkeyup="this.value = minmax(this.value, 0, <?php echo $row_InsertTransaksiClaim['QSisaKem']; ?>)" required></td>
	    <td><input name="tx_inserttransaksiclaimbarang2_Amount[]" type="text" class="form-control" id="tx_inserttransaksiclaimbarang2_Amount" autocomplete="off" required></td>
	    <td><input name="tx_inserttransaksiclaimbarang2_Purchase[]" type="text" class="form-control" id="tx_inserttransaksiclaimbarang2_Purchase[]" value=<?php echo $row_InsertTransaksiClaim['Purchase']; ?> readonly></td>
	    </tr>
	  <?php $increment++; ?>
	  <?php } while ($row_InsertTransaksiClaim = mysql_fetch_assoc($InsertTransaksiClaim)); ?>
                  </table>
                      </div>
                <!-- /.box-body -->
                <div class="box-footer">
                <table class="table table-hover table-bordered" id="tb_inserttransaksiclaimbarang2_example2" name="tb_inserttransaksiclaimbarang2_example2">
                    <thead>
                      <th>PPN</th>
                    </thead>
                    <tbody>
                      <tr>

          <td>
          <input name="tx_inserttransaksiclaimbarang2_PPN" type="hidden" id="tx_inserttransaksiclaimbarang2_PPN" value="0">
          <input name="tx_inserttransaksiclaimbarang2_PPN" type="checkbox" id="tx_inserttransaksiclaimbarang2_PPN" value="1"></td>
                      </tr>
    				</tbody>
                </table>
                <a href="InsertTransaksiClaimBarang.php?Reference=<?php echo $tx_inserttransaksiclaimbarang2_Reference; ?>&Periode=<?php echo $row_Periode['Periode']; ?>"><button type="button" class="btn btn-default pull-left">Cancel</button></a>
		   <button type="submit" name="bt_inserttransaksiclaimbarang2_submit" id="bt_inserttransaksiclaimbarang2_submit" class="btn btn-success pull-right">Insert</button>
                </div>
                <input name="hd_inserttransaksiclaimbarang2_Invoice2" type="hidden" id="hd_inserttransaksiclaimbarang2_Invoice2" value="<?php echo str_pad($row_LastId['Id'] + 1, 5, "0", STR_PAD_LEFT); ?>">
                <input name="hd_inserttransaksiclaimbarang2_Invoice3" type="hidden" id="hd_inserttransaksiclaimbarang2_Invoice3" value="<?php echo str_pad($row_LastId['Id'] + 2, 5, "0", STR_PAD_LEFT); ?>">
            <input name="hd_inserttransaksiclaimbarang2_Reference2" type="hidden" id="hd_inserttransaksiclaimbarang2_Reference2" value="<?php echo $tx_inserttransaksiclaimbarang2_Reference; ?>">
          <input name="hd_inserttransaksiclaimbarang2_Periode2" type="hidden" id="hd_inserttransaksiclaimbarang2_Periode2" value="<?php echo $row_Periode['Periode']; ?>">
  <input type="hidden" name="MM_insert" value="form1">
  <input type="hidden" name="MM_update" value="form1">
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

<script>

function limit(text){
	var check = <?php echo $month ?>;
	var test = text.value; 
	var tgl = test.substr(3,2);
	var TanggalS = <?php echo json_encode($S) ?>;
	var TanggalS2 = <?php echo json_encode($S2) ?>;
	
    if (tgl < check) { 
		document.getElementById('hd_inserttransaksiclaimbarang2_S2').value = text.value;
    }
    else { 
		document.getElementById('hd_inserttransaksiclaimbarang2_S2').value = TanggalS2;
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
</body>
</html>
<?php
  mysql_free_result($Menu);
  mysql_free_result($InsertTransaksiClaim);
  mysql_free_result($LastClaim);
  mysql_free_result($Periode);
  mysql_free_result($LastId);
  mysql_free_result($User);
?>
