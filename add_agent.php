<?php
   include '/Applications/XAMPP/xamppfiles/htdocs/TRAVELMANAGEMENT/db_connect.php';

   // Get the JSON input
   $data = json_decode(file_get_contents('php://input'), true);
   

   // Check if the required fields are present
   if (isset($data['agentID'], $data['agentName'], $data['agentSex'], $data['agentDOB'], $data['agentPhone'])) {
       // Prepare and bind
       $stmt = $conn->prepare("INSERT INTO Agent (agentID, agentName, agentSex, agentDOB, agentPhone) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE agentName=?, agentSex=?, agentDOB=?, agentPhone=?");
       $stmt->bind_param("sssssssss", $data['agentID'], $data['agentName'], $data['agentSex'], $data['agentDOB'], $data['agentPhone'], $data['agentName'], $data['agentSex'], $data['agentDOB'], $data['agentPhone']);

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
   error_reporting(E_ALL);
ini_set('display_errors', 1);
   ?>
