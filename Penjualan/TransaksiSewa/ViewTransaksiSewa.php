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

$colname_View = "-1";
if (isset($_GET['Id'])) {
  $colname_View = $_GET['Id'];
}
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT transaksi.*, project.Project, customer.Customer FROM transaksi INNER JOIN pocustomer ON transaksi.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode INNER JOIN customer ON project.CCode=customer.CCode WHERE transaksi.Id = %s", GetSQLValueString($colname_View, "int"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);

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
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link href="../../Button.css" rel="stylesheet" type="text/css">
<style type="text/css">
body {
	background-image: url(../../Image/Wood.png);
	background-repeat: no-repeat;
}
</style>

</head>

<body>


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

<div class="ui-layout-north">


      <h2 align="center"><?php echo $row_View['Barang']; ?></h2>
    </div>
<div class="ui-layout-center">

<form id="form1" name="form1" method="POST">
  <table width="1000" border="0">
    <tbody>
      <tr>
        <th width="250">&nbsp;</th>
        <th width="100" align="right">No. Purchase</th>
        <th width="75" align="right">&nbsp;</th>
        <td width="557" colspan="2"><input name="Invoice" type="text" class="textview" id="Invoice" value="<?php echo $row_View['Purchase']; ?>" readonly></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Project</th>
        <th align="right">&nbsp;</th>
        <td colspan="2"><input name="TglStart" type="text" class="textview" id="TglStart" value="<?php echo $row_View['Project']; ?>" readonly></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Customer</th>
        <th align="right">&nbsp;</th>
        <td colspan="2"><input name="TglEnd" type="text" class="textview" id="TglEnd" value="<?php echo $row_View['Customer']; ?>" readonly></td>
      </tr>
	   <tr>
        <th>&nbsp;</th>
        <th align="right">J/S</th>
        <th align="right">&nbsp;</th>
        <td colspan="2"><input name="Nama" type="text" class="textview" id="Nama" value="<?php echo $row_View['JS']; ?>" readonly></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Barang</th>
        <th align="right">&nbsp;</th>
        <td colspan="2"><input name="Quantity" type="text" class="textview" id="Quantity" value="<?php echo $row_View['Barang']; ?>" readonly></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Quantity</th>
        <th align="right">&nbsp;</th>
        <td colspan="2"><input name="Customer" type="text" class="textview" id="Customer" value="<?php echo $row_View['Quantity']; ?>" readonly></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Amount</th>
        <th align="right">&nbsp;</th>
        <td colspan="2"><input name="JSC" type="text" id="JSC" class="textview" value="<?php echo $row_View['Amount']; ?>" readonly></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Tanggal Sewa</th>
        <th align="right">&nbsp;</th>
        <td colspan="2"><input name="Project" type="text" class="textview" id="Project" value="<?php echo $row_View['TglStart']; ?>" readonly></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Reference</th>
        <th align="right">&nbsp;</th>
        <td colspan="2"><input name="Amount" type="text" class="textview" id="Amount" value="<?php echo $row_View['Reference']; ?>" readonly></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Status</th>
        <th align="right">&nbsp;</th>
        <td colspan="2"><input name="Status" type="text" class="textview" id="Status" value="<?php echo $row_View['Status']; ?>" readonly></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td colspan="2" align="center"><a><button type="button" class="button2">Print</button></a></td>
        <td width="155"><a href="TransaksiSewa.php"><button type="button" class="button2">Cancel</button></a></td>
        <td width="415">&nbsp;</td>
      </tr>
    </tbody>
  </table>
</form>
</div>
</body>
</html>
<?php
mysql_free_result($View);
?>
