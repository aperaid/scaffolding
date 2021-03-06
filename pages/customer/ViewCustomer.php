<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

$colname_View = "-1";
if (isset($_GET['Id'])) {
  $colname_View = $_GET['Id'];
}
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT * FROM customer WHERE Id = %s", GetSQLValueString($colname_View, "int"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);

mysql_select_db($database_Connection, $Connection);
$query_Menu = "SELECT * FROM menu";
$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
$row_Menu = mysql_fetch_assoc($Menu);
$totalRows_Menu = mysql_num_rows($Menu);

//FUNCTION BUTTON DISABLE
$check_ccode = $row_View['CCode'];
mysql_select_db($database_Connection, $Connection);
$query_check = sprintf("SELECT check_customer('$check_ccode') AS result");
$check = mysql_query($query_check, $Connection) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);
//FUNCTION BUTTON DISABLE END

?>

<?php
	$PAGE="View Customer";
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
        <small>View</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="Customer.php">Customer</a></li>
        <li class="active">View Customer</li>
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
            <form id="fm_viewcustomer_form1" name="fm_viewcustomer_form1" method="post" class="form-horizontal">
              <div class="box-body with-border">
                <div class="form-group">
                  <label class="col-sm-2 control-label">Company Code</label>
                  <div class="col-sm-4">
                    <input id="tx_viewcustomer_CCode" name="tx_viewcustomer_CCode" type="text" class="form-control" value="<?php echo $row_View['CCode']; ?>"  readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Nama Perusahaan</label>
                  <div class="col-sm-7">
                    <input id="tx_viewcustomer_Company" name="tx_viewcustomer_Company" type="text" class="form-control" value="<?php echo $row_View['Company']; ?>"  readonly>
                  </div>
                  <label class="col-sm-1 control-label">Telp</label>
                  <div class="col-sm-2">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-phone"></i>
                      </div>
                      <input id="nb_viewcustomer_CompPhone" name="nb_viewcustomer_CompPhone" type="text" class="form-control" value="<?php echo $row_View['CompPhone']; ?>"  readonly>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-4">
                    <input id="tx_viewcustomer_Alamat" name="tx_viewcustomer_Alamat" type="text" class="form-control" value="<?php echo $row_View['Alamat']; ?>"  readonly>
                  </div>
                  <label class="col-sm-1 control-label">Kota</label>
                  <div class="col-sm-2">
                    <input id="tx_viewcustomer_Kota" name="tx_viewcustomer_Kota" type="text" class="form-control" value="<?php echo $row_View['Kota']; ?>" readonly>
                  </div>
                  <label class="col-sm-1 control-label">Fax</label>
                  <div class="col-sm-2">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-fax"></i>
                      </div>
                      <input id="nb_viewcustomer_Fax" name="nb_viewcustomer_Fax" type="text" class="form-control" value="<?php echo $row_View['Fax']; ?>" readonly>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">NPWP</label>
                  <div class="col-sm-4">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-legal"></i></span>
                      <input id="nb_viewcustomer_NPWP" name="nb_viewcustomer_NPWP" type="text" class="form-control" value="<?php echo $row_View['NPWP']; ?>"  readonly>
                    </div>
                  </div>
                  <label class="col-sm-1 control-label">Kodepos</label>
                  <div class="col-sm-2">
                    <input id="nb_viewcustomer_Zip" name="nb_viewcustomer_Zip" type="number" class="form-control" value="<?php echo $row_View['Zip']; ?>"  readonly>
                  </div>
                  
                  <label class="col-sm-1 control-label">Email</label>
                  <div class="col-sm-2">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                      <input id="tx_viewcustomer_CompEmail" name="tx_viewcustomer_CompEmail" type="text" class="form-control" value="<?php echo $row_View['CompEmail']; ?>" readonly>
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
                      <input id="tx_viewcustomer_Customer" name="tx_viewcustomer_Customer" type="text" class="form-control" value="<?php echo $row_View['Customer']; ?>"  readonly>
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
                      <input id="nb_viewcustomer_CustPhone" name="nb_viewcustomer_CustPhone" type="text" class="form-control" value="<?php echo $row_View['CustPhone']; ?>"  readonly>
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Email CP</label>
                  <div class="col-sm-2">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                      <input id="tx_viewcustomer_CustEmail" name="tx_viewcustomer_CustEmail"type="text" class="form-control" value="<?php echo $row_View['CustEmail']; ?>" readonly>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <a href="EditCustomer.php?Id=<?php echo $row_View['Id']; ?>"><button id="edit_button" type="button" class="btn btn-primary pull-right" >Edit</button></a>
                <div class="btn-group"><a href="Customer.php"><button type="button" class="btn btn-default pull-left">Back</button></a></div>
                <div class="btn-group" ><a href="DeleteCustomer.php?CCode=<?php echo $row_View['CCode']; ?>" onclick="return confirm('Delete Customer?')"><button id="delete_button" type="button" <?php if ($row_check['result'] == 1) { ?> class="btn btn-default pull-left" disabled <?php } else { ?> class="btn btn-danger pull-left" <?php }?>>Delete</button></a></div>
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

<?php
mysql_free_result($View);
?>