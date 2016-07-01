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
  $insertSQL = sprintf("INSERT INTO project (PCode, Project, Alamat, CCode) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['tx_insertproject_PCode'], "text"),
                       GetSQLValueString($_POST['tx_insertproject_Project'], "text"),
					   GetSQLValueString($_POST['tx_insertproject_Alamat'], "text"),
                       GetSQLValueString($_POST['tx_insertproject_CCode'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "Project.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_View = "-1";
if (isset($_GET['Id'])) {
  $colname_View = $_GET['Id'];
}

?>

<?php
$PAGE="Insert Project";
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
        <small>Insert</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="project.php">Project</a></li>
        <li class="active">Insert Project</li>
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
            <form action="<?php echo $editFormAction; ?>" id="fm_insertproject_form1" name="fm_insertproject_form1" method="post" class="form-horizontal">
              <div class="box-body with-border">
                <div class="form-group">
                  <label class="col-sm-2 control-label">Project Code</label>
                  <div class="col-sm-6">
                    <input id="tx_insertproject_PCode" name="tx_insertproject_PCode" type="text" autocomplete="off" onKeyUp="capital()" class="form-control" placeholder="Project Code" maxlength="5" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Project Name</label>
                  <div class="col-sm-4">
                    <input id="tx_insertproject_Project" name="tx_insertproject_Project" type="text" autocomplete="off" onKeyUp="capital()" class="form-control" placeholder="Nama Project" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Alamat Project</label>
                  <div class="col-sm-4">
                    <input id="tx_insertproject_Alamat" name="tx_insertproject_Alamat" type="text" autocomplete="off" class="form-control" placeholder="Jl. Nama Jalan 1A No.10, Kelurahan, Kecamatan, Kota" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Company Code</label>
                  <div class="col-sm-4">
                    <input id="tx_insertproject_CCode" name="tx_insertproject_CCode" type="text" autocomplete="off" onKeyUp="capital()" class="form-control" placeholder="Company Code" maxlength="5" required>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" name="bt_insertproject_submit" id="bt_insertproject_submit" class="btn btn-info pull-right">Insert</button>
                <div class="btn-group"><a href="Project.php"><button type="button" class="btn btn-default pull-left">Cancel</button></a></div>
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
    var x = document.getElementById("tx_insertproject_PCode");
    x.value = x.value.toUpperCase();
	var x = document.getElementById("tx_insertproject_Project");
    x.value = x.value.toUpperCase();
	var x = document.getElementById("tx_insertproject_CCode");
    x.value = x.value.toUpperCase();
}
</script>
<script>
$(function() {
    var availableTags = <?php include ("../autocomplete2.php");?>;
    $( "#tx_insertproject_CCode" ).autocomplete({
      source: availableTags,
	  autoFocus: true
    });
  });
</script>
</script>