<?php
require_once '../includes/db_connect.php';
session_start();

// Redirect if not logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user stats
$stats_sql = "SELECT 
    (SELECT COUNT(*) FROM submissions WHERE user_id = ?) as total_submissions,
    (SELECT COUNT(*) FROM submissions WHERE user_id = ? AND status = 'accepted') as solved_problems,
    (SELECT COUNT(*) FROM comments WHERE user_id = ?) as total_comments
    FROM dual";
$stmt = $conn->prepare($stats_sql);
$stmt->bind_param("iii", $user_id, $user_id, $user_id);
$stmt->execute();
$stats_result = $stmt->get_result();
$stats = $stats_result->fetch_assoc();

// Fetch recent submissions
$submissions_sql = "SELECT s.*, p.title as problem_title 
                   FROM submissions s 
                   LEFT JOIN coding_problems p ON s.problem_id = p.problem_id 
                   WHERE s.user_id = ? 
                   ORDER BY s.created_at DESC 
                   LIMIT 5";
$stmt = $conn->prepare($submissions_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$submissions_result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CS Learning Hub</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .dashboard-container {
            padding: 6rem 2rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .welcome-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: var(--shadow);
            text-align: center;
            transition: var(--transition);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--text-dark);
        }
        
        .stat-label {
            color: var(--text-light);
            margin-top: 0.5rem;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }
        
        .dashboard-card {
            background: var(--white);
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }
        
        .dashboard-card h3 {
            margin-bottom: 1rem;
            color: var(--text-dark);
        }
        
        .activity-list {
            list-style: none;
        }
        
        .activity-item {
            padding: 1rem;
            border-bottom: 1px solid var(--background-light);
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--background-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
        }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-title {
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .activity-time {
            font-size: 0.875rem;
            color: var(--text-light);
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-accepted {
            background: #d4edda;
            color: #155724;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-error {
            background: #f8d7da;
            color: #721c24;
        }
        
        .progress-section {
            margin-top: 1rem;
        }
        
        .progress-bar {
            width: 100%;
            height: 10px;
            background: var(--background-light);
            border-radius: 5px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: var(--primary-color);
            transition: width 0.3s ease;
        }
        
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .action-btn {
            padding: 1rem;
            border: 2px solid var(--primary-color);
            border-radius: 10px;
            background: transparent;
            color: var(--primary-color);
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
            text-decoration: none;
        }
        
        .action-btn:hover {
            background: var(--primary-color);
            color: white;
        }
        
        body.dark-mode .stat-card,
        body.dark-mode .dashboard-card {
            background: #2d2d2d;
            color: var(--white);
        }
        
        body.dark-mode .stat-value,
        body.dark-mode .dashboard-card h3,
        body.dark-mode .activity-title {
            color: var(--white);
        }
        
        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
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
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="dashboard-container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['full_name'] ?: $_SESSION['username']); ?>!</h1>
            <p>Track your progress, continue learning, and achieve your goals.</p>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-code"></i>
                </div>
                <div class="stat-value"><?php echo $stats['solved_problems'] ?: 0; ?></div>
                <div class="stat-label">Problems Solved</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="stat-value"><?php echo $stats['total_submissions'] ?: 0; ?></div>
                <div class="stat-label">Total Submissions</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-value">8</div>
                <div class="stat-label">Courses Enrolled</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-comment"></i>
                </div>
                <div class="stat-value"><?php echo $stats['total_comments'] ?: 0; ?></div>
                <div class="stat-label">Comments</div>
            </div>
        </div>

        <!-- Main Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- Left Column -->
            <div>
                <!-- Recent Activity -->
                <div class="dashboard-card">
                    <h3>Recent Activity</h3>
                    <ul class="activity-list">
                        <?php
                        if($submissions_result->num_rows > 0) {
                            while($submission = $submissions_result->fetch_assoc()) {
                                $status_class = 'status-' . strtolower($submission['status']);
                                ?>
                                <li class="activity-item">
                                    <div class="activity-icon">
                                        <i class="fas fa-code"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">
                                            Submitted solution for "<?php echo htmlspecialchars($submission['problem_title']); ?>"
                                        </div>
                                        <div class="activity-time">
                                            <?php echo date('M d, Y H:i', strtotime($submission['created_at'])); ?>
                                        </div>
                                    </div>
                                    <span class="status-badge <?php echo $status_class; ?>">
                                        <?php echo ucfirst($submission['status']); ?>
                                    </span>
                                </li>
                                <?php
                            }
                        } else {
                            // Sample activities
                            ?>
                            <li class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-code"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">Solved "Two Sum" problem</div>
                                    <div class="activity-time">2 hours ago</div>
                                </div>
                                <span class="status-badge status-accepted">Accepted</span>
                            </li>
                            <li class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">Completed DSA course module</div>
                                    <div class="activity-time">Yesterday</div>
                                </div>
                                <span class="status-badge status-accepted">Completed</span>
                            </li>
                            <li class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-comment"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">Commented on "Getting Started with ML"</div>
                                    <div class="activity-time">3 days ago</div>
                                </div>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                    <a href="#" style="color: var(--primary-color); text-decoration: none;">View all activity →</a>
                </div>

                <!-- Progress Tracking -->
                <div class="dashboard-card">
                    <h3>Learning Progress</h3>
                    <div class="progress-section">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span>Data Structures & Algorithms</span>
                            <span>75%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 75%;"></div>
                        </div>
                    </div>
                    <div class="progress-section">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span>Web Development</span>
                            <span>45%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 45%;"></div>
                        </div>
                    </div>
                    <div class="progress-section">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span>Database Management</span>
                            <span>60%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 60%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <!-- Quick Actions -->
                <div class="dashboard-card">
                    <h3>Quick Actions</h3>
                    <div class="quick-actions">
                        <a href="coding.php" class="action-btn">
                            <i class="fas fa-code"></i>
                            <div>Practice Coding</div>
                        </a>
                        <a href="courses.php" class="action-btn">
                            <i class="fas fa-book"></i>
                            <div>Browse Courses</div>
                        </a>
                        <a href="career.php" class="action-btn">
                            <i class="fas fa-briefcase"></i>
                            <div>View Internships</div>
                        </a>
                        <a href="blog.php" class="action-btn">
                            <i class="fas fa-blog"></i>
                            <div>Read Blog</div>
                        </a>
                    </div>
                </div>

                <!-- Saved Items -->
                <div class="dashboard-card">
                    <h3>Saved Items</h3>
                    <ul class="activity-list">
                        <li class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-bookmark"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Dynamic Programming Cheat Sheet</div>
                                <div class="activity-time">Saved 2 days ago</div>
                            </div>
                        </li>
                        <li class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-bookmark"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Google Interview Experience</div>
                                <div class="activity-time">Saved 5 days ago</div>
                            </div>
                        </li>
                        <li class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-bookmark"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">System Design Interview Guide</div>
                                <div class="activity-time">Saved 1 week ago</div>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Upcoming Deadlines -->
                <div class="dashboard-card">
                    <h3>Upcoming Deadlines</h3>
                    <ul class="activity-list">
                        <li class="activity-item">
                            <div class="activity-icon" style="color: #e74c3c;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Google Internship Application</div>
                                <div class="activity-time">Due in 3 days</div>
                            </div>
                        </li>
                        <li class="activity-item">
                            <div class="activity-icon" style="color: #f39c12;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Weekly Coding Challenge</div>
                                <div class="activity-time">Due in 5 days</div>
                            </div>
                        </li>
                        <li class="activity-item">
                            <div class="activity-icon" style="color: #27ae60;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">DSA Course Quiz</div>
                                <div class="activity-time">Due in 7 days</div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/main.js"></script>
</body>
</html>