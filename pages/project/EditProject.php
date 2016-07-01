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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "fm_editproject_form1")) {
  $updateSQL = sprintf("select edit_project(%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tx_editproject_PCode'], "text"),
                       GetSQLValueString($_POST['tx_editproject_Project'], "text"),
                       GetSQLValueString($_POST['tx_editproject_Alamat'], "text"),
                       GetSQLValueString($_POST['tx_editproject_CCode'], "text"),
                       GetSQLValueString($_POST['hd_editproject_Id'], "int"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());

  $updateGoTo = "ViewProject.php";
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
$query_Edit = sprintf("SELECT * FROM project WHERE Id = %s", GetSQLValueString($colname_Edit, "text"));
$Edit = mysql_query($query_Edit, $Connection) or die(mysql_error());
$row_Edit = mysql_fetch_assoc($Edit);
$totalRows_Edit = mysql_num_rows($Edit);

//FUNCTION BUTTON DISABLE
$check_pcode = $row_Edit['PCode'];
mysql_select_db($database_Connection, $Connection);
$query_check = sprintf("SELECT check_project('$check_pcode') AS result");
$check = mysql_query($query_check, $Connection) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);
//FUNCTION BUTTON DISABLE END
?>

<?php
// Declare Root directory
$PAGE="Edit Project";
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
        <small>Edit</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="project.php">Project</a></li>
        <li class="active">Edit Project</li>
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
            <form action="<?php echo $editFormAction; ?>" id="fm_editproject_form1" name="fm_editproject_form1" method="post" class="form-horizontal">
              <div class="box-body with-border">
                <div class="form-group">
                  <input name="hd_editproject_Id" type="hidden" id="hd_editproject_Id" value="<?php echo $row_Edit['Id']; ?>">
                  <label class="col-sm-2 control-label">Project Code</label>
                  <div class="col-sm-6">
                    <input id="tx_editproject_PCode" name="tx_editproject_PCode" type="text" autocomplete="off" onKeyUp="capital()" class="form-control" placeholder="Project Code" value="<?php echo $row_Edit['PCode']; ?>" maxlength="5" required <?php if ($row_check['result'] == 1) { ?> readonly <?php } ?>>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Project Name</label>
                  <div class="col-sm-4">
                    <input id="tx_editproject_Project" name="tx_editproject_Project" type="text" autocomplete="off" onKeyUp="capital()" class="form-control" placeholder="Nama Project" value="<?php echo $row_Edit['Project']; ?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Alamat Project</label>
                  <div class="col-sm-4">
                    <input id="tx_editproject_Alamat" name="tx_editproject_Alamat" type="text" autocomplete="off" onKeyUp="capital()" class="form-control" placeholder="Jl. Nama Jalan 1A No.10, Kelurahan, Kecamatan, Kota" value="<?php echo $row_Edit['Alamat']; ?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Company Code</label>
                  <div class="col-sm-4">
                    <input id="tx_editproject_CCode" name="tx_editproject_CCode" type="text" onKeyUp="capital()" class="form-control" placeholder="Company Code" value="<?php echo $row_Edit['CCode']; ?>" maxlength="5" required>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" name="bt_editproject_submit" id="bt_editproject_submit" class="btn btn-info pull-right">Update</button>
                <div class="btn-group"><a href="ViewProject.php?Id=<?php echo $row_Edit['Id']; ?>"><button type="button" class="btn btn-default pull-left">Cancel</button></a></div>
              </div>
              <input type="hidden" name="MM_update" value="fm_editproject_form1">
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
    var x = document.getElementById("tx_editproject_PCode");
    x.value = x.value.toUpperCase();
	var x = document.getElementById("tx_editproject_Project");
    x.value = x.value.toUpperCase();
	var x = document.getElementById("tx_editproject_CCode");
    x.value = x.value.toUpperCase();
}
</script>
<script>
$(function() {
    var availableTags = <?php include ("../autocomplete2.php");?>;
    $( "#tx_editproject_CCode" ).autocomplete({
      source: availableTags,
	  autoFocus: true
    });
  });
</script>
<?php
mysql_free_result($Edit);
?>
