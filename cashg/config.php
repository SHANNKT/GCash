<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gcash_db"; // dapat ito ang name ng database mo sa phpMyAdmin

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode([
        "success" => false,
        "message" => "❌ Database connection failed: " . $conn->connect_error
    ]));
}
?>
