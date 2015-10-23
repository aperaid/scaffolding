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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE jualproject SET Id=%s, Project=%s, CCode=%s WHERE PCode=%s",
                       GetSQLValueString($_POST['Id'], "int"),
                       GetSQLValueString($_POST['Project'], "text"),
                       GetSQLValueString($_POST['CCode'], "text"),
                       GetSQLValueString($_POST['PCode'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($updateSQL, $Connection) or die(mysql_error());

  $updateGoTo = "ViewProject.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_Edit = "-1";
if (isset($_GET['Id'])) {
  $colname_Edit = $_GET['Id'];
}
mysql_select_db($database_Connection, $Connection);
$query_Edit = sprintf("SELECT * FROM jualproject WHERE Id = %s", GetSQLValueString($colname_Edit, "int"));
$Edit = mysql_query($query_Edit, $Connection) or die(mysql_error());
$row_Edit = mysql_fetch_assoc($Edit);
$totalRows_Edit = mysql_num_rows($Edit);$colname_Edit = "-1";
if (isset($_GET['PCode'])) {
  $colname_Edit = $_GET['PCode'];
}
mysql_select_db($database_Connection, $Connection);
$query_Edit = sprintf("SELECT * FROM jualproject WHERE PCode = %s", GetSQLValueString($colname_Edit, "text"));
$Edit = mysql_query($query_Edit, $Connection) or die(mysql_error());
$row_Edit = mysql_fetch_assoc($Edit);
$totalRows_Edit = mysql_num_rows($Edit);

$colname_View = "-1";
if (isset($_GET['Id'])) {
$colname_View = $_GET['Id'];

mysql_select_db($database_Connection, $Connection);
$query_Menu = "SELECT * FROM menu";
$Menu = mysql_query($query_Menu, $Connection) or die(mysql_error());
$row_Menu = mysql_fetch_assoc($Menu);
$totalRows_Menu = mysql_num_rows($Menu);
}
mysql_select_db($database_Connection, $Connection);
$query_View = sprintf("SELECT * FROM jualproject WHERE Id = %s", GetSQLValueString($colname_View, "int"));
$View = mysql_query($query_View, $Connection) or die(mysql_error());
$row_View = mysql_fetch_assoc($View);
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
	var x = document.getElementById("Project");
    x.value = x.value.toUpperCase();
	var x = document.getElementById("CCode");
    x.value = x.value.toUpperCase();
}
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

<table width="950" border="0">


  <tbody>
    <tr>
      <th align="center"><h2>EDIT</h2></th>
    </tr>
  </tbody>
</table>
</p>
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <table width="1005" border="0">
    <tbody>
      <tr>
        <th width="75"><input name="Id" type="hidden" id="Id" value="<?php echo $row_Edit['Id']; ?>"></th>
        <th width="125" align="right"> Project Code</th>
        <th width="50" align="right">&nbsp;</th>
        <th colspan="2" align="left"><input name="PCode" type="text" id="PCode" style="width:514px;" autocomplete="off" onKeyUp="capital()" value="<?php echo $row_Edit['PCode']; ?>" class="textbox"></th>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Project</th>
        <th align="right">&nbsp;</th>
        <th colspan="2" align="left"><input name="Project" type="text" id="Project" style="width:514px;" autocomplete="off" onKeyUp="capital()" value="<?php echo $row_Edit['Project']; ?>" class="textbox"></th>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th align="right">Customer Code</th>
        <th align="right">&nbsp;</th>
        <th colspan="2" align="left"><input name="CCode" type="text" id="CCode" autocomplete="off" value="<?php echo $row_Edit['CCode']; ?>" class="textbox" style="width:514px;"></th>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td colspan="2" align="center">&nbsp;</td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center"></td>
        <td width="202" align="left"><input type="submit" name="submit" id="submit" class="submit" value="Edit"></td>
        <td width="531" align="left"><a href="ViewProject.php?PCode=<?php echo $row_Edit['PCode']; ?>"><button type="button" class="submit">Cancel</button></a></td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_update" value="form1">
</form>
</body>
</html>
<?php
mysql_free_result($Edit);
?>
