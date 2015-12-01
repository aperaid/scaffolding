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

$colname_Extend = "-1";
if (isset($_GET['Reference'])) {
  $colname_Extend = $_GET['Reference'];
}

$colname_Extend2 = "-1";
if (isset($_GET['Periode'])) {
  $colname_Extend2 = $_GET['Periode'];
}

mysql_select_db($database_Connection, $Connection);
$query_Extend = sprintf("SELECT periode.* FROM periode WHERE periode.Reference=%s AND periode.Periode=%s AND SJKem IS NULL ORDER BY periode.Id ASC", GetSQLValueString($colname_Extend, "text"),GetSQLValueString($colname_Extend2, "text"));
$Extend = mysql_query($query_Extend, $Connection) or die(mysql_error());
$row_Extend = mysql_fetch_assoc($Extend);
$totalRows_Extend = mysql_num_rows($Extend);

for($i=0;$i<$totalRows_Extend;$i++){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO periode (Periode, S, E, Quantity, IsiSJKir, Reference) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Periode'][$i], "int"),
                       GetSQLValueString($_POST['S'][$i], "text"),
                       GetSQLValueString($_POST['E'][$i], "text"),
                       GetSQLValueString($_POST['Quantity'][$i], "int"),
                       GetSQLValueString($_POST['IsiSJKir'][$i], "text"),
                       GetSQLValueString($_POST['Reference'][$i], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "ViewTransaksiSewa.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
}
?>

<script type="text/javascript">
    function submit()
    {
        document.getElementById("submit").click(); // Simulates button click
        document.submitForm.submit(); // Submits the form without the button
    }
</script>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body onLoad="submit()">
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <table width="1350" border="0">
    <tbody>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
          <?php $stgl = 0; $etgl = 0; $bln = substr($row_Extend['S'], 3, -5); $thn = substr($row_Extend['S'], 6);
			if ($bln == 1){
				$stgl = '01';
				$etgl = 28;
				$bln = '02';
				if ($thn == 2016 || $thn == 2020 || $thn == 2024){
				$stgl = '01';
				$etgl = 29;
				$bln = '02';
				}
				}
			elseif ($bln == 2){
				$stgl = '01';
				$etgl = 31;
				$bln = '03';
				}
			elseif ($bln == 3){
				$stgl = '01';
				$etgl = 30;
				$bln = '04';
				}
			elseif ($bln == 4){
				$stgl = '01';
				$etgl = 31;
				$bln = '05';
				}
			elseif ($bln == 5){
				$stgl = '01';
				$etgl = 30;
				$bln = '06';
				}
			elseif ($bln == 6){
				$stgl = '01';
				$etgl = 31;
				$bln = '07';
				}
			elseif ($bln == 7){
				$stgl = '01';
				$etgl = 31;
				$bln = '08';
				}
			elseif ($bln == 8){
				$stgl = '01';
				$etgl = 30;
				$bln = '09';
				}
			elseif ($bln == 9){
				$stgl = '01';
				$etgl = 31;
				$bln = '10';
				}
			elseif ($bln == 10){
				$stgl = '01';
				$etgl = 30;
				$bln = '11';
				}
			elseif ($bln == 11){
				$stgl = '01';
				$etgl = 31;
				$bln = '12';
				}
			elseif ($bln == 12){
				$stgl = '01';
				$etgl = 31;
				$bln = '01';
				$thn = $thn + 1;
				}
			?>
      <?php do { ?>
        <tr>
          <td><input name="Periode[]" type="hidden" id="Periode" value="<?php echo $row_Extend['Periode']+1; ?>">
            <input name="S[]" type="hidden" id="S" value="<?php echo $stgl, '/', $bln, '/', $thn; ?>">
            <input name="E[]" type="hidden" id="E" value="<?php echo $etgl, '/', $bln, '/', $thn; ?>">
            <input name="Quantity[]" type="hidden" id="Quantity" value="<?php echo $row_Extend['Quantity']; ?>">
            <input name="IsiSJKir[]" type="hidden" id="IsiSJKir" value="<?php echo $row_Extend['IsiSJKir']; ?>">
          <input name="Reference[]" type="hidden" id="Reference" value="<?php echo $row_Extend['Reference']; ?>"></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      <?php } while ($row_Extend = mysql_fetch_assoc($Extend)); ?>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </tbody>
  </table>
  <p>
    <input type="submit" name="submit" id="submit" value="">
  </p>
  <p>&nbsp;</p>
  <input type="hidden" name="MM_insert" value="form1">
</form>
</body>
</html>
<?php
mysql_free_result($Extend);
?>
