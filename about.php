<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - CS Learning Hub</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .about-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 6rem 2rem 4rem;
            text-align: center;
        }
        
        .about-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 4rem 2rem;
        }
        
        .mission-section {
            text-align: center;
            margin-bottom: 4rem;
        }
        
        .mission-text {
            font-size: 1.2rem;
            line-height: 1.8;
            color: var(--text-dark);
            max-width: 800px;
            margin: 2rem auto;
        }
        
        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
        }
        
        .value-card {
            background: var(--white);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: var(--shadow);
            text-align: center;
            transition: var(--transition);
        }
        
        .value-card:hover {
            transform: translateY(-10px);
        }
        
        .value-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .team-section {
            margin-top: 4rem;
        }
        
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .team-member {
            background: var(--white);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
            text-align: center;
            transition: var(--transition);
        }
        
        .team-member:hover {
            transform: translateY(-5px);
        }
        
        .member-image {
            width: 100%;
            height: 250px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }
        
        .member-info {
            padding: 1.5rem;
        }
        
        .member-name {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }
        
        .member-role {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .member-bio {
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        .stats-section {
            background: var(--background-light);
            padding: 4rem 2rem;
            margin-top: 4rem;
        }
        
        .stats-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            text-align: center;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .stat-label {
            color: var(--text-dark);
            font-size: 1.1rem;
        }
        
        body.dark-mode .mission-text,
        body.dark-mode .stat-label {
            color: var(--white);
        }
        
        body.dark-mode .value-card,
        body.dark-mode .team-member {
            background: #2d2d2d;
            color: var(--white);
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">
                <i class="fas fa-code"></i> CS Learning Hub
            </a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="pages/courses.php">Courses</a></li>
                <li><a href="pages/coding.php">Practice</a></li>
                <li><a href="pages/blog.php">Blog</a></li>
                <li><a href="pages/career.php">Career</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="pages/dashboard.php">Dashboard</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="about-header">
        <h1>About CS Learning Hub</h1>
        <p>Empowering the next generation of computer scientists</p>
    </div>

    <div class="about-content">
        <!-- Mission Section -->
        <div class="mission-section">
            <h2 class="section-title">Our Mission</h2>
            <p class="mission-text">
                At CS Learning Hub, we're on a mission to make quality computer science education accessible to everyone. 
                We believe that learning should be engaging, practical, and tailored to each student's needs. 
                Our platform combines comprehensive study materials, hands-on coding practice, and career guidance 
                to help students succeed in their academic and professional journeys.
            </p>
        </div>

        <!-- Values Section -->
        <h2 class="section-title">Our Core Values</h2>
        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>Quality Education</h3>
                <p>We provide high-quality, up-to-date learning materials curated by industry experts.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Community First</h3>
                <p>We foster a supportive community where students learn and grow together.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-rocket"></i>
                </div>
                <h3>Practical Learning</h3>
                <p>We emphasize hands-on practice and real-world applications of concepts.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <h3>Student Success</h3>
                <p>We're committed to helping every student achieve their career goals.</p>
            </div>
        </div>

        <!-- Team Section -->
        <div class="team-section">
            <h2 class="section-title">Meet Our Team</h2>
            <div class="team-grid">
                <div class="team-member">
                    <div class="member-image"></div>
                    <div class="member-info">
                        <h3 class="member-name">Dr. Sarah Johnson</h3>
                        <div class="member-role">Founder & CEO</div>
                        <p class="member-bio">PhD in Computer Science with 15+ years of teaching experience.</p>
                    </div>
                </div>
                <div class="team-member">
                    <div class="member-image"></div>
                    <div class="member-info">
                        <h3 class="member-name">Prof. Michael Chen</h3>
                        <div class="member-role">Head of Curriculum</div>
                        <p class="member-bio">Former Google engineer passionate about CS education.</p>
                    </div>
                </div>
                <div class="team-member">
                    <div class="member-image"></div>
                    <div class="member-info">
                        <h3 class="member-name">Emily Rodriguez</h3>
                        <div class="member-role">Community Manager</div>
                        <p class="member-bio">Dedicated to building an inclusive learning community.</p>
                    </div>
                </div>
                <div class="team-member">
                    <div class="member-image"></div>
                    <div class="member-info">
                        <h3 class="member-name">David Kumar</h3>
                        <div class="member-role">Technical Lead</div>
                        <p class="member-bio">Full-stack developer with expertise in EdTech.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="stats-section">
        <div class="stats-grid">
            <div>
                <div class="stat-number">50,000+</div>
                <div class="stat-label">Active Students</div>
            </div>
            <div>
                <div class="stat-number">500+</div>
                <div class="stat-label">Video Tutorials</div>
            </div>
            <div>
                <div class="stat-number">1000+</div>
                <div class="stat-label">Coding Problems</div>
            </div>
            <div>
                <div class="stat-number">95%</div>
                <div class="stat-label">Success Rate</div>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>