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

$checkbox = $_SESSION['cb_insertsjkembalibarang_checkbox'];
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
$query_LastIsiSJKembali = "SELECT Id FROM isisjkembali ORDER BY Id DESC";
$LastIsiSJKembali = mysql_query($query_LastIsiSJKembali, $Connection) or die(mysql_error());
$row_LastIsiSJKembali = mysql_fetch_assoc($LastIsiSJKembali);
$totalRows_LastIsiSJKembali = mysql_num_rows($LastIsiSJKembali);

$colname_GetPeriode = "-1";
if (isset($_GET['Reference'])) {
  $colname_GetPeriode = $_GET['Reference'];
}
mysql_select_db($database_Connection, $Connection);
$query_GetPeriode = sprintf("SELECT MAX(Periode) AS Periode FROM periode WHERE Reference = %s AND Deletes != 'KembaliE' AND Deletes != 'ClaimS' AND Deletes != 'ClaimE' AND Deletes != 'Jual'", GetSQLValueString($colname_GetPeriode, "text"));
$GetPeriode = mysql_query($query_GetPeriode, $Connection) or die(mysql_error());
$row_GetPeriode = mysql_fetch_assoc($GetPeriode);
$totalRows_GetPeriode = mysql_num_rows($GetPeriode);

$Periode = $row_GetPeriode['Periode'];

mysql_select_db($database_Connection, $Connection);
$query_InsertSJKembali = sprintf("SELECT isisjkirim.*, isisjkirim.QSisaKemInsert, periode.S, sjkirim.SJKir, sjkirim.Tgl, transaksi.Barang, transaksi.Purchase, transaksi.Reference FROM isisjkirim LEFT JOIN periode ON isisjkirim.IsiSJKir=periode.IsiSJKir INNER JOIN sjkirim ON isisjkirim.SJKir=sjkirim.SJKir INNER JOIN transaksi ON isisjkirim.Purchase=transaksi.Purchase WHERE isisjkirim.IsiSJKir IN ($IsiSJKir) AND transaksi.Reference=%s AND periode.Periode IN ($Periode) GROUP BY isisjkirim.IsiSJKir ORDER BY isisjkirim.Id ASC", GetSQLValueString($colname_GetPeriode, "text"));
$InsertSJKembali = mysql_query($query_InsertSJKembali, $Connection) or die(mysql_error());
$row_InsertSJKembali = mysql_fetch_assoc($InsertSJKembali);
$totalRows_InsertSJKembali = mysql_num_rows($InsertSJKembali);

mysql_select_db($database_Connection, $Connection);
$query_LastInvoiceId = "SELECT Id FROM invoice ORDER BY Id DESC";
$LastInvoiceId = mysql_query($query_LastInvoiceId, $Connection) or die(mysql_error());
$row_LastInvoiceId = mysql_fetch_assoc($LastInvoiceId);
$totalRows_LastInvoiceId = mysql_num_rows($LastInvoiceId);

mysql_select_db($database_Connection, $Connection);
$query_Invoice = sprintf("SELECT * FROM invoice WHERE Reference = %s ORDER BY Id DESC", GetSQLValueString($colname_GetPeriode, "text"));
$Invoice = mysql_query($query_Invoice, $Connection) or die(mysql_error());
$row_Invoice = mysql_fetch_assoc($Invoice);
$totalRows_Invoice = mysql_num_rows($Invoice);

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO sjkembali (SJKem, Tgl, Reference) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_SJKem'], "text"),
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_Tgl'], "text"),
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_Reference'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
}

for($i=0;$i<$totalRows_InsertSJKembali;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE isisjkirim SET QSisaKemInsert=QSisaKemInsert-%s WHERE IsiSJKir=%s",
                       GetSQLValueString($_POST['tx_insertsjkembalibarang2_QTertanda'][$i], "int"),
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_IsiSJKir'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE periode SET Quantity=Quantity-%s WHERE Periode=%s AND IsiSJKir = %s AND Deletes !='KembaliS' AND Deletes != 'KembaliE' AND Deletes != 'ClaimS' AND Deletes != 'ClaimE' AND Deletes != 'Jual'",
                       GetSQLValueString($_POST['tx_insertsjkembalibarang2_QTertanda'][$i], "int"),
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_Periode'][$i], "text"),
					   GetSQLValueString($_POST['hd_insertsjkembalibarang2_IsiSJKir'][$i], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO periode (Periode, S, E, Quantity, IsiSJKir, SJKem, Reference, Purchase, Deletes) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, 'KembaliS')",
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_Periode'][$i], "int"),
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_S'][$i], "text"),
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_E'][$i], "text"),
                       GetSQLValueString($_POST['tx_insertsjkembalibarang2_QTertanda'][$i], "int"),
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_IsiSJKir'][$i], "text"),
					   GetSQLValueString($_POST['hd_insertsjkembalibarang2_SJKem'], "text"),
					   GetSQLValueString($_POST['hd_insertsjkembalibarang2_Reference2'][$i], "text"),
					   GetSQLValueString($_POST['hd_insertsjkembalibarang2_Purchase'][$i], "text"));
  $insertSQL2 = sprintf("INSERT INTO periode (Periode, S, E, Quantity, IsiSJKir, SJKem, Reference, Purchase, Deletes) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, 'KembaliE')",
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_Periode3'][$i], "int"),
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_S2'][$i], "text"),
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_E'][$i], "text"),
                       GetSQLValueString($_POST['tx_insertsjkembalibarang2_QTertanda'][$i], "int"),
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_IsiSJKir'][$i], "text"),
					   GetSQLValueString($_POST['hd_insertsjkembalibarang2_SJKem'], "text"),
					   GetSQLValueString($_POST['hd_insertsjkembalibarang2_Reference2'][$i], "text"),
					   GetSQLValueString($_POST['hd_insertsjkembalibarang2_Purchase'][$i], "text"));
  $deleteSQL = sprintf("DELETE FROM periode WHERE S=E");
  $alterSQL = sprintf("ALTER TABLE periode AUTO_INCREMENT = 1");


  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($insertSQL2, $Connection) or die(mysql_error());
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());
}
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO invoice (Invoice, JSC, Tgl, PPN, Transport, Reference, Periode) VALUES (%s, 'Sewa', %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_Invoice'], "text"),
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_LastTgl'], "text"),
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_PPN'], "int"),
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_Transport'], "text"),
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_Reference'], "text"),
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_Periode2'], "int"));
  $deleteSQL = "DELETE FROM invoice WHERE invoice.Periode NOT IN (SELECT periode.Periode FROM periode)";
  $alterSQL = sprintf("ALTER TABLE invoice AUTO_INCREMENT = 1");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());
}

for($i=0;$i<$totalRows_InsertSJKembali;$i++){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO isisjkembali (IsiSJKem, Warehouse, QTertanda, Purchase, SJKem, Periode, IsiSJKir) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_IsiSJKem'][$i], "text"),
                       GetSQLValueString($_POST['tx_insertsjkembalibarang2_Warehouse'][$i], "text"),
                       GetSQLValueString($_POST['tx_insertsjkembalibarang2_QTertanda'][$i], "int"),
					   GetSQLValueString($_POST['hd_insertsjkembalibarang2_Purchase'][$i], "text"),
					   GetSQLValueString($_POST['hd_insertsjkembalibarang2_SJKem'], "text"),
                       GetSQLValueString($_POST['hd_insertsjkembalibarang2_Periode'][$i], "text"),
					   GetSQLValueString($_POST['hd_insertsjkembalibarang2_IsiSJKir'][$i], "text"));
 $deleteSQL = sprintf("DELETE FROM isisjkembali WHERE QTertanda=0");
 $alterSQL = sprintf("ALTER TABLE isisjkembali AUTO_INCREMENT = 1");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($deleteSQL, $Connection) or die(mysql_error());
  $Result1 = mysql_query($alterSQL, $Connection) or die(mysql_error());

  $insertGoTo = "SJKembali.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
  }
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	unset($_SESSION['cb_insertsjkembalibarang_checkbox']);
	unset($_SESSION['tx_insertsjkembali_SJKem']);
	unset($_SESSION['tx_insertsjkembali_Tgl']);
	unset($_SESSION['tx_insertsjkembali_Reference']);
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
        Surat Jalan Kembali
        <small>Item</small>
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
              <form action="<?php echo $editFormAction; ?>" id="fm_insertsjkembalibarang2_form1" name="fm_insertsjkembalibarang2_form1" method="POST">
              <div class="box box-primary">
            	<div class="box-body">
                  <table id="tb_insertsjkembalibarang2_example1" name="tb_insertsjkiribarang2_example1" class="table table-bordered table-striped table-responsive">
                  <thead>
					  <tr>
					    <th>Tgl Kirim</th>
					    <th>Barang</th>
					    <th>Warehouse</th>
					    <th>Q Sisa Kembali</th>
					    <th>Q Pengambilan</th>
					  </tr>
                    </thead>
                    <tbody>
           
	<?php 
	$tx_insertsjkembalibarang2_SJKem = substr($_SESSION['tx_insertsjkembali_SJKem'], 1, -1);
	$tx_insertsjkembalibarang2_Tgl = substr($_SESSION['tx_insertsjkembali_Tgl'], 1, -1);
	$tx_insertsjkembalibarang2_Reference = substr($_SESSION['tx_insertsjkembali_Reference'], 1, -1);
 	?>     
	<?php 
	
	$tgl = $tx_insertsjkembalibarang2_Tgl;
	$convert = str_replace('/', '-', $tgl);
	$tgl2 = $row_InsertSJKembali['S'];
	$convert2 = str_replace('/', '-', $tgl2);
	
	$S2 = date('01/m/Y', strtotime($convert));
	$E2 = date('t/m/Y', strtotime($convert));
	
	$last = date('t-m-Y', strtotime($convert2));
	$E = strtotime($last);
	$check = strtotime($convert);
	
	if($check <= $E)
	{
		$S = $tx_insertsjkembalibarang2_Tgl;
	}
	else
	{
		$S = $S2;			
	}
	
	?>
     
    <?php $increment = 1; ?>
	<?php do { ?>
	  <tr>
      <!--<?php $FirstDate = substr($tx_insertsjkembalibarang2_Tgl, 2); ?>-->
      	  <input name="hd_insertsjkembalibarang2_SJKem" type="hidden" id="hd_insertsjkembalibarang2_SJKem" value="<?php echo $tx_insertsjkembalibarang2_SJKem; ?>">
          <input name="hd_insertsjkembalibarang2_Tgl" type="hidden" id="hd_insertsjkembalibarang2_Tgl" value="<?php echo $tx_insertsjkembalibarang2_Tgl; ?>">
          <input name="hd_insertsjkembalibarang2_Reference" type="hidden" id="hd_insertsjkembalibarang2_Reference" value="<?php echo $tx_insertsjkembalibarang2_Reference; ?>">
      	  <input name="hd_insertsjkembalibarang2_Invoice" type="hidden" id="hd_insertsjkembalibarang2_Invoice" value="<?php echo str_pad($row_LastInvoiceId['Id'] + 1, 5, "0", STR_PAD_LEFT); ?>">
          <input name="hd_insertsjkembalibarang2_PPN" type="hidden" id="hd_insertsjkembalibarang2_PPN" value="<?php echo $row_Invoice['PPN']; ?>">
          <input name="hd_insertsjkembalibarang2_Transport" type="hidden" id="hd_insertsjkembalibarang2_Transport" value=0>
          <input name="hd_insertsjkembalibarang2_Periode2" type="hidden" id="hd_insertsjkembalibarang2_Periode2" value="<?php echo $row_Invoice['Periode'] + 1; ?>">
          <input name="hd_insertsjkembalibarang2_Periode3[]" type="hidden" id="hd_insertsjkembalibarang2_Periode3" value="<?php echo $row_Invoice['Periode'] + 1; ?>">
	      <input name="hd_insertsjkembalibarang2_Id[]" type="hidden" id="hd_insertsjkembalibarang2_Id" value="<?php echo $row_InsertSJKembali['Id']; ?>">
	      <input name="hd_insertsjkembalibarang2_Reference2[]" type="hidden" id="hd_insertsjkembalibarang2_Reference" value="<?php echo $row_InsertSJKembali['Reference']; ?>">
          <input name="hd_insertsjkembalibarang2_IsiSJKir[]" type="hidden" id="hd_insertsjkembalibarang2_IsiSJKir" value="<?php echo $row_InsertSJKembali['IsiSJKir']; ?>">
	      <input name="hd_insertsjkembalibarang2_Purchase[]" type="hidden" id="hd_insertsjkembalibarang2_Purchase" value="<?php echo $row_InsertSJKembali['Purchase']; ?>">
	        <input name="hd_insertsjkembalibarang2_Periode[]" type="hidden" id="hd_insertsjkembalibarang2_Periode" value="<?php echo $row_GetPeriode['Periode']; ?>">
	        <input name="hd_insertsjkembalibarang2_S[]" type="hidden" id="hd_insertsjkembalibarang2_S" value="<?php echo $row_InsertSJKembali['S']; ?>">
            <input name="hd_insertsjkembalibarang2_E[]" type="hidden" id="hd_insertsjkembalibarang2_E2" value="<?php echo $tx_insertsjkembalibarang2_Tgl; ?>">
            <input name="hd_insertsjkembalibarang2_S2[]" type="hidden" id="hd_insertsjkembalibarang2_S2" value="<?php echo $S; ?>">
	        <input name="hd_insertsjkembalibarang2_E[]" type="hidden" id="hd_insertsjkembalibarang2_E" value="<?php echo $tx_insertsjkembalibarang2_Tgl; ?>">
            <input name="hd_insertsjkembalibarang2_LastTgl" type="hidden" id="hd_insertsjkembalibarang2_LastTgl" value="<?php echo $E2; ?>">
	        <input name="hd_insertsjkembalibarang2_IsiSJKem[]" type="hidden" id="hd_insertsjkembalibarang2_IsiSJKem" value="<?php echo $row_LastIsiSJKembali['Id'] + $increment; ?>">
	    <td><input name="tx_insertsjkembalibarang2_Tgl[]" type="text" class="form-control" id="tx_insertsjkembalibarang2_Tgl" value="<?php echo $row_InsertSJKembali['Tgl']; ?>" readonly></td>
	    <td><input name="tx_insertsjkembalibarang2_Barang[]" type="text" class="form-control" id="tx_insertsjkembalibarang2_Barang" value="<?php echo $row_InsertSJKembali['Barang']; ?>" readonly></td>
	    <td><input name="tx_insertsjkembalibarang2_Warehouse[]" type="text" class="form-control" id="tx_insertsjkembalibarang2_Warehouse" autocomplete="off"></td>
	    <td><input name="tx_insertsjkembalibarang2_QSisaKem[]" type="text" class="form-control" id="tx_insertsjkembalibarang2_QSisaKem" value="<?php echo $row_InsertSJKembali['QSisaKemInsert']; ?>" readonly></td>
	    <td><input name="tx_insertsjkembalibarang2_QTertanda[]" type="text" class="form-control" id="tx_insertsjkembalibarang2_QTertanda" autocomplete="off" value="<?php echo $row_InsertSJKembali['QSisaKemInsert']; ?>" onkeyup="this.value = minmax(this.value, 0, <?php echo $row_InsertSJKembali['QSisaKemInsert']; ?>)" required></td>
	    </tr>
        <?php $increment++; ?>
	  <?php } while ($row_InsertSJKembali = mysql_fetch_assoc($InsertSJKembali)); ?>
    				</tbody>
                </table>
                <input type="hidden" name="MM_insert" value="form1">
  			    <input type="hidden" name="MM_update" value="form1">
                </div>
            <!-- /.box-body -->
            <div class="box-footer">
                  <a href="InsertSJKembaliBarang.php?Reference=<?php echo $_GET['Reference']; ?>"><button type="button" class="btn btn-default pull-left">Cancel</button></a>
                  <button type="submit" id="bt_insertsjkembalibarang2_submit" name="bt_insertsjkembalibarang2_submit" class="btn btn-success pull-right">Insert</button>
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
  mysql_free_result($Select);
  mysql_free_result($LastIsiSJKembali);
  mysql_free_result($Reference);
  mysql_free_result($GetPeriode);
  mysql_free_result($InsertSJKembali);
  mysql_free_result($LastTglS);
?>
