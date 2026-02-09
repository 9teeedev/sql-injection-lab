<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscriber Login - The Daily Secure</title>
    <link rel="icon" href="favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="news_theme.css">
    <style>
        body { background-color: #f3f4f6; }
        .login-container { max-width: 400px; margin: 4rem auto; padding: 0 1.5rem; }
        .login-card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border-top: 4px solid var(--news-accent); }
        .login-header { text-align: center; margin-bottom: 2rem; }
        .login-header h1 { font-family: var(--news-font); font-size: 1.8rem; color: #111827; margin-bottom: 0.5rem; }
        .login-header p { color: #6b7280; font-size: 0.9rem; }
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

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>Subscriber Login</h1>
                <p>Access premium content and expert analysis</p>
            </div>

            <?php
            require_once 'db.php';
            $sql = "waiting for input..."; // Default message
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = $_POST['username'] ?? '';
                $password = $_POST['password'] ?? '';
                
                // VULNERABLE QUERY
                $sql = "SELECT * FROM users WHERE username = '" . $username . "' AND password = '" . $password . "'";
                
                try {
                    $result = $conn->query($sql);
                    
                    if ($result && $result->num_rows > 0) {
                        $user = $result->fetch_assoc();
                        echo '<div class="alert alert-success" style="text-align: center; margin-bottom: 1.5rem;">';
                        echo '<div style="font-size: 1.2rem; margin-bottom: 0.5rem;">🎉 Access Granted</div>';
                        echo 'Welcome, <strong>' . htmlspecialchars($user['username']) . '</strong><br>';
                        echo '<small>Secret: ' . htmlspecialchars($user['secret_data']) . '</small>';
                        echo '</div>';
                    } else {
                        echo '<div class="alert alert-error" style="text-align: center; margin-bottom: 1.5rem;">Invalid email or password</div>';
                    }
                } catch (mysqli_sql_exception $e) {
                    echo '<div class="alert alert-error" style="text-align: center; margin-bottom: 1.5rem;">';
                    echo '<strong>SQL Error:</strong><br>';
                    echo '<code>' . htmlspecialchars($e->getMessage()) . '</code>';
                    echo '</div>';
                }
            }
            ?>

            <form method="POST" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="username">Username / Email</label>
                    <input type="text" id="username" name="username" placeholder="Enter username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter password" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.8rem; font-size: 1rem; margin-top: 1rem;">Sign In</button>
            </form>

            <script>
            function validateForm() {
                const username = document.getElementById('username').value.trim();
                const password = document.getElementById('password').value.trim();

                if (!username || !password) {
                    alert('Please enter both username and password');
                    return false;
                }
                return true;
            }
            </script>
            
            <div style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem;">
                <a href="#" style="color: var(--news-accent); text-decoration: none;">Forgot password?</a>
            </div>
        </div>
    </div>

    <!-- Debug Console -->
    <div class="debug-console" id="debugConsole">
        <div class="debug-header" onclick="toggleConsole()">
            <div class="debug-title">
                <span>⚠️ Developer Console</span>
                <span style="font-size: 0.8em; font-weight: normal; opacity: 0.7;">(Auth Module)</span>
            </div>
            <div style="font-size: 0.8em; opacity: 0.7;">Click to Toggle</div>
        </div>
        <div class="debug-content">
            <span class="debug-label">EXECUTED QUERY:</span>
            <code class="debug-code"><?php echo htmlspecialchars($sql); ?></code>
            <div style="margin-top: 0.5rem; font-size: 0.8em; color: #64748b;">
                <strong>Hint:</strong> Inject SQL in the username field. Try:
                <div style="margin-top: 0.5rem;"><strong>Bypass Login:</strong></div>
                <code class="debug-code" style="font-size: 0.8em; opacity: 0.9;">' OR 1=1 #</code>
                <div style="margin-top: 0.5rem;"><strong>Target Admin:</strong></div>
                <code class="debug-code" style="font-size: 0.8em; opacity: 0.9;">admin' #</code>
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
