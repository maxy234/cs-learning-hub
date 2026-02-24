<?php
require_once '../includes/db_connect.php';
session_start();

// Check if user is admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Get statistics
$stats_sql = "SELECT 
    (SELECT COUNT(*) FROM users) as total_users,
    (SELECT COUNT(*) FROM subjects) as total_subjects,
    (SELECT COUNT(*) FROM coding_problems) as total_problems,
    (SELECT COUNT(*) FROM blog_posts) as total_posts,
    (SELECT COUNT(*) FROM internships) as total_internships
    FROM dual";
$stats_result = $conn->query($stats_sql);
$stats = $stats_result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - CS Learning Hub</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background: var(--text-dark);
            color: white;
            padding: 2rem 0;
        }
        
        .sidebar-header {
            padding: 0 1.5rem;
            margin-bottom: 2rem;
        }
        
        .sidebar-menu {
            list-style: none;
        }
        
        .sidebar-menu li {
            margin-bottom: 0.5rem;
        }
        
        .sidebar-menu a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: var(--transition);
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: var(--primary-color);
            color: white;
        }
        
        .sidebar-menu i {
            width: 20px;
            margin-right: 0.5rem;
        }
        
        .main-content {
            flex: 1;
            background: var(--background-light);
            padding: 2rem;
        }
        
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .admin-title {
            font-size: 2rem;
            color: var(--text-dark);
        }
        
        .admin-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .admin-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: var(--shadow);
        }
        
        .admin-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .admin-card-icon {
            width: 50px;
            height: 50px;
            background: var(--background-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.5rem;
        }
        
        .admin-card-value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--text-dark);
        }
        
        .admin-card-label {
            color: var(--text-light);
        }
        
        .recent-table {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
        }
        
        .recent-table table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .recent-table th,
        .recent-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--background-light);
        }
        
        .recent-table th {
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .action-btn {
            padding: 0.25rem 0.5rem;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            margin: 0 0.25rem;
        }
        
        .edit-btn {
            background: #ffc107;
            color: #212529;
        }
        
        .delete-btn {
            background: #dc3545;
            color: white;
        }
        
        @media (max-width: 768px) {
            .admin-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Admin Panel</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="#" class="active"><i class="fas fa-dashboard"></i> Dashboard</a></li>
                <li><a href="#"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="#"><i class="fas fa-book"></i> Subjects</a></li>
                <li><a href="#"><i class="fas fa-code"></i> Problems</a></li>
                <li><a href="#"><i class="fas fa-blog"></i> Blog Posts</a></li>
                <li><a href="#"><i class="fas fa-briefcase"></i> Internships</a></li>
                <li><a href="#"><i class="fas fa-comment"></i> Comments</a></li>
                <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="admin-header">
                <h1 class="admin-title">Dashboard</h1>
                <div>
                    <span>Welcome, Admin</span>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="admin-stats">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <div class="admin-card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="admin-card-value"><?php echo $stats['total_users']; ?></div>
                    <div class="admin-card-label">Total Users</div>
                </div>
                
                <div class="admin-card">
                    <div class="admin-card-header">
                        <div class="admin-card-icon">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                    <div class="admin-card-value"><?php echo $stats['total_subjects']; ?></div>
                    <div class="admin-card-label">Subjects</div>
                </div>
                
                <div class="admin-card">
                    <div class="admin-card-header">
                        <div class="admin-card-icon">
                            <i class="fas fa-code"></i>
                        </div>
                    </div>
                    <div class="admin-card-value"><?php echo $stats['total_problems']; ?></div>
                    <div class="admin-card-label">Coding Problems</div>
                </div>
                
                <div class="admin-card">
                    <div class="admin-card-header">
                        <div class="admin-card-icon">
                            <i class="fas fa-blog"></i>
                        </div>
                    </div>
                    <div class="admin-card-value"><?php echo $stats['total_posts']; ?></div>
                    <div class="admin-card-label">Blog Posts</div>
                </div>
                
                <div class="admin-card">
                    <div class="admin-card-header">
                        <div class="admin-card-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                    </div>
                    <div class="admin-card-value"><?php echo $stats['total_internships']; ?></div>
                    <div class="admin-card-label">Internships</div>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="recent-table">
                <h3 style="margin-bottom: 1rem;">Recent Users</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Full Name</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $users_sql = "SELECT * FROM users ORDER BY created_at DESC LIMIT 5";
                        $users_result = $conn->query($users_sql);
                        
                        if($users_result->num_rows > 0) {
                            while($user = $users_result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $user['user_id'] . "</td>";
                                echo "<td>" . htmlspecialchars($user['username']) . "</td>";
                                echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($user['full_name']) . "</td>";
                                echo "<td>" . ucfirst($user['role']) . "</td>";
                                echo "<td>" . date('M d, Y', strtotime($user['created_at'])) . "</td>";
                                echo "<td>";
                                echo "<button class='action-btn edit-btn' onclick='editUser(" . $user['user_id'] . ")'><i class='fas fa-edit'></i></button>";
                                echo "<button class='action-btn delete-btn' onclick='deleteUser(" . $user['user_id'] . ")'><i class='fas fa-trash'></i></button>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Quick Actions -->
            <div style="margin-top: 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <button class="btn btn-primary" onclick="addNew()">
                    <i class="fas fa-plus"></i> Add New Subject
                </button>
                <button class="btn btn-primary" onclick="addNew()">
                    <i class="fas fa-plus"></i> Add Coding Problem
                </button>
                <button class="btn btn-primary" onclick="addNew()">
                    <i class="fas fa-plus"></i> Add Blog Post
                </button>
                <button class="btn btn-primary" onclick="addNew()">
                    <i class="fas fa-plus"></i> Add Internship
                </button>
            </div>
        </div>
    </div>

    <script>
    function editUser(id) {
        alert('Edit user: ' + id);
        // Implement edit functionality
    }
    
    function deleteUser(id) {
        if(confirm('Are you sure you want to delete this user?')) {
            alert('Delete user: ' + id);
            // Implement delete functionality
        }
    }
    
    function addNew() {
        alert('Add new item functionality');
        // Implement add new functionality
    }
    </script>
</body>
</html>