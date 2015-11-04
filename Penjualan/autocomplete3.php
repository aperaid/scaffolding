<?php

error_reporting(E_ALL^E_NOTICE);

$mysqli = new mysqli('localhost', 'root', '', 'scaffoldingjual');
$text = $mysqli->real_escape_string($_GET['term']);

$query = "SELECT Reference FROM pocustomer WHERE Reference LIKE '%$text%' ORDER BY Reference ASC";
$result = $mysqli->query($query);
$json = '[';
$first = true;
while($row = $result->fetch_assoc())
{
    if (!$first) { $json .=  ','; } else { $first = false; }
    $json .= '{"value":"'.$row['Reference'].'"}';
}
$json .= ']';
echo $json;
?>