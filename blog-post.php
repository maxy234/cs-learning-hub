<?php
require_once '../includes/db_connect.php';
session_start();

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Update view count
$update_sql = "UPDATE blog_posts SET views = views + 1 WHERE post_id = ?";
$stmt = $conn->prepare($update_sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();

// Fetch blog post
$post_sql = "SELECT b.*, u.username, u.full_name 
             FROM blog_posts b 
             LEFT JOIN users u ON b.user_id = u.user_id 
             WHERE b.post_id = ?";
$stmt = $conn->prepare($post_sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$post_result = $stmt->get_result();
$post = $post_result->fetch_assoc();

// Fetch comments
$comments_sql = "SELECT c.*, u.username, u.full_name 
                 FROM comments c 
                 LEFT JOIN users u ON c.user_id = u.user_id 
                 WHERE c.post_id = ? 
                 ORDER BY c.created_at DESC";
$stmt = $conn->prepare($comments_sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$comments_result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title'] ?: 'Blog Post'); ?> - CS Learning Hub</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .blog-post-container {
            max-width: 900px;
            margin: 6rem auto 2rem;
            padding: 0 2rem;
        }
        
        .blog-post-header {
            margin-bottom: 2rem;
        }
        
        .blog-post-title {
            font-size: 2.5rem;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }
        
        .blog-post-meta {
            display: flex;
            gap: 2rem;
            color: var(--text-light);
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }
        
        .blog-post-image {
            width: 100%;
            height: 400px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 10px;
            margin-bottom: 2rem;
            overflow: hidden;
        }
        
        .blog-post-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .blog-post-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--text-dark);
            margin-bottom: 3rem;
        }
        
        .blog-post-content h2 {
            margin: 2rem 0 1rem;
        }
        
        .blog-post-content p {
            margin-bottom: 1.5rem;
        }
        
        .blog-post-content pre {
            background: var(--background-light);
            padding: 1rem;
            border-radius: 5px;
            overflow-x: auto;
            margin-bottom: 1.5rem;
        }
        
        .blog-post-content code {
            background: var(--background-light);
            padding: 0.2rem 0.4rem;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
        
        .share-buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 3rem;
            flex-wrap: wrap;
        }
        
        .share-btn {
            padding: 0.5rem 1.5rem;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .share-btn.facebook { background: #3b5998; }
        .share-btn.twitter { background: #1da1f2; }
        .share-btn.linkedin { background: #0077b5; }
        .share-btn.whatsapp { background: #25d366; }
        
        .share-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .comments-section {
            margin-top: 3rem;
        }
        
        .comment-form {
            background: var(--white);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }
        
        .comment {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: var(--shadow);
            margin-bottom: 1rem;
        }
        
        .comment-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        
        .comment-author {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .comment-date {
            color: var(--text-light);
            font-size: 0.875rem;
        }
        
        .comment-content {
            color: var(--text-dark);
            line-height: 1.6;
        }
        
        .related-posts {
            margin-top: 4rem;
        }
        
        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .related-card {
            background: var(--white);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            cursor: pointer;
        }
        
        .related-card:hover {
            transform: translateY(-5px);
        }
        
        .related-image {
            height: 150px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }
        
        .related-content {
            padding: 1rem;
        }
        
        .related-title {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
        
        body.dark-mode .blog-post-title,
        body.dark-mode .blog-post-content {
            color: var(--white);
        }
        
        body.dark-mode .comment,
        body.dark-mode .comment-form,
        body.dark-mode .related-card {
            background: #2d2d2d;
            color: var(--white);
        }
        
        @media (max-width: 768px) {
            .blog-post-title {
                font-size: 2rem;
            }
            
            .blog-post-image {
                height: 250px;
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
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                <?php else: ?>
                    <li><a href="../login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="blog-post-container">
        <!-- Breadcrumb -->
        <div style="margin-bottom: 2rem;">
            <a href="blog.php" style="color: var(--primary-color);">← Back to Blog</a>
        </div>

        <?php if($post): ?>
            <!-- Blog Post Header -->
            <div class="blog-post-header">
                <h1 class="blog-post-title"><?php echo htmlspecialchars($post['title']); ?></h1>
                <div class="blog-post-meta">
                    <span><i class="far fa-user"></i> <?php echo htmlspecialchars($post['full_name'] ?: $post['username']); ?></span>
                    <span><i class="far fa-calendar"></i> <?php echo date('F d, Y', strtotime($post['created_at'])); ?></span>
                    <span><i class="far fa-clock"></i> 5 min read</span>
                    <span><i class="far fa-eye"></i> <?php echo $post['views']; ?> views</span>
                </div>
            </div>

            <!-- Blog Post Image -->
            <div class="blog-post-image">
                <?php if($post['featured_image']): ?>
                    <img src="../<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                <?php endif; ?>
            </div>

            <!-- Blog Post Content -->
            <div class="blog-post-content">
                <?php echo nl2br(htmlspecialchars($post['content'])); ?>
            </div>

            <!-- Share Buttons -->
            <div class="share-buttons">
                <button class="share-btn facebook" onclick="share('facebook')">
                    <i class="fab fa-facebook-f"></i> Share
                </button>
                <button class="share-btn twitter" onclick="share('twitter')">
                    <i class="fab fa-twitter"></i> Tweet
                </button>
                <button class="share-btn linkedin" onclick="share('linkedin')">
                    <i class="fab fa-linkedin-in"></i> Share
                </button>
                <button class="share-btn whatsapp" onclick="share('whatsapp')">
                    <i class="fab fa-whatsapp"></i> Share
                </button>
            </div>

            <!-- Comments Section -->
            <div class="comments-section">
                <h2>Comments (<?php echo $comments_result->num_rows; ?>)</h2>

                <!-- Comment Form -->
                <div class="comment-form">
                    <h3>Leave a Comment</h3>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <form id="commentForm" onsubmit="submitComment(event, <?php echo $post_id; ?>)">
                            <div class="form-group">
                                <textarea class="form-control" id="commentContent" rows="4" placeholder="Write your comment..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Post Comment</button>
                        </form>
                    <?php else: ?>
                        <p>Please <a href="../login.php">login</a> to leave a comment.</p>
                    <?php endif; ?>
                </div>

                <!-- Comments List -->
                <div id="commentsList">
                    <?php
                    if($comments_result->num_rows > 0) {
                        while($comment = $comments_result->fetch_assoc()) {
                            ?>
                            <div class="comment">
                                <div class="comment-header">
                                    <span class="comment-author"><?php echo htmlspecialchars($comment['full_name'] ?: $comment['username']); ?></span>
                                    <span class="comment-date"><?php echo date('M d, Y H:i', strtotime($comment['created_at'])); ?></span>
                                </div>
                                <div class="comment-content">
                                    <?php echo nl2br(htmlspecialchars($comment['comment'])); ?>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        // Sample comments
                        ?>
                        <div class="comment">
                            <div class="comment-header">
                                <span class="comment-author">John Doe</span>
                                <span class="comment-date">Mar 15, 2024 14:30</span>
                            </div>
                            <div class="comment-content">
                                Great article! Very helpful for beginners. Looking forward to more content like this.
                            </div>
                        </div>
                        <div class="comment">
                            <div class="comment-header">
                                <span class="comment-author">Jane Smith</span>
                                <span class="comment-date">Mar 14, 2024 09:15</span>
                            </div>
                            <div class="comment-content">
                                Thanks for sharing! This cleared up a lot of confusion I had about the topic.
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <!-- Related Posts -->
            <div class="related-posts">
                <h2>Related Posts</h2>
                <div class="related-grid">
                    <div class="related-card" onclick="viewPost(2)">
                        <div class="related-image"></div>
                        <div class="related-content">
                            <h4 class="related-title">Getting Started with Python</h4>
                            <p style="color: var(--text-light); font-size: 0.875rem;">5 min read</p>
                        </div>
                    </div>
                    <div class="related-card" onclick="viewPost(3)">
                        <div class="related-image"></div>
                        <div class="related-content">
                            <h4 class="related-title">Understanding Algorithms</h4>
                            <p style="color: var(--text-light); font-size: 0.875rem;">8 min read</p>
                        </div>
                    </div>
                    <div class="related-card" onclick="viewPost(4)">
                        <div class="related-image"></div>
                        <div class="related-content">
                            <h4 class="related-title">Web Development Basics</h4>
                            <p style="color: var(--text-light); font-size: 0.875rem;">6 min read</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 4rem;">
                <h2>Post not found</h2>
                <p>The blog post you're looking for doesn't exist.</p>
                <a href="blog.php" class="btn btn-primary">Back to Blog</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
    function viewPost(postId) {
        window.location.href = 'blog-post.php?id=' + postId;
    }
    
    function share(platform) {
        const url = encodeURIComponent(window.location.href);
        const title = encodeURIComponent(document.querySelector('.blog-post-title').textContent);
        
        let shareUrl = '';
        
        switch(platform) {
            case 'facebook':
                shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                break;
            case 'twitter':
                shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
                break;
            case 'linkedin':
                shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${url}`;
                break;
            case 'whatsapp':
                shareUrl = `https://api.whatsapp.com/send?text=${title} ${url}`;
                break;
        }
        
        window.open(shareUrl, '_blank', 'width=600,height=400');
    }
    
    function submitComment(event, postId) {
        event.preventDefault();
        
        const comment = document.getElementById('commentContent').value;
        
        <?php if(!isset($_SESSION['user_id'])): ?>
            window.location.href = '../login.php';
            return;
        <?php endif; ?>
        
        // Submit comment via AJAX
        const formData = new FormData();
        formData.append('post_id', postId);
        formData.append('comment', comment);
        
        fetch('../api/add-comment.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Reload comments
                location.reload();
            } else {
                alert('Failed to post comment. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
    </script>

    <script src="../assets/js/main.js"></script>
</body>
</html>