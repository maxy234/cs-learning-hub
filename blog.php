<?php
require_once '../includes/db_connect.php';
session_start();

// Fetch blog posts
$posts_sql = "SELECT b.*, u.username, u.full_name 
              FROM blog_posts b 
              LEFT JOIN users u ON b.user_id = u.user_id 
              ORDER BY b.created_at DESC";
$posts_result = $conn->query($posts_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - CS Learning Hub</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .blog-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 4rem 2rem;
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .blog-categories {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }
        
        .category-btn {
            padding: 0.5rem 1.5rem;
            border: 2px solid var(--primary-color);
            background: transparent;
            color: var(--primary-color);
            border-radius: 25px;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
        }
        
        .category-btn:hover,
        .category-btn.active {
            background: var(--primary-color);
            color: white;
        }
        
        .blog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .blog-card {
            background: var(--white);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            cursor: pointer;
        }
        
        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        }
        
        .blog-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            position: relative;
            overflow: hidden;
        }
        
        .blog-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }
        
        .blog-card:hover .blog-image img {
            transform: scale(1.05);
        }
        
        .blog-content {
            padding: 1.5rem;
        }
        
        .blog-meta {
            display: flex;
            gap: 1rem;
            color: var(--text-light);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }
        
        .blog-title {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
        }
        
        .blog-excerpt {
            color: var(--text-light);
            margin-bottom: 1rem;
            line-height: 1.6;
        }
        
        .read-more {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .read-more:hover {
            gap: 1rem;
        }
        
        .category-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: var(--accent-color);
            color: white;
            padding: 0.25rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            z-index: 1;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin: 3rem 0;
        }
        
        .page-btn {
            padding: 0.5rem 1rem;
            border: 1px solid var(--primary-color);
            background: transparent;
            color: var(--primary-color);
            border-radius: 5px;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .page-btn:hover,
        .page-btn.active {
            background: var(--primary-color);
            color: white;
        }
        
        body.dark-mode .blog-card {
            background: #2d2d2d;
        }
        
        body.dark-mode .blog-title {
            color: var(--white);
        }
        
        .search-bar {
            max-width: 600px;
            margin: 2rem auto;
            display: flex;
            gap: 0.5rem;
        }
        
        .search-input {
            flex: 1;
            padding: 0.75rem;
            border: 2px solid var(--background-light);
            border-radius: 5px;
            font-size: 1rem;
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        .search-btn {
            padding: 0.75rem 2rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .search-btn:hover {
            background: var(--secondary-color);
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
                <li><a href="blog.php" class="active">Blog</a></li>
                <li><a href="career.php">Career</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                <?php else: ?>
                    <li><a href="../login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="blog-header">
        <h1>Tech Blog & Tutorials</h1>
        <p>Stay updated with the latest in technology and computer science</p>
    </div>

    <!-- Search Bar -->
    <div class="search-bar">
        <input type="text" class="search-input" id="searchInput" placeholder="Search articles...">
        <button class="search-btn" onclick="searchPosts()"><i class="fas fa-search"></i> Search</button>
    </div>

    <!-- Categories -->
    <div class="blog-categories">
        <button class="category-btn active" data-category="all">All</button>
        <button class="category-btn" data-category="tutorials">Programming Tutorials</button>
        <button class="category-btn" data-category="ai_ml">AI & Machine Learning</button>
        <button class="category-btn" data-category="cybersecurity">Cybersecurity</button>
        <button class="category-btn" data-category="career_advice">Career Advice</button>
        <button class="category-btn" data-category="project_ideas">Project Ideas</button>
    </div>

    <!-- Blog Posts Grid -->
    <div class="blog-grid" id="blogGrid">
        <?php
        if($posts_result->num_rows > 0) {
            while($post = $posts_result->fetch_assoc()) {
                $excerpt = substr(strip_tags($post['content']), 0, 150) . '...';
                $category_display = str_replace('_', ' ', $post['category']);
                ?>
                <div class="blog-card" data-category="<?php echo $post['category']; ?>" onclick="viewPost(<?php echo $post['post_id']; ?>)">
                    <div class="blog-image">
                        <span class="category-badge"><?php echo ucwords($category_display); ?></span>
                        <?php if($post['featured_image']): ?>
                            <img src="../<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                        <?php else: ?>
                            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));"></div>
                        <?php endif; ?>
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">
                            <span><i class="far fa-calendar"></i> <?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                            <span><i class="far fa-user"></i> <?php echo htmlspecialchars($post['full_name'] ?: $post['username']); ?></span>
                            <span><i class="far fa-eye"></i> <?php echo $post['views']; ?> views</span>
                        </div>
                        <h3 class="blog-title"><?php echo htmlspecialchars($post['title']); ?></h3>
                        <p class="blog-excerpt"><?php echo $excerpt; ?></p>
                        <a href="blog-post.php?id=<?php echo $post['post_id']; ?>" class="read-more">
                            Read More <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <?php
            }
        } else {
            // Sample blog posts if no data in database
            $sample_posts = [
                [
                    'title' => 'How to Master Data Structures in 30 Days',
                    'category' => 'tutorials',
                    'author' => 'John Doe',
                    'date' => 'Mar 15, 2024',
                    'views' => 1234,
                    'excerpt' => 'Learn the most effective strategies to master data structures quickly and efficiently...'
                ],
                [
                    'title' => 'Getting Started with Machine Learning: A Beginner\'s Guide',
                    'category' => 'ai_ml',
                    'author' => 'Jane Smith',
                    'date' => 'Mar 12, 2024',
                    'views' => 2345,
                    'excerpt' => 'Everything you need to know to start your machine learning journey...'
                ],
                [
                    'title' => 'Top 10 Cybersecurity Threats in 2024',
                    'category' => 'cybersecurity',
                    'author' => 'Mike Johnson',
                    'date' => 'Mar 10, 2024',
                    'views' => 3456,
                    'excerpt' => 'Stay protected by understanding the latest cybersecurity threats and how to prevent them...'
                ],
                [
                    'title' => 'How to Crack Technical Interviews at FAANG',
                    'category' => 'career_advice',
                    'author' => 'Sarah Wilson',
                    'date' => 'Mar 8, 2024',
                    'views' => 4567,
                    'excerpt' => 'Expert tips and strategies to ace your technical interviews at top tech companies...'
                ],
                [
                    'title' => '20 Innovative Final Year Project Ideas for CS Students',
                    'category' => 'project_ideas',
                    'author' => 'Alex Chen',
                    'date' => 'Mar 5, 2024',
                    'views' => 5678,
                    'excerpt' => 'Get inspired with these unique and challenging project ideas for your final year...'
                ],
                [
                    'title' => 'Understanding Dynamic Programming: From Basics to Advanced',
                    'category' => 'tutorials',
                    'author' => 'David Lee',
                    'date' => 'Mar 3, 2024',
                    'views' => 6789,
                    'excerpt' => 'A comprehensive guide to understanding and implementing dynamic programming solutions...'
                ]
            ];
            
            foreach($sample_posts as $post) {
                ?>
                <div class="blog-card" data-category="<?php echo $post['category']; ?>" onclick="viewPost(1)">
                    <div class="blog-image">
                        <span class="category-badge"><?php echo ucwords(str_replace('_', ' ', $post['category'])); ?></span>
                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));"></div>
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">
                            <span><i class="far fa-calendar"></i> <?php echo $post['date']; ?></span>
                            <span><i class="far fa-user"></i> <?php echo $post['author']; ?></span>
                            <span><i class="far fa-eye"></i> <?php echo $post['views']; ?> views</span>
                        </div>
                        <h3 class="blog-title"><?php echo $post['title']; ?></h3>
                        <p class="blog-excerpt"><?php echo $post['excerpt']; ?></p>
                        <a href="blog-post.php?id=1" class="read-more">
                            Read More <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <button class="page-btn active">1</button>
        <button class="page-btn">2</button>
        <button class="page-btn">3</button>
        <button class="page-btn">4</button>
        <button class="page-btn">5</button>
        <button class="page-btn"><i class="fas fa-chevron-right"></i></button>
    </div>

    <script>
    function viewPost(postId) {
        window.location.href = 'blog-post.php?id=' + postId;
    }
    
    function searchPosts() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const posts = document.querySelectorAll('.blog-card');
        
        posts.forEach(post => {
            const title = post.querySelector('.blog-title').textContent.toLowerCase();
            const excerpt = post.querySelector('.blog-excerpt').textContent.toLowerCase();
            
            if(title.includes(searchTerm) || excerpt.includes(searchTerm)) {
                post.style.display = 'block';
            } else {
                post.style.display = 'none';
            }
        });
    }
    
    // Category filtering
    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active button
            document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const category = this.dataset.category;
            const posts = document.querySelectorAll('.blog-card');
            
            posts.forEach(post => {
                if(category === 'all' || post.dataset.category === category) {
                    post.style.display = 'block';
                } else {
                    post.style.display = 'none';
                }
            });
        });
    });
    
    // Enter key for search
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if(e.key === 'Enter') {
            searchPosts();
        }
    });
    </script>

    <script src="../assets/js/main.js"></script>
</body>
</html>