<?php require_once('../../Connections/Connection.php'); ?>
<?php
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
	$query_SuratJalan = "SELECT * FROM jualsuratjalan ORDER BY Id ASC";
	$SuratJalan = mysql_query($query_SuratJalan, $Connection) or die(mysql_error());
	$row_SuratJalan = mysql_fetch_assoc($SuratJalan);
	$totalRows_SuratJalan = mysql_num_rows($SuratJalan);
		
	$query_menu = "SELECT * FROM menu ORDER BY Id ASC";
	$menu = mysql_query($query_menu, $Connection) or die(mysql_error());
	$row_menu = mysql_fetch_assoc($menu);
	$totalRows_menu = mysql_num_rows($menu);
	
	mysql_select_db($database_Connection, $Connection);
	$query_Menu = "SELECT * FROM menu";
	$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
	$row_Menu = mysql_fetch_assoc($Menu);
	$totalRows_Menu = mysql_num_rows($Menu);
	?>
<!------------------------------------------------------->
<link rel="stylesheet" type="text/css" href="../../JQuery/Layout/layout.css">
<script type="text/javascript" src="../../JQuery/Layout/jquery.js"></script>
<script type="text/javascript" src="../../JQuery/Layout/jquery.ui.all.js"></script>
<script type="text/javascript" src="../../JQuery/Layout/jquery.layout.js"></script>
<script type='text/javascript'>
	var jq126 = jQuery.noConflict();
</script>
<script type="text/javascript">
	var myLayout;// a var is required because this page utilizes: myLayout.allowOverflow() method
	
	jq126(document).ready(function () {
	myLayout = jq126('body').layout({
		// enable showOverflow on west-pane so popups will overlap north pane
		west__showOverflowOnHover: true
	
	//,	west__fxSettings_open: { easing: "easeOutBounce", duration: 750 }
	});
	
	});
	
</script>
<!------------------------------------------------------->
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="../../JQuery/DataTable/css/jquery.dataTables.css">
<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="../../JQuery/DataTable/js/jquery.js"></script>
<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="../../JQuery/datatable/js/jquery.dataTables.js"></script>
<script>
	$(document).ready( function () {
	    $('#contentTable').DataTable();
	} );
	
</script>
<!------------------------------------------------------->
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>PT. BDN | Surat Jalan</title>
		<link href="../../Button.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div class="ui-layout-north">
			<div class="title">
				Surat Jalan
			</div>
		</div>
		<div class="ui-layout-west">
			<table class="menuTable">
					
				<?php do { ?>    
                	<tr>
                    <td class="Menu">
                    <a href="../<?php echo $row_Menu['link']; ?>">
                    <button type="button" class="button">
					<?php echo $row_Menu['nama']; ?></button></a></td>
                    </tr>
                    
                <?php } while ($row_Menu = mysql_fetch_assoc($Menu)); ?>
                    <tr>
                    <td class="Menu">&nbsp;</td>
                    </tr>
                    
			</table>
		</div>
		<div class="ui-layout-south">
			<div class="footer">
				PT. Berlian Djaya Nusantara
			</div>
			<div class="copyright">
				Powered by Apera ERP
			</div>
		</div>
		<div class="ui-layout-east">
			<table class="menuTable">
				<tr>
					<td>
						<a href="InsertSuratJalan.php"><button class="button2" type=
							"button">Insert</button></a>
					</td>
				</tr>
			</table>
		</div>
		<div class="ui-layout-center">
			<table id="contentTable">
				<thead>
					<th class="noinvoice">No. Invoice</th>
					<th class="tanggal">Tgl Jual</th>
					<th class="noinvoice">No. Customer</th>
					<th class="customer">Customer</th>
					<th class="noinvoice">No. Project</th>
					<th class="project">Project</th>
					<th class="status">Status</th>
					<th class="status">SJ/PB</th>
					<th class="tombol">Opsi</th>
				</thead>
				<tbody>
					<?php do { ?>
					<tr>
						<td class="noinvoice"><?php echo $row_SuratJalan['No']; ?></td>
						<td class="tanggal"><?php echo $row_SuratJalan['Tgl']; ?></td>
						<td class="noinvoice"><?php echo $row_SuratJalan['NoCustomer']; ?></td>
						<td class="customer"><?php echo $row_SuratJalan['Customer']; ?></td>
						<td class="noinvoice"><?php echo $row_SuratJalan['NoProject']; ?></td>
						<td class="project"><?php echo $row_SuratJalan['Project']; ?></td>
						<td class="status"><?php echo $row_SuratJalan['Status']; ?></td>
						<td class="status"><?php echo $row_SuratJalan['SJPB']; ?></td>
						<td class="tombol"><a href="ViewSuratJalan.php?Id=<?php echo $row_SuratJalan['Id']; ?>"><button type="button" class="button3">View</button></a>-<a href="DeleteSuratJalan.php?Id=<?php echo $row_SuratJalan['Id']; ?>"><button type="button" class="button3">Delete</button></a></td>
					</tr>
					<?php } while ($row_SuratJalan = mysql_fetch_assoc($SuratJalan)); ?>
				</tbody>
			</table>
		</div>
	</body>
</html>
<?php
	mysql_free_result($SuratJalan);
	?>