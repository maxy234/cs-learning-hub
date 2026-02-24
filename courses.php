<?php
require_once '../includes/db_connect.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses - CS Learning Hub</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="../index.php" class="logo">
                <i class="fas fa-code"></i> CS Learning Hub
            </a>
            <ul class="nav-links">
                <li><a href="../index.php">Home</a></li>
                <li><a href="courses.php" class="active">Courses</a></li>
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
        <h1 class="section-title">Computer Science Courses</h1>
        
        <!-- Filters -->
        <div class="subject-filters">
            <button class="filter-btn active" data-filter="all">All</button>
            <button class="filter-btn" data-filter="beginner">Beginner</button>
            <button class="filter-btn" data-filter="intermediate">Intermediate</button>
            <button class="filter-btn" data-filter="advanced">Advanced</button>
        </div>

        <!-- Subjects Grid -->
        <div class="subjects-grid">
            <?php
            $sql = "SELECT * FROM subjects ORDER BY subject_name";
            $result = $conn->query($sql);
            
            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    ?>
                    <div class="subject-card filter-item" data-category="<?php echo $row['difficulty']; ?>">
                        <div class="subject-card-header">
                            <h3><?php echo htmlspecialchars($row['subject_name']); ?></h3>
                            <span class="difficulty-badge"><?php echo ucfirst($row['difficulty']); ?></span>
                        </div>
                        <div class="subject-card-body">
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <p><strong>Semester:</strong> <?php echo $row['semester']; ?></p>
                            
                            <div class="subject-resources">
                                <span class="resource-badge"><i class="fas fa-file-pdf"></i> Notes</span>
                                <span class="resource-badge"><i class="fas fa-video"></i> Videos</span>
                                <span class="resource-badge"><i class="fas fa-pencil-alt"></i> Practice</span>
                                <span class="resource-badge"><i class="fas fa-file-alt"></i> PYQs</span>
                            </div>
                            
                            <a href="subject-detail.php?id=<?php echo $row['subject_id']; ?>" class="btn btn-primary" style="margin-top: 1rem; width: 100%;">
                                View Materials
                            </a>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>

    <footer class="footer">
        <!-- Same footer as index.php -->
        <div class="footer-content">
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="courses.php">Courses</a></li>
                    <li><a href="coding.php">Coding Practice</a></li>
                    <li><a href="blog.php">Blog</a></li>
                    <li><a href="career.php">Career Portal</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 CS Learning Hub. All rights reserved.</p>
        </div>
    </footer>

    <script src="../assets/js/main.js"></script>
</body>
</html>