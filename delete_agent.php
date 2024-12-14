<?php
   include 'db_connect.php';

   // Enable error reporting
   error_reporting(E_ALL);
   ini_set('display_errors', 1);

   // Get the JSON input
   $data = json_decode(file_get_contents('php://input'), true);

   // Check if agentID is provided
   if (isset($data['agentID'])) {
       $agentID = $data['agentID'];

       // Prepare the SQL statement
       $stmt = $conn->prepare("DELETE FROM Agent WHERE agentID = ?");
       $stmt->bind_param("s", $agentID);

       // Execute the statement
       if ($stmt->execute()) {
           echo json_encode(['success' => true]);
       } else {
           echo json_encode(['success' => false, 'error' => 'Database error: ' . $stmt->error]);
       }

       $stmt->close();
   } else {
       echo json_encode(['success' => false, 'error' => 'Invalid input']);
   }

   $conn->close();
   ?>
