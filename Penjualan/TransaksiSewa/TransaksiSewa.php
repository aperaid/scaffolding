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
	$query_TransaksiSewa = "SELECT * FROM jualtransaksisewa ORDER BY Id ASC";
	$TransaksiSewa = mysql_query($query_TransaksiSewa, $Connection) or die(mysql_error());
	$row_TransaksiSewa = mysql_fetch_assoc($TransaksiSewa);
	$totalRows_TransaksiSewa = mysql_num_rows($TransaksiSewa);
	
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
<script type='text/javascript'>
	var jq1113 = jQuery.noConflict();
</script>
<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="../../JQuery/datatable/js/jquery.dataTables.js"></script>
<script>
	jq1113(document).ready( function () {
	    jq1113('#contentTable').DataTable();
	} );
	
</script>
<!------------------------------------------------------->
<link rel="stylesheet" href="../../JQuery/cssmenu/styles.css">
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script src="../../JQuery/cssmenu/script.js"></script>
<!------------------------------------------------------->
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>PT. BDN | Transaksi Claim</title>
		<link href="../../Button.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div class="ui-layout-north" onmouseover="myLayout.allowOverflow('north')" onmouseout="myLayout.resetOverflow(this)">
			
            
            <div class="title">
				TRANSAKSI SEWA
			</div>
		</div>
		<div class="ui-layout-west">
			<div id='cssmenu'>
            <ul>
               <li><a href='#'><span>Home</span></a></li>
               <?php do { ?>    
                	<li>                    
                    <a href="../<?php echo $row_menu['link']; ?>">
                    <span>
					<?php echo $row_menu['nama']; ?></span></a>
                    </li>                    
                <?php } while ($row_menu = mysql_fetch_assoc($menu)); ?>
            </ul>
            </div>
            
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
		<!--div class="ui-layout-south">
			<div class="footer">PT. Berlian Djaya Nusantara</div>
			<div class="copyright">Powered by Apera ERP</div>
		</div-->
		<!--div class="ui-layout-east">
			<table class="menuTable">
				<tr>
					<td><a href="InsertTransaksiSewa.php"><button type="button" class="button2">Insert</button></a></td>
				</tr>
			</table>
		</div-->
		<div class="ui-layout-center">
			<table id="contentTable" class="display">
				<thead>
					<th>No</th>
					<th>TglStart</th>
					<th>TglEnd</th>
					<th>Customer</th>
					<th>JSC</th>
					<th>Project</th>
					<th>Amount</th>
					<th>Status</th>
					<th>Opsi</th>
				</thead>
				<tbody>
					<?php do { ?>
					<tr>
						<td><?php echo $row_TransaksiSewa['No']; ?></td>
						<td><?php echo $row_TransaksiSewa['TglStart']; ?></td>
						<td><?php echo $row_TransaksiSewa['TglEnd']; ?></td>
						<td><?php echo $row_TransaksiSewa['Customer']; ?></td>
						<td><?php echo $row_TransaksiSewa['JSC']; ?></td>
						<td><?php echo $row_TransaksiSewa['Project']; ?></td>
						<td><?php echo $row_TransaksiSewa['Amount']; ?></td>
						<td><?php echo $row_TransaksiSewa['Status']; ?></td>
						<td><a href="ViewTransaksiSewa.php?Id=<?php echo $row_TransaksiSewa['Id']; ?>"><button type="button" class="button3">View</button></a>-<a href="DeleteTransaksiSewa.php?Id=<?php echo $row_TransaksiSewa['Id']; ?>"><button type="button" class="button3">Delete</button></a></td>
					</tr>
					<?php } while ($row_TransaksiSewa = mysql_fetch_assoc($TransaksiSewa)); ?>
				</tbody>
			</table>
		</div>
	</body>
</html>
<?php
	mysql_free_result($TransaksiSewa);
	?>