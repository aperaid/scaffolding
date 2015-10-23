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

mysql_select_db($database_Connection, $Connection);
$query_Project = "SELECT * FROM jualproject ORDER BY Id ASC";
$Project = mysql_query($query_Project, $Connection) or die(mysql_error());
$row_Project = mysql_fetch_assoc($Project);
$totalRows_Project = mysql_num_rows($Project);mysql_select_db($database_Connection, $Connection);
$query_Project = "SELECT * FROM jualproject ORDER BY Id ASC";
$Project = mysql_query($query_Project, $Connection) or die(mysql_error());
$row_Project = mysql_fetch_assoc($Project);
$totalRows_Project = mysql_num_rows($Project);

	$query_menu = "SELECT * FROM menu ORDER BY Id ASC";
	$menu = mysql_query($query_menu, $Connection) or die(mysql_error());
	$row_menu = mysql_fetch_assoc($menu);
	$totalRows_menu = mysql_num_rows($menu);	
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
                    <a href="../<?php echo $row_menu['link']; ?>">
                    <button type="button" class="button">
					<?php echo $row_menu['nama']; ?></button></a></td>
                    </tr>
                    
                <?php } while ($row_menu = mysql_fetch_assoc($menu)); ?>
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
        <td align="center"><h2>PROJECT</h2></td>
      </tr>
    </tbody>
  </table>
  <div style="float:left;width:85%; height:485px; overflow:auto">
  <table width="950" border="1">
    <tbody>
      <tr>
        <th>Code</th>
        <th>Project</th>
        <th>&nbsp;</th>
      </tr>
        <?php do { ?>
        <tr>
          <td align="center"><?php echo $row_Project['PCode']; ?></td>
          <td align="center"><?php echo $row_Project['Project']; ?></td>
          <td width="300" align="center"><a href="ViewProject.php?PCode=<?php echo $row_Project['PCode']; ?>"><button type="button" class="button3">View</button></a>-<a href="DeleteProject.php?Id=<?php echo $row_Project['Id']; ?>"><button type="button" class="button3">Delete</button></a></td>
          </tr>
        <?php } while ($row_Project = mysql_fetch_assoc($Project)); ?>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </tbody>
  </table>
  </div>
  <table width="950" border="0">
  <tbody>
    <tr>
      <td><a href="InsertProject.php"><button type="button" class="button2">Insert</button></a></td>
    </tr>
  </tbody>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($Project);
?>
