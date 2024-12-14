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
    $packageID = $data['packageID'] ?? null;
    $packageName = $data['packageName'] ?? null;
    $destinationID = $data['destinationID'] ?? null;
    $packageTransport = $data['packageTransport'] ?? null;
    $packageSDate = $data['packageSDate'] ?? null;
    $packageEDate = $data['packageEDate'] ?? null;
    $packageTDays = $data['packageTDays'] ?? null;
    $itineraryID = $data['itineraryID'] ?? null;
    $itineraryDay = $data['itineraryDay'] ?? null;
    $packageAccommodation = $data['packageAccommodation'] ?? null;
    $paymentID = $data['paymentID'] ?? null;
    $packagePrice = $data['packagePrice'] ?? null;

    // Prepare the SQL query
    $query = "UPDATE Package SET
        packageName = ?, 
        destinationID = ?, 
        packageTransport = ?, 
        packageSDate = ?, 
        packageEDate = ?, 
        packageTDays = ?, 
        itineraryID = ?, 
        itineraryDay = ?, 
        packageAccommodation = ?, 
        paymentID = ?, 
        packagePrice = ?
        WHERE packageID = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param(
            "ssssssssssss",
            $packageName,
            $destinationID,
            $packageTransport,
            $packageSDate,
            $packageEDate,
            $packageTDays,
            $itineraryID,
            $itineraryDay,
            $packageAccommodation,
            $paymentID,
            $packagePrice,
            $packageID
        );

        // Execute and check for success
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Package updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No data received.']);
}
$conn->close();
?>
