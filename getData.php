<?php 
ini_set("error_reporting", E_ALL);

// This is just an example of reading server side data and sending it to the client.
// It reads a json formatted text file and outputs it.

//$string = file_get_contents("sampleData.json");
//echo $string;

$db = new SQLite3('measurements.db');

echo "Sensor time;CO2 concentration;Temperature;Humidity\n";
echo "YYYY-MM-DD HH:MM:SS+ZZ;ppm;degC;%\n";

$stmt = $db->prepare("SELECT device_id, received_at, co2, temperature, humidity FROM measurements where device_id = :device_id");      
$stmt->bindParam(':device_id', $_GET["device_id"]);
$result = $stmt->execute();


while($row = $result->fetchArray()) {
    echo "{$row['received_at']};{$row['co2']};{$row['temperature']};{$row['humidity']}\n";
}



?>
