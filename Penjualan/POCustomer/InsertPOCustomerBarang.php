<?php require_once('../../Connections/Connection.php'); ?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $truncateSQL = sprintf("TRUNCATE TABLE inserted");

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($truncateSQL, $Connection) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO inserted (PPN, Transport) VALUES (%s, %s)",
                       GetSQLValueString($_POST['PPN'], "int"),
                       GetSQLValueString($_POST['Transport'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());
}
for($i=0;$i<10;$i++){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO transaksi (Purchase, JS, Barang, Quantity, QSisaKirInsert, QSisaKir, Amount, TglStart, Reference) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Purchase'][$i], "text"),
                       GetSQLValueString($_POST['JS'][$i], "text"),
                       GetSQLValueString($_POST['Barang'][$i], "text"),
                       GetSQLValueString($_POST['Quantity'][$i], "int"),
					   GetSQLValueString($_POST['Quantity'][$i], "int"),
					   GetSQLValueString($_POST['Quantity'][$i], "int"),
                       GetSQLValueString($_POST['Amount'][$i], "text"),
                       GetSQLValueString($_POST['TglStart'][$i], "text"),
                       GetSQLValueString($_POST['Reference'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "InsertPOCustomerBarang2.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}}

mysql_select_db($database_Connection, $Connection);
$query_Menu = "SELECT * FROM menu";
$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
$row_Menu = mysql_fetch_assoc($Menu);
$totalRows_Menu = mysql_num_rows($Menu);

$colname_Reference = "-1";
if (isset($_GET['Id'])) {
  $colname_Reference = $_GET['Id'];
}
mysql_select_db($database_Connection, $Connection);
$query_Reference = sprintf("SELECT Reference FROM pocustomer WHERE Id = %s", GetSQLValueString($colname_Reference, "int"));
$Reference = mysql_query($query_Reference, $Connection) or die(mysql_error());
$row_Reference = mysql_fetch_assoc($Reference);
$totalRows_Reference = mysql_num_rows($Reference);

mysql_select_db($database_Connection, $Connection);
$query_Purchase = "SELECT Id FROM transaksi ORDER BY Id DESC";
$Purchase = mysql_query($query_Purchase, $Connection) or die(mysql_error());
$row_Purchase = mysql_fetch_assoc($Purchase);
$totalRows_Purchase = mysql_num_rows($Purchase);

mysql_select_db($database_Connection, $Connection);
$query_LastReference = "SELECT Reference FROM pocustomer ORDER BY Id DESC";
$LastReference = mysql_query($query_LastReference, $Connection) or die(mysql_error());
$row_LastReference = mysql_fetch_assoc($LastReference);
$totalRows_LastReference = mysql_num_rows($LastReference);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>BDN ERP | Insert PO</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
  <!-- jQueryUI -->
  <link rel="stylesheet" href="../../plugins/jQueryUI/jquery-ui.css" >
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">
  <!-- datepicker -->
  <link rel="stylesheet" href="../../plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">

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
    <a href="index2.html" class="logo">
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
              <img src="../../dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs">Administrator</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                  Admin - Adminstrator
                  <small>Super Profile</small>
                </p>
              </li>
              
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-right">
                  <a href="#" class="btn btn-default btn-flat">Sign out</a>
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
        <li><a href="../<?php echo $row_Menu['link']; ?>"><i class="<?php echo $row_Menu['icon']; ?>"></i> <span><?php echo $row_Menu['nama']; ?></span></a></li>
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
        Insert PO
        <small>Item</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="">Purchase Order</a></li>
        <li><a href="">New PO</a></li>
        <li class="active">Insert PO Item</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Input PO Item</h3>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
              <form action="<?php echo $editFormAction; ?>Reference=<?php echo $row_LastReference['Reference']; ?>" id="form1" name="form1" method="POST">
                <div class="box-body">
                  <table class="table table-hover table-bordered" id="customFields">
                    <thead>
                      <th>Barang</th>
                      <th>J/S</th>
                      <th>Amount</th>
                      <th>Quantity</th>
                      <th>Tanggal Permintaan</th>
                      <th><a href="javascript:void(0);" id="addCF" class=" glyphicon glyphicon-plus"></a></th>
                    </thead>
                  </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                <table class="table table-hover table-bordered" id="customFields">
                    <thead>
                      <th>PPN</th>
                      <th>Transport</th>
                    </thead>
                    <tbody>
                      <tr>
                        <td><input name="PPN" type="text" id="PPN" value="0" autocomplete="off"></td>
                        <td><input name="Transport" type="text" id="Transport" value="0" autocomplete="off"></td>
                      </tr>
    				</tbody>
                </table>
                  <a href="CancelBarang.php?Reference=<?php echo $row_LastReference['Reference']; ?>"><button type="button" class="btn btn-default pull-left">Cancel</button></a>
                  <button type="submit" id="submit" class="btn btn-success pull-right">Insert</button>
                </div>
              <input name="Reference" type="hidden" id="Reference" value="<?php echo $row_LastReference['Reference']; ?>">
              <input type="hidden" name="MM_insert" value="form1">
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
<script src="../../	plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- jQuery UI -->
<script src="../../plugins/jQueryUI/jquery-ui.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<!-- datepicker -->
<script src="../../plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<!-- page script -->
<script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>
<script>
function capital() {
    var x = document.getElementById("CCode");
    x.value = x.value.toUpperCase();
	var x = document.getElementById("Company");
    x.value = x.value.toUpperCase();
}
</script>
<script>
$(function() {
  var availableTags = <?php include ("../autocomplete.php");?>;
  $( "#PCode" ).autocomplete({
	source: availableTags
  });
});
</script>
<script>
function capital() {
	var x = document.getElementById("PCode");
    x.value = x.value.toUpperCase();
}
</script>
<script>
     $(document).ready(function(){
	var max_fields      = 10; //maximum input boxes allowed
	
	var x = 0; //initial text box count
	var y = <?php echo $row_Purchase['Id']; ?>;
	var z = y;
	$("#addCF").click(function(){
		if(x < max_fields){ //max input box allowed
            x++; //text box count increment
			z++;
		$("#customFields").append('<tr><td class="hidden"><input name="Purchase[]" class="textbox" id="Purchase" value="'+ z +'"></td><td><input type="text" name="Barang[]" id="Barang" autocomplete="off" class="form-control"></td><td><select name="JS[]" id="JS" class="form-control"><option>Jual</option><option>Sewa</option></select></td><td><input type="text" name="Amount[]" autocomplete="off" id="Amount" class="form-control"></td><td><input type="text" name="Quantity[]" id="Quantity" autocomplete="off" class="form-control"></td><td><input type="text" name="TglStart[]" id="Tgl" autocomplete="off" class="form-control"></td><td><a href="javascript:void(0);" class="remCF glyphicon glyphicon-remove"></a></td></tr>');
		}
	});
    $("#customFields").on('click','.remCF',function(){
        $(this).parent().parent().remove();
		x--;
    });
});
</script>
<script>

  $('#Tgl').datepicker({
	  format: "dd/mm/yyyy",
	  orientation: "bottom left",
	  todayHighlight: true
  }); 
</script>
</body>
</html>
<?php
mysql_free_result($Menu);
?>