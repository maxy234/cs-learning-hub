<?php
require_once '../includes/db_connect.php';
session_start();

$subject_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Fetch subject details
$subject_sql = "SELECT * FROM subjects WHERE subject_id = ?";
$stmt = $conn->prepare($subject_sql);
$stmt->bind_param("i", $subject_id);
$stmt->execute();
$subject_result = $stmt->get_result();
$subject = $subject_result->fetch_assoc();

// Fetch study materials
$materials_sql = "SELECT * FROM study_materials WHERE subject_id = ? ORDER BY material_type";
$stmt = $conn->prepare($materials_sql);
$stmt->bind_param("i", $subject_id);
$stmt->execute();
$materials_result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($subject['subject_name']); ?> - CS Learning Hub</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .materials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .material-section {
            background: var(--white);
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
        }
        
        .material-list {
            list-style: none;
            margin-top: 1rem;
        }
        
        .material-list li {
            padding: 0.75rem;
            border-bottom: 1px solid var(--background-light);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .material-list li:last-child {
            border-bottom: none;
        }
        
        .material-list a {
            color: var(--text-dark);
            text-decoration: none;
            flex: 1;
        }
        
        .material-list a:hover {
            color: var(--primary-color);
        }
        
        .download-btn {
            color: var(--primary-color);
            font-size: 1.2rem;
        }
        
        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            margin-bottom: 1rem;
        }
        
        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 10px;
        }
        
        .topic-summary {
            background: var(--background-light);
            padding: 1.5rem;
            border-radius: 10px;
            margin-top: 2rem;
        }
        
        .topic-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        .topic-tag {
            background: var(--primary-color);
            color: var(--white);
            padding: 0.25rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
        }
        
        body.dark-mode .material-section {
            background: #2d2d2d;
            color: var(--white);
        }
        
        body.dark-mode .material-list a {
            color: var(--white);
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="../index.php" class="logo">
                <i class="fas fa-code"></i> CS Learning Hub
            </a>
            <ul class="nav-links">
                <li><a href="../index.php">Home</a></li>
                <li><a href="courses.php">Courses</a></li>
                <li><a href="coding.php">Practice</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="career.php">Career</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                <?php else: ?>
                    <li><a href="../login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="subjects-container">
        <!-- Breadcrumb -->
        <div style="margin-bottom: 2rem;">
            <a href="courses.php" style="color: var(--primary-color);">← Back to Courses</a>
        </div>

        <!-- Subject Header -->
        <div style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; padding: 2rem; border-radius: 10px; margin-bottom: 2rem;">
            <h1><?php echo htmlspecialchars($subject['subject_name']); ?></h1>
            <p><?php echo htmlspecialchars($subject['description']); ?></p>
            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                <span><i class="fas fa-layer-group"></i> Semester: <?php echo $subject['semester']; ?></span>
                <span><i class="fas fa-signal"></i> Difficulty: <?php echo ucfirst($subject['difficulty']); ?></span>
            </div>
        </div>

        <!-- Study Materials -->
        <div class="materials-grid">
            <!-- Lecture Notes Section -->
            <div class="material-section">
                <h3><i class="fas fa-file-pdf" style="color: #e74c3c;"></i> Lecture Notes</h3>
                <ul class="material-list">
                    <?php
                    $materials_result->data_seek(0);
                    while($material = $materials_result->fetch_assoc()) {
                        if($material['material_type'] == 'notes') {
                            echo '<li>';
                            echo '<i class="fas fa-file-pdf" style="color: #e74c3c;"></i>';
                            echo '<a href="#" onclick="downloadMaterial(' . $material['material_id'] . ')">' . htmlspecialchars($material['title']) . '</a>';
                            echo '<a href="#" class="download-btn" onclick="downloadMaterial(' . $material['material_id'] . ')"><i class="fas fa-download"></i></a>';
                            echo '</li>';
                        }
                    }
                    
                    // Sample data if no notes exist
                    if($materials_result->num_rows == 0) {
                        echo '<li><i class="fas fa-file-pdf" style="color: #e74c3c;"></i> <a href="#">Chapter 1: Introduction</a> <a href="#" class="download-btn"><i class="fas fa-download"></i></a></li>';
                        echo '<li><i class="fas fa-file-pdf" style="color: #e74c3c;"></i> <a href="#">Chapter 2: Core Concepts</a> <a href="#" class="download-btn"><i class="fas fa-download"></i></a></li>';
                        echo '<li><i class="fas fa-file-pdf" style="color: #e74c3c;"></i> <a href="#">Chapter 3: Advanced Topics</a> <a href="#" class="download-btn"><i class="fas fa-download"></i></a></li>';
                    }
                    ?>
                </ul>
            </div>

            <!-- Video Tutorials Section -->
            <div class="material-section">
                <h3><i class="fas fa-video" style="color: #3498db;"></i> Video Tutorials</h3>
                <div class="video-container">
                    <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" frameborder="0" allowfullscreen></iframe>
                </div>
                <ul class="material-list">
                    <?php
                    $materials_result->data_seek(0);
                    while($material = $materials_result->fetch_assoc()) {
                        if($material['material_type'] == 'video') {
                            echo '<li>';
                            echo '<i class="fas fa-play" style="color: #3498db;"></i>';
                            echo '<a href="#" onclick="playVideo(' . $material['material_id'] . ')">' . htmlspecialchars($material['title']) . '</a>';
                            echo '</li>';
                        }
                    }
                    
                    if($materials_result->num_rows == 0) {
                        echo '<li><i class="fas fa-play" style="color: #3498db;"></i> <a href="#">Lecture 1: Introduction</a></li>';
                        echo '<li><i class="fas fa-play" style="color: #3498db;"></i> <a href="#">Lecture 2: Key Concepts</a></li>';
                        echo '<li><i class="fas fa-play" style="color: #3498db;"></i> <a href="#">Lecture 3: Examples</a></li>';
                    }
                    ?>
                </ul>
            </div>

            <!-- Practice Questions Section -->
            <div class="material-section">
                <h3><i class="fas fa-pencil-alt" style="color: #f39c12;"></i> Practice Questions</h3>
                <ul class="material-list">
                    <?php
                    $materials_result->data_seek(0);
                    while($material = $materials_result->fetch_assoc()) {
                        if($material['material_type'] == 'practice') {
                            echo '<li>';
                            echo '<i class="fas fa-question-circle" style="color: #f39c12;"></i>';
                            echo '<a href="#" onclick="startPractice(' . $material['material_id'] . ')">' . htmlspecialchars($material['title']) . '</a>';
                            echo '</li>';
                        }
                    }
                    
                    if($materials_result->num_rows == 0) {
                        echo '<li><i class="fas fa-question-circle" style="color: #f39c12;"></i> <a href="#">Set 1: Basic Questions</a></li>';
                        echo '<li><i class="fas fa-question-circle" style="color: #f39c12;"></i> <a href="#">Set 2: Intermediate Questions</a></li>';
                        echo '<li><i class="fas fa-question-circle" style="color: #f39c12;"></i> <a href="#">Set 3: Advanced Questions</a></li>';
                    }
                    ?>
                </ul>
                <button class="btn btn-primary" style="width: 100%; margin-top: 1rem;" onclick="generateQuiz()">Generate Practice Quiz</button>
            </div>

            <!-- Previous Year Papers Section -->
            <div class="material-section">
                <h3><i class="fas fa-file-alt" style="color: #27ae60;"></i> Previous Year Papers</h3>
                <ul class="material-list">
                    <?php
                    $materials_result->data_seek(0);
                    while($material = $materials_result->fetch_assoc()) {
                        if($material['material_type'] == 'exam_paper') {
                            echo '<li>';
                            echo '<i class="fas fa-file-alt" style="color: #27ae60;"></i>';
                            echo '<a href="#" onclick="downloadMaterial(' . $material['material_id'] . ')">' . htmlspecialchars($material['title']) . '</a>';
                            echo '<a href="#" class="download-btn" onclick="downloadMaterial(' . $material['material_id'] . ')"><i class="fas fa-download"></i></a>';
                            echo '</li>';
                        }
                    }
                    
                    if($materials_result->num_rows == 0) {
                        echo '<li><i class="fas fa-file-alt" style="color: #27ae60;"></i> <a href="#">Mid Semester 2023</a> <a href="#" class="download-btn"><i class="fas fa-download"></i></a></li>';
                        echo '<li><i class="fas fa-file-alt" style="color: #27ae60;"></i> <a href="#">End Semester 2023</a> <a href="#" class="download-btn"><i class="fas fa-download"></i></a></li>';
                        echo '<li><i class="fas fa-file-alt" style="color: #27ae60;"></i> <a href="#">Mid Semester 2022</a> <a href="#" class="download-btn"><i class="fas fa-download"></i></a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>

        <!-- Important Topics Summary -->
        <div class="topic-summary">
            <h3><i class="fas fa-star" style="color: var(--accent-color);"></i> Important Topics Summary</h3>
            <div class="topic-tags">
                <span class="topic-tag">Arrays & Strings</span>
                <span class="topic-tag">Linked Lists</span>
                <span class="topic-tag">Trees & Graphs</span>
                <span class="topic-tag">Sorting Algorithms</span>
                <span class="topic-tag">Searching Algorithms</span>
                <span class="topic-tag">Dynamic Programming</span>
                <span class="topic-tag">Time Complexity</span>
                <span class="topic-tag">Space Complexity</span>
            </div>
            
            <div style="margin-top: 2rem;">
                <h4>Key Points to Remember:</h4>
                <ul style="margin-top: 1rem; padding-left: 1.5rem;">
                    <li>Focus on understanding core concepts rather than memorization</li>
                    <li>Practice coding problems regularly to reinforce learning</li>
                    <li>Review previous year papers to understand exam pattern</li>
                    <li>Watch video tutorials for complex topics</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
    function downloadMaterial(materialId) {
        <?php if(!isset($_SESSION['user_id'])): ?>
            if(confirm('Please login to download materials')) {
                window.location.href = '../login.php';
            }
            return;
        <?php endif; ?>
        
        alert('Download started for material ID: ' + materialId);
        // Implement actual download logic here
    }
    
    function playVideo(videoId) {
        alert('Playing video: ' + videoId);
        // Implement video playback logic
    }
    
    function startPractice(practiceId) {
        window.location.href = 'coding.php?practice=' + practiceId;
    }
    
    function generateQuiz() {
        <?php if(!isset($_SESSION['user_id'])): ?>
            if(confirm('Please login to generate quiz')) {
                window.location.href = '../login.php';
            }
            return;
        <?php endif; ?>
        
        alert('Generating personalized quiz...');
        // Implement quiz generation logic
    }
    </script>

    <script src="../assets/js/main.js"></script>
</body>
</html>