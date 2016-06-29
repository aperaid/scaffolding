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
$query_POCustomer = "SELECT pocustomer.*, project.Project, customer.Company, sum(transaksi.Amount*transaksi.Quantity) AS Amount FROM pocustomer INNER JOIN project ON pocustomer.PCode=project.PCode INNER JOIN customer ON project.CCode=customer.CCode LEFT JOIN transaksi on pocustomer.Reference=transaksi.reference GROUP BY pocustomer.Reference";
$POCustomer = mysql_query($query_POCustomer, $Connection) or die(mysql_error());
$row_POCustomer = mysql_fetch_assoc($POCustomer);
$totalRows_POCustomer = mysql_num_rows($POCustomer);

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


<?php
// Declare Root directory
$ROOT="../../";
$PAGE="PO Customer";
$top_menu_sel="menu_po";
include_once($ROOT . 'pages/html_header.php');
include_once($ROOT . 'pages/html_main_header.php');
?>
  
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Purchase Order
			<small>All</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
			<li class="active">PO</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<a href="InsertPOCustomer.php"><button type="button" class="btn btn-success pull-left">New Reference</button></a>
						<button id="bt_pocustomer_view" class="btn btn-primary pull-right">View</button>
						<button id="bt_pocustomer_edit" class="btn btn-primary pull-right"  style="margin-right: 5px;">Edit</button>
					</div>
					<div class="box-body">
						<table id="tb_pocustomer" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>Reference</th>
									<th>Tgl</th>
									<th>Company</th>
									<th>Project</th>
									<th>Price</th>
									<th>RefCheck</th>
								</tr>
							</thead>
						</table>
					</div>
				<!-- /.box-body -->
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
<script>
	$(document).ready(function () {
		$("#bt_pocustomer_edit").attr("disabled", true);
		$("#bt_pocustomer_view").attr("disabled", true);
		
		// Set Table to Datatable
		var table = $("#tb_pocustomer").DataTable({
		"paging": false,
		"processing": true,
		"serverSide": true,
		"scrollY": "100%",
		"sAjaxSource": "ref_table.php",
		"columnDefs":[
			{
				"targets": [4],
				"render": $.fn.dataTable.render.number( '.', ',', 0, 'Rp ' )
			},
			{
				"targets": [5],
				"visible": false,
				"searchable": false
			}
		],
		"order": [[1, "desc"]]
		});
		
		// Set when selected
		$('#tb_pocustomer tbody').on( 'click', 'tr', function () {
			
			$("#bt_pocustomer_view").removeAttr("disabled");
			
			var data = table.row( this ).data();
			
			if  (data[5] == 1){
				$("#bt_pocustomer_edit").attr("disabled", true);
			}
			else
			{
				$("#bt_pocustomer_edit").removeAttr("disabled");
			}
			
			if ( $(this).hasClass('active') ) {
				$(this).removeClass('active');
				
				$("#bt_pocustomer_edit").attr("disabled", true);
				$("#bt_pocustomer_view").attr("disabled", true);
			}
			else {
				table.$('tr.active').removeClass('active');
				$(this).addClass('active');
			}
		} );
		
		// When button edit is clicked
		$('#bt_pocustomer_edit').click( function () {
			var data = table.row('.active').data();
			window.open("EditPOCustomer.php?Id="+ data[0],"_self");
		} );
		
		// When button view is clicked
		$('#bt_pocustomer_view').click( function () {
			var data = table.row('.active').data();
			window.open("ViewTransaksi.php?Reference="+ data[0],"_self");
		} );
		
	});
</script>

<?php
  mysql_free_result($POCustomer);
  mysql_free_result($Menu);
  mysql_free_result($User);
?>