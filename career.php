<?php
require_once '../includes/db_connect.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career Portal - CS Learning Hub</title>
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
                <li><a href="courses.php">Courses</a></li>
                <li><a href="coding.php">Practice</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="career.php" class="active">Career</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                <?php else: ?>
                    <li><a href="../login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="career-container">
        <h1 class="section-title">Career & Internship Portal</h1>

        <!-- Internship Listings -->
        <section style="margin-bottom: 3rem;">
            <h2>Latest Internships</h2>
            <div class="internship-list">
                <?php
                $sql = "SELECT * FROM internships WHERE deadline >= CURDATE() ORDER BY deadline ASC";
                $result = $conn->query($sql);
                
                if($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        ?>
                        <div class="internship-card">
                            <div class="company-header">
                                <span class="company-name"><?php echo htmlspecialchars($row['company_name']); ?></span>
                                <span class="deadline">Deadline: <?php echo $row['deadline']; ?></span>
                            </div>
                            <h3><?php echo htmlspecialchars($row['position']); ?></h3>
                            <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($row['location']); ?></p>
                            <p><strong>Eligibility:</strong> <?php echo htmlspecialchars($row['eligibility']); ?></p>
                            <a href="<?php echo htmlspecialchars($row['apply_link']); ?>" target="_blank" class="btn btn-primary">Apply Now</a>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>No internships available at the moment. Check back later!</p>";
                }
                ?>
            </div>
        </section>

        <!-- Resume Builder Section -->
        <section style="margin-bottom: 3rem;">
            <h2>Resume Builder</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <h3>Professional Templates</h3>
                    <p>Choose from multiple ATS-friendly resume templates</p>
                    <a href="#" class="btn btn-secondary">Browse Templates</a>
                </div>
                <div class="feature-card">
                    <h3>Resume Tips</h3>
                    <p>Expert tips to make your resume stand out</p>
                    <a href="#" class="btn btn-secondary">Read Guide</a>
                </div>
                <div class="feature-card">
                    <h3>PDF Download</h3>
                    <p>Download your resume in PDF format instantly</p>
                    <a href="#" class="btn btn-secondary">Create Resume</a>
                </div>
            </div>
        </section>

        <!-- Interview Preparation -->
        <section style="margin-bottom: 3rem;">
            <h2>Interview Preparation</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <h3>HR Questions</h3>
                    <p>Common HR interview questions and answers</p>
                    <a href="#" class="btn btn-secondary">Practice</a>
                </div>
                <div class="feature-card">
                    <h3>Technical Questions</h3>
                    <p>Company-wise technical interview questions</p>
                    <a href="#" class="btn btn-secondary">View Questions</a>
                </div>
                <div class="feature-card">
                    <h3>Mock Interviews</h3>
                    <p>Book a mock interview session</p>
                    <a href="#" class="btn btn-secondary">Book Now</a>
                </div>
            </div>
        </section>

        <!-- Top Hiring Companies -->
        <section>
            <h2>Top Hiring Companies</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <i class="fab fa-google" style="font-size: 3rem; color: #4285F4;"></i>
                    <h3>Google</h3>
                    <p>View openings →</p>
                </div>
                <div class="feature-card">
                    <i class="fab fa-microsoft" style="font-size: 3rem; color: #00A4EF;"></i>
                    <h3>Microsoft</h3>
                    <p>View openings →</p>
                </div>
                <div class="feature-card">
                    <i class="fab fa-amazon" style="font-size: 3rem; color: #FF9900;"></i>
                    <h3>Amazon</h3>
                    <p>View openings →</p>
                </div>
                <div class="feature-card">
                    <i class="fab fa-meta" style="font-size: 3rem; color: #0668E1;"></i>
                    <h3>Meta</h3>
                    <p>View openings →</p>
                </div>
            </div>
        </section>
    </div>

    <script src="../assets/js/main.js"></script>
</body>
</html>