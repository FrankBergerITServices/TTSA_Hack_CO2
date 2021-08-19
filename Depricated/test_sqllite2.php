<?php

$db = new SQLite3('measurements.db');

$res = $db->query('SELECT * FROM measurements');

while ($row = $res->fetchArray()) {

    $jsonArray[] = $row;
}

echo json_encode($jsonArray)

?>
