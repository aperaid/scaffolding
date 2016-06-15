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

//Menu
mysql_select_db($database_Connection, $Connection);
$query_Menu = "SELECT * FROM menu";
$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
$row_Menu = mysql_fetch_assoc($Menu);
$totalRows_Menu = mysql_num_rows($Menu);

//Assign SJKEM dari URL
$colname_View = "-1"; //assign default value auto dari dreamweaver (masya ollo)
if (isset($_GET['SJKem'])) {
  $colname_View = $_GET['SJKem'];
}

//Ambil Kode SJKEM lagi
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT SJKem FROM sjkembali WHERE SJKem = %s", GetSQLValueString($colname_View, "text"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);

//Assign SJKEM dari URL lagi
$colname_EditIsiSJKembali = "-1"; //assign default value auto dari dreamweaver (masya ollo)
if (isset($_GET['SJKem'])) {
  $colname_EditIsiSJKembali = $_GET['SJKem'];
}

//Query buat nunjukin isisjkembali
mysql_select_db($database_Connection, $Connection);
$query_EditIsiSJKembali = sprintf("SELECT isisjkembali.*, isisjkirim.QSisaKem, sjkirim.Tgl, transaksi.Barang, project.Project FROM isisjkembali INNER JOIN isisjkirim ON isisjkembali.IsiSJKir=isisjkirim.IsiSJKir INNER JOIN sjkirim ON isisjkirim.SJKir=sjkirim.SJKir INNER JOIN transaksi ON isisjkembali.Purchase=transaksi.Purchase INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode WHERE isisjkembali.SJKem = %s ORDER BY isisjkembali.Id ASC", GetSQLValueString($colname_EditIsiSJKembali, "text"));
$EditIsiSJKembali = mysql_query($query_EditIsiSJKembali, $Connection) or die(mysql_error());
$row_EditIsiSJKembali = mysql_fetch_assoc($EditIsiSJKembali);
$totalRows_EditIsiSJKembali = mysql_num_rows($EditIsiSJKembali);

//Query khusus untuk ambil IsiSJKir apa aja yg ada di sjkembali ini 
$query = mysql_query($query_EditIsiSJKembali, $Connection) or die(mysql_error());
$IsiSJKir = array();
while($row = mysql_fetch_assoc($query)){
	$IsiSJKir[] = $row['IsiSJKir'];
}
$IsiSJKir2 = join(',',$IsiSJKir); 

mysql_select_db($database_Connection, $Connection);
$query_Tgl = sprintf("SELECT Tgl FROM sjkembali WHERE SJKem = %s", GetSQLValueString($colname_EditIsiSJKembali, "text"));
$Tgl = mysql_query($query_Tgl, $Connection) or die(mysql_error());
$row_Tgl = mysql_fetch_assoc($Tgl);
$totalRows_Tgl = mysql_num_rows($Tgl);

//Query ambil tanggal start & end kembali S&E yg udah ada 
mysql_select_db($database_Connection, $Connection);
$query_Tanggal = sprintf("SELECT E FROM periode WHERE IsiSJKir IN ($IsiSJKir2) AND (Deletes='Sewa' OR Deletes='Extend')", GetSQLValueString($colname_EditIsiSJKembali, "text"));
$Tanggal = mysql_query($query_Tanggal, $Connection) or die(mysql_error());
$row_Tanggal = mysql_fetch_assoc($Tanggal);
$totalRows_Tanggal = mysql_num_rows($Tanggal);

//Query ambil E untuk enddate datepicker
/*	Rumusnya:
	Di dalam satu sjkembali, ada banyak isisjkir. Masing2 isisjkir, punya periodenya masing-masing.
	Masing-masing periode dari isisjkir tersebut, punya S dan E yg berbeda.
	Sehingga datepicker harus menunjukkan tanggal maksimal dengan tanggal E yg paling pertama
	Contoh, ada 2 isisjkir di satu sjkembali:
	#############################################################################################
	#	isisjkir	Q		S		E		Deletes												#
	#	--------	---		---		---		-------												#
	#	1			50		10/1	9/2		Extend	<-ini yg diambil E nya jadi maks datepicker	#
	#	2			50		15/1	14/2	Extend												#
	#############################################################################################
*/
//BINGUNG, masih blom dikerjain

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

// update overall qsisakembali di transaksi diupdate
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
for($i=0;$i<$totalRows_EditIsiSJKembali;$i++){
	$QTerima = GetSQLValueString($_POST['tx_editsjkembaliquantity_QTerima2'][$i], "int");
  $updateSQL = sprintf("UPDATE transaksi SET QSisaKem=QSisaKem+$QTerima-%s WHERE Purchase=%s",
                       GetSQLValueString($_POST['tx_editsjkembaliquantity_QTerima'][$i], "int"),
                       GetSQLValueString($_POST['hd_editsjkembaliquantity_Purchase'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
}

// jumlah qsisakembaliquantity di isisjkirim diupdate
for($i=0;$i<$totalRows_EditIsiSJKembali;$i++){
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	$QTerima = GetSQLValueString($_POST['tx_editsjkembaliquantity_QTerima2'][$i], "int");
  $updateSQL = sprintf("UPDATE isisjkirim SET QSisaKem=QSisaKem+$QTerima-%s WHERE IsiSJKir=%s",
                       GetSQLValueString($_POST['tx_editsjkembaliquantity_QTerima'][$i], "int"),
                       GetSQLValueString($_POST['hd_editsjkembaliquantity_IsiSJKir'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
}

//update qterima di sjkembali
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
for($i=0;$i<$totalRows_EditIsiSJKembali;$i++){
  $updateSQL = sprintf("UPDATE isisjkembali SET QTerima=%s WHERE Id=%s",
                       GetSQLValueString($_POST['tx_editsjkembaliquantity_QTerima'][$i], "int"),
                       GetSQLValueString($_POST['hd_editsjkembaliquantity_Id'][$i], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());

}
}

/* OLD atur tanggal periode
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE periode SET E=%s WHERE IsiSJKir IN ($IsiSJKir2) AND (Deletes='Kembalis' OR Deletes='KembaliE')",
                       GetSQLValueString($_POST['tx_editsjkembaliquantity_E'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());
}
*/

/**** Update Tanggal ****/
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
/* --- Tanggal yang diinput sama user --- */
// 1. Input tgl sjkembali
$tgl_input_user = $_POST['tx_editsjkembaliquantity_E'];
// 2. Abis itu di convert dalam bentuk tanggal yg bisa dicompare system
$tgl_input_user_converted = str_replace('/', '-', $tgl_input_user);
// 3. convert ke bentuk comparable
$tgl_input_user_system = strtotime($tgl_input_user_converted);

/* --- Tanggal start sewa atau extend --- */
// 1. Tanggal Start (S) di periode
$tgl_start = $row_Tanggal['S'];
// 2. Abis itu diconvert dalam bentuk tanggal yg bisa dicompare system trus dimasukkin ke variable $convert2
$tgl_start_converted = str_replace('/', '-', $tgl_start);
// 3. Tanggal start sewa/extend, ambil tanggal akhirnya, masukin ke $last
$tgl_31_start_converted = date('t-m-Y', strtotime($tgl_start_converted));
// 4. convert ke bentuk comparable
$tgl_31_start_system = strtotime($tgl_31_start_converted);

/* JADI Sekarang Kita Punya: */
$tgl_input_user_system;	// Tanggal yg diinput user
$tgl_31_start_system;	// Tanggal 31 start

// Bandingin apakah tgl yg diinput user lebih kecil atau sama dengan tanggal akhir dari start bulan sewa/extendnya
if($tgl_input_user_system <= $tgl_31_start_system)
{
	// Kalau lebih kecil/sama dengan tanggal akhir bulan tgl_start
	// 1. Update Tanggal KembaliS yg udah ada
	$update_tgl_sql=sprintf("UPDATE periode SET E='$tgl_input_user' WHERE sjkem='$colname_EditIsiSJKembali' AND Deletes='KembaliS' ");
	mysql_select_db($database_Connection, $Connection);
	$query = mysql_query($update_tgl_sql, $Connection) or die(mysql_error());
	// 2. Hapus row KembaliE ada ato ngga
	$delete_tgl_sql=sprintf("DELETE FROM periode WHERE periode.sjkem='$colname_EditIsiSJKembali' AND Deletes='KembaliE' ");
	mysql_select_db($database_Connection, $Connection);
	$query = mysql_query($delete_tgl_sql, $Connection) or die(mysql_error());
}
else	
{
	// Kalau lebih besar
	// 1. Update Tanggal KembaliS dan Kembali E yg udah ada
	$update_tgl_sql=sprintf("UPDATE periode SET periode.E='$tgl_input_user' WHERE sjkem='$colname_EditIsiSJKembali' AND (Deletes='KembaliS' OR Deletes='KembaliE')");
	mysql_select_db($database_Connection, $Connection);
	$query = mysql_query($update_tgl_sql, $Connection) or die(mysql_error());
	// 2. Insert Kembali E Baru kalau memang belum ada
	// Tanggal End yg udah ada, diconvert biar bisa jadi perbandingan
	$tgl_end = $row_Tanggal['E'];
	$tgl_end = str_replace('/', '-', $tgl_end);
	$tgl_end_system = strtotime($tgl_end);
	//Perbandingan apakah tanggal END yg udah ada itu lebih besar dari tanggal 31 bulan sewa/extendnya
	if($tgl_31_start_system > $tgl_end_system){
		//Tanggal yg diinput sama user, ambil tanggal 01 nya
		$tgl_01_input_user = date('01/m/Y', strtotime($tgl_input_user_converted));
		//Insert row KembaliE dengan menduplikat row Kembali S yg udah ada
		$insert_tgl_sql=sprintf("INSERT INTO periode (IsiSJKir, Periode, S, E, Quantity, SJKem, Reference, Purchase, Claim, Deletes)
								SELECT IsiSJKir, Periode+1, '$tgl_01_input_user', '$tgl_input_user', Quantity, SJKem, Reference, Purchase, Claim, 'KembaliE'
								FROM periode WHERE SJKem='$colname_EditIsiSJKembali' AND Deletes='KembaliS'"
								);
		mysql_select_db($database_Connection, $Connection);
		$query = mysql_query($insert_tgl_sql, $Connection) or die(mysql_error());
	}
}

// Redirect ke sjkembali
$updateGoTo = "ViewSJKembali.php";
if (isset($_SERVER['QUERY_STRING'])) {
	$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
	$updateGoTo .= $_SERVER['QUERY_STRING'];
}  
header(sprintf("Location: %s", $updateGoTo));

}
/*** UPDATE TANGGAL END ***/

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
  <title>BDN ERP | Edit SJ Kembali Quantity</title>
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
        Surat Jalan Kembali
        <small>Edit</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="../SJKembali/SJKembali.php">SJ Kembali</a></li>
        <li class="active">Edit SJ Kembali Quantity</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
		<form action="<?php echo $editFormAction; ?>" id="fm_editsjkembaliquantity_form1" name="fm_editsjkembaliquantity_form1" method="POST">
          <div class="box box-primary">
            <div class="box-body no-padding">
				  <table id="tb_editsjkembaliquantity_example1" name="tb_editsjkembaliquantity_example1" class="table table-bordered">
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
					  <?php 
					  $Min = $row_Tgl['Tgl'];
					  $Tgl = $row_Tanggal['E'];
					  $x=1; 
					  ?>
					  <?php do { ?>
						<tr>
									<input name="hd_editsjkembaliquantity_Id[]"			id="hd_editsjkembaliquantity_Id"							type="hidden"	value="<?php echo $row_EditIsiSJKembali['Id']; ?>">
									<input name="hd_editsjkembaliquantity_QSisaKem2"	id="hd_editsjkembaliquantity_QSisaKem2<?php echo $x; ?>"	type="hidden"	value="<?php echo $row_EditIsiSJKembali['QTertanda']; ?>">
									<input name="hd_editsjkembaliquantity_IsiSJKir[]"	id="hd_editsjkembaliquantity_IsiSJKir"						type="hidden"	class="textview"		value="<?php echo $row_EditIsiSJKembali['IsiSJKir']; ?>">
									<input name="hd_editsjkembaliquantity_Purchase[]"	id="hd_editsjkembaliquantity_Purchase"						type="hidden"	class="textview"		value="<?php echo $row_EditIsiSJKembali['Purchase']; ?>">
									<input name="tx_editsjkembaliquantity_QTerima2[]"	id="tx_editsjkembaliquantity_QTerima2"						type="hidden"	class="form-control"	autocomplete="off" value="<?php echo $row_EditIsiSJKembali['QTerima']; ?>">
							<td>	<input name="tx_editsjkembaliquantity_Tgl"			id="tx_editsjkembaliquantity_Tgl"							type="text"		class="form-control"	value="<?php echo $row_EditIsiSJKembali['Tgl']; ?>" readonly></td>
							<td>	<input name="tx_editsjkembaliquantity_Barang"		id="tx_editsjkembaliquantity_Barang"						type="text"		class="form-control"	value="<?php echo $row_EditIsiSJKembali['Barang']; ?>" readonly></td>
							<td>	<input name="tx_editsjkembaliquantity_Warehouse[]"	id="tx_editsjkembaliquantity_Warehouse"						type="text"		class="form-control"	value="<?php echo $row_EditIsiSJKembali['Warehouse']; ?>" readonly></td>
							<td>	<input name="tx_editsjkembaliquantity_QTertanda[]"	id="tx_editsjkembaliquantity_QTertanda"						type="text"		class="form-control"	value="<?php echo $row_EditIsiSJKembali['QTertanda']; ?>"autocomplete="off"  readonly></td>
									<input name="tx_editsjkembaliquantity_QSisaKem[]"	id="tx_editsjkembaliquantity_QSisaKem<?php echo $x; ?>"		type="hidden"	class="form-control"	value="<?php echo $row_EditIsiSJKembali['QSisaKem']; ?>" readonly>
                            <td>	<input name="tx_editsjkembaliquantity_QTerima[]"	id="tx_editsjkembaliquantity_QTerima<?php echo $x; ?>"		type="text"		class="form-control"	value="<?php echo $row_EditIsiSJKembali['QTerima']; ?>" autocomplete="off" onkeyup="this.value = minmax(this.value, 0, <?php echo $row_EditIsiSJKembali['QTertanda']; ?>)" onKeyUp="sisa();"  required></td>
                          </tr>
						<?php $x++; ?>
						<?php } while ($row_EditIsiSJKembali = mysql_fetch_assoc($EditIsiSJKembali)); ?>
					</tbody>
				  </table>
			</div>
            <!-- /.box-body -->
            <div class="box-footer">
				<label>Tanggal Selesai Penghitungan</label>
				<div class="input-group">
				<div class="input-group-addon">
                <i class="fa fa-calendar"></i>
                </div>
					<input name="tx_editsjkembaliquantity_E" type="text" class="form-control" id="tx_editsjkembaliquantity_E" autocomplete="off" value="<?php echo $Tgl; ?>" required>
				</div>
				<br>
				<a href="ViewSJKembali.php?SJKem=<?php echo $row_View['SJKem']; ?>"><button type="button" class="btn btn-default">Cancel</button></a>
				<button type="submit" name="bt_editsjkembaliquantity_submit" id="bt_editsjkembaliquantity_submit" class="btn btn-success pull-right">Update</button>
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
<!-- Custome Script -->
<script language="javascript">
  function sisa() {
  for(x = 1; x < 11; x++){
    var txtFirstNumberValue = document.getElementById('hd_editsjkembaliquantity_QSisaKem2'+x).value;
    var txtSecondNumberValue = document.getElementById('tx_editsjkembaliquantity_QTerima'+x).value;
	var result = parseInt(txtFirstNumberValue) - parseInt(txtSecondNumberValue);
	  if (!isNaN(result)) {
		document.getElementById('tx_editsjkembaliquantity_QSisaKem'+x).value = result;
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
var Min = <?php echo json_encode($Min); ?>;
  $('#tx_editsjkembaliquantity_E').datepicker({
	  format: "dd/mm/yyyy",
	  startDate: Min,
	  orientation: "bottom left",
	  todayHighlight: true,
	  autoclose: true
  }); 
</script>
</body>
</html>

<?php
  mysql_free_result($Menu);
  mysql_free_result($EditIsiSJKembali);
  mysql_free_result($View);
?>
