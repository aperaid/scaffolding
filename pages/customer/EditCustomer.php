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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE customer SET CCode=%s, Company=%s, Customer=%s, Alamat=%s, Zip=%s, Kota=%s, CompPhone=%s, CustPhone=%s, Fax=%s, NPWP=%s, CompEmail=%s, CustEmail=%s WHERE Id=%s",
                       GetSQLValueString($_POST['tx_editcustomer_CCode'], "text"),
                       GetSQLValueString($_POST['tx_editcustomer_Company'], "text"),
                       GetSQLValueString($_POST['tx_editcustomer_Customer'], "text"),
                       GetSQLValueString($_POST['tx_editcustomer_Alamat'], "text"),
                       GetSQLValueString($_POST['nb_editcustomer_Zip'], "int"),
                       GetSQLValueString($_POST['tx_editcustomer_Kota'], "text"),
                       GetSQLValueString($_POST['nb_editcustomer_CompPhone'], "text"),
                       GetSQLValueString($_POST['nb_editcustomer_CustPhone'], "text"),
                       GetSQLValueString($_POST['nb_editcustomer_Fax'], "text"),
                       GetSQLValueString($_POST['nb_editcustomer_NPWP'], "text"),
                       GetSQLValueString($_POST['tx_editcustomer_CompEmail'], "text"),
                       GetSQLValueString($_POST['tx_editcustomer_CustEmail'], "text"),
                       GetSQLValueString($_POST['hd_editcustomer_Id'], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());

  $updateGoTo = "ViewCustomer.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}


$colname_Edit = "-1";
if (isset($_GET['Id'])) {
  $colname_Edit = $_GET['Id'];
}
mysql_select_db($database_Connection, $Connection);
$query_Edit = sprintf("SELECT * FROM customer WHERE Id = %s", GetSQLValueString($colname_Edit, "int"));
$Edit = mysql_query($query_Edit, $Connection) or die(mysql_error());
$row_Edit = mysql_fetch_assoc($Edit);
$totalRows_Edit = mysql_num_rows($Edit);

//check project exist to disable ccode editFormAction
mysql_select_db($database_Connection, $Connection);
$query_check = sprintf("SELECT check_customer(%s) AS result", GetSQLValueString($row_Edit['CCode'], "text"));
$check = mysql_query($query_check, $Connection) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
//check end
?>

<?php
	$PAGE="Edit Customer";
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
        <small>Edit</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="customer.php">Customer</a></li>
        <li class="active">Edit Customer</li>
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
            <form action="<?php echo $editFormAction; ?>" id="fm_editcustomer_form1" name="fm_editcustomer_form1" method="post" class="form-horizontal">
              <div class="box-body with-border">
                <div class="form-group">
                  <input name="hd_editcustomer_Id" type="hidden" id="hd_editcustomer_Id" value="<?php echo $row_Edit['Id']; ?>">
                  <label class="col-sm-2 control-label">Company Code</label>
                  <div class="col-sm-4">
                    <input id="tx_editcustomer_CCode" autocomplete="off" onKeyUp="capital()" name="tx_editcustomer_CCode" type="text" class="form-control" value="<?php echo $row_Edit['CCode']; ?>" placeholder="Company Code" maxlength="5" required <?php if ($row_check['result']==1) { ?> readonly <?php } ?>>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Nama Perusahaan</label>
                  <div class="col-sm-7">
                    <input id="tx_editcustomer_Company" autocomplete="off" onKeyUp="capital()" name="tx_editcustomer_Company" type="text" class="form-control" value="<?php echo $row_Edit['Company']; ?>" placeholder="Nama Perusahaan" required>
                  </div>
                  <label class="col-sm-1 control-label">Telp</label>
                  <div class="col-sm-2">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-phone"></i>
                      </div>
                      <input id="nb_editcustomer_CompPhone" name="nb_editcustomer_CompPhone" type="number" class="form-control" value="<?php echo $row_Edit['CompPhone']; ?>" placeholder="021-123456">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-4">
                    <input id="tx_editcustomer_Alamat" name="tx_editcustomer_Alamat" type="text" class="form-control" value="<?php echo $row_Edit['Alamat']; ?>"  placeholder="Jl. Nama Jalan No.1, Kelurahan, Kecamatan">
                  </div>
                  <label class="col-sm-1 control-label">Kota</label>
                  <div class="col-sm-2">
                    <input id="tx_editcustomer_Kota" name="tx_editcustomer_Kota" type="text" class="form-control" value="<?php echo $row_Edit['Kota']; ?>" placeholder="Nama Kota">
                  </div>
                  <label class="col-sm-1 control-label">Fax</label>
                  <div class="col-sm-2">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-fax"></i>
                      </div>
                      <input id="nb_editcustomer_Fax" name="nb_editcustomer_Fax" type="number" class="form-control" value="<?php echo $row_Edit['Fax']; ?>" placeholder="021-123456">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">NPWP</label>
                  <div class="col-sm-4">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-legal"></i></span>
                      <input id="nb_editcustomer_NPWP" name="nb_editcustomer_NPWP" type="number" class="form-control" value="<?php echo $row_Edit['NPWP']; ?>"  placeholder="12.123.123.0-012.000">
                    </div>
                  </div>
                  <label class="col-sm-1 control-label">Kodepos</label>
                  <div class="col-sm-2">
                    <input id="nb_editcustomer_Zip" name="nb_editcustomer_Zip" type="number" class="form-control" value="<?php echo $row_Edit['Zip']; ?>"  placeholder="10203">
                  </div>
                  
                  <label class="col-sm-1 control-label">Email</label>
                  <div class="col-sm-2">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                      <input id="tx_editcustomer_CompEmail" name="tx_editcustomer_CompEmail" type="text" class="form-control" value="<?php echo $row_Edit['CompEmail']; ?>" placeholder="email@company.com">
                    </div>
                  </div>
                </div>
                <hr>
                <hr>
                <div class="form-group">
                  <label class="col-sm-2 control-label">CP</label>
                  <div class="col-sm-10">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-user"></i>
                      </div>
                      <input id="tx_editcustomer_Customer" name="tx_editcustomer_Customer" type="text" class="form-control" value="<?php echo $row_Edit['Customer']; ?>" placeholder="Nama CP">
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
                      <input id="nb_editcustomer_CustPhone" name="nb_editcustomer_CustPhone" type="number" class="form-control" value="<?php echo $row_Edit['CustPhone']; ?>" placeholder="021-123456">
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Email CP</label>
                  <div class="col-sm-2">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                      <input id="tx_editcustomer_CustEmail" name="tx_editcustomer_CustEmail"type="text" class="form-control" value="<?php echo $row_Edit['CustEmail']; ?>" placeholder="Email CP">
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <a href="ViewCustomer.php?Id=<?php echo $row_Edit['Id']; ?>"><button type="button" class="btn btn-default pull-left">Cancel</button></a>
                <button type="submit" name="bt_editcustomer_submit" id="bt_editcustomer_submit" class="btn btn-info pull-right">Update</button>
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
<?php include_once($ROOT . 'pages/footer.php'); ?>
<!-- /.footer-wrapper -->

<!-- page script -->
<script>
function capital() {
    var x = document.getElementById("tx_editcustomer_CCode");
    x.value = x.value.toUpperCase();
	var x = document.getElementById("tx_editcustomer_Company");
    x.value = x.value.toUpperCase();
}
</script>

<?php
  mysql_free_result($Edit);
  mysql_free_result($check);
?>