<?php
$servername = "localhost";
$username = "root";
$password = "";
$connname = "pharmacy_db";

$conn = new mysqli($servername, $username, $password, $connname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>