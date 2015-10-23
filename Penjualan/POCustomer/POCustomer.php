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
	$query_POCustomer = "SELECT * FROM jualpocustomer ORDER BY Id ASC";
	$POCustomer = mysql_query($query_POCustomer, $Connection) or die(mysql_error());
	$row_POCustomer = mysql_fetch_assoc($POCustomer);
	$totalRows_POCustomer = mysql_num_rows($POCustomer);
	
	$query_menu = "SELECT * FROM menu ORDER BY Id ASC";
	$menu = mysql_query($query_menu, $Connection) or die(mysql_error());
	$row_menu = mysql_fetch_assoc($menu);
	$totalRows_menu = mysql_num_rows($menu);	
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
		<title>PT. BDN | PO Customer</title>
		<link href="../../Button.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div class="ui-layout-north">
			<div class="title">
				PO CUSTOMER
			</div>
		</div>
		<div class="ui-layout-west">
			<table class="menuTable">
					
				<?php do { ?>    
                	<tr>
                    <td class="Menu">
                    <a href="../<?php echo $row_menu['link']; ?>">
                    <button type="button" class="button">
					<?php echo $row_menu['nama']; ?></button></a></td>
                    </tr>
                    
                <?php } while ($row_menu = mysql_fetch_assoc($menu)); ?>
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
						<a href="InsertPOCustomer.php"><button class="button2" type=
							"button">Insert</button></a>
					</td>
				</tr>
			</table>
		</div>
		<div class="ui-layout-center">
			<table id="contentTable">
				<thead>
					<th >No. Invoice</th>
					<th >Tgl Jual</th>
					<th >Customer</th>
					<th >J/S/C</th>
					<th >Project</th>
					<th >Amount</th>
					<th >Status</th>
					<th >Opsi</th>
				</thead>
				<tbody>
					<?php do { ?>
					<tr>
						<td ><?php echo $row_POCustomer['Reference']; ?></td>
						<td ><?php echo $row_POCustomer['Tgl']; ?></td>
						<td ><?php echo $row_POCustomer['Customer']; ?></td>
						<td ><?php echo $row_POCustomer['JS']; ?></td>
						<td ><?php echo $row_POCustomer['Project']; ?></td>
						<td ><?php echo $row_POCustomer['Amount']; ?></td>
						<td ><?php echo $row_POCustomer['Status']; ?></td>
						<td ><a href="ViewPOCustomer.php?Id=<?php echo $row_POCustomer['Id']; ?>"><button type="button" class="button3">View</button></a>-<a href="DeletePOCustomer.php?Id=<?php echo $row_POCustomer['Id']; ?>"><button type="button" class="button3">Delete</button></a></td>
					</tr>
					<?php } while ($row_POCustomer = mysql_fetch_assoc($POCustomer)); ?>
					<tr>
						<td class="noinvoice">&nbsp;</td>
						<td class="tanggal">&nbsp;</td>
						<td class="customer">&nbsp;</td>
						<td class="JSC">&nbsp;</td>
						<td class="project">&nbsp;</td>
						<td class="amount">&nbsp;</td>
						<td class="status">&nbsp;</td>
						<td class="tombol">&nbsp;</td>
					</tr>
				</tbody>
			</table>
		</div>
	</body>
</html>
<?php
	mysql_free_result($POCustomer);
	?>