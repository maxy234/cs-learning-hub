<?php
require_once '../includes/db_connect.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coding Practice - CS Learning Hub</title>
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
                <li><a href="coding.php" class="active">Practice</a></li>
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

    <div class="coding-container">
        <!-- Problem Categories Sidebar -->
        <div class="problem-sidebar">
            <h3>Problem Categories</h3>
            <ul class="problem-categories">
                <li><a href="#" class="active" data-category="all">All Problems</a></li>
                <li><a href="#" data-category="arrays">Arrays</a></li>
                <li><a href="#" data-category="strings">Strings</a></li>
                <li><a href="#" data-category="linked_list">Linked List</a></li>
                <li><a href="#" data-category="trees">Trees</a></li>
                <li><a href="#" data-category="graphs">Graphs</a></li>
                <li><a href="#" data-category="dynamic_programming">Dynamic Programming</a></li>
            </ul>

            <h3 style="margin-top: 2rem;">Leaderboard</h3>
            <ul class="leaderboard-list">
                <?php
                $leaderboard_sql = "SELECT u.username, COUNT(s.submission_id) as solved 
                                   FROM users u 
                                   LEFT JOIN submissions s ON u.user_id = s.user_id AND s.status = 'accepted'
                                   GROUP BY u.user_id 
                                   ORDER BY solved DESC 
                                   LIMIT 5";
                $leaderboard_result = $conn->query($leaderboard_sql);
                $rank = 1;
                while($row = $leaderboard_result->fetch_assoc()) {
                    echo "<li>#{$rank} {$row['username']} - {$row['solved']} solved</li>";
                    $rank++;
                }
                ?>
            </ul>
        </div>

        <!-- Problem Editor Section -->
        <div class="problem-editor">
            <?php
            // Get current problem (default to first problem)
            $problem_id = isset($_GET['problem']) ? $_GET['problem'] : 1;
            $problem_sql = "SELECT * FROM coding_problems WHERE problem_id = ?";
            $stmt = $conn->prepare($problem_sql);
            $stmt->bind_param("i", $problem_id);
            $stmt->execute();
            $problem_result = $stmt->get_result();
            $problem = $problem_result->fetch_assoc();
            ?>

            <div class="problem-description">
                <h2><?php echo htmlspecialchars($problem['title']); ?></h2>
                <p><?php echo nl2br(htmlspecialchars($problem['description'])); ?></p>
                
                <h3>Sample Input:</h3>
                <pre><?php echo htmlspecialchars($problem['sample_input']); ?></pre>
                
                <h3>Sample Output:</h3>
                <pre><?php echo htmlspecialchars($problem['sample_output']); ?></pre>
                
                <h3>Constraints:</h3>
                <p><?php echo htmlspecialchars($problem['constraints']); ?></p>
            </div>

            <div class="editor-header">
                <span>Code Editor</span>
                <select id="language">
                    <option value="python">Python</option>
                    <option value="javascript">JavaScript</option>
                    <option value="java">Java</option>
                    <option value="cpp">C++</option>
                </select>
            </div>

            <textarea id="codeEditor" class="editor-textarea" placeholder="Write your code here..."></textarea>

            <div class="editor-footer">
                <button class="btn btn-secondary" onclick="runCode()">Run Code</button>
                <button class="btn btn-primary" onclick="submitCode(<?php echo $problem['problem_id']; ?>)">Submit</button>
            </div>

            <div id="testResults" class="test-results" style="padding: 1rem; display: none;">
                <h3>Test Results:</h3>
                <div id="resultsContent"></div>
            </div>
        </div>
    </div>

    <script>
    function runCode() {
        const code = document.getElementById('codeEditor').value;
        const language = document.getElementById('language').value;
        
        // Show loading
        document.getElementById('testResults').style.display = 'block';
        document.getElementById('resultsContent').innerHTML = '<div class="loading-spinner"></div>';
        
        // Simulate code execution (in real implementation, this would call a backend API)
        setTimeout(() => {
            document.getElementById('resultsContent').innerHTML = `
                <div class="success-message">
                    <i class="fas fa-check-circle"></i> Code executed successfully!
                    <pre>Output: Sample output matches expected output.</pre>
                </div>
            `;
        }, 1500);
    }

    function submitCode(problemId) {
        const code = document.getElementById('codeEditor').value;
        const language = document.getElementById('language').value;
        
        <?php if(!isset($_SESSION['user_id'])): ?>
            window.location.href = '../login.php';
            return;
        <?php endif; ?>
        
        // Submit code to server
        const formData = new FormData();
        formData.append('problem_id', problemId);
        formData.append('code', code);
        formData.append('language', language);
        
        fetch('../api/submit-code.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('testResults').style.display = 'block';
            if(data.success) {
                document.getElementById('resultsContent').innerHTML = `
                    <div class="success-message">
                        <i class="fas fa-check-circle"></i> ${data.message}
                        <p>Test cases passed: ${data.passed}/${data.total}</p>
                    </div>
                `;
            } else {
                document.getElementById('resultsContent').innerHTML = `
                    <div class="error-message">
                        <i class="fas fa-times-circle"></i> ${data.message}
                    </div>
                `;
            }
        });
    }
    </script>

    <script src="../assets/js/main.js"></script>
</body>
</html>