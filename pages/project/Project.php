<?php require_once('../../connections/Connection.php'); ?>
<?php
// Declare Root directory
$ROOT="../../";

include($ROOT . "pages/login/session.php");
include_once($ROOT . "pages/functionphp.php");

mysql_select_db($database_Connection, $Connection);
$query_Project = "SELECT * FROM project ORDER BY Id ASC";
$Project = mysql_query($query_Project, $Connection) or die(mysql_error());
$row_Project = mysql_fetch_assoc($Project);
$totalRows_Project = mysql_num_rows($Project);
?>

<?php
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
        <small>All</small>
        <large><a href="InsertProject.php"><button type="button" class="btn btn-success btn-sm">New Project</button></a></large>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">Project</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-body">
              <table id="tb_project" class="table table-bordered table-striped table-responsive">
                <thead>
                <tr>
                  <th>Project ID</th>
                  <th>Project Code</th>
                  <th>Project Name</th>
                </tr>
                </thead>                
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
	var table = $("#tb_project").DataTable({
		"paging": false,
		"processing": true,
		"serverSide": true,
		"sAjaxSource": "project_table.php",
		"scrollY": "100%",
		"columnDefs":[
			{
				"targets": [0],
				"visible": false,
				"searchable": false
			}
		],
		"order": [[0, "desc"]]
		});
	$('#tb_project').on('click', 'tr', function () {
			var data = table.row( this ).data();
			window.open("viewproject.php?Id="+ data[0],"_self");
		} );
  });
</script>
<?php
mysql_free_result($Project);
?>