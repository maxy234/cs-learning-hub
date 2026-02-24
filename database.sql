-- Create database
CREATE DATABASE IF NOT EXISTS cs_learning_hub;
USE cs_learning_hub;

-- Users table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    role ENUM('student', 'admin') DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Courses/Subjects table
CREATE TABLE subjects (
    subject_id INT AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(100) NOT NULL,
    description TEXT,
    semester INT,
    difficulty ENUM('beginner', 'intermediate', 'advanced') DEFAULT 'beginner',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Study materials table
CREATE TABLE study_materials (
    material_id INT AUTO_INCREMENT PRIMARY KEY,
    subject_id INT,
    title VARCHAR(200) NOT NULL,
    material_type ENUM('notes', 'video', 'practice', 'exam_paper') NOT NULL,
    file_path VARCHAR(255),
    video_url VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id) ON DELETE CASCADE
);

-- Coding problems table
CREATE TABLE coding_problems (
    problem_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    category ENUM('arrays', 'strings', 'linked_list', 'trees', 'graphs', 'dynamic_programming') NOT NULL,
    difficulty ENUM('easy', 'medium', 'hard') DEFAULT 'easy',
    sample_input TEXT,
    sample_output TEXT,
    constraints TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Blog posts table
CREATE TABLE blog_posts (
    post_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    category ENUM('tutorials', 'ai_ml', 'cybersecurity', 'career_advice', 'project_ideas') NOT NULL,
    featured_image VARCHAR(255),
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
);

-- Comments table
CREATE TABLE comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT,
    user_id INT,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES blog_posts(post_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Internship listings table
CREATE TABLE internships (
    internship_id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(100) NOT NULL,
    position VARCHAR(200) NOT NULL,
    location VARCHAR(100),
    eligibility TEXT,
    apply_link VARCHAR(255),
    deadline DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Coding submissions table
CREATE TABLE submissions (
    submission_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    problem_id INT,
    code TEXT,
    language VARCHAR(50),
    status ENUM('pending', 'accepted', 'wrong_answer', 'error') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (problem_id) REFERENCES coding_problems(problem_id) ON DELETE CASCADE
);

-- Insert sample data
INSERT INTO users (username, email, password, full_name, role) VALUES
('admin', 'admin@cshub.com', '$2y$10$YourHashedPasswordHere', 'Administrator', 'admin'),
('john_doe', 'john@example.com', '$2y$10$YourHashedPasswordHere', 'John Doe', 'student');

INSERT INTO subjects (subject_name, description, semester, difficulty) VALUES
('Data Structures & Algorithms', 'Learn fundamental data structures and algorithms', 3, 'intermediate'),
('Operating Systems', 'Understanding OS concepts and design', 4, 'intermediate'),
('Database Management Systems', 'SQL, normalization, and database design', 3, 'beginner'),
('Computer Networks', 'Network protocols and architecture', 5, 'intermediate'),
('Web Development', 'Modern web technologies and frameworks', 4, 'beginner');

INSERT INTO coding_problems (title, description, category, difficulty, sample_input, sample_output, constraints) VALUES
('Two Sum', 'Given an array of integers nums and an integer target, return indices of the two numbers that add up to target.', 'arrays', 'easy', 'nums = [2,7,11,15], target = 9', '[0,1]', '2 <= nums.length <= 104'),
('Reverse String', 'Write a function that reverses a string.', 'strings', 'easy', '["h","e","l","l","o"]', '["o","l","l","e","h"]', '1 <= s.length <= 105');

INSERT INTO blog_posts (user_id, title, content, category) VALUES
(1, 'How to Master Data Structures', 'Content about mastering data structures...', 'tutorials'),
(1, 'Getting Started with Machine Learning', 'Content about ML basics...', 'ai_ml');

INSERT INTO internships (company_name, position, location, eligibility, apply_link, deadline) VALUES
('Google', 'Software Engineering Intern', 'Mountain View, CA', 'CS students graduating in 2025', 'https://careers.google.com', '2024-12-31'),
('Microsoft', 'Data Science Intern', 'Redmond, WA', 'CS/IT students with GPA > 3.5', 'https://careers.microsoft.com', '2024-11-30');