<?php
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = 'GxCOgyXr1b5G8o@599Pm&%T';
$dbName = 'c1occsyspay';

$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>