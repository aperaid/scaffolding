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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$colname_View = "-1";
if (isset($_GET['Reference'])) {
  $colname_View = $_GET['Reference'];
  $colname_ViewInvoice = $_GET['Invoice'];
}
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT invoice.*, project.Project, customer.Company FROM invoice INNER JOIN pocustomer ON invoice.Reference=pocustomer.Reference INNER JOIN project ON pocustomer.PCode=project.PCode INNER JOIN customer ON project.CCode=customer.CCode WHERE invoice.Reference = %s AND invoice.Invoice = %s", GetSQLValueString($colname_View, "text"), GetSQLValueString($colname_ViewInvoice, "text"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);

$colname_Count = "-1";
if (isset($_GET['Reference'])) {
  $colname_Count = $_GET['Reference'];
  $colname_ViewJS = $_GET['JS'];
  $colname_ViewInvoice = $_GET['Invoice'];
}

$colname_View2 = "-1";
if (isset($_GET['JS'])) {
  $colname_View2 = $_GET['Reference'];
  $colname_ViewJS = $_GET['JS'];
}
mysql_select_db($database_Connection, $Connection);
$query_View2 = sprintf("SELECT isisjkirim.QKirim, sjkirim.SJKir, transaksi.Purchase, transaksi.Barang, sjkirim.Tgl AS S, transaksi.Amount FROM transaksi RIGHT JOIN isisjkirim ON isisjkirim.Purchase = transaksi.Purchase LEFT JOIN sjkirim ON sjkirim.SJKir = isisjkirim.SJKir WHERE transaksi.Reference = %s AND transaksi.JS = %s", GetSQLValueString($colname_View2, "text"),
GetSQLValueString($colname_ViewJS, "text"));
$View2 = mysql_query($query_View2, $Connection) or die(mysql_error());
$row_View2 = mysql_fetch_assoc($View2);

if (isset($_GET['totalRows_View2'])) {
  $totalRows_View2 = $_GET['totalRows_View2'];
} else {
  $all_View2 = mysql_query($query_View2);
  $totalRows_View2 = mysql_num_rows($all_View2);
}

mysql_select_db($database_Connection, $Connection);
$query_Menu = "SELECT * FROM menu";
$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
$row_Menu = mysql_fetch_assoc($Menu);
$totalRows_Menu = mysql_num_rows($Menu);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE invoice SET PPN=%s, Transport=%s WHERE Invoice=%s",
                       GetSQLValueString($_POST['PPN'], "int"),
                       GetSQLValueString($_POST['Transport'], "text"),
                       GetSQLValueString($_POST['Invoice'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());

  $updateGoTo = "ViewInvoiceJual.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
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

<script language="javascript">
  function ppn() {
    var txtFirstNumberValue = document.getElementById('hd_viewinvoicejual_Totals2').value;
    var txtSecondNumberValue = document.getElementById('tx_viewinvoicejual_PPN').value;
	var result = (parseFloat(txtFirstNumberValue) * parseFloat(txtSecondNumberValue)*0.1)+parseFloat(txtFirstNumberValue);
	  if (!isNaN(result)) {
		document.getElementById('tx_viewinvoicejual_Totals').value = result;
      }
   }
</script>

<script language="javascript">
  function transport() {
    var txtFirstNumberValue = document.getElementById('hd_viewinvoicejual_Totals2').value;
    var txtSecondNumberValue = document.getElementById('tx_viewinvoicejual_Transport').value;
	var result = parseFloat(txtFirstNumberValue) + parseFloat(txtSecondNumberValue);
	  if (!isNaN(result)) {
		document.getElementById('tx_viewinvoicejual_Totals').value = result;
      }
   }
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

<div class="ui-layout-north">


      <h2 align="center"><?php echo $row_View['Reference']; ?></h2>
    </div>
<div class="ui-layout-center">

<form action="<?php echo $editFormAction; ?>" id="fm_viewinvoicejual_form1" name="form1" method="POST">
  <table width="1000" border="0">
    <tbody>
      <tr>
        <th width="162">&nbsp;</th>
        <th width="87" align="right">No. Invoice</th>
        <th width="128" align="right">&nbsp;</th>
        <td width="605"><input name="Invoice" type="text" class="textview" id="tx_viewinvoicejual_Invoice" value="<?php echo $row_View['Invoice']; ?>" readonly></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Project</th>
        <th align="right">&nbsp;</th>
        <td><input name="TglStart" type="text" class="textview" id="tx_viewinvoicejual_TglStart" value="<?php echo $row_View['Project']; ?>" readonly></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Company</th>
        <th align="right">&nbsp;</th>
        <td><input name="TglEnd" type="text" class="textview" id="tx_viewinvoicejual_TglEnd" value="<?php echo $row_View['Company']; ?>" readonly></td>
      </tr>
    </tbody>
  </table>
  <table width="1000" border="0">
    <tbody>
      <tr>
        <th align="center">SJ Kirim</th>
        <th align="center">No. Purchase</th>
        <th align="center">Item</th>
        <th>Quantity Kirim</th>
        <th>Amount</th>
        <th>Total</th>
        </tr>
      <?php 
	  $total = 0;
	  do { ?>
      
        <tr>
          <td align="center"><input name="SJKir" type="text" class="textview" id="tx_viewinvoicejual_SJKir" value="<?php echo $row_View2['SJKir']; ?>" readonly></td>
          <td align="center"><input name="Purchase" type="text" class="textview" id="tx_viewinvoicejual_Purchase" value="<?php echo $row_View2['Purchase']; ?>" readonly></td>
          <td align="center"><input name="Barang" type="text" class="textview" id="tx_viewinvoicejual_Barang" value="<?php echo $row_View2['Barang']; ?>" readonly></td>
          <td align="center"><input name="Quantity" type="text" class="textview" id="tx_viewinvoicejual_Quantity" value="<?php echo $row_View2['QKirim']; ?>" readonly></td>
          <td align="center"><input name="Amount" type="text" class="textview" id="tx_viewinvoicejual_Amount" value="<?php echo $row_View2['Amount']; ?>" readonly></td>
          <?php $test = $row_View2['QKirim']* $row_View2['Amount']; $total += $test ?>
          <td align="center"><input name="Total" type="text" class="textview" id="tx_viewinvoicejual_Total" value="<?php echo round($test, 2) ?>" readonly></td>
        </tr>
      <?php } while ($row_View2 = mysql_fetch_assoc($View2)); ?>
    </tbody>
</table>
  <table width="1000" border="0">
    <tbody>
      <tr>
        <th width="160">&nbsp;</th>
        <th width="90" align="right">Pajak</th>
        <th width="129" align="right">&nbsp;</th>
        <td colspan="2"><input name="PPN" type="text" class="textbox" id="tx_viewinvoicejual_PPN" autocomplete="off" value="<?php echo $row_View['PPN']; ?>" onKeyUp="ppn()"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Transport</th>
        <th align="right">&nbsp;</th>
        <td colspan="2"><input name="Transport" type="text" class="textbox" id="tx_viewinvoicejual_Transport" autocomplete="off" value="<?php echo $row_View['Transport']; ?>" onKeyUp="transport()"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Total</th>
        <th align="right">&nbsp;</th>
        <input name="Totals2" type="hidden" id="hd_viewinvoicejual_Totals2" value="<?php echo round($total, 2); ?>" >
        <td colspan="2"><input name="Total" type="text" class="textview" id="tx_viewinvoicejual_Totals" value="<?php echo round($total, 2); ?>" readonly></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td colspan="2" align="center">&nbsp;</td>
        <td width="153">&nbsp;</td>
        <td width="446">&nbsp;</td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td colspan="2" align="right"><a>
          <button type="button" class="button2">Print</button>
        </a></td>
        <td align="center"><input type="submit" name="submit" id="bt_viewinvoicejual_submit" class="button2" value="Update"></td>
        <td><a href="InvoiceJual.php">
          <button type="button" class="button2">Cancel</button>
        </a></td>
      </tr>
    </tbody>
</table>
  <p>&nbsp;</p>
  <input type="hidden" name="MM_update" value="form1">
</form>
</div>
</body>
</html>
<?php
  mysql_free_result($View);
  mysql_free_result($View2);
?>
