<?php
// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/db_connect.php';

if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';
$success = '';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $db->escapeString($_POST['username']);
    $email = $db->escapeString($_POST['email']);
    $full_name = $db->escapeString($_POST['full_name']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Check if username or email exists
    $check_sql = "SELECT user_id FROM users WHERE username = ? OR email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if($check_result->num_rows > 0) {
        $error = "Username or email already exists!";
    } else {
        $sql = "INSERT INTO users (username, email, full_name, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $email, $full_name, $password);
        
        if($stmt->execute()) {
            $success = "Registration successful! You can now login.";
        } else {
            $error = "Registration failed. Please try again.";
        }
        $stmt->close();
    }
    $check_stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - CS Learning Hub</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="auth-container">
        <form class="auth-form" method="POST" action="">
            <h2>Create Account</h2>
            
            <?php if($error): ?>
                <div class="error-message" style="color: red; margin-bottom: 1rem; text-align: center;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if($success): ?>
                <div class="success-message" style="color: green; margin-bottom: 1rem; text-align: center;">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name" required>
            </div>
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">Sign Up</button>
            
            <p style="text-align: center; margin-top: 1rem;">
                Already have an account? <a href="login.php">Login</a>
            </p>
        </form>
    </div>
</body>
</html>