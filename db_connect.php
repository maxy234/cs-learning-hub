<?php
require_once 'config.php';

class Database {
    private $connection;
    
    public function __construct() {
        $this->connect();
    }
    
    private function connect() {
        // First connect without database to create it if needed
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS);
        
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
        
        // Create database if it doesn't exist
        $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
        if ($this->connection->query($sql) === TRUE) {
            // Select the database
            $this->connection->select_db(DB_NAME);
            
            // Create tables if they don't exist
            $this->createTables();
        } else {
            die("Error creating database: " . $this->connection->error);
        }
        
        $this->connection->set_charset("utf8");
    }
    
    private function createTables() {
        // Users table
        $sql = "CREATE TABLE IF NOT EXISTS users (
            user_id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            full_name VARCHAR(100),
            role ENUM('student', 'admin') DEFAULT 'student',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->connection->query($sql);
        
        // Subjects table
        $sql = "CREATE TABLE IF NOT EXISTS subjects (
            subject_id INT AUTO_INCREMENT PRIMARY KEY,
            subject_name VARCHAR(100) NOT NULL,
            description TEXT,
            semester INT,
            difficulty ENUM('beginner', 'intermediate', 'advanced') DEFAULT 'beginner',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->connection->query($sql);
        
        // Study materials table
        $sql = "CREATE TABLE IF NOT EXISTS study_materials (
            material_id INT AUTO_INCREMENT PRIMARY KEY,
            subject_id INT,
            title VARCHAR(200) NOT NULL,
            material_type ENUM('notes', 'video', 'practice', 'exam_paper') NOT NULL,
            file_path VARCHAR(255),
            video_url VARCHAR(255),
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (subject_id) REFERENCES subjects(subject_id) ON DELETE CASCADE
        )";
        $this->connection->query($sql);
        
        // Coding problems table
        $sql = "CREATE TABLE IF NOT EXISTS coding_problems (
            problem_id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(200) NOT NULL,
            description TEXT NOT NULL,
            category ENUM('arrays', 'strings', 'linked_list', 'trees', 'graphs', 'dynamic_programming') NOT NULL,
            difficulty ENUM('easy', 'medium', 'hard') DEFAULT 'easy',
            sample_input TEXT,
            sample_output TEXT,
            constraints TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->connection->query($sql);
        
        // Blog posts table
        $sql = "CREATE TABLE IF NOT EXISTS blog_posts (
            post_id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            title VARCHAR(200) NOT NULL,
            content TEXT NOT NULL,
            category ENUM('tutorials', 'ai_ml', 'cybersecurity', 'career_advice', 'project_ideas') NOT NULL,
            featured_image VARCHAR(255),
            views INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
        )";
        $this->connection->query($sql);
        
        // Comments table
        $sql = "CREATE TABLE IF NOT EXISTS comments (
            comment_id INT AUTO_INCREMENT PRIMARY KEY,
            post_id INT,
            user_id INT,
            comment TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (post_id) REFERENCES blog_posts(post_id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
        )";
        $this->connection->query($sql);
        
        // Internships table
        $sql = "CREATE TABLE IF NOT EXISTS internships (
            internship_id INT AUTO_INCREMENT PRIMARY KEY,
            company_name VARCHAR(100) NOT NULL,
            position VARCHAR(200) NOT NULL,
            location VARCHAR(100),
            eligibility TEXT,
            apply_link VARCHAR(255),
            deadline DATE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->connection->query($sql);
        
        // Submissions table
        $sql = "CREATE TABLE IF NOT EXISTS submissions (
            submission_id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            problem_id INT,
            code TEXT,
            language VARCHAR(50),
            status ENUM('pending', 'accepted', 'wrong_answer', 'error') DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
            FOREIGN KEY (problem_id) REFERENCES coding_problems(problem_id) ON DELETE CASCADE
        )";
        $this->connection->query($sql);
        
        // Insert sample data if tables are empty
        $this->insertSampleData();
    }
    
    private function insertSampleData() {
        // Check if users table is empty
        $result = $this->connection->query("SELECT COUNT(*) as count FROM users");
        $row = $result->fetch_assoc();
        
        if($row['count'] == 0) {
            // Insert admin user (password: admin123)
            $hashed_password = password_hash('admin123', PASSWORD_DEFAULT);
            $this->connection->query("INSERT INTO users (username, email, password, full_name, role) VALUES 
                ('admin', 'admin@cshub.com', '$hashed_password', 'Administrator', 'admin')");
            
            // Insert sample user (password: password123)
            $hashed_password = password_hash('password123', PASSWORD_DEFAULT);
            $this->connection->query("INSERT INTO users (username, email, password, full_name, role) VALUES 
                ('john_doe', 'john@example.com', '$hashed_password', 'John Doe', 'student')");
        }
        
        // Check if subjects table is empty
        $result = $this->connection->query("SELECT COUNT(*) as count FROM subjects");
        $row = $result->fetch_assoc();
        
        if($row['count'] == 0) {
            $this->connection->query("INSERT INTO subjects (subject_name, description, semester, difficulty) VALUES
                ('Data Structures & Algorithms', 'Learn fundamental data structures and algorithms', 3, 'intermediate'),
                ('Operating Systems', 'Understanding OS concepts and design', 4, 'intermediate'),
                ('Database Management Systems', 'SQL, normalization, and database design', 3, 'beginner'),
                ('Computer Networks', 'Network protocols and architecture', 5, 'intermediate'),
                ('Web Development', 'Modern web technologies and frameworks', 4, 'beginner')");
        }
        
        // Check if coding_problems table is empty
        $result = $this->connection->query("SELECT COUNT(*) as count FROM coding_problems");
        $row = $result->fetch_assoc();
        
        if($row['count'] == 0) {
            $this->connection->query("INSERT INTO coding_problems (title, description, category, difficulty, sample_input, sample_output, constraints) VALUES
                ('Two Sum', 'Given an array of integers nums and an integer target, return indices of the two numbers that add up to target.', 'arrays', 'easy', '[2,7,11,15], target = 9', '[0,1]', '2 <= nums.length <= 104'),
                ('Reverse String', 'Write a function that reverses a string.', 'strings', 'easy', '[\"h\",\"e\",\"l\",\"l\",\"o\"]', '[\"o\",\"l\",\"l\",\"e\",\"h\"]', '1 <= s.length <= 105')");
        }
        
        // Check if blog_posts table is empty
        $result = $this->connection->query("SELECT COUNT(*) as count FROM blog_posts");
        $row = $result->fetch_assoc();
        
        if($row['count'] == 0) {
            $this->connection->query("INSERT INTO blog_posts (user_id, title, content, category) VALUES
                (1, 'How to Master Data Structures', 'Content about mastering data structures...', 'tutorials'),
                (1, 'Getting Started with Machine Learning', 'Content about ML basics...', 'ai_ml')");
        }
        
        // Check if internships table is empty
        $result = $this->connection->query("SELECT COUNT(*) as count FROM internships");
        $row = $result->fetch_assoc();
        
        if($row['count'] == 0) {
            $this->connection->query("INSERT INTO internships (company_name, position, location, eligibility, apply_link, deadline) VALUES
                ('Google', 'Software Engineering Intern', 'Mountain View, CA', 'CS students graduating in 2025', 'https://careers.google.com', DATE_ADD(CURDATE(), INTERVAL 30 DAY)),
                ('Microsoft', 'Data Science Intern', 'Redmond, WA', 'CS/IT students with GPA > 3.5', 'https://careers.microsoft.com', DATE_ADD(CURDATE(), INTERVAL 45 DAY))");
        }
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function prepare($sql) {
        return $this->connection->prepare($sql);
    }
    
    public function escapeString($string) {
        return $this->connection->real_escape_string($string);
    }
    
    public function __destruct() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}

// Create database instance
$db = new Database();
$conn = $db->getConnection();
?>