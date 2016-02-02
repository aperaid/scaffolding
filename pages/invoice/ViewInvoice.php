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
  $colname_ViewPeriode = $_GET['Periode'];
}

mysql_select_db($database_Connection, $Connection);
$query_View2 = sprintf("SELECT sjkirim.SJKir, transaksi.Purchase, transaksi.Barang, periode.Quantity, periode.S, periode.E, periode.SJKem, transaksi.Amount FROM periode LEFT JOIN isisjkirim ON periode.IsiSJKir=isisjkirim.IsiSJKir 
LEFT JOIN transaksi ON isisjkirim.Purchase=transaksi.Purchase LEFT JOIN sjkirim ON isisjkirim.SJKir=sjkirim.SJKir
WHERE transaksi.Reference = %s AND transaksi.JS = %s AND periode.Periode = %s ORDER BY periode.Id ASC", GetSQLValueString($colname_View2, "text"),
GetSQLValueString($colname_ViewJS, "text"),
GetSQLValueString($colname_ViewPeriode, "text"));
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

  $updateGoTo = "ViewInvoice.php";
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
  function tot() {
    var txtFirstNumberValue = document.getElementById('tx_viewinvoice_Totals2').value;
    var txtSecondNumberValue = document.getElementById('tx_viewinvoice_PPN').value;
	var txtThirdNumberValue = document.getElementById('tx_viewinvoice_Transport').value;
	var result = (parseFloat(txtFirstNumberValue) * parseFloat(txtSecondNumberValue)*0.1)+parseFloat(txtFirstNumberValue) + parseFloat(txtThirdNumberValue);
	  if (!isNaN(result)) {
		document.getElementById('tx_viewinvoice_Totals').value = result;
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

<form action="<?php echo $editFormAction; ?>" id="fm_viewinvoice_form1" name="form1" method="POST">
  <table width="1000" border="0">
    <tbody>
      <tr>
        <th width="162">&nbsp;</th>
        <th width="87" align="right">No. Invoice</th>
        <th width="128" align="right">&nbsp;</th>
        <td width="605"><input name="Invoice" type="text" class="textview" id="tx_viewinvoice_Invoice" value="<?php echo $row_View['Invoice']; ?>" readonly></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Project</th>
        <th align="right">&nbsp;</th>
        <td><input name="TglStart" type="text" class="textview" id="tx_viewinvoice_TglStart" value="<?php echo $row_View['Project']; ?>" readonly></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Company</th>
        <th align="right">&nbsp;</th>
        <td><input name="TglEnd" type="text" class="textview" id="tx_viewinvoice_TglEnd" value="<?php echo $row_View['Company']; ?>" readonly></td>
      </tr>
    </tbody>
  </table>
  <table width="1000" border="0">
    <tbody>
      <tr>
        <th align="center">SJ Kirim</th>
        <th align="center">SJ Kembali</th>
        <th align="center">No. Purchase</th>
        <th align="center">Item</th>
        <th>S</th>
        <th>E</th>
        <th>S-E</th>
        <th>Periode</th>
        <th>I</th>
        <th>Quantity</th>
        <th>Amount</th>
        <th>Total</th>
        </tr>
      <?php 
	  $total = 0;
	  do { ?>
      
        <tr>
          <td align="center"><input name="SJKir" type="text" class="textview" id="tx_viewinvoice_SJKir" value="<?php echo $row_View2['SJKir']; ?>" readonly></td>
          <td align="center"><input name="SJKem" type="text" class="textview" id="tx_viewinvoice_SJKem" value="<?php echo $row_View2['SJKem']; ?>" readonly></td>
          <td align="center"><input name="Purchase" type="text" class="textview" id="tx_viewinvoice_Purchase" value="<?php echo $row_View2['Purchase']; ?>" readonly></td>
          <td align="center"><input name="Barang" type="text" class="textview" id="tx_viewinvoice_Barang" value="<?php echo $row_View2['Barang']; ?>" readonly></td>
          <td align="center"><input name="TglStart" type="text" class="textview" id="tx_viewinvoice_TglStart" value="<?php echo $row_View2['S']; ?>" readonly></td>
          <td align="center"><input name="TglEnd" type="text" class="textview" id="tx_viewinvoice_TglEnd" value="<?php echo $row_View2['E']; ?>" readonly></td>
          <td align="center">
            <?php 
		  $date1 = $row_View2['S'];
		  $date2 = $row_View2['E'];
		  $date1 = str_replace('/', '-', $date1);
		  $date2 = str_replace('/', '-', $date2);
		  $sjkem = strtotime($date2); $sjkir = strtotime($date1); ?>
            <input name="S-E" type="text" class="textview" id="tx_viewinvoice_S-E" value="<?php echo round((($sjkem - $sjkir) / 86400),1)+1 ?>" readonly></td>
          <td align="center">
            <?php $bln = substr($row_View2['S'], 3, -5); $thn = substr($row_View2['S'], 6);
			if ($bln == 1){
				$bln = 31;
				}
			elseif ($bln == 2){
				$bln = 28;
				if ($thn == 2016 || $thn == 2020 || $thn == 2024){
				$bln = 29;
				}
				}
			elseif ($bln == 3){
				$bln = 31;
				}
			elseif ($bln == 4){
				$bln = 30;
				}
			elseif ($bln == 5){
				$bln = 31;
				}
			elseif ($bln == 6){
				$bln = 30;
				}
			elseif ($bln == 7){
				$bln = 31;
				}
			elseif ($bln == 8){
				$bln = 31;
				}
			elseif ($bln == 9){
				$bln = 30;
				}
			elseif ($bln == 10){
				$bln = 31;
				}
			elseif ($bln == 11){
				$bln = 30;
				}
			elseif ($bln == 12){
				$bln = 31;
				}
			else {
				$bln = "ada kesalahan bulan";
				}
			?>
            <input name="Periode" type="text" class="textview" id="tx_viewinvoice_Periode" value="<?php echo $bln ?>" readonly></td>
          <td align="center"><input name="Indeks" type="text" class="textview" id="tx_viewinvoice_Indeks" value="<?php echo round(((($sjkem - $sjkir) / 86400)+1)/$bln, 4) ?>" readonly></td>
          <td align="center"><input name="Quantity" type="text" class="textview" id="tx_viewinvoice_Quantity" value="<?php echo $row_View2['Quantity']; ?>" readonly></td>
          <td align="center"><input name="Amount" type="text" class="textview" id="tx_viewinvoice_Amount" value="<?php echo $row_View2['Amount']; ?>" readonly></td>
          <?php $test = ((($sjkem - $sjkir) / 86400)+1)/$bln*$row_View2['Quantity']* $row_View2['Amount']; $total += $test ?>
          <td align="center"><input name="Total" type="text" class="textview" id="tx_viewinvoice_Total" value="<?php echo round($test, 2) ?>" readonly></td>
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
        <td colspan="2"><input name="PPN" type="text" class="textbox" id="tx_viewinvoice_PPN" autocomplete="off" value="<?php echo $row_View['PPN']; ?>" onKeyUp="tot()">
          <input name="PPN2" type="hidden" class="textbox" id="tx_viewinvoice_PPN2" autocomplete="off" value="<?php echo $row_View['PPN']; ?>"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Transport</th>
        <th align="right">&nbsp;</th>
        <td colspan="2"><input name="Transport" type="text" class="textbox" id="tx_viewinvoice_Transport" autocomplete="off" value="<?php echo $row_View['Transport']; ?>" onKeyUp="tot()">
          <input name="Transport2" type="hidden" class="textbox" id="tx_viewinvoice_Transport2" autocomplete="off" value="<?php echo $row_View['Transport']; ?>"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Total</th>
        <th align="right">&nbsp;</th>
        <input name="Totals2" type="hidden" id="tx_viewinvoice_Totals2" value="<?php echo round($total, 2); ?>" >
        <td colspan="2"><input name="Total" type="text" class="textview" id="tx_viewinvoice_Totals" value="<?php echo round(($total*$row_View['PPN']*0.1)+$total+$row_View['Transport'], 2); ?>" readonly></td>
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
        <td align="center"><input type="submit" name="submit" id="tx_viewinvoice_submit" class="button2" value="Update"></td>
        <td><a href="Invoice.php">
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
