<?php
// gcash/cashg/index.php - WITH DATABASE CHECK
session_start();
include 'config.php'; // Kukunin ang $conn object para sa database

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$mpin = $data['mpin'] ?? '';

if (empty($mpin) || strlen($mpin) !== 4) {
    echo json_encode(["success" => false, "message" => "Invalid MPIN format."]);
    exit;
}

// NOTE: Dito ka naghahanap ng user. Ipagpalagay natin na 
// ang phone number ay hardcoded muna para sa testing (9814766012)
$phone_number = '9814766012'; 

// Prepare statement para maiwasan ang SQL Injection
$stmt = $conn->prepare("SELECT user_id FROM users WHERE phone_number = ? AND mpin = ?");

// Dito ini-bind ang values: "ss" (dalawang string)
$stmt->bind_param("ss", $phone_number, $mpin); 
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // ✅ SUCCESS! Ang MPIN ay TAMA.
    // Dito ire-redirect sa home.php (o home.html)
    echo json_encode(["success" => true, "redirect" => "cashg/home.php"]); 
} else {
    // ❌ FAIL! Mali ang MPIN.
    echo json_encode(["success" => false, "message" => "Incorrect MPIN. Please try again."]);
}

$stmt->close();
$conn->close();
?>