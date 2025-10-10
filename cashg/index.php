<?php
// index.php
include 'config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$mpin = $input['mpin'] ?? '';
$phone_number = '9814766012'; // test value, dapat meron ka nito sa database

if (empty($mpin) || strlen($mpin) !== 4) {
    echo json_encode(["success" => false, "message" => "Invalid MPIN format."]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE phone = ? AND mpin = ?");
$stmt->bind_param("ss", $phone_number, $mpin);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["success" => true, "redirect" => "home.php"]);
} else {
    echo json_encode(["success" => false, "message" => "Incorrect MPIN."]);
}

$stmt->close();
$conn->close();
?>
