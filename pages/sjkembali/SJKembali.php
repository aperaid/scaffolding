<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");
	
mysql_select_db($database_Connection, $Connection);
$query_SJKembali = "SELECT sjkembali.*, project.Project, customer.Customer FROM sjkembali INNER JOIN pocustomer ON sjkembali.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode INNER JOIN customer ON project.CCode=customer.CCode ORDER BY sjkembali.Id ASC";
$SJKembali = mysql_query($query_SJKembali, $Connection) or die(mysql_error());
$row_SJKembali = mysql_fetch_assoc($SJKembali);
$totalRows_SJKembali = mysql_num_rows($SJKembali);
	
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
$PAGE="SJ Kembali";
$top_menu_sel="menu_sjkembali";
include_once($ROOT . 'pages/html_header.php');
include_once($ROOT . 'pages/html_main_header.php');
?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Surat Jalan Kembali
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">SJ Kembali</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-body">
              <table id="tb_sjkembali_example1" class="table table-bordered table-striped table-responsive">
                <thead>
                <tr>
                  <th>SJ Code</th>
                  <th>Tgl Tertanda</th>
                  <th>Customer</th>
                  <th>Project</th>
                  <th>View</th>
                  <th>Delete</th>
                </tr>
                </thead>
                <tbody>
					<?php do { ?>
					<tr>
						<td><?php echo $row_SJKembali['SJKem']; ?></td>
						<td><?php echo $row_SJKembali['Tgl']; ?></td>
						<td><?php echo $row_SJKembali['Customer']; ?></td>
						<td><?php echo $row_SJKembali['Project']; ?></td>
						<td><a href="ViewSJKembali.php?SJKem=<?php echo $row_SJKembali['SJKem']; ?>"><button type="button" class="btn btn-primary btn-sm btn-block">View</button></a></td>
						<?php
						mysql_select_db($database_Connection, $Connection);
						$query_check = sprintf("SELECT check_sjkem(%s) AS result", GetSQLValueString($row_SJKembali['SJKem'], "text"));
						$check = mysql_query($query_check, $Connection) or die(mysql_error());
						$row_check = mysql_fetch_assoc($check);
						?>
						<td><a href="DeleteSJKembali.php?SJKem=<?php echo $row_SJKembali['SJKem']; ?>" onclick="return confirm('Delete Pengembalian?')"><button type="button" <?php if ($row_check['result'] == 0) { ?> class="btn btn-block btn-sm btn-danger" <?php } else { ?> class="btn btn-block btn-sm btn-default" disabled <?php } ?>>Delete</button></a></td>
					</tr>
					<?php } while ($row_SJKembali = mysql_fetch_assoc($SJKembali)); ?>
				</tbody>
                
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
<!-- page script -->
<script>
  $(function () {
    $("#tb_sjkembali_example1").DataTable();
  });
</script>
<?php
  mysql_free_result($SJKembali);
  mysql_free_result($check)
?>