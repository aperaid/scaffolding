<?php

error_reporting(E_ALL^E_NOTICE);

$mysqli = new mysqli('181.224.157.45', 'apera145_bdn', '890iop890iop', 'apera145_scaffolding');
$text = $mysqli->real_escape_string($_GET['term']);

$query = "SELECT PCode FROM project WHERE PCode LIKE '%$text%' ORDER BY PCode ASC";
$result = $mysqli->query($query);
$json = '[';
$first = true;
while($row = $result->fetch_assoc())
{
    if (!$first) { $json .=  ','; } else { $first = false; }
    $json .= '{"value":"'.$row['PCode'].'"}';
}
$json .= ']';
echo $json;
?>