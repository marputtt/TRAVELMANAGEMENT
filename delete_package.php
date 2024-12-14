<?php
include 'db_connect.php'; // Include your database connection file

// Get the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

// Prepare and bind
$stmt = $conn->prepare("DELETE FROM Package WHERE packageID = ?");
$stmt->bind_param("s", $data['packageID']);

// Execute the statement
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Package ID not found']);
    }
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
