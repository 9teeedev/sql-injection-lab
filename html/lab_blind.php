<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promo Code Validator - The Daily Secure</title>
    <link rel="icon" href="favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="news_theme.css">
    <style>
        body { background-color: #f3f4f6; }
        .promo-container { max-width: 500px; margin: 4rem auto; padding: 0 1.5rem; }
        .promo-card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); text-align: center; }
        .promo-icon { font-size: 3rem; margin-bottom: 1rem; color: var(--news-accent); }
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

    <div class="promo-container">
        <div class="promo-card">
            <div class="promo-icon">🎁</div>
            <h1 style="font-family: var(--news-font); color: #111827; margin-bottom: 0.5rem;">Redeem Offer</h1>
            <p style="color: #6b7280; margin-bottom: 2rem;">Enter your internal reference ID to validate your subscription discount.</p>

            <form method="GET" action="">
                <div class="form-group">
                    <input type="text" name="id" placeholder="Enter Reference ID (e.g. 1)" 
                           value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>"
                           style="width: 100%; text-align: center; padding: 1rem; font-size: 1.2rem; letter-spacing: 1px;">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 1.1rem; padding: 0.8rem;">Validate Code</button>
            </form>

            <?php
            error_reporting(0); // Suppress errors for Blind SQLi
            require_once 'db.php';
            
            $sql = "waiting for input...";
            
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                
                // VULNERABLE QUERY
                $sql = "SELECT id FROM articles WHERE id = " . $id;
                
                $result = @$conn->query($sql);
                
                echo '<div style="margin-top: 2rem;">';
                if ($result && $result->num_rows > 0) {
                    echo '<div style="color: #16a34a; font-weight: 700; font-size: 1.2rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">';
                    echo '<span>✓</span> Valid Reference Code';
                    echo '</div>';
                    echo '<p style="color: #166534; font-size: 0.9rem; margin-top: 0.5rem;">Your discount will be applied at checkout.</p>';
                } else {
                    echo '<div style="color: #dc2626; font-weight: 600; font-size: 1.1rem;">Invalid Code</div>';
                    echo '<p style="color: #991b1b; font-size: 0.9rem; margin-top: 0.5rem;">Please check your reference ID and try again.</p>';
                }
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <!-- Debug Console -->
    <div class="debug-console" id="debugConsole">
        <div class="debug-header" onclick="toggleConsole()">
            <div class="debug-title">
                <span>⚠️ Developer Console</span>
                <span style="font-size: 0.8em; font-weight: normal; opacity: 0.7;">(Blind SQLi Mode)</span>
            </div>
            <div style="font-size: 0.8em; opacity: 0.7;">Click to Toggle</div>
        </div>
        <div class="debug-content">
            <span class="debug-label">EXECUTED QUERY:</span>
            <code class="debug-code"><?php echo htmlspecialchars($sql); ?></code>
            <div style="margin-top: 0.5rem; font-size: 0.8em; color: #64748b;">
                <strong>Hint:</strong> Data is hidden. Use Boolean Logic.
                <div style="margin-top: 0.5rem;"><strong>Test True (Valid):</strong></div>
                <code class="debug-code" style="font-size: 0.8em; opacity: 0.9;">1 AND 1=1</code>
                <div style="margin-top: 0.5rem;"><strong>Test False (Invalid):</strong></div>
                <code class="debug-code" style="font-size: 0.8em; opacity: 0.9;">1 AND 1=2</code>
                <div style="margin-top: 0.5rem;"><strong>Advanced (Guess Table Name = 'a'):</strong></div>
                <code class="debug-code" style="font-size: 0.8em; opacity: 0.9;">1 AND substring((SELECT table_name FROM information_schema.tables WHERE table_schema='sqli_lab' LIMIT 0,1), 1, 1) = 'a'</code>
                <div style="margin-top: 0.5rem;"><strong>Advanced (Guess Admin Password = '1'):</strong></div>
                <code class="debug-code" style="font-size: 0.8em; opacity: 0.9;">1 AND substring((SELECT password FROM users WHERE username='admin'), 1, 1) = '1'</code>
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
