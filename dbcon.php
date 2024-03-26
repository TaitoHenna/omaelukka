<?php
// Tietokannan tiedot

$host = "localhost";
$username = "abc";
$password = getenv('DB_PASSWORD'); // Environmental -muuttuja salasanaa varten
$db = "abc";

// Luodaan yhteys
$conn = new mysqli($host, $username, $password, $db);

// Tarkistetaan yhteys
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
