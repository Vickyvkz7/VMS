<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Twilio\Rest\Client;

// Load Twilio credentials from environment variables
$account_sid = getenv('TWILIO_ACCOUNT_SID');
$auth_token = getenv('TWILIO_AUTH_TOKEN');
$twilio_number = getenv('TWILIO_NUMBER');
//-----------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Accept JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    $name = htmlspecialchars($input['name'] ?? '');
    $phone = preg_replace('/[^0-9]/', '', $input['phone'] ?? '');
    // Use the passkey provided from the POST data
    $passkey = isset($input['passkey']) ? preg_replace('/[^0-9]/', '', $input['passkey']) : '';
    if (!preg_match('/^\d{6}$/', $passkey)) {
        header('Content-Type: text/plain');
        echo 'ERROR: Invalid passkey.';
        exit;
    }
    // Validate phone number
    if (strlen($phone) < 10) {
        header('Content-Type: text/plain');
        echo 'ERROR: Invalid phone number.';
        exit;
    }
    // Message to send 
    $message = "Hello $name, thank you for visiting KPR College of Arts Science And Research! Your passkey is: $passkey";
    try {
        $client = new Client($account_sid, $auth_token);
        $client->messages->create(
            "+91$phone", 
            [
                'from' => $twilio_number,
                'body' => $message
            ]
        );
        header('Content-Type: text/plain');
        echo 'SUCCESS: Message sent to visitor! Passkey: ' . $passkey;
    } catch (Exception $e) {
        header('Content-Type: text/plain');
        echo 'ERROR: Failed to send message: ' . $e->getMessage();
    }
} else {
    header('Content-Type: text/plain');
    echo 'ERROR: Invalid request.';
}
?>
