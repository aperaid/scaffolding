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
mysql_select_db($database_Connection, $Connection);
$query_Count = sprintf("SELECT ((SUM(Amount*Quantity)+invoice.Transport)*invoice.PPN*0.1)+(SUM(Amount*Quantity)+invoice.Transport) FROM invoice INNER JOIN transaksi ON invoice.Reference=transaksi.Reference WHERE transaksi.Reference = %s AND transaksi.JS = %s AND invoice.Invoice = %s", GetSQLValueString($colname_Count, "text"), GetSQLValueString($colname_ViewJS, "text"), GetSQLValueString($colname_ViewInvoice, "text"));
$Count = mysql_query($query_Count, $Connection) or die(mysql_error());
$row_Count = mysql_fetch_assoc($Count);
$totalRows_Count = mysql_num_rows($Count);

$maxRows_View2 = 10;
$pageNum_View2 = 0;
if (isset($_GET['pageNum_View2'])) {
  $pageNum_View2 = $_GET['pageNum_View2'];
}
$startRow_View2 = $pageNum_View2 * $maxRows_View2;

$colname_View2 = "-1";
if (isset($_GET['JS'])) {
  $colname_View2 = $_GET['Reference'];
  $colname_ViewJS = $_GET['JS'];
}
mysql_select_db($database_Connection, $Connection);
$query_View2 = sprintf("SELECT Purchase, Barang, Quantity, Amount FROM transaksi WHERE Reference = %s AND JS = %s", GetSQLValueString($colname_View2, "text"),
GetSQLValueString($colname_ViewJS, "text"));
$query_limit_View2 = sprintf("%s LIMIT %d, %d", $query_View2, $startRow_View2, $maxRows_View2);
$View2 = mysql_query($query_limit_View2, $Connection) or die(mysql_error());
$row_View2 = mysql_fetch_assoc($View2);

if (isset($_GET['totalRows_View2'])) {
  $totalRows_View2 = $_GET['totalRows_View2'];
} else {
  $all_View2 = mysql_query($query_View2);
  $totalRows_View2 = mysql_num_rows($all_View2);
}
$totalPages_View2 = ceil($totalRows_View2/$maxRows_View2)-1;

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

$colname_SJKem = "-1";
if (isset($_GET['Reference'])) {
  $colname_SJKem = $_GET['Reference'];
}
mysql_select_db($database_Connection, $Connection);
$query_SJKem = sprintf("SELECT Tgl FROM sjkembali WHERE Reference = %s ORDER BY Id ASC", GetSQLValueString($colname_SJKem, "text"));
$SJKem = mysql_query($query_SJKem, $Connection) or die(mysql_error());
$row_SJKem = mysql_fetch_assoc($SJKem);
$totalRows_SJKem = mysql_num_rows($SJKem);

$colname_SJKir = "-1";
if (isset($_GET['Reference'])) {
  $colname_SJKir = $_GET['Reference'];
}
mysql_select_db($database_Connection, $Connection);
$query_SJKir = sprintf("SELECT Tgl FROM sjkirim WHERE Reference = %s ORDER BY Id ASC", GetSQLValueString($colname_SJKir, "text"));
$SJKir = mysql_query($query_SJKir, $Connection) or die(mysql_error());
$row_SJKir = mysql_fetch_assoc($SJKir);
$totalRows_SJKir = mysql_num_rows($SJKir);

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

<script>
var z = "Jual";
if(z != "Sewa") {
	document.getElementById("Period").style.display='none';
}
</script>
 
<script language="javascript">
  function bagi() {
    var txtFirstNumberValue = document.getElementById('Period').value;
    var txtSecondNumberValue = document.getElementById('Period2').value;
	var result = parseInt(txtFirstNumberValue) / parseInt(txtSecondNumberValue);
	  if (!isNaN(result)) {
		document.getElementById('Index').value = result;
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


      <h2 align="center"><?php echo $row_View['Reference']; ?></h2>
    </div>
<div class="ui-layout-center">

<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <table width="1000" border="0">
    <tbody>
      <tr>
        <th width="162">&nbsp;</th>
        <th width="87" align="right">No. Invoice</th>
        <th width="128" align="right">&nbsp;</th>
        <td width="605"><input name="Invoice" type="text" class="textview" id="Invoice" value="<?php echo $row_View['Invoice']; ?>" readonly></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Project</th>
        <th align="right">&nbsp;</th>
        <td><input name="TglStart" type="text" class="textview" id="TglStart" value="<?php echo $row_View['Project']; ?>" readonly></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Company</th>
        <th align="right">&nbsp;</th>
        <td><input name="TglEnd" type="text" class="textview" id="TglEnd" value="<?php echo $row_View['Company']; ?>" readonly></td>
      </tr>
    </tbody>
  </table>
  <table width="1000" border="0">
    <tbody>
      <tr>
        <th align="center">No. Purchase</th>
        <th align="center">Item</th>
        <th>Quantity</th>
        <th>Amount</th>
        <th>Period</th>
      </tr>
      <?php do { ?>
      
        <tr>
          <td align="center"><input name="Purchase" type="text" class="textview" id="Purchase" value="<?php echo $row_View2['Purchase']; ?>" readonly></td>
          <td align="center"><input name="Barang" type="text" class="textview" id="Barang" value="<?php echo $row_View2['Barang']; ?>" readonly></td>
          <td align="center"><input name="Quantity" type="text" class="textview" id="Quantity" value="<?php echo $row_View2['Quantity']; ?>" readonly></td>
          <td align="center"><input name="Amount" type="text" class="textview" id="Amount" value="<?php echo $row_View2['Amount']; ?>" readonly></td>
          <?php $sjkem = strtotime($row_SJKem['Tgl']); $sjkir = strtotime($row_SJKir['Tgl']); ?>
          <td align="center"><input type="text" name="Perio" id="Perio" value="<?php echo ($sjkem - $sjkir) / 86400 ?>"></td>
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
        <td colspan="2"><input name="PPN" type="text" class="textbox" id="PPN" autocomplete="off" value="<?php echo $row_View['PPN']; ?>"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Transport</th>
        <th align="right">&nbsp;</th>
        <td colspan="2"><input name="Transport" type="text" class="textbox" id="Transport" autocomplete="off" value="<?php echo $row_View['Transport']; ?>"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Total</th>
        <th align="right">&nbsp;</th>
        <td colspan="2"><input name="Total" type="text" class="textview" id="Total" value="<?php echo $row_Count['((SUM(Amount*Quantity)+invoice.Transport)*invoice.PPN*0.1)+(SUM(Amount*Quantity)+invoice.Transport)']; ?>" readonly></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <th align="right">Period</th>
        <td align="center">&nbsp;</td>
        <td colspan="2">
			<?php $bln = substr($row_View['Tgl'], 3, -5); $thn = substr($row_View['Tgl'], 6);
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
        	<input name="Period" id="Period" type="text" value="<?php echo $bln ?>" class="textbox" onKeyUp="bagi()">
        	<input name="Period2" id="Period2" type="text" value="<?php echo $bln ?>" class="textview">
        </td>
        </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <th align="right">Index</th>
        <td align="center">&nbsp;</td>
        <td colspan="2"><input name="Index" id="Index" type="text" class="textview" value="1" readonly></td>
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
        <td align="center"><input type="submit" name="submit" id="submit" class="button2" value="Update"></td>
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
