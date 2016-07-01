<?php require_once('connections/Connection.php'); ?>
<?php date_default_timezone_set("Asia/Krasnoyarsk"); ?>
<?php
// Declare Root directory
$ROOT="";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

$Today = date("d/m/Y");
$Today1 = date("d/m/Y", time()+86400);
$Today2 = date("d/m/Y", time()+86400*2);
$Today3 = date("d/m/Y", time()+86400*3);
$Today4 = date("d/m/Y", time()+86400*4);
$Today5 = date("d/m/Y", time()+86400*5);
$Today6 = date("d/m/Y", time()+86400*6);
$Today7 = date("d/m/Y", time()+86400*7);

mysql_select_db($database_Connection, $Connection);
$query_View = "SELECT periode.Id, periode.Periode, periode.IsiSJKir, periode.Reference, isisjkirim.SJKir, transaksi.Barang, periode.S, periode.E, periode.Deletes, project.Project, customer.Company, customer.Customer, customer.CompPhone, customer.CustPhone FROM periode LEFT JOIN isisjkirim ON periode.IsiSJKir=isisjkirim.IsiSJKir LEFT JOIN transaksi ON isisjkirim.Purchase=transaksi.Purchase LEFT JOIN pocustomer ON transaksi.Reference=pocustomer.Reference LEFT JOIN project ON pocustomer.PCode=project.PCode LEFT JOIN customer ON project.CCode=customer.CCode WHERE (periode.Deletes='Sewa' OR periode.Deletes='Extend') AND periode.Id IN (SELECT MAX(periode.Id) AS Id FROM periode LEFT JOIN isisjkirim ON periode.IsiSJKir=isisjkirim.IsiSJKir WHERE periode.Deletes = 'Sewa' OR periode.Deletes = 'Extend' GROUP BY isisjkirim.SJKir) AND (periode.E = '$Today' OR periode.E = '$Today1' OR periode.E = '$Today2' OR periode.E = '$Today3' OR periode.E = '$Today4' OR periode.E = '$Today5' OR periode.E = '$Today6' OR periode.E = '$Today7') GROUP BY isisjkirim.SJKir, periode.Periode, periode.Deletes ORDER BY periode.Id ASC";
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>BDN ERP | Customer</title>
  <!-- CSS Include -->
  <?php include_once('pages/cssinclude.php');  ?>

</head>
<body class="hold-transition skin-blue fixed sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <?php include_once('pages/logo.php'); ?>
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
              <img src="library/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo "Welcome " . $_SESSION['username']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="library/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                  <?php echo $_SESSION['name']; ?> - <?php echo $_SESSION['access']; ?>
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
		<!-- Sidebar Menu -->
		<?php
			$top_menu_sel="menu_home";
			include_once('pages/menu.php');
		?>
	</section>
    <!-- /.sidebar -->
  </aside>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Home
        <small>Dashboard </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-body">
              <table id="tb_index_example1" class="table table-bordered table-striped table-responsive">
                <thead>
                <tr>
                  <th>Periode</th>
                  <th>Start</th>
                  <th>End</th>
                  <th>Company</th>
                  <th>Project</th>
                  <th>Customer</th>
                  <th>Phone</th>
                  <th>View</th>
                  <th>Extend</th>
                </tr>
                </thead>
				<tbody>
					<?php do { ?>
					<tr>
						<td><?php echo $row_View['Periode']; ?></td>
						<td><?php echo $row_View['S']; ?></td>
						<td><?php echo $row_View['E']; ?></td>
						<td><?php echo $row_View['Company']; ?></td>
						<td><?php echo $row_View['Project']; ?></td>
                        <td><?php echo $row_View['Customer']; ?></td>
                        <td><?php echo $row_View['CompPhone']; ?></td>
                        <td>
                        <a href="pages/transaksisewa/ViewTransaksiSewa2.php?Reference=<?php echo $row_View['Reference']; ?>&Periode=<?php echo $row_View['Periode']; ?>&SJKir=<?php echo $row_View['SJKir']; ?>&Deletes=<?php echo $row_View['Deletes']; ?>"><button type="button" class="btn btn-block btn-primary btn-sm">View Detail</button></a></td>
                        <td><a href="pages/transaksisewa/ExtendTransaksiSewa.php?Reference=<?php echo $row_View['Reference']; ?>&Periode=<?php echo $row_View['Periode']; ?>&SJKir=<?php echo $row_View['SJKir']; ?>" onclick="return confirm('Extend Sewa hanya boleh dilakukan di akhir periode dan sudah ada konfirmasi dari customer. Lanjutkan?')"><button type="button" name="bt_viewtransaksisewa_extend" id="bt_viewtransaksisewa_extend" class="btn btn-block btn-primary btn-sm">Extend</button></a></td>
					</tr>
					<?php } while ($row_View = mysql_fetch_assoc($View)); ?>
				</tbody>
              </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
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
	<?php include_once('pages/footer.php'); ?>
  </footer>
  <!-- /.footer-wrapper -->
  
  <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>

<!-- jsinclude -->
<?php include_once('pages/jsinclude.php'); ?>

<!-- page script -->
<script>
  $(function () {
    $("#tb_index_example1").DataTable();
  });

</script>

</body>
</html>
<?php
mysql_free_result($View);
?>
