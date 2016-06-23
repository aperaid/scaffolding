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

$colname_View = "-1";
if (isset($_GET['Reference'])) {
  $colname_View = $_GET['Reference'];
}
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT periode.Id, periode.Periode, periode.S, periode.E, periode.Deletes, isisjkirim.SJKir, customer.Customer, project.Project, periode.Reference FROM periode LEFT JOIN isisjkirim ON periode.IsiSJKir=isisjkirim.IsiSJKir LEFT JOIN pocustomer ON periode.Reference=pocustomer.Reference LEFT JOIN project ON pocustomer.PCode=project.PCode LEFT JOIN customer ON project.CCode=customer.CCode WHERE periode.Reference = %s GROUP BY periode.Periode", GetSQLValueString($colname_View, "text"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);

$query_LastPeriode = sprintf("SELECT MAX(Periode) AS Periode FROM periode WHERE Reference = %s", GetSQLValueString($colname_View, "text"));
 $LastPeriode = mysql_query($query_LastPeriode, $Connection) or die(mysql_error());
 $row_LastPeriode = mysql_fetch_assoc($LastPeriode);
 $totalRows_LastPeriode = mysql_num_rows($LastPeriode);

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

<?php
// Declare Root directory
$ROOT="../../";
$PAGE="Transaksi Sewa";
$top_menu_sel="menu_sewa";
include_once($ROOT . 'pages/html_header.php');
include_once($ROOT . 'pages/html_main_header.php');
?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Transaksi Sewa
        <small>View</small>
        <large><a href="ExtendTransaksiSewa.php?Reference=<?php echo $row_View['Reference']; ?>&Periode=<?php echo $row_LastPeriode['Periode']; ?>&SJKir=<?php echo $row_View['SJKir']; ?>" onclick="return confirm('Extend Sewa hanya boleh dilakukan di akhir periode dan sudah ada konfirmasi dari customer. Lanjutkan?')"><button type="button" class="btn btn-success btn-sm">Extend</button></a></large>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="TransaksiSewa.php">Transaksi Sewa</a></li>
        <li class="active">View Transaksi Sewa</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-body">
              <table id="tb_viewtransaksisewa_example1" class="table table-bordered table-striped table-responsive">
                <thead>
                <tr>
                  <th>Periode</th>
                  <th>Start</th>
                  <th>End</th>
                  <th>Customer</th>
                  <th>Project</th>
                  <th>View</th>
                </tr>
                </thead>
				<tbody>
					<?php do { ?>
                    
					<tr>
						<td><?php echo $row_View['Periode']; ?></td>
						<td><?php echo $row_View['S']; ?></td>
						<td><?php echo $row_View['E']; ?></td>
						<td><?php echo $row_View['Customer']; ?></td>
						<td><?php echo $row_View['Project']; ?></td>
						<td><a href="ViewTransaksiSewa2.php?Reference=<?php echo $row_View['Reference']; ?>&Periode=<?php echo $row_View['Periode']; ?>"><button type="button" class="btn btn-block btn-primary btn-sm">View Detail</button></a></td>
					</tr>
					<?php } while ($row_View = mysql_fetch_assoc($View)); ?>
				</tbody>
              </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                  <a href="TransaksiSewa.php"><button type="button" class="btn btn-default pull-left">Back</button></a>
			</div>
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
<?php include_once($ROOT . 'pages/footer.php'); ?>
<!-- /.footer-wrapper -->

<!-- page script -->
<script>
  $(function () {
    $("#tb_viewtransaksisewa_example1").DataTable();
  });
</script>

<?php
mysql_free_result($Menu);
mysql_free_result($View);
mysql_free_result($User);
?>