<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php'; // Ensure your database connection file is included

// Get JSON input
$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    // Extract data safely with null fallback
    $bookingID = $data['bookingID'] ?? null;
    $agentID = $data['agentID'] ?? null;
    $customerID = $data['customerID'] ?? null;
    $packageID = $data['packageID'] ?? null;
    $bookedDate = $data['bookedDate'] ?? null;

    // Check if bookingID already exists
    $checkQuery = "SELECT COUNT(*) FROM Booking WHERE bookingID = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $bookingID);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        // If bookingID exists, update the bookedDate
        $updateQuery = "UPDATE Booking SET bookedDate = ? WHERE bookingID = ?";
        $updateStmt = $conn->prepare($updateQuery);
        if ($updateStmt) {
            $updateStmt->bind_param("ss", $bookedDate, $bookingID);
            if ($updateStmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Booking date updated successfully.']);
            } else {
                echo json_encode(['success' => false, 'error' => $updateStmt->error]);
            }
            $updateStmt->close();
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
    } else {
        // If bookingID does not exist, insert a new record
        $insertQuery = "INSERT INTO Booking (bookingID, agentID, customerID, packageID, bookedDate) VALUES (?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);

        if ($insertStmt) {
            $insertStmt->bind_param("sssss", $bookingID, $agentID, $customerID, $packageID, $bookedDate);
            if ($insertStmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Booking added successfully.']);
            } else {
                echo json_encode(['success' => false, 'error' => $insertStmt->error]);
            }
            $insertStmt->close();
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No data received.']);
}

$conn->close();
?>
