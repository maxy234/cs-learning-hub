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

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $db->escapeString($_POST['username']);
    $password = $_POST['password'];
    
    $sql = "SELECT user_id, username, password, full_name, role FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if(password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CS Learning Hub</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="auth-container">
        <form class="auth-form" method="POST" action="">
            <h2>Welcome Back!</h2>
            
            <?php if($error): ?>
                <div class="error-message" style="color: red; margin-bottom: 1rem; text-align: center;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
            
            <p style="text-align: center; margin-top: 1rem;">
                Don't have an account? <a href="signup.php">Sign up</a>
            </p>
        </form>
    </div>
</body>
</html>