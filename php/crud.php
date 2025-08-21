<?php
date_default_timezone_set('Asia/Kolkata');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle CRUD actions
$action = $_GET['action'] ?? '';

if ($action === 'create') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $conn->prepare("INSERT INTO visitors (name, contact, purpose, location, visitDate, visitTime, exit_time) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "sssssss",
        $data['name'],
        $data['contact'],
        $data['purpose'],
        $data['location'],
        $data['visitDate'],
        $data['visitTime'],
        isset($data['exitTime']) ? $data['exitTime'] : null
    );
    $stmt->execute();
    echo "Visitor added successfully!";
} elseif ($action === 'read') {
    if (isset($_GET['id'])) {
        // Fetch a single visitor by ID
        $id = intval($_GET['id']);
        $query = "SELECT * FROM visitors WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $visitor = $result->fetch_assoc();

        if ($visitor) {
            echo json_encode($visitor);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Visitor not found']);
        }
        $stmt->close();
    } else {
        // Fetch all visitors
        $query = "SELECT * FROM visitors";
        $result = $conn->query($query);
        $visitors = [];

        while ($row = $result->fetch_assoc()) {
            $visitors[] = $row;
        }

        echo json_encode($visitors);
    }
} elseif ($action === 'update') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $data = json_decode(file_get_contents('php://input'), true);
        // Fetch current values
        $stmt = $conn->prepare("SELECT name, contact, purpose FROM visitors WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $current = $result->fetch_assoc();
        $stmt->close();
        // Use new value if provided, else keep old
        $name = isset($data['name']) && $data['name'] !== '' ? $data['name'] : $current['name'];
        $contact = isset($data['contact']) && $data['contact'] !== '' ? $data['contact'] : $current['contact'];
        $purpose = isset($data['purpose']) && $data['purpose'] !== '' ? $data['purpose'] : $current['purpose'];
        $stmt = $conn->prepare("UPDATE visitors SET name = ?, contact = ?, purpose = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $contact, $purpose, $id);
        $stmt->execute();
        echo "Visitor updated successfully!";
        $stmt->close();
    } else if (isset($_GET['passkey'])) {
        $passkey = $_GET['passkey'];
        $data = json_decode(file_get_contents('php://input'), true);
        // Fetch current values
        $stmt = $conn->prepare("SELECT name, contact, purpose FROM visitors WHERE passkey = ?");
        $stmt->bind_param("s", $passkey);
        $stmt->execute();
        $result = $stmt->get_result();
        $current = $result->fetch_assoc();
        $stmt->close();
        // Use new value if provided, else keep old
        $name = isset($data['name']) && $data['name'] !== '' ? $data['name'] : $current['name'];
        $contact = isset($data['contact']) && $data['contact'] !== '' ? $data['contact'] : $current['contact'];
        $purpose = isset($data['purpose']) && $data['purpose'] !== '' ? $data['purpose'] : $current['purpose'];
        $stmt = $conn->prepare("UPDATE visitors SET name = ?, contact = ?, purpose = ? WHERE passkey = ?");
        $stmt->bind_param("ssss", $name, $contact, $purpose, $passkey);
        $stmt->execute();
        echo "Visitor updated successfully!";
        $stmt->close();
    }
} elseif ($action === 'delete') {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $query = "DELETE FROM visitors WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo 'Visitor deleted successfully';
        } else {
            http_response_code(404);
            echo 'Visitor not found';
        }
        $stmt->close();
    } else if (isset($_GET['passkey'])) {
        $passkey = $_GET['passkey'];
        $query = "DELETE FROM visitors WHERE passkey = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $passkey);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo 'Visitor deleted successfully';
        } else {
            http_response_code(404);
            echo 'Visitor not found';
        }
        $stmt->close();
    }
}

// Automatically delete records older than 2 months
if ($action === 'auto_cleanup') {
    $query = "DELETE FROM visitors WHERE visit_date < DATE_SUB(CURDATE(), INTERVAL 2 MONTH)";
    $conn->query($query);
    echo "Old records deleted.";
    $conn->close();
    exit;
}

// Close connection
$conn->close();
?>
