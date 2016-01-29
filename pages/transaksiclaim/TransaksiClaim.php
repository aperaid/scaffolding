<?php require_once('../../connections/Connection.php'); ?>
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
	
$colname_TransaksiClaim = "-1";
if (isset($_GET['Reference'])) {
  $colname_TransaksiClaim = $_GET['Reference'];
}
	
mysql_select_db($database_Connection, $Connection);
$query_TransaksiClaim = "SELECT transaksiclaim.*, transaksi.Barang, transaksi.QSisaKem, project.Project FROM transaksiclaim INNER JOIN transaksi ON transaksiclaim.Purchase=transaksi.Purchase INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode ORDER BY transaksiclaim.Id ASC";
$TransaksiClaim = mysql_query($query_TransaksiClaim, $Connection) or die(mysql_error());
$row_TransaksiClaim = mysql_fetch_assoc($TransaksiClaim);
$totalRows_TransaksiClaim = mysql_num_rows($TransaksiClaim);
	
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
	    $('#tb_transaksiclaim_contentTable').DataTable();
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
				TRANSAKSI CLAIM</div>
		</div>
		<div class="ui-layout-west">
			<table class="menuTable">
					
				<?php do { ?>    
                	<tr>
                    <td class="Menu">
                    <a href="../../<?php echo $row_Menu['link']; ?>">
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
						<a href="InsertTransaksiClaim.php">
						<button class="button2" type=
							"button">Tambah Claim</button></a>
					</td>
				</tr>
			</table>
		</div>
		<div class="ui-layout-center">
			<table id="tb_transaksiclaim_contentTable">
				<thead>
					<tr>
					<th class="noinvoice">No. Claim</th>
					<th class="noinvoice">Tanggal Claim</th>
					<th class="customer">Project</th>
					<th class="customer">Amount</th>
					<th class="customer">Quantity Claim</th>
					<th class="tombol">Opsi</th>
				</thead>
				<tbody>
					<?php do { ?>
					<tr>
						<td align="center" class="noinvoice"><?php echo $row_TransaksiClaim['Claim']; ?></td>
						<td align="center" class="noinvoice"><?php echo $row_TransaksiClaim['Tgl']; ?></td>
						<td align="center" class="customer"><?php echo $row_TransaksiClaim['Project']; ?></td>
						<td align="center" class="customer"><?php echo $row_TransaksiClaim['Amount']; ?></td>
						<td align="center" class="customer"><?php echo $row_TransaksiClaim['QClaim']; ?></td>
					  <td align="center" class="tombol"><a href="DeleteTransaksiClaim.php?Id=<?php echo $row_TransaksiClaim['Id']; ?> "><button type="button" class="button3">Batal</button></a></td>
					</tr>
					<?php } while ($row_TransaksiClaim = mysql_fetch_assoc($TransaksiClaim)); ?>
				</tbody>
			</table>
		</div>
	</body>
</html>
<?php
	mysql_free_result($Menu);

	mysql_free_result($TransaksiClaim);
	?>