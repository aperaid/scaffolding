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
if (isset($_GET['PCode'])) {
  $colname_View = $_GET['PCode'];
}
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT jualproject.*, jualcustomer.Alamat, jualcustomer.Contact, jualcustomer.Phone, jualcustomer.CCode FROM jualproject INNER JOIN jualcustomer ON jualproject.CCode=jualcustomer.CCode WHERE PCode = %s", GetSQLValueString($colname_View, "text"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
$totalRows_View = mysql_num_rows($View);

mysql_select_db($database_Connection, $Connection);
$query_Menu = "SELECT * FROM menu";
$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
$row_Menu = mysql_fetch_assoc($Menu);
$totalRows_Menu = mysql_num_rows($Menu);
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

<table width="950" border="0">


  <tbody>
    <tr>
      <th align="center"><h2><?php echo $row_View['Project']; ?></h2></th>
    </tr>
  </tbody>
</table>
</p>
<form id="form1" name="form1" method="POST">
  <table width="1005" border="0">
    <tbody>
      <tr>
        <th width="75">&nbsp;</th>
        <th width="125" align="right">Project Code</th>
        <th width="50" align="right">&nbsp;</th>
        <th colspan="4" align="left"><input name="PCode" type="text" class="textview" id="PCode" style="width:512px;" value="<?php echo $row_View['PCode']; ?>" readonly></th>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Project</th>
        <th align="right">&nbsp;</th>
        <th colspan="4" align="left"><input name="Project" type="text" class="textview" id="Project" style="width:512px;" value="<?php echo $row_View['Project']; ?>" readonly></th>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Alamat</th>
        <th align="right">&nbsp;</th>
        <th colspan="4" align="left"><input name="Alamat" type="text" class="textview" id="Alamat" style="width:512px;" value="<?php echo $row_View['Alamat']; ?>" readonly></th>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Contact</th>
        <th align="right">&nbsp;</th>
        <th width="150" align="left"><input name="Contact" type="text" class="textview" id="Contact" value="<?php echo $row_View['Contact']; ?>" readonly></th>
        <th width="100" align="right">Phone</th>
        <th width="50" align="right">&nbsp;</th>
        <td width="425"><input name="Phone" type="text" class="textview" id="Phone" value="<?php echo $row_View['Phone']; ?>" readonly></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Customer Code</th>
        <th align="right">&nbsp;</th>
        <th colspan="4" align="left"><input name="CCode" type="text" class="textview" id="CCode" style="width:512px" value="<?php echo $row_View['CCode']; ?>" readonly></th>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center"><a href="EditProject.php?PCode=<?php echo $row_View['PCode']; ?>">
          <button type="button" class="button2">Edit Project</button>
        </a></td>
        <td colspan="2" align="left"><a><button type="button" class="button2">Print</button></a></td>
        <td align="left"><a href="Project.php"><button type="button" class="button2">Back</button></a></td>
        <td>&nbsp;</td>
      </tr>
    </tbody>
  </table>
</form>
</body>
</html>
<?php
mysql_free_result($View);
?>
