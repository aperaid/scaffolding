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
$query_SJKembali = "SELECT sjkembali.*, project.Project, customer.Customer FROM sjkembali INNER JOIN pocustomer ON sjkembali.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode INNER JOIN customer ON project.CCode=customer.CCode ORDER BY sjkembali.Id ASC";
$SJKembali = mysql_query($query_SJKembali, $Connection) or die(mysql_error());
$row_SJKembali = mysql_fetch_assoc($SJKembali);
$totalRows_SJKembali = mysql_num_rows($SJKembali);
	
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
				SURAT JALAN
			KEMBALI</div>
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
						<a href="InsertSJKembali.php">
						<button class="button2" type=
							"button">Tambah Kembali</button></a>
					</td>
				</tr>
			</table>
		</div>
		<div class="ui-layout-center">
			<table id="contentTable">
				<thead>
					<th class="noinvoice">No. SJ</th>
					<th class="noinvoice">Tanggal Tertanda</th>
					<th class="customer">Customer</th>
					<th class="noinvoice">Project</th>
					<th class="tombol">Opsi</th>
				</thead>
				<tbody>
					<?php do { ?>
					<tr>
						<td class="noinvoice"><?php echo $row_SJKembali['SJKem']; ?></td>
						<td class="noinvoice"><?php echo $row_SJKembali['Tgl']; ?></td>
						<td class="customer"><?php echo $row_SJKembali['Customer']; ?></td>
						<td class="noinvoice"><?php echo $row_SJKembali['Project']; ?></td>
						<td class="tombol"><a href="ViewSJKembali.php?SJKem=<?php echo $row_SJKembali['SJKem']; ?>"><button type="button" class="button3">View</button></a>&nbsp;<a href="DeleteSJKembali.php?SJKem=<?php echo $row_SJKembali['SJKem']; ?>"><button type="button" class="button3">Batal</button></a></td>
					</tr>
					<?php } while ($row_SJKembali = mysql_fetch_assoc($SJKembali)); ?>
				</tbody>
			</table>
		</div>
	</body>
</html>
<?php
	mysql_free_result($Menu);

	mysql_free_result($SJKembali);
	?>