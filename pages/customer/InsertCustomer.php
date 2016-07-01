<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO customer (CCode, Company, Customer, Alamat, Zip, Kota, CompPhone, CustPhone, Fax, NPWP, CompEmail, CustEmail) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tx_insertcustomer_CCode'], "text"),
                       GetSQLValueString($_POST['tx_insertcustomer_Company'], "text"),
                       GetSQLValueString($_POST['tx_insertcustomer_Customer'], "text"),
                       GetSQLValueString($_POST['tx_insertcustomer_Alamat'], "text"),
                       GetSQLValueString($_POST['nb_insertcustomer_Zip'], "int"),
                       GetSQLValueString($_POST['tx_insertcustomer_Kota'], "text"),
                       GetSQLValueString($_POST['nb_insertcustomer_CompPhone'], "text"),
                       GetSQLValueString($_POST['nb_insertcustomer_CustPhone'], "text"),
                       GetSQLValueString($_POST['nb_insertcustomer_Fax'], "text"),
                       GetSQLValueString($_POST['nb_insertcustomer_NPWP'], "text"),
                       GetSQLValueString($_POST['tx_insertcustomer_CompEmail'], "text"),
                       GetSQLValueString($_POST['tx_insertcustomer_CustEmail'], "text"));

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

<?php
	// Declare Root directory
	$PAGE="Insert Customer";
	$top_menu_sel="menu_customer";
	include_once($ROOT . 'pages/html_header.php');
	include_once($ROOT . 'pages/html_main_header.php');
?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Customer
        <small>Insert</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
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
            <form action="<?php echo $editFormAction; ?>" id="fm_insertcustomer_form1" name="fm_insertcustomer_form1" method="post" class="form-horizontal">
              <div class="box-body with-border">
                <div class="form-group">
                  <label class="col-sm-2 control-label">Company Code</label>
                  <div class="col-sm-4">
                    <input id="tx_insertcustomer_CCode" autocomplete="off" onKeyUp="capital()" name="tx_insertcustomer_CCode" type="text" class="form-control" placeholder="Company Code" maxlength="5" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Nama Perusahaan</label>
                  <div class="col-sm-7">
                    <input id="tx_insertcustomer_Company" autocomplete="off" onKeyUp="capital()" name="tx_insertcustomer_Company" type="text" class="form-control" placeholder="Nama Perusahaan" required>
                  </div>
                  <label class="col-sm-1 control-label">NPWP</label>
                  <div class="col-sm-2">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-legal"></i></span>
                      <input id="nb_insertcustomer_NPWP" name="nb_insertcustomer_NPWP" type="number" class="form-control" placeholder="12.456.789.0-012.123">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-4">
                    <input id="tx_insertcustomer_Alamat" name="tx_insertcustomer_Alamat" type="text" class="form-control" placeholder="Jl. Nama Jalan 1A No.10, Kelurahan, Kecamatan, Kota">
                  </div>
                  <label class="col-sm-1 control-label">Kota</label>
                  <div class="col-sm-2">
                    <input id="tx_insertcustomer_Kota" name="tx_insertcustomer_Kota" type="text" class="form-control" placeholder="Kota">
                  </div>
                  <label class="col-sm-1 control-label">Kodepos</label>
                  <div class="col-sm-2">
                    <input id="nb_insertcustomer_Zip" name="nb_insertcustomer_Zip" type="number" class="form-control" placeholder="10203">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Telp</label>
                  <div class="col-sm-2">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-phone"></i>
                      </div>
                      <input id="nb_insertcustomer_CompPhone" name="nb_insertcustomer_CompPhone" type="number" class="form-control" placeholder="021-123456">
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Fax</label>
                  <div class="col-sm-2">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-fax"></i>
                      </div>
                      <input id="nb_insertcustomer_Fax" name="nb_insertcustomer_Fax" type="number" class="form-control" placeholder="021-123456">
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-2">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                      <input id="tx_insertcustomer_CompEmail" name="tx_insertcustomer_CompEmail" type="text" class="form-control" placeholder="Email">
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
                      <input id="tx_insertcustomer_Customer" name="tx_insertcustomer_Customer" type="text" class="form-control" placeholder="Nama CP">
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
                      <input id="nb_insertcustomer_CustPhone" name="nb_insertcustomer_CustPhone" type="number" class="form-control" placeholder="021-123456">
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Email CP</label>
                  <div class="col-sm-2">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                      <input id="tx_insertcustomer_CustEmail" name="tx_insertcustomer_CustEmail"type="text" class="form-control" placeholder="Email CP">
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <a href="customer.php"><button type="button" class="btn btn-default pull-Left">Cancel</button></a>
                <button type="submit" name="bt_insertcustomer_submit" id="bt_insertcustomer_submit" class="btn btn-info pull-right">Insert</button>
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
<?php include_once($ROOT . 'pages/footer.php'); ?>
<!-- /.footer-wrapper -->

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
    var x = document.getElementById("tx_insertcustomer_CCode");
    x.value = x.value.toUpperCase();
	var x = document.getElementById("tx_insertcustomer_Company");
    x.value = x.value.toUpperCase();
}
</script>

<?php
  mysql_free_result($Menu);
  mysql_free_result($User);
?>