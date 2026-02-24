<?php
require_once '../includes/db_connect.php';
session_start();

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to submit code']);
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $problem_id = $_POST['problem_id'];
    $code = $_POST['code'];
    $language = $_POST['language'];
    
    // Here you would typically:
    // 1. Save the code to a temporary file
    // 2. Compile/run the code in a sandboxed environment
    // 3. Compare output with expected output
    // 4. Update the database with the result
    
    // For demonstration, we'll simulate a successful submission
    $status = rand(0, 1) ? 'accepted' : 'wrong_answer';
    
    $sql = "INSERT INTO submissions (user_id, problem_id, code, language, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisss", $user_id, $problem_id, $code, $language, $status);
    
    if($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => $status == 'accepted' ? 'All test cases passed!' : 'Wrong answer on test case 3',
            'status' => $status,
            'passed' => $status == 'accepted' ? 10 : 7,
            'total' => 10
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save submission']);
    }
    
    $stmt->close();
}
?>