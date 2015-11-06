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
  $insertSQL = sprintf("INSERT INTO customer (CCode, Company, Customer, Alamat, Zip, Kota, CompPhone, CustPhone, Fax, NPWP, CompEmail, CustEmail) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['CCode'], "text"),
                       GetSQLValueString($_POST['Company'], "text"),
                       GetSQLValueString($_POST['Customer'], "text"),
                       GetSQLValueString($_POST['Alamat'], "text"),
                       GetSQLValueString($_POST['Zip'], "int"),
                       GetSQLValueString($_POST['Kota'], "text"),
                       GetSQLValueString($_POST['CompPhone'], "text"),
                       GetSQLValueString($_POST['CustPhone'], "text"),
                       GetSQLValueString($_POST['Fax'], "text"),
                       GetSQLValueString($_POST['NPWP'], "text"),
                       GetSQLValueString($_POST['CompEmail'], "text"),
                       GetSQLValueString($_POST['CustEmail'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "Customer.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

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
  <title>BDN ERP | Insert Customer</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">
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
        Insert Customer
        <small>New</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="customer.php">Customer</a></li>
        <li class="active">Insert Customer</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Company Detail</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="post" class="form-horizontal">
              <div class="box-body with-border">
                <div class="form-group">
                  <label class="col-sm-2 control-label">Company Code</label>
                  <div class="col-sm-4">
                    <input id="CCode" name="CCode" type="text" class="form-control" placeholder="Company Code">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Nama Perusahaan</label>
                  <div class="col-sm-7">
                    <input id="Company" name="Company" type="text" class="form-control" placeholder="Nama Perusahaan">
                  </div>
                  <label class="col-sm-1 control-label">NPWP</label>
                  <div class="col-sm-2">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-legal"></i></span>
                      <input id="NPWP" name="NPWP" type="text" class="form-control" placeholder="12.456.789.0-012.123">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-4">
                    <input id="Alamat" name="Alamat" type="text" class="form-control" placeholder="Jl. Nama Jalan 1A No.10, Kelurahan, Kecamatan, Kota">
                  </div>
                  <label class="col-sm-1 control-label">Kota</label>
                  <div class="col-sm-2">
                    <input id="Kota" name="Kota" type="number" class="form-control" placeholder="Kota">
                  </div>
                  <label class="col-sm-1 control-label">Kodepos</label>
                  <div class="col-sm-2">
                    <input id="Zip" name="Zip" type="number" class="form-control" placeholder="10203">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Telp</label>
                  <div class="col-sm-2">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-phone"></i>
                      </div>
                      <input id="CompPhone" name="CompPhone" type="text" class="form-control" placeholder="021-123456">
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Fax</label>
                  <div class="col-sm-2">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-fax"></i>
                      </div>
                      <input id="Fax" name="Fax" type="text" class="form-control" placeholder="021-123456">
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-2">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                      <input id="CompEmail" name="CompEmail" type="text" class="form-control" placeholder="Email">
                    </div>
                  </div>
                </div>
                <hr>
                <div class="form-group">
                  <label class="col-sm-2 control-label">CP</label>
                  <div class="col-sm-10">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-user"></i>
                      </div>
                      <input id="Customer" name="Customer" type="text" class="form-control" placeholder="Nama CP">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Telp</label>
                  <div class="col-sm-2">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-phone"></i>
                      </div>
                      <input id="CustPhone" name="CustPhone" type="text" class="form-control" placeholder="021-123456">
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Email CP</label>
                  <div class="col-sm-2">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                      <input id="CustEmail" name="CustEmail"type="text" class="form-control" placeholder="Email CP">
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" name="submit" id="submit" class="btn btn-info pull-right">Insert</button>
              </div>
              <input type="hidden" name="MM_insert" value="form1">
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
<script src="../../	plugins/jQuery/jQuery-2.1.4.min.js"></script>
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
</body>
</html>
<?php
mysql_free_result($Menu);
?>