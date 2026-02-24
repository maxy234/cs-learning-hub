<?php
require_once '../includes/db_connect.php';
session_start();

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to comment']);
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'];
    $comment = $_POST['comment'];
    
    $sql = "INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $post_id, $user_id, $comment);
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Comment added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add comment']);
    }
    
    $stmt->close();
}
?>