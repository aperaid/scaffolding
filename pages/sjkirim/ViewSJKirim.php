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

$colname_ViewIsiSJKirim = "-1";
if (isset($_GET['SJKir'])) {
  $colname_ViewIsiSJKirim = $_GET['SJKir'];
}
mysql_select_db($database_Connection, $Connection);
$query_ViewIsiSJKirim = sprintf("SELECT isisjkirim.Id AS Id2, isisjkirim.*, periode.Periode, transaksi.Barang, transaksi.JS, transaksi.QSisaKir, transaksi.Reference, project.*, customer.* FROM isisjkirim LEFT JOIN periode ON isisjkirim.IsiSJKir=periode.IsiSJKir INNER JOIN transaksi ON isisjkirim.Purchase=transaksi.Purchase INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode INNER JOIN customer ON project.CCode=customer.CCode WHERE isisjkirim.SJKir = %s GROUP BY periode.IsiSJKir ORDER BY isisjkirim.Id ASC", GetSQLValueString($colname_ViewIsiSJKirim, "text"));
$ViewIsiSJKirim = mysql_query($query_ViewIsiSJKirim, $Connection) or die(mysql_error());
$row_ViewIsiSJKirim = mysql_fetch_assoc($ViewIsiSJKirim);
$totalRows_ViewIsiSJKirim = mysql_num_rows($ViewIsiSJKirim);

$query_ViewSJKirim = sprintf("SELECT Tgl FROM SJKirim WHERE SJKir=%s", GetSQLValueString($colname_ViewIsiSJKirim, "text"));
$ViewSJKirim = mysql_query($query_ViewSJKirim, $Connection) or die(mysql_error());
$row_ViewSJKirim = mysql_fetch_assoc($ViewSJKirim);

$colname_View = "-1";
if (isset($_GET['SJKir'])) {
  $colname_View = $_GET['SJKir'];
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

//Qttd disabled function
mysql_select_db($database_Connection, $Connection);
$qttdbutton = sprintf("SELECT check_qttdbutton(%s) as result",  GetSQLValueString($row_ViewIsiSJKirim['Id2'], "text"));
$query_qttdbutton = mysql_query($qttdbutton, $Connection) or die(mysql_error());
$row_qttdbutton = mysql_fetch_assoc($query_qttdbutton);
?>

<?php
// Declare Root directory
$ROOT="../../";
$PAGE="View SJ Kirim";
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
			<small>View</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
			<li><a href="../SJKirim/SJKirim.php">SJ Kirim</a></li>
			<li class="active">View SJ Kirim</li>
		</ol>
	</section>
	
	<!-- Main content -->
	<section class="content">
	<section class="invoice">
		<div class="row">
			<div class="col-xs-12">
				<h2 class="page-header">
					<i class="fa fa-globe"></i> SJ Kirim | <?php echo $row_ViewIsiSJKirim['SJKir']; ?>
					<small class="pull-right">Date: <?php echo $row_ViewSJKirim['Tgl']; ?></small>
				</h2>
			</div>
		</div>

	<!-- info row -->
	<div class="row invoice-info">
		<div class="col-sm-4 invoice-col">
			Company
			<address>
				<strong><?php echo $row_ViewIsiSJKirim['Company']; ?></strong><br>
				<?php echo $row_ViewIsiSJKirim['Alamat']; ?><br>
				<?php echo $row_ViewIsiSJKirim['Kota']; ?>,  <?php echo $row_ViewIsiSJKirim['Zip']; ?><br>
				Phone: <?php echo $row_ViewIsiSJKirim['CompPhone']; ?><br>
				Email: <?php echo $row_ViewIsiSJKirim['CompEmail']; ?>
			</address>
		</div>
		<div class="col-sm-4 invoice-col">
			Project
			<address>
				<strong><?php echo $row_ViewIsiSJKirim['Project']; ?></strong><br>
				<?php echo $row_ViewIsiSJKirim['Alamat']; ?><br>
				<?php echo $row_ViewIsiSJKirim['Kota']; ?>,  <?php echo $row_ViewIsiSJKirim['Zip']; ?><br>
			</address>
		</div>
		<div class="col-sm-4 invoice-col">
			Contact Person
			<address>
				<strong><?php echo $row_ViewIsiSJKirim['Customer']; ?></strong><br>
				Phone: <?php echo $row_ViewIsiSJKirim['CustPhone']; ?><br>
				Email: <?php echo $row_ViewIsiSJKirim['CustEmail']; ?>
			</address>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 table-responsive">
			<table id="tb_viewsjkirim_example1" class="table table-striped">
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
					$Periode = $row_ViewIsiSJKirim['Periode']; 
					$Reference = $row_ViewIsiSJKirim['Reference'];
					?>
					<?php do { ?>
					<tr>
						<td><input name="tx_viewsjkirim_JS" type="text" class="form-control" id="tx_viewsjkirim_JS" value="<?php echo $row_ViewIsiSJKirim['JS']; ?>" readonly></td>
						<td><input name="tx_viewsjkirim_Barang" type="text" class="form-control" id="tx_viewsjkirim_Barang" value="<?php echo $row_ViewIsiSJKirim['Barang']; ?>" readonly></td>
						<td><input name="tx_viewsjkirim_Warehouse" type="text" class="form-control" id="tx_viewsjkirim_Warehouse" value="<?php echo $row_ViewIsiSJKirim['Warehouse']; ?>" readonly></td>
						<td><input name="tx_viewsjkirim_QKirim" type="text" class="form-control" id="tx_viewsjkirim_QKirim" value="<?php echo $row_ViewIsiSJKirim['QKirim']; ?>" readonly></td>
						<td><input name="tx_viewsjkirim_QTertanda" type="text" class="form-control" id="tx_viewsjkirim_QTertanda" value="<?php echo $row_ViewIsiSJKirim['QTertanda']; ?>" readonly></td>
					</tr>
					<?php } while ($row_ViewIsiSJKirim = mysql_fetch_assoc($ViewIsiSJKirim)); ?>
				</tbody>
			</table>
		</div>

		<?php
		//Edit button disabled function
		$query = mysql_query($query_ViewIsiSJKirim) or die(mysql_error());
		$angka = array();
		while($row = mysql_fetch_assoc($query)){
		$angka[] = $row['QTertanda'];
		}
		$jumlah = array_sum($angka) ;
		?>
		
		<div class="box-footer">
			<a href="SJKirim.php"><button type="button" class="btn btn-default">Back</button></a>
			<a href="#"><button type="button" class="btn btn-default">Print</button></a>

			<div class="btn-group pull-right">
				<a href="EditSJKirim.php?SJKir=<?php echo $_GET['SJKir']; ?>"><button type="button" <?php if ($jumlah > '0'){ ?> class="btn btn-default" disabled <?php   } else { ?> class="btn btn-primary" <?php } ?>>Edit Pengiriman</button></a>
				<a href="EditSJKirimQuantity.php?SJKir=<?php echo $_GET['SJKir']; ?>&Periode=<?php echo $Periode; ?>&Reference=<?php echo $Reference; ?>"><button type="button" <?php if ($row_qttdbutton['result'] > 0) { ?> class="btn btn-default" disabled <?php } else { ?> class="btn btn-success" <?php } ?>>Q Tertanda</button></a>
			</div>
		</div>
	</div>
	<!-- /.row -->
	</section>
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
  
<!-- Footer Wrapper -->
<?php include_once($ROOT . 'pages/footer.php'); ?>
<!-- /.footer-wrapper -->

<?php
  mysql_free_result($Menu);
  mysql_free_result($ViewIsiSJKirim);
  mysql_free_result($User);
?>