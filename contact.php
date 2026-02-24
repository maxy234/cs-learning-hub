<?php
// At the VERY TOP of each PHP file (before any output)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Then include other files
require_once 'includes/db_connect.php';
?>


<?php
session_start();

$message_sent = false;
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Here you would typically send an email or save to database
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    // For demonstration, we'll just set a flag
    $message_sent = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - CS Learning Hub</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .contact-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 6rem 2rem 4rem;
            text-align: center;
        }
        
        .contact-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 4rem 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
        }
        
        .contact-info {
            background: var(--white);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: var(--shadow);
        }
        
        .contact-info h3 {
            margin-bottom: 2rem;
            color: var(--text-dark);
        }
        
        .info-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .info-icon {
            width: 50px;
            height: 50px;
            background: var(--background-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.2rem;
        }
        
        .info-content h4 {
            margin-bottom: 0.25rem;
        }
        
        .info-content p {
            color: var(--text-light);
        }
        
        .contact-form {
            background: var(--white);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: var(--shadow);
        }
        
        .contact-form h3 {
            margin-bottom: 2rem;
            color: var(--text-dark);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--background-light);
            border-radius: 5px;
            transition: var(--transition);
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        .map-container {
            margin-top: 4rem;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }
        
        .faq-section {
            max-width: 1200px;
            margin: 4rem auto;
            padding: 0 2rem;
        }
        
        .faq-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .faq-item {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: var(--shadow);
        }
        
        .faq-question {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }
        
        .faq-answer {
            color: var(--text-light);
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        
        body.dark-mode .contact-info,
        body.dark-mode .contact-form,
        body.dark-mode .faq-item {
            background: #2d2d2d;
            color: var(--white);
        }
        
        @media (max-width: 768px) {
            .contact-container {
                grid-template-columns: 1fr;
            }
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

    <div class="contact-header">
        <h1>Get in Touch</h1>
        <p>We're here to help and answer any questions you might have</p>
    </div>

    <div class="contact-container">
        <!-- Contact Information -->
        <div class="contact-info">
            <h3>Contact Information</h3>
            
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="info-content">
                    <h4>Visit Us</h4>
                    <p>123 Tech Street<br>Silicon Valley, CA 94025</p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="info-content">
                    <h4>Call Us</h4>
                    <p>+1 (555) 123-4567<br>Mon-Fri, 9am-6pm PST</p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="info-content">
                    <h4>Email Us</h4>
                    <p>support@cshub.com<br>info@cshub.com</p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="info-content">
                    <h4>Business Hours</h4>
                    <p>Monday - Friday: 9:00 AM - 6:00 PM<br>Saturday: 10:00 AM - 4:00 PM</p>
                </div>
            </div>
            
            <div style="margin-top: 2rem;">
                <h4>Follow Us</h4>
                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <a href="#" style="color: var(--primary-color); font-size: 1.5rem;"><i class="fab fa-facebook"></i></a>
                    <a href="#" style="color: var(--primary-color); font-size: 1.5rem;"><i class="fab fa-twitter"></i></a>
                    <a href="#" style="color: var(--primary-color); font-size: 1.5rem;"><i class="fab fa-linkedin"></i></a>
                    <a href="#" style="color: var(--primary-color); font-size: 1.5rem;"><i class="fab fa-github"></i></a>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="contact-form">
            <h3>Send Us a Message</h3>
            
            <?php if($message_sent): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i> Thank you for your message! We'll get back to you soon.
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Your Name *</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" class="form-control" id="subject" name="subject">
                </div>
                
                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">Send Message</button>
            </form>
        </div>
    </div>

    <!-- Map -->
    <div class="map-container">
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3168.636256520488!2d-122.088844684688!3d37.422065979824!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x808fba02425dad8f%3A0x6c296c5d3b9d5a1a!2sGoogleplex!5e0!3m2!1sen!2sus!4v1620000000000!5m2!1sen!2sus" 
            width="100%" 
            height="450" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy">
        </iframe>
    </div>

    <!-- FAQ Section -->
    <div class="faq-section">
        <h2 class="section-title">Frequently Asked Questions</h2>
        <div class="faq-grid">
            <div class="faq-item">
                <div class="faq-question">How do I create an account?</div>
                <div class="faq-answer">Click on the "Login" button and select "Sign up". Fill in your details and you're ready to start learning!</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">Is the content free?</div>
                <div class="faq-answer">Yes, all our core content is completely free. We also offer premium features for advanced learners.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">How can I track my progress?</div>
                <div class="faq-answer">Once you create an account, you can access your personal dashboard to track your learning progress.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">Can I download materials?</div>
                <div class="faq-answer">Yes, registered users can download study materials, notes, and previous year papers.</div>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>