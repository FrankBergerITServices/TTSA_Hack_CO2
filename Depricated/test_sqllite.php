<?php

$ver = SQLite3::version();

echo $ver['versionString'] . "\n";
echo $ver['versionNumber'] . "\n";

var_dump($ver);

$db = new SQLite3('test.db');

$version = $db->querySingle('SELECT SQLITE_VERSION()');

echo $version . "\n";

$db = new SQLite3('test.db');

$db->exec("CREATE TABLE cars(id INTEGER PRIMARY KEY, name TEXT, price INT)");
$db->exec("INSERT INTO cars(name, price) VALUES('Audi', 52642)");
$db->exec("INSERT INTO cars(name, price) VALUES('Mercedes', 57127)");
$db->exec("INSERT INTO cars(name, price) VALUES('Skoda', 9000)");
$db->exec("INSERT INTO cars(name, price) VALUES('Volvo', 29000)");
$db->exec("INSERT INTO cars(name, price) VALUES('Bentley', 350000)");
$db->exec("INSERT INTO cars(name, price) VALUES('Citroen', 21000)");
$db->exec("INSERT INTO cars(name, price) VALUES('Hummer', 41400)");
$db->exec("INSERT INTO cars(name, price) VALUES('Volkswagen', 21600)");

?>
