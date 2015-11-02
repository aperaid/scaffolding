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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO transaksi (Purchase, JS, Barang, Quantity, Amount, TglStart, Reference) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Purchase'], "text"),
                       GetSQLValueString($_POST['JS'], "text"),
                       GetSQLValueString($_POST['Barang'], "text"),
                       GetSQLValueString($_POST['Quantity'], "int"),
                       GetSQLValueString($_POST['Amount'], "text"),
                       GetSQLValueString($_POST['TglStart'], "text"),
                       GetSQLValueString($_POST['Reference'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "POCustomer.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO transaksi (Purchase, JS, Barang, Quantity, Amount, TglStart, Reference) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Purchase2'], "text"),
                       GetSQLValueString($_POST['JS2'], "text"),
                       GetSQLValueString($_POST['Barang2'], "text"),
                       GetSQLValueString($_POST['Quantity2'], "int"),
                       GetSQLValueString($_POST['Amount2'], "text"),
                       GetSQLValueString($_POST['TglStart2'], "text"),
                       GetSQLValueString($_POST['Reference'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "POCustomer.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO transaksi (Purchase, JS, Barang, Quantity, Amount, TglStart, Reference) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Purchase3'], "text"),
                       GetSQLValueString($_POST['JS3'], "text"),
                       GetSQLValueString($_POST['Barang3'], "text"),
                       GetSQLValueString($_POST['Quantity3'], "int"),
                       GetSQLValueString($_POST['Amount3'], "text"),
                       GetSQLValueString($_POST['TglStart3'], "text"),
                       GetSQLValueString($_POST['Reference'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "POCustomer.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO transaksi (Purchase, JS, Barang, Quantity, Amount, TglStart, Reference) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Purchase4'], "text"),
                       GetSQLValueString($_POST['JS4'], "text"),
                       GetSQLValueString($_POST['Barang4'], "text"),
                       GetSQLValueString($_POST['Quantity4'], "int"),
                       GetSQLValueString($_POST['Amount4'], "text"),
                       GetSQLValueString($_POST['TglStart4'], "text"),
                       GetSQLValueString($_POST['Reference'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "POCustomer.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO transaksi (Purchase, JS, Barang, Quantity, Amount, TglStart, Reference) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Purchase5'], "text"),
                       GetSQLValueString($_POST['JS5'], "text"),
                       GetSQLValueString($_POST['Barang5'], "text"),
                       GetSQLValueString($_POST['Quantity5'], "int"),
                       GetSQLValueString($_POST['Amount5'], "text"),
                       GetSQLValueString($_POST['TglStart5'], "text"),
                       GetSQLValueString($_POST['Reference'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "POCustomer.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO transaksi (Purchase, JS, Barang, Quantity, Amount, TglStart, Reference) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Purchase6'], "text"),
                       GetSQLValueString($_POST['JS6'], "text"),
                       GetSQLValueString($_POST['Barang6'], "text"),
                       GetSQLValueString($_POST['Quantity6'], "int"),
                       GetSQLValueString($_POST['Amount6'], "text"),
                       GetSQLValueString($_POST['TglStart6'], "text"),
                       GetSQLValueString($_POST['Reference'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "POCustomer.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO transaksi (Purchase, JS, Barang, Quantity, Amount, TglStart, Reference) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Purchase7'], "text"),
                       GetSQLValueString($_POST['JS7'], "text"),
                       GetSQLValueString($_POST['Barang7'], "text"),
                       GetSQLValueString($_POST['Quantity7'], "int"),
                       GetSQLValueString($_POST['Amount7'], "text"),
                       GetSQLValueString($_POST['TglStart7'], "text"),
                       GetSQLValueString($_POST['Reference'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "POCustomer.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO transaksi (Purchase, JS, Barang, Quantity, Amount, TglStart, Reference) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Purchase8'], "text"),
                       GetSQLValueString($_POST['JS8'], "text"),
                       GetSQLValueString($_POST['Barang8'], "text"),
                       GetSQLValueString($_POST['Quantity8'], "int"),
                       GetSQLValueString($_POST['Amount8'], "text"),
                       GetSQLValueString($_POST['TglStart8'], "text"),
                       GetSQLValueString($_POST['Reference'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "POCustomer.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO transaksi (Purchase, JS, Barang, Quantity, Amount, TglStart, Reference) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Purchase9'], "text"),
                       GetSQLValueString($_POST['JS9'], "text"),
                       GetSQLValueString($_POST['Barang9'], "text"),
                       GetSQLValueString($_POST['Quantity9'], "int"),
                       GetSQLValueString($_POST['Amount9'], "text"),
                       GetSQLValueString($_POST['TglStart9'], "text"),
                       GetSQLValueString($_POST['Reference'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "POCustomer.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO transaksi (Purchase, JS, Barang, Quantity, Amount, TglStart, Reference) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Purchase10'], "text"),
                       GetSQLValueString($_POST['JS10'], "text"),
                       GetSQLValueString($_POST['Barang10'], "text"),
                       GetSQLValueString($_POST['Quantity10'], "int"),
                       GetSQLValueString($_POST['Amount10'], "text"),
                       GetSQLValueString($_POST['TglStart10'], "text"),
                       GetSQLValueString($_POST['Reference'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "POCustomer.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_Connection, $Connection);
$query_Menu = "SELECT * FROM menu";
$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
$row_Menu = mysql_fetch_assoc($Menu);
$totalRows_Menu = mysql_num_rows($Menu);

$colname_Reference = "-1";
if (isset($_GET['Id'])) {
  $colname_Reference = $_GET['Id'];
}
mysql_select_db($database_Connection, $Connection);
$query_Reference = sprintf("SELECT Reference FROM pocustomer WHERE Id = %s", GetSQLValueString($colname_Reference, "int"));
$Reference = mysql_query($query_Reference, $Connection) or die(mysql_error());
$row_Reference = mysql_fetch_assoc($Reference);
$totalRows_Reference = mysql_num_rows($Reference);

mysql_select_db($database_Connection, $Connection);
$query_Purchase = "SELECT Id FROM transaksi ORDER BY Id DESC";
$Purchase = mysql_query($query_Purchase, $Connection) or die(mysql_error());
$row_Purchase = mysql_fetch_assoc($Purchase);
$totalRows_Purchase = mysql_num_rows($Purchase);

mysql_select_db($database_Connection, $Connection);
$query_LastReference = "SELECT Reference FROM pocustomer ORDER BY Id DESC";
$LastReference = mysql_query($query_LastReference, $Connection) or die(mysql_error());
$row_LastReference = mysql_fetch_assoc($LastReference);
$totalRows_LastReference = mysql_num_rows($LastReference);
?>
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

<script>
function capital() {
	var x = document.getElementById("PCode");
    x.value = x.value.toUpperCase();
}
</script>

<link href="/scaffolding/JQuery2/jquery-ui.css" rel="stylesheet" type="text/css">
<script src="../../JQuery2/external/jquery/jquery.js"></script>
<script src="../../JQuery2/jquery-ui.js"></script>

<script>
  $(function() {
    $( "#TglStart,#TglStart2,#TglStart3,#TglStart4,#TglStart5" ).datepicker();
  });
</script>

<script type="text/javascript">
$(function() {
    var availableTags = <?php include ("../autocomplete.php");?>;
    $( "#PCode" ).autocomplete({
      source: availableTags
    });
  });
</script>

<script type="text/javascript">
$(document).ready(function(){
	var max_fields      = 10; //maximum input boxes allowed
	
	var x = 1; //initlal text box count
	$(".addCF").click(function(){
		if(x < max_fields){ //max input box allowed
            x++; //text box increment
		$("#customFields").append('<tr><td><input name="Purchase'+ x +'" type="text" class="textbox" id="Purchase" value="<?php echo str_pad($row_Purchase['Id']+1, 5, "0", STR_PAD_LEFT); ?>" readonly></td><td><input type="text" name="Barang'+ x +'" id="Barang" class="textbox"></td><td><select name="JS'+ x +'"' + x +' id="JS"><option>Jual</option><option>Sewa</option></select></td><td><input type="text" name="Amount'+ x +'" id="Amount" class="textbox"></td><td><input type="text" name="Quantity'+ x +'" id="Quantity" class="textbox"></td><td><input type="text" name="TglStart'+ x +'" id="TglStart" class="textbox"></td><td><a href="javascript:void(0);" class="remCF">Remove</a></td></tr>');
		}
	});
    $("#customFields").on('click','.remCF',function(){
        $(this).parent().parent().remove();
    });
});
</script>

</head>

<body>
<div style="float:left;width:15%">
  <table width="200" border="0">
    <tbody>
      <tr>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th>&nbsp;</th>
      </tr>
      
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
                    
    </tbody>
  </table>
</div>

<div style="float:left;width:85%">

<table width="1000" border="0">


  <tbody>
    <tr>
      <th align="center"><h2><?php echo $row_LastReference['Reference']; ?></h2></th>
    </tr>
  </tbody>
</table>
</p>
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <table width="1000" border="0" id="customFields">
    <thead>
	  <th>Purchase</th>
					  <th>Barang</th>
					  <th>J/S</th>
					  <th>Amount</th>
					  <th>Quantity</th>
					  <th>Tanggal Permintaan</th>
                      <th><a href="javascript:void(0);" class="addCF">Add</a></th>
				<tbody>
                  <tr>
                    <td><input name="Purchase" type="text" class="textbox" id="Purchase" value="<?php echo str_pad($row_Purchase['Id']+1, 5, "0", STR_PAD_LEFT); ?>" readonly></td>
                    <td><input type="text" name="Barang" id="Barang" class="textbox"></td>
                    <td><select name="JS" id="JS">
                      <option>Jual</option>
                      <option>Sewa</option>
                    </select></td>
                    <td><input type="text" name="Amount" id="Amount" class="textbox"></td>
                    <td><input type="text" name="Quantity" id="Quantity" class="textbox"></td>
                    <td><input type="text" name="TglStart" id="TglStart" class="textbox"></td>
                  </tr>
                </tbody>
  </table>
  <input name="Reference" type="hidden" id="Reference" value="<?php echo $row_LastReference['Reference']; ?>">
  <input type="hidden" name="MM_insert" value="form1">
  <table width="1000" border="0" id="customFields2">
    <thead>
      <th><input type="submit" name="submit" id="submit" class="submit" value="Insert"></th>
        <tbody>
        </tbody>
  </table>
</form>
</div>

</body>
</html>
<?php
mysql_free_result($Menu);

mysql_free_result($Reference);

mysql_free_result($Purchase);

mysql_free_result($LastReference);
?>
