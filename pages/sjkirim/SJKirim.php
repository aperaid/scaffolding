<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

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
$query_SJKirim = "SELECT sjkirim.*, project.Project, customer.Customer FROM sjkirim INNER JOIN pocustomer ON sjkirim.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode INNER JOIN customer ON project.CCode=customer.CCode ORDER BY sjkirim.Id ASC";
$SJKirim = mysql_query($query_SJKirim, $Connection) or die(mysql_error());
$row_SJKirim = mysql_fetch_assoc($SJKirim);
$totalRows_SJKirim = mysql_num_rows($SJKirim);

mysql_select_db($database_Connection, $Connection);
$query_ViewIsiSJKirim = sprintf("SELECT isisjkirim.*, transaksi.Barang, transaksi.JS, transaksi.QSisaKir, project.*, customer.* FROM isisjkirim INNER JOIN transaksi ON isisjkirim.Purchase=transaksi.Purchase INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode INNER JOIN customer ON project.CCode=customer.CCode WHERE isisjkirim.SJKir = %s ORDER BY isisjkirim.Id ASC", GetSQLValueString($row_SJKirim['SJKir'], "text"));
?>

<?php
$PAGE="SJ Kirim";
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
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">SJ Kirim</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-body">
              <table id="tb_sjkirim_example1" class="table table-bordered table-striped table-responsive">
                <thead>
                <tr>
                  <th>SJ Code</th>
                  <th>Tgl Kirim</th>
                  <th>Customer</th>
                  <th>Project</th>
                  <th>View</th>
                  <th>Delete</th>
                </tr>
                </thead>
                <tbody>
				  <?php do { ?>
					<tr>
						<td class="noinvoice"><?php echo $row_SJKirim['SJKir']; ?></td>
						<td class="noinvoice"><?php echo $row_SJKirim['Tgl']; ?></td>
						<td class="customer"><?php echo $row_SJKirim['Customer']; ?></td>
						<td class="noinvoice"><?php echo $row_SJKirim['Project']; ?></td>
						<td><a href="ViewSJKirim.php?SJKir=<?php echo $row_SJKirim['SJKir']; ?>"><button type="button" class="btn btn-block btn-primary btn-sm">View</button></a></td>
                        <?php
						mysql_select_db($database_Connection, $Connection);
						$query_check = sprintf("SELECT check_sjkir(%s) AS result", GetSQLValueString($row_SJKirim['SJKir'], "text"));
						$check = mysql_query($query_check, $Connection) or die(mysql_error());
						$row_check = mysql_fetch_assoc($check);
						?>
						<td><a href="DeleteSJKirim.php?SJKir=<?php echo $row_SJKirim['SJKir']; ?>" onclick="return confirm('Delete Pengiriman?')"><button type="button" <?php if ($row_check['result'] == 0) { ?>  class="btn btn-block btn-sm btn-danger" <?php } else { ?> class="btn btn-block btn-sm btn-default" disabled <?php } ?>>Delete</button></a></td>
					</tr>
				  <?php } while ($row_SJKirim = mysql_fetch_assoc($SJKirim)); ?>
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
    $("#tb_sjkirim_example1").DataTable();
  });
</script>

<?php
  mysql_free_result($SJKirim);
?>