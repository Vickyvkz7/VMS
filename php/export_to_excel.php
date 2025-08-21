<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "visitor_management"); 

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all data from the database
$sql = "SELECT * FROM visitors";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Set headers for the Excel file
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=visitors_data.xls");

    // Output column headers
    $fields = $result->fetch_fields();
    foreach ($fields as $field) {
        echo $field->name . "\t";
    }
    echo "\n";

    // Output rows
    while ($row = $result->fetch_assoc()) {
        echo implode("\t", array_map(function ($value) {
            return str_replace("\t", " ", $value); // Replace tabs to avoid breaking the format
        }, $row)) . "\n";
    }
} else {
    echo "No data found.";
}

$conn->close();
?>
