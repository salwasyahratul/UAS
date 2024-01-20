<?php
$host = "localhost";
$username = "tama";
$password = "bukalah11";
$database = "db_salwa";

// Create connection
$koneksi = new mysqli($host, $username, $password, $database);

// Check connection
if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}
?>
