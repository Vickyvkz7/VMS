<?php
date_default_timezone_set('Asia/Kolkata');
$servername = "localhost";
$username = "root";
$password = ""; // default for XAMPP
$dbname = "vms";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle JSON POST for AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    $input = json_decode(file_get_contents('php://input'), true);
    $name = $conn->real_escape_string($input['name'] ?? '');
    $contact = $conn->real_escape_string($input['contact'] ?? '');
    $purpose = $conn->real_escape_string($input['purpose'] ?? '');
    $department = $conn->real_escape_string($input['department-select'] ?? '');
    $dean_department = $conn->real_escape_string($input['dean-select'] ?? '');
    $cdc_program = $conn->real_escape_string($input['cdc-select'] ?? '');
    $synergy_program = $conn->real_escape_string($input['synergy-select'] ?? '');
    $admission_program = $conn->real_escape_string($input['program'] ?? '');
    $domain = $conn->real_escape_string($input['domain'] ?? '');
    $course = $conn->real_escape_string($input['course'] ?? '');
    $qualification = $conn->real_escape_string($input['qualification'] ?? '');
    $percentage = $conn->real_escape_string($input['percentage'] ?? '');
    $location = $conn->real_escape_string($input['location'] ?? '');
    $passkey = $conn->real_escape_string($input['passkey'] ?? '');
    $now = date('Y-m-d H:i:s');
    $visit_date = $conn->real_escape_string($input['visitDate'] ?? date('Y-m-d'));
    $entry_time = $conn->real_escape_string($input['visitTime'] ?? date('H:i:s'));
    $sql = "INSERT INTO visitors (name, contact, purpose, department, dean_department, cdc_program, synergy_program, admission_program, domain, course, qualification, percentage, location, passkey, visit_date, entry_time, created_at) VALUES ('{$name}', '{$contact}', '{$purpose}', '{$department}', '{$dean_department}', '{$cdc_program}', '{$synergy_program}', '{$admission_program}', '{$domain}', '{$course}', '{$qualification}', '{$percentage}', '{$location}', '{$passkey}', '{$visit_date}', '{$entry_time}', '{$now}')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true, "message" => "Visitor added.", "passkey" => $passkey]);
    } else {
        echo json_encode(["success" => false, "message" => $conn->error]);
    }
    exit;
}
?>
    echo "Error: " . $stmt->error;
}

// Close connection
$stmt->close();
$conn->close();
?>