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
$query_View = sprintf("SELECT project.Id, project.PCode, project.Project, project.Alamat, customer.Company, customer.CompPhone, customer.Customer, customer.CustPhone, customer.CCode FROM project INNER JOIN customer ON project.CCode=customer.CCode WHERE project.Id = %s", GetSQLValueString($colname_View, "text"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);

mysql_select_db($database_Connection, $Connection);
$query_Menu = "SELECT * FROM menu";
$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
$row_Menu = mysql_fetch_assoc($Menu);
$totalRows_Menu = mysql_num_rows($Menu);


//FUNCTION BUTTON DISABLE
$check_pcode = $row_View['PCode'];
mysql_select_db($database_Connection, $Connection);
$query_check = sprintf("SELECT check_project('$check_pcode') AS result");
$check = mysql_query($query_check, $Connection) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);
//FUNCTION BUTTON DISABLE END
?>

<?php
// Declare Root directory
$PAGE="Project";
$top_menu_sel="menu_project";
include_once($ROOT . 'pages/html_header.php');
include_once($ROOT . 'pages/html_main_header.php');
?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Project
        <small>View</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="project.php">Project</a></li>
        <li class="active">View Project</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Project Detail</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form id="fm_viewproject_form1" name="fm_viewproject_form1" method="post" class="form-horizontal">
              <div class="box-body with-border">
                <div class="form-group">
                  <label class="col-sm-2 control-label">Project Code</label>
                  <div class="col-sm-6">
                    <input id="tx_viewproject_PCode" name="tx_viewproject_PCode" type="text" class="form-control" value="<?php echo $row_View['PCode']; ?>"  readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Project Name</label>
                  <div class="col-sm-6">
                    <input id="tx_viewproject_Project" name="tx_viewproject_Project" type="text" class="form-control" value="<?php echo $row_View['Project']; ?>"  readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Alamat Project</label>
                  <div class="col-sm-6">
                    <input id="tx_viewproject_Alamat" name="tx_viewproject_Alamat" type="text" class="form-control" value="<?php echo $row_View['Alamat']; ?>"  readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Company Code</label>
                  <div class="col-sm-6">
                    <input id="tx_viewproject_CCode" name="tx_viewproject_CCode" type="text" class="form-control" value="<?php echo $row_View['CCode']; ?>"  readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Company Name</label>
                  <div class="col-sm-6">
                    <input id="tx_viewproject_Company" name="tx_viewproject_Company" type="text" class="form-control" value="<?php echo $row_View['Company']; ?>"  readonly>
                  </div>
                  <label class="col-sm-1 control-label">Telp</label>
                  <div class="col-sm-3">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-phone"></i>
                      </div>
                      <input id="tx_viewproject_CompPhone" name="tx_viewproject_CompPhone" type="text" class="form-control" value="<?php echo $row_View['CompPhone']; ?>"  readonly>
                    </div>
                  </div>
                </div>
                <hr>
                <div class="form-group">
                  <label class="col-sm-2 control-label">CP</label>
                  <div class="col-sm-6">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-user"></i>
                      </div>
                      <input id="tx_viewproject_Customer" name="tx_viewproject_Customer" type="text" class="form-control" value="<?php echo $row_View['Customer']; ?>"  readonly>
                    </div>
                  </div>
                  <label class="col-sm-1 control-label">Telp</label>
                  <div class="col-sm-3">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-phone"></i>
                      </div>
                      <input id="tx_viewproject_CustPhone" name="tx_viewproject_CustPhone" type="text" class="form-control" value="<?php echo $row_View['CustPhone']; ?>"  readonly>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <a href="EditProject.php?Id=<?php echo $row_View['Id']; ?>"><button type="button" class="btn btn-info pull-right">Edit</button></a>
                <div class="btn-group"><a href="Project.php"><button type="button" class="btn btn-default pull-left">Back</button></a></div>
                <div class="btn-group" ><a href="DeleteProject.php?PCode=<?php echo $row_View['Id']; ?>" onclick="return confirm('Delete Project?')"><button id="delete_button" type="button" <?php if ($row_check['result'] == 1) { ?>class="btn btn-default pull-left" disabled<?php } else { ?> class="btn btn-danger pull-left" <?php } ?>>Delete</button></a></div>
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
mysql_free_result($check);
mysql_free_result($View);
?>