<?php
header('Content-Type: application/json');
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $passkey = isset($_POST['passkey']) ? trim($_POST['passkey']) : '';
    if (!preg_match('/^\d{6}$/', $passkey)) {
        echo json_encode(['success' => false, 'message' => 'Invalid passkey format.']);
        exit;
    }
    $stmt = $conn->prepare("SELECT id, exit_time FROM visitors WHERE passkey = ?");
    $stmt->bind_param('s', $passkey);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if (!empty($row['exit_time']) && $row['exit_time'] !== '00:00:00') {
            echo json_encode(['success' => false, 'message' => 'Exit already recorded for this passkey.']);
            exit;
        }
        $update = $conn->prepare("UPDATE visitors SET exit_time = CURTIME() WHERE id = ?");
        $update->bind_param('i', $row['id']);
        if ($update->execute()) {
            echo json_encode(['success' => true, 'message' => 'Exit time recorded successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to record exit time.']);
        }
        $update->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Passkey not found.']);
    }
    $stmt->close();
    $conn->close();
    exit;
}
echo json_encode(['success' => false, 'message' => 'Invalid request.']);
exit;
