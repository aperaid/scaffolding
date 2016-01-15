<?php require_once('../../connection/connection.php'); ?>
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

$colname_View = "-1";
if (isset($_GET['Id'])) {
  $colname_View = $_GET['Id'];
}
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT transaksi.*, project.Project, customer.Customer FROM transaksi INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode INNER JOIN customer ON project.CCode=customer.CCode WHERE transaksi.Id = %s", GetSQLValueString($colname_View, "int"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);

mysql_select_db($database_Connection, $Connection);
$query_Menu = "SELECT * FROM menu";
$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
$row_Menu = mysql_fetch_assoc($Menu);
$totalRows_Menu = mysql_num_rows($Menu);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>BDN ERP | View Customer</title>
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
              <span class="hidden-xs">Administrator</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="../../library/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

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
        Transaksi Sewa
        <small>Detail</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="TransaksiJual.php">Transaksi Sewa</a></li>
        <li class="active">View Detail</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-4">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Company Detail</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body with-border">
              <div class="form-group">
                  <label for="exampleInputEmail1">Company Name</label>
                  <input name="Customer" type="text" class="form-control" id="Customer" value="<?php echo $row_View['Customer']; ?>" readonly>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Project Detail</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body with-border">
              <div class="form-group">
                  <label>Project Name</label>
                  <input name="Project" type="text" class="form-control" id="Project" value="<?php echo $row_View['Project']; ?>" readonly>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-xs-8">
          <!-- Horizontal Form -->
          <form>
            <div class="box box-info">
              <div class="box-header with-border">
                <h3 class="box-title">Transaction Detail</h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body with-border form-horizontal">
                <div class="form-group">
                  <label class="col-sm-3 control-label">PO Code</label>
                  <div class="col-sm-7">
                    <input name="Purchase" type="text" class="form-control" id="Purchase" value="<?php echo $row_View['Reference']; ?>" readonly>
                  </div>
                  <div class="col-sm-2">
                    <a href="../POCustomer/ViewTransaksi.php?Reference=<?php echo $row_View['Reference']; ?>"><span class="btn btn-primary">View PO</button></a>
                  </div>
                </div>
                <hr>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Transaction Code</label>
                  <div class="col-sm-9">
                    <input name="Purchase" type="text" class="form-control" id="Purchase" value="<?php echo $row_View['Purchase']; ?>" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">J/S</label>
                  <div class="col-sm-9">
                    <input name="Purchase" type="text" class="form-control" id="Purchase" value="<?php echo $row_View['JS']; ?>" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Item Name</label>
                  <div class="col-sm-9">
                    <input name="Purchase" type="text" class="form-control" id="Purchase" value="<?php echo $row_View['Barang']; ?>" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Quantity</label>
                  <div class="col-sm-9">
                    <input name="Purchase" type="text" class="form-control" id="Purchase" value="<?php echo $row_View['Quantity']; ?>" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Sell Date</label>
                  <div class="col-sm-9">
                    <input name="Purchase" type="text" class="form-control" id="Purchase" value="<?php echo $row_View['TglStart']; ?>" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Price</label>
                  <div class="col-sm-9">
                    <input name="Purchase" type="text" class="form-control" id="Purchase" value="<?php echo $row_View['Amount']; ?>" readonly>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
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
  $(document).ready(function() {
    $("#dialog").dialog({
      autoOpen: false,
      modal: true
    });
  });

  $(".confirmLink").click(function(e) {
    e.preventDefault();
    var targetUrl = $(this).attr("href");

    $("#dialog").dialog({
      buttons : {
        "Confirm" : function() {
          window.location.href = targetUrl;
        },
        "Cancel" : function() {
          $(this).dialog("close");
        }
      }
    });

    $("#dialog").dialog("open");
  });
</script>
</body>
</html>
<?php
mysql_free_result($View);
?>