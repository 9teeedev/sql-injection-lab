<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Daily Secure - Technology News</title>
    <link rel="icon" href="favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="news_theme.css">
    <style>
        /* Additional overrides for this specific page */
        body {
            background-color: #f3f4f6;
        }
    </style>
</head>
<body class="news-body">

    <!-- Navbar -->
    <nav class="news-navbar">
        <div class="news-container">
            <a href="index.php" class="news-brand">
                <span>The Daily</span> Secure
            </a>
            <div class="news-nav-links">
                <a href="lab_union.php" <?php echo basename($_SERVER['PHP_SELF']) == 'lab_union.php' ? 'class="active"' : ''; ?>>Technology</a>
                <a href="lab_auto.php" <?php echo basename($_SERVER['PHP_SELF']) == 'lab_auto.php' ? 'class="active"' : ''; ?>>Archive</a>
                <a href="lab_login.php" <?php echo basename($_SERVER['PHP_SELF']) == 'lab_login.php' ? 'class="active"' : ''; ?>>Login</a>
                <a href="lab_error.php" <?php echo basename($_SERVER['PHP_SELF']) == 'lab_error.php' ? 'class="active"' : ''; ?>>Support</a>
                <a href="lab_blind.php" <?php echo basename($_SERVER['PHP_SELF']) == 'lab_blind.php' ? 'class="active"' : ''; ?>>Offers</a>
                <a href="lab_second_order.php" <?php echo basename($_SERVER['PHP_SELF']) == 'lab_second_order.php' ? 'class="active"' : ''; ?>>HR Portal</a>
                <a href="lab_file.php" <?php echo basename($_SERVER['PHP_SELF']) == 'lab_file.php' ? 'class="active"' : ''; ?>>Transparency</a>
                <a href="index.php" style="border-left: 1px solid #cbd5e1; padding-left: 1rem; margin-left: 0.5rem;">Lab Dashboard</a>
            </div>
        </div>
    </nav>

    <?php
    require_once 'db.php';
    
    // Default to article 1 if no ID provided in URL, but let form submit empty
    $id = isset($_GET['id']) ? trim($_GET['id']) : '1';
    $article = null; // Initialize variable
    $articles = []; // Initialize array
    $error_msg = null; // Initialize error message
    
    // Check for empty ID manually submitted via form
    if ($id === '' && isset($_GET['id'])) {
        $error_msg = "Please enter an Article ID.";
        $sql = ""; // No query executed
    } else {
        // VULNERABLE QUERY
        $sql = "SELECT id, title, content FROM articles WHERE id = " . $id;
        
        // Execute
        try {
            $result = $conn->query($sql);
            
            if ($result && $result->num_rows > 0) {
                // Fetch all rows for UNION injection to work
                while ($row = $result->fetch_assoc()) {
                    $articles[] = $row;
                }
                $article = $articles[0]; // First article for display
            } else {
                // If not found or error, simplified handling for the news view
                $error_msg = $conn->error ? "Database Error: " . $conn->error : "Article not found.";
            }
        } catch (mysqli_sql_exception $e) {
            $error_msg = "Database Error: " . $e->getMessage();
        }
    }
    ?>

    <!-- Main Content -->
    <?php if ($article): ?>
    <div class="article-hero">
        <div class="article-header">
            <div class="article-category">Cybersecurity</div>
            <h1 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h1>
            <div class="article-meta">
                <span>By <strong>Senior Editor</strong></span>
                <span>•</span>
                <span><?php echo date('F j, Y'); ?></span>
                <span>•</span>
                <span>5 min read</span>
            </div>
        </div>
    </div>

    <div class="news-main-grid">
        <div class="article-content">
            <p><strong>(London)</strong> — <?php echo nl2br($article['content']); ?></p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            <h3>Understanding the Impact</h3>
            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            
            <?php if (count($articles) > 1): ?>
            <!-- Hidden Injected Results (Displayed as Related News) -->
            <div style="margin-top: 3rem; border-top: 1px solid #e2e8f0; padding-top: 2rem;">
                <h3 style="margin-bottom: 1.5rem; font-family: var(--news-font);">More from the Archive</h3>
                <?php 
                // Skip the first one which is already shown above
                for ($i = 1; $i < count($articles); $i++): 
                    $row = $articles[$i];
                ?>
                <div class="news-widget" style="margin-bottom: 1.5rem; background: #f8fafc;">
                    <div style="color: #ef4444; font-size: 0.75rem; text-transform: uppercase; font-weight: bold; margin-bottom: 0.25rem;">Confidential / Hidden</div>
                    <h4 style="margin: 0 0 0.5rem 0; font-size: 1.2rem;">
                        <span style="color: #1e293b;"><?php echo htmlspecialchars($row['title'] ?? ''); ?></span>
                    </h4>
                    <p style="color: #475569; font-size: 0.95rem; line-height: 1.5;"><?php echo htmlspecialchars($row['content'] ?? ''); ?></p>
                </div>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="news-sidebar">
            <div class="news-widget">
                <div class="widget-title">Search Article ID</div>
                <form method="GET" action="">
                    <div class="form-group">
                        <input type="text" name="id" placeholder="Enter ID (e.g. 1)" value="<?php echo htmlspecialchars($id); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Go</button>
                </form>
            </div>

            <div class="news-widget">
                <div class="widget-title">Trending Now</div>
                <a href="?id=2" class="related-post">
                    <h4>Understanding UNION Attacks</h4>
                    <span>12k views</span>
                </a>
                <a href="?id=3" class="related-post">
                    <h4>Why Error Handling Matters</h4>
                    <span>8k views</span>
                </a>
                <a href="?id=5" class="related-post">
                    <h4>Protecting Your Database</h4>
                    <span>5k views</span>
                </a>
            </div>
        </div>
    </div>
    <?php else: ?>
        <div class="news-container" style="padding-top: 3rem;">
            <div class="card" style="width: 100%; text-align: center; padding: 3rem;">
                <h1 style="color: var(--news-accent); margin-bottom: 1rem;">404 - Article Not Found</h1>
                <p><?php echo $error_msg; ?></p>
                <a href="?id=1" class="btn btn-primary">Return to Home</a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Debug Console (Fixed Bottom) -->
    <div class="debug-console" id="debugConsole">
        <div class="debug-header" onclick="toggleConsole()">
            <div class="debug-title">
                <span>⚠️ Developer Console</span>
                <span style="font-size: 0.8em; font-weight: normal; opacity: 0.7;">(Debug Mode Active)</span>
            </div>
            <div style="font-size: 0.8em; opacity: 0.7;">Click to Toggle</div>
        </div>
        <div class="debug-content">
            <span class="debug-label">EXECUTED QUERY:</span>
            <code class="debug-code"><?php echo htmlspecialchars($sql); ?></code>
            
            <?php if ($conn->error): ?>
            <span class="debug-label" style="color: var(--debug-error); margin-top: 0.5rem;">MYSQL ERROR:</span>
            <code class="debug-code" style="color: var(--debug-error); border: 1px solid var(--debug-error);"><?php echo htmlspecialchars($conn->error); ?></code>
            <?php endif; ?>
            
            <div style="margin-top: 0.5rem; font-size: 0.8em; color: #64748b;">
                <strong>Hint:</strong> Inject SQL in the ID parameter. Try:
                <code class="debug-code" style="font-size: 0.8em; opacity: 0.9;">-1 UNION SELECT 1,username,password FROM users #</code>
            </div>
        </div>
    </div>

    <script>
        function toggleConsole() {
            document.getElementById('debugConsole').classList.toggle('console-minimized');
        }
    </script>

</body>
</html>
