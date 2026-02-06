<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Portal - The Daily Secure</title>
    <link rel="icon" href="favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="news_theme.css">
    <style>
        body { background-color: #f3f4f6; }
        /* Updated for 3 columns */
        .portal-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; max-width: 1200px; margin: 3rem auto; padding: 0 1.5rem; }
        .portal-card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); display: flex; flex-direction: column; }
        .portal-header h2 { font-family: var(--news-font); color: #111827; border-bottom: 2px solid var(--news-accent); padding-bottom: 0.5rem; display: inline-block; margin-bottom: 1.5rem; font-size: 1.25rem; }
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

    <div class="portal-grid">
        <!-- Registration Column -->
        <div class="portal-card">
            <div class="portal-header">
                <h2>Step 1: Onboarding</h2>
            </div>
            <p style="color: #6b7280; margin-bottom: 1.5rem;">Create a temporary account.</p>

            <form method="POST">
                <input type="hidden" name="action" value="register">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="e.g. jdoe">
                </div>
                <div class="form-group">
                    <label>Temporary Password</label>
                    <input type="text" name="password" value="welcome2024">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Create Account</button>
            </form>

            <?php
            require_once 'db.php';
            $debug_sql = "waiting for action...";

            // SETUP: Ensure target 'admin' exists
            $check = $conn->query("SELECT id FROM lab6_users WHERE username = 'admin'");
            if ($check->num_rows === 0) {
                $conn->query("INSERT INTO lab6_users (username, password) VALUES ('admin', 'super_secret_password')");
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
                if ($_POST['action'] === 'register') {
                    $username = $_POST['username'] ?? '';
                    $password = $_POST['password'] ?? '';
                    
                    if ($username === '') {
                        echo '<div class="alert alert-warning" style="margin-top: 1rem;">⚠️ Username cannot be empty</div>';
                    } elseif (strtolower($username) === 'admin') {
                        echo '<div class="alert alert-error" style="margin-top: 1rem;">❌ Username "admin" is already taken!</div>';
                    } else {
                        // SAFE QUERY (Prepared)
                        $stmt = $conn->prepare("INSERT INTO lab6_users (username, password) VALUES (?, ?)");
                        $stmt->bind_param("ss", $username, $password);
                        $debug_sql = "INSERT INTO lab6_users ... (Prepared Statement: SAFE)";
                        
                        try {
                            if ($stmt->execute()) {
                                echo '<div class="alert alert-success" style="margin-top: 1rem;">✅ Employee Registered</div>';
                            } else {
                                echo '<div class="alert alert-error" style="margin-top: 1rem;">Error: ' . htmlspecialchars($conn->error) . '</div>';
                            }
                        } catch (Exception $e) {
                             echo '<div class="alert alert-error" style="margin-top: 1rem;">❌ Username already exists or Error</div>';
                        }
                        $stmt->close();
                    }
                }
            }
            ?>
        </div>

        <!-- Password Change Column -->
        <div class="portal-card">
            <div class="portal-header">
                <h2>Step 2: Password Reset</h2>
            </div>
            <p style="color: #6b7280; margin-bottom: 1.5rem;">Reset password for existing employees.</p>

            <form method="POST">
                <input type="hidden" name="action" value="change_password">
                <div class="form-group">
                    <label>Employee Username</label>
                    <input type="text" name="username" placeholder="e.g. admin' #">
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input type="text" name="new_password" placeholder="New secure password">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; background-color: #0f172a;">Update Password</button>
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
                if ($_POST['action'] === 'change_password') {
                    $username = $_POST['username'] ?? '';
                    $new_password = $_POST['new_password'] ?? '';
                    
                    // Validate empty fields
                    if ($username === '' || $new_password === '') {
                        echo '<div class="alert alert-warning" style="margin-top: 1rem;">⚠️ Username and password cannot be empty</div>';
                    } elseif (strtolower($username) === 'admin') {
                        echo '<div class="alert alert-error" style="margin-top: 1rem;">❌ Access Denied: Cannot reset Admin password directly!</div>';
                    } else {
                        // VULNERABLE LOGIC: Second Order Injection source
                        $result = $conn->query("SELECT username FROM lab6_users WHERE username = '" . $conn->real_escape_string($username) . "'");
                        
                        if ($result && $result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $stored_username = $row['username']; // Malicious payload comes from DB
                            
                            // SECOND ORDER INJECTION HERE
                            $update_sql = "UPDATE lab6_users SET password = '" . $conn->real_escape_string($new_password) . "' WHERE username = '$stored_username'";
                            $debug_sql = $update_sql;
                            
                            try {
                                if ($conn->query($update_sql)) {
                                    echo '<div class="alert alert-success" style="margin-top: 1rem;">✅ Password Updated</div>';
                                } else {
                                    echo '<div class="alert alert-error" style="margin-top: 1rem;">Update Failed: ' . htmlspecialchars($conn->error) . '</div>';
                                }
                            } catch (mysqli_sql_exception $e) {
                                echo '<div class="alert alert-error" style="margin-top: 1rem;">SQL Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
                            }
                        } else {
                            echo '<div class="alert alert-warning" style="margin-top: 1rem;">User not found</div>';
                        }
                    }
                }
            }
            ?>
        </div>

        <!-- Verification Column (Login) -->
        <div class="portal-card">
            <div class="portal-header">
                <h2>Step 3: Verify Access</h2>
            </div>
            <p style="color: #6b7280; margin-bottom: 1.5rem;">Test if you can login as target.</p>

            <form method="POST">
                <input type="hidden" name="action" value="login">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Target e.g. admin">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter password">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; background-color: #16a34a;">Test Login</button>
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
                if ($_POST['action'] === 'login') {
                    $u = $_POST['username'] ?? '';
                    $p = $_POST['password'] ?? '';

                    // Safe Login Check
                    $stmt_login = $conn->prepare("SELECT id FROM lab6_users WHERE username = ? AND password = ?");
                    $stmt_login->bind_param("ss", $u, $p);
                    $stmt_login->execute();
                    $stmt_login->store_result();

                    if ($stmt_login->num_rows > 0) {
                        echo '<div class="alert alert-success" style="margin-top: 1rem; background: #f0fdf4; border-color: #bbf7d0;">';
                        echo '<div style="font-size: 1.1rem; font-weight: 700;">🎉 SUCCESS!</div>';
                        echo 'Logged in as <strong>'.htmlspecialchars($u).'</strong>';
                        echo '</div>';
                    } else {
                        echo '<div class="alert alert-error" style="margin-top: 1rem;">❌ Login Failed</div>';
                    }
                    $stmt_login->close();
                }
            }
            ?>
        </div>
    </div>

    <!-- Registered List -->
    <div style="max-width: 1000px; margin: 0 auto; padding: 0 1.5rem;">
        <h3 style="color: #64748b; font-size: 0.9rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 1px;">Recent Hires</h3>
        <div style="background: white; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0;">
            <?php
            $users = $conn->query("SELECT username FROM lab6_users ORDER BY id DESC LIMIT 5");
            if ($users) {
                while($u = $users->fetch_assoc()) {
                    echo '<div style="padding: 0.75rem 1rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 0.5rem;">';
                    echo '<span style="background: #e2e8f0; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">USER</span>';
                    echo '<code style="color: #475569;">' . htmlspecialchars($u['username']) . '</code>';
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
                <span style="font-size: 0.8em; font-weight: normal; opacity: 0.7;">(HR Module)</span>
            </div>
            <div style="font-size: 0.8em; opacity: 0.7;">Click to Toggle</div>
        </div>
        <div class="debug-content">
            <span class="debug-label">LAST EXECUTED QUERY:</span>
            <code class="debug-code"><?php echo htmlspecialchars($debug_sql); ?></code>
            <div style="margin-top: 0.5rem; font-size: 0.8em; color: #64748b;">
                <strong>Hint:</strong> Second-Order SQLi.
                <div style="margin-top: 0.5rem;"><strong>1. Register (Stored safely):</strong></div>
                <code class="debug-code" style="font-size: 0.8em; opacity: 0.9;">admin' #</code>
                <div style="margin-top: 0.5rem;"><strong>2. Change Password (Executes unsafely):</strong></div>
                <code class="debug-code" style="font-size: 0.8em; opacity: 0.9;">(Use the same username)</code>

                <!-- Clear Data Button -->
                <form method="POST" style="margin-top: 1rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1rem;">
                    <input type="hidden" name="action" value="clear_users">
                    <button type="submit" style="background: transparent; border: 1px solid #ef4444; color: #ef4444; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem; cursor: pointer;">🗑️ Clear Recent Hires</button>
                    <span style="font-size: 0.75em; color: #94a3b8; margin-left: 0.5rem;">(Resets extra users)</span>
                </form>
            </div>
        </div>
    </div>

    <?php
    // Handle Clear Action
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'clear_users') {
        // Keep 'admin' but delete others
        $conn->query("DELETE FROM lab6_users WHERE username != 'admin'");
        // RESET Admin Password to Default (Factory Reset)
        $conn->query("UPDATE lab6_users SET password = 'super_secret_password' WHERE username = 'admin'");
        // Reload to refresh list
        echo "<script>window.location.href='lab_second_order.php';</script>";
        exit;
    }
    ?>

    <script>
        function toggleConsole() {
            document.getElementById('debugConsole').classList.toggle('console-minimized');
        }
    </script>
</body>
</html>
