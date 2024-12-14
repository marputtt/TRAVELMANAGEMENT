<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'error.log'); // Log errors to a file
include 'db_connect.php'; // Include your database connection file

// Handle AJAX requests for deleting itineraries
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON input
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if the request is for deleting an itinerary
    if (isset($data['itineraryID']) && isset($data['itineraryDay'])) {
        $itineraryID = $data['itineraryID'];
        $itineraryDay = $data['itineraryDay'];

        // Prepare the SQL statement
        $stmt = $conn->prepare("DELETE FROM Itinerary WHERE itineraryID = ? AND itineraryDay = ?");
        $stmt->bind_param("si", $itineraryID, $itineraryDay);

        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Database error: ' . $stmt->error]);
        }

        $stmt->close();
        $conn->close();
        exit; // Exit after handling the AJAX request
    }

    // If the request is invalid
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    $conn->close();
    exit; // Exit after handling the AJAX request
}
?>
