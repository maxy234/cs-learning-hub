<?php
require_once '../includes/db_connect.php';

header('Content-Type: application/json');

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    // Validate email
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address']);
        exit();
    }
    
    // Here you would typically save to a newsletter table
    // For demonstration, we'll just return success
    
    echo json_encode(['success' => true, 'message' => 'Successfully subscribed to newsletter!']);
}
?>