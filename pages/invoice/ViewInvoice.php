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

$colname_View = "-1";
if (isset($_GET['Reference'])) {
  $colname_View = $_GET['Reference'];
  $colname_ViewInvoice = $_GET['Invoice'];
}
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT invoice.*, project.Project, customer.Company FROM invoice INNER JOIN pocustomer ON invoice.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode INNER JOIN customer ON project.CCode=customer.CCode WHERE invoice.Reference = %s AND invoice.Invoice = %s", GetSQLValueString($colname_View, "text"), GetSQLValueString($colname_ViewInvoice, "text"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);

$colname_Count = "-1";
if (isset($_GET['Reference'])) {
  $colname_Count = $_GET['Reference'];
  $colname_ViewJS = $_GET['JS'];
  $colname_ViewInvoice = $_GET['Invoice'];
}

$colname_View2 = "-1";
if (isset($_GET['JS'])) {
  $colname_View2 = $_GET['Reference'];
  $colname_ViewJS = $_GET['JS'];
  $colname_ViewPeriode = $_GET['Periode'];
}

mysql_select_db($database_Connection, $Connection);
$query_View2 = sprintf("SELECT sjkirim.SJKir, transaksi.Purchase, transaksi.Barang, periode.Quantity, periode.S, periode.E, periode.SJKem, periode.Deletes, transaksi.Amount, periode.Periode FROM periode LEFT JOIN isisjkirim ON periode.IsiSJKir=isisjkirim.IsiSJKir 
LEFT JOIN transaksi ON isisjkirim.Purchase=transaksi.Purchase LEFT JOIN sjkirim ON isisjkirim.SJKir=sjkirim.SJKir
WHERE transaksi.Reference = %s AND transaksi.JS = %s AND periode.Periode = %s ORDER BY periode.Id ASC", GetSQLValueString($colname_View2, "text"),
GetSQLValueString($colname_ViewJS, "text"),
GetSQLValueString($colname_ViewPeriode, "text"));
$View2 = mysql_query($query_View2, $Connection) or die(mysql_error());
$row_View2 = mysql_fetch_assoc($View2);

if (isset($_GET['totalRows_View2'])) {
  $totalRows_View2 = $_GET['totalRows_View2'];
} else {
  $all_View2 = mysql_query($query_View2);
  $totalRows_View2 = mysql_num_rows($all_View2);
}

mysql_select_db($database_Connection, $Connection);
$query_Menu = "SELECT * FROM menu";
$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
$row_Menu = mysql_fetch_assoc($Menu);
$totalRows_Menu = mysql_num_rows($Menu);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE invoice SET PPN=%s, Transport=%s WHERE Invoice=%s",
                       GetSQLValueString($_POST['tx_viewinvoice_PPN'], "int"),
                       GetSQLValueString($_POST['tx_viewinvoice_Transport'], "text"),
                       GetSQLValueString($_POST['tx_viewinvoice_Invoice'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());

  $updateGoTo = "ViewInvoice.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
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
  <title>BDN ERP | View Invoice Sewa</title>
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
        Invoice Sewa
        <small>View</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="Invoice.php">Invoice Sewa</a></li>
        <li class="active">View Invoice Sewa</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Invoice Detail</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="<?php echo $editFormAction; ?>" id="fm_viewinvoice_form1" name="fm_viewinvoice_form1" method="POST" class="form-horizontal">
            
            <div class="box-body with-border">
                <div class="form-group">
                  <label class="col-sm-2 control-label">No. Invoice</label>
                  <div class="col-sm-6">
                    <input id="tx_viewinvoice_Invoice" name="tx_viewinvoice_Invoice" type="text" class="form-control" value="<?php echo $row_View['Invoice']; ?>"  readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Project</label>
                  <div class="col-sm-6">
                    <input id="tx_viewinvoice_Project" name="tx_viewinvoice_Project" type="text" class="form-control" value="<?php echo $row_View['Project']; ?>"  readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Company</label>
                  <div class="col-sm-6">
                    <input id="tx_viewinvoice_Company" name="tx_viewinvoice_Company" type="text" class="form-control" value="<?php echo $row_View['Company']; ?>"  readonly>
                  </div>
                </div>
            <div>
  <table id="tb_viewinvoice_example1" name="tb_viewinvoice_example1" class="table table-bordered table-striped table-responsive">
	<thead>
      <tr>
        <th align="center">SJ Kirim</th>
        <th align="center">SJ Kembali</th>
        <th align="center">No. Purchase</th>
        <th align="center">Item</th>
        <th>S</th>
        <th>E</th>
        <th>S-E</th>
        <th>Periode</th>
        <th>I</th>
        <th>Quantity</th>
        <th>Amount</th>
        <th>Total</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $total = 0;
	  do { ?>
      
        <tr>
          <td><?php echo $row_View2['SJKir']; ?></td>
          <td><?php echo $row_View2['SJKem']; ?></td>
          <td><?php echo $row_View2['Purchase']; ?></td>
          <td><?php echo $row_View2['Barang']; ?></td>
          
          <?php 
		  $date1 = $row_View2['S'];
		  $date2 = $row_View2['E'];
		  $date1 = str_replace('/', '-', $date1);
		  $date2 = str_replace('/', '-', $date2);
		  $FirstDate = date('01/m/Y', strtotime($date1));
		  $LastDate = date('t/m/Y', strtotime($date1));
		  /*$Proof = strtotime("-1 day +1 month", strtotime($date1));
		  $Proof2 = date("d/m/Y", $Proof);*/
		  
		  if($row_View['Periode'] == 1){
			  $FirstDate2 = $row_View2['S'];
		  }
		  elseif($row_View2['Deletes'] == 'Extend' || $row_View2['Deletes'] == 'KembaliS' || $row_View2['Deletes'] == 'KembaliE'){
			  $FirstDate2 = $FirstDate;
		  }
		  
		  if(/*$Proof2 == $row_View2['E'] || */$row_View2['Deletes'] == 'Extend'){
			  $LastDate2 = $LastDate;
		  }
		  else{
			  $LastDate2 = $row_View2['E'];
		  }
		
		  $Freplace = str_replace('/', '-', $FirstDate2);
		  $Lreplace = str_replace('/', '-', $LastDate2);
		  $sjkem = strtotime($Lreplace); $sjkir = strtotime($Freplace); 
		  
		  $SE = round((($sjkem - $sjkir) / 86400),1)+1;
		  
		  $Days = str_replace('/', ',', $FirstDate2);
		  $M = substr($Days, 3, -5);
		  $Y = substr($Days, 6);
		  $Days2 = cal_days_in_month(CAL_GREGORIAN, $M, $Y);
		  
		  ?>
          
          <td><?php echo $FirstDate2 ?></td>
          <td><?php echo $LastDate2; ?></td>
          <td><?php echo $SE; ?></td>
          <td><?php echo $Days2 ?></td>
          <td><?php echo round(((($sjkem - $sjkir) / 86400)+1)/$Days2, 4) ?></td>
          <td><?php echo $row_View2['Quantity']; ?></td>
          <td><?php echo $row_View2['Amount']; ?></td>
          <?php $total2 = ((($sjkem - $sjkir) / 86400)+1)/$Days2*$row_View2['Quantity']* $row_View2['Amount']; $total += $total2 ?>
          <td><?php echo round($total2, 2) ?></td>
        </tr>
      <?php } while ($row_View2 = mysql_fetch_assoc($View2)); ?>
    </tbody>
    </table>
    </div>
    
    <div class="form-group">
                  <label class="col-sm-2 control-label">Pajak</label>
                  <div class="col-sm-6">
                    <input id="tx_viewinvoice_PPN" name="tx_viewinvoice_PPN" type="text" class="form-control" value="<?php echo $row_View['PPN']; ?>" onKeyUp="tot()">
                    <input id="hd_viewinvoice_PPN2" name="hd_viewinvoice_PPN2" type="hidden" autocomplete="off" value="<?php echo $row_View['PPN']; ?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Transport</label>
                  <div class="col-sm-6">
                    <input id="tx_viewinvoice_Transport" name="tx_viewinvoice_Transport" type="text" class="form-control" value="<?php if ($row_View['Periode'] == 1){ echo $row_View['Transport']; }?>" onKeyUp="tot()">
                    <input id="hd_viewinvoice_Transport2" name="hd_viewinvoice_Transport2" type="hidden" autocomplete="off" value="<?php if ($row_View['Periode'] == 1){ echo $row_View['Transport']; }?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Total</label>
                  <div class="col-sm-6">
                    <input id="tx_viewinvoice_Totals" name="tx_viewinvoice_Totals" type="text" class="form-control" value="<?php if ($row_View['Periode'] == 1){$toss = $row_View['Transport']; } 
					else $toss = 0;
					 echo round(($total*$row_View['PPN']*0.1)+$total+$toss, 2);?>"  readonly>
                    <input id="hd_viewinvoice_Totals2" name="hd_viewinvoice_Totals2" type="hidden" value="<?php echo round($total, 2); ?>" >
                  </div>
                </div>
                
                <div class="box-footer">
                <button type="submit" name="tx_viewinvoice_submit" id="tx_viewinvoice_submit" class="btn btn-info pull-right">Update</button>
                <div class="btn-group"><a href="Invoice.php"><button type="button" class="btn btn-default pull-left">Back</button></a></div>
                <div class="btn-group" ><a href="#" class="btn btn-default"><i class="fa fa-print"></i> Print</a></div>
              </div>
              <input type="hidden" name="MM_update" value="form1">
<!-- /.box-footer -->
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

<script language="javascript">
  function tot() {
    var txtFirstNumberValue = document.getElementById('hd_viewinvoice_Totals2').value;
    var txtSecondNumberValue = document.getElementById('tx_viewinvoice_PPN').value;
	var txtThirdNumberValue = document.getElementById('tx_viewinvoice_Transport').value;
	var result = (parseFloat(txtFirstNumberValue) * parseFloat(txtSecondNumberValue)*0.1)+parseFloat(txtFirstNumberValue) + parseFloat(txtThirdNumberValue);
	  if (!isNaN(result)) {
		document.getElementById('tx_viewinvoice_Totals').value = result;
      }
   }
</script>
</body>
</html>
<?php
  mysql_free_result($View);
  mysql_free_result($View2);
?>
