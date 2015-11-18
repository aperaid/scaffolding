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

mysql_select_db($database_Connection, $Connection);
$query_Menu = "SELECT * FROM menu";
$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
$row_Menu = mysql_fetch_assoc($Menu);
$totalRows_Menu = mysql_num_rows($Menu);

$colname_ViewIsiSJKembali = "-1";
if (isset($_GET['SJKem'])) {
  $colname_ViewIsiSJKembali = $_GET['SJKem'];
}
mysql_select_db($database_Connection, $Connection);
$query_ViewIsiSJKembali = sprintf("SELECT isisjkembali.*, transaksi.Barang, transaksi.JS, transaksi.QSisaKem, project.Project FROM isisjkembali INNER JOIN transaksi ON isisjkembali.Purchase=transaksi.Purchase INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode WHERE isisjkembali.SJKem = %s ORDER BY isisjkembali.Id ASC", GetSQLValueString($colname_ViewIsiSJKembali, "text"));
$ViewIsiSJKembali = mysql_query($query_ViewIsiSJKembali, $Connection) or die(mysql_error());
$row_ViewIsiSJKembali = mysql_fetch_assoc($ViewIsiSJKembali);
$totalRows_ViewIsiSJKembali = mysql_num_rows($ViewIsiSJKembali);

$colname_View = "-1";
if (isset($_GET['SJKem'])) {
  $colname_View = $_GET['SJKem'];
}
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT SJKem FROM sjkembali WHERE SJKem = %s", GetSQLValueString($colname_View, "text"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>BDN ERP | View PO Customer</title>
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
        SJ Kembali
        <small>View Detail</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="">SJ Kembali</a></li>
        <li class="active">View SJ</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box box-primary">
            <div class="box-body no-padding">
              <table id="example1" class="table table-bordered">
                <thead>
                <tr>
					<th>#</th>
					<th>#Pur</th>
					<th>J/S</th>
					<th>Barang</th>
					<th>Warehouse</th>
					<th>Q Sisa Kembali</th>
					<th>Q Tertanda</th>
					<th>Q Terima</th>
                </tr>
                </thead>
                <tbody>
					<?php do { ?>
					<tr>
						<input name="Id" type="hidden" id="Id">
						<td><?php echo $row_ViewIsiSJKembali['IsiSJKem']; ?></td>
						<td><?php echo $row_ViewIsiSJKembali['Purchase']; ?></td>
						<td><input name="JS" type="text" class="form-control" id="JS" value="<?php echo $row_ViewIsiSJKembali['JS']; ?>" readonly></td>
						<td><input name="Barang" type="text" class="form-control" id="Barang" value="<?php echo $row_ViewIsiSJKembali['Barang']; ?>" readonly></td>
						<td><input name="Warehouse" type="text" class="form-control" id="Warehouse" value="<?php echo $row_ViewIsiSJKembali['Warehouse']; ?>" readonly></td>
						<td><input name="QSisaKem" type="text" class="form-control" id="QSisaKem" value="<?php echo $row_ViewIsiSJKembali['QSisaKem']; ?>" readonly></td>
						<td><input name="QTertanda" type="text" class="form-control" id="QTertanda" value="<?php echo $row_ViewIsiSJKembali['QTertanda']; ?>" readonly></td>
						<td><input name="QTerima" type="text" class="form-control" id="QTerima" value="<?php echo $row_ViewIsiSJKembali['QTerima']; ?>" readonly></td
					></tr>
					<?php } while ($row_ViewIsiSJKembali = mysql_fetch_assoc($ViewIsiSJKembali)); ?>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
				<a href="SJKembali.php"><button type="button" class="btn btn-default">Cancel</button></a>
				<div class="btn-group pull-right">
					<a href="EditSJKembali.php?SJKem=<?php echo $row_View['SJKem']; ?>"><button type="button" class="btn btn-primary">Edit Pengembalian</button></a>
					<a href="EditSJKembaliQuantity.php?SJKem=<?php echo $row_View['SJKem']; ?>"><button type="button" class="btn btn-success">Quantity Terima</button></a>
				</div>
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
<script src="../../plugins/jQuery/jQuery-2.1.4.min.js"></script>
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
</body>
</html>
<?php
mysql_free_result($Menu);

mysql_free_result($ViewIsiSJKembali);

mysql_free_result($View);
?>