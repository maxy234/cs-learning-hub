<?php
// Simple database connection test
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'cs_learning_hub';

// Test connection without database
$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "✅ Connected to MySQL server successfully<br>";

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $db";
if ($conn->query($sql) === TRUE) {
    echo "✅ Database '$db' created or already exists<br>";
} else {
    echo "❌ Error creating database: " . $conn->error . "<br>";
}

// Select database
$conn->select_db($db);
echo "✅ Selected database '$db'<br>";

// Test creating a simple table
$sql = "CREATE TABLE IF NOT EXISTS test_table (
    id INT AUTO_INCREMENT PRIMARY KEY,
    test VARCHAR(100)
)";
if ($conn->query($sql) === TRUE) {
    echo "✅ Test table created successfully<br>";
} else {
    echo "❌ Error creating table: " . $conn->error . "<br>";
}

// Drop test table
$conn->query("DROP TABLE test_table");

$conn->close();
echo "<br>🎉 Database setup complete! You can now access your website.";
?>