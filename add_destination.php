<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'error.log');

header('Content-Type: application/json');

include 'db_connect.php';

// Handle AJAX requests for adding destinations
try {
    // Get the JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Validate input
    if (!$data) {
        throw new Exception('Invalid JSON input');
    }

    // Check if all required fields are present
    $requiredFields = ['destinationID', 'destinationContinent', 'destinationCountry', 'destinationCity'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || trim($data[$field]) === '') {
            throw new Exception("Missing or empty field: $field");
        }
    }

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO Destination (destinationID, destinationContinent, destinationCountry, destinationCity) 
                            VALUES (?, ?, ?, ?) 
                            ON DUPLICATE KEY UPDATE 
                            destinationContinent = ?, 
                            destinationCountry = ?, 
                            destinationCity = ?");

    // Prepare variables
    $destinationID = $data['destinationID'];
    $destinationContinent = $data['destinationContinent'];
    $destinationCountry = $data['destinationCountry'];
    $destinationCity = $data['destinationCity'];

    // Bind parameters
    $stmt->bind_param("sssssss", 
        $destinationID, $destinationContinent, $destinationCountry, $destinationCity,
        $destinationContinent, $destinationCountry, $destinationCity
    );

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception('Database error: ' . $stmt->error);
    }

    $stmt->close();
} catch (Exception $e) {
    // Log the full error
    error_log($e->getMessage());
    
    // Return a clean JSON error response
    echo json_encode([
        'success' => false, 
        'error' => $e->getMessage()
    ]);
} finally {
    // Ensure connection is closed
    if (isset($conn)) {
        $conn->close();
    }
}
exit;
?>
