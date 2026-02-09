<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Injection Training Lab</title>
    <link rel="icon" href="favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🔬 SQL Injection Training Lab</h1>
            <p>A safe environment to learn and practice SQL injection techniques</p>
        </header>

        <div class="card">
            <h2>⚠️ Educational Purpose Only</h2>
            <p>This lab is designed for cybersecurity education. All vulnerabilities are intentional. 
               Never use these techniques on systems without explicit permission.</p>
        </div>

        <div class="lab-grid">
            <a href="lab_union.php" class="lab-card">
                <span class="lab-number">Lab 1</span>
                <h3>Union-Based SQL Injection</h3>
                <p>Learn how to use UNION statements to extract data from other database tables.</p>
            </a>

            <a href="lab_auto.php" class="lab-card">
                <span class="lab-number">Lab 2</span>
                <h3>Automated SQL Injection</h3>
                <p>Practice using automated tools like sqlmap to discover and exploit vulnerabilities.</p>
            </a>

            <a href="lab_login.php" class="lab-card">
                <span class="lab-number">Lab 3</span>
                <h3>Authentication Bypass</h3>
                <p>Bypass login forms using SQL injection in authentication queries.</p>
            </a>

            <a href="lab_error.php" class="lab-card">
                <span class="lab-number">Lab 4</span>
                <h3>Error-Based SQL Injection</h3>
                <p>Extract data through database error messages revealed by the application.</p>
            </a>

            <a href="lab_blind.php" class="lab-card">
                <span class="lab-number">Lab 5</span>
                <h3>Blind SQL Injection</h3>
                <p>Infer database information through boolean responses when errors are hidden.</p>
            </a>

            <a href="lab_second_order.php" class="lab-card">
                <span class="lab-number">Lab 6</span>
                <h3>Second-Order SQL Injection</h3>
                <p>Payload stored now, executed later when used in a different query context.</p>
            </a>

            <a href="lab_file.php" class="lab-card">
                <span class="lab-number">Lab 7</span>
                <h3>File Read/Write via SQLi</h3>
                <p>Use LOAD_FILE() and INTO OUTFILE to access the server file system.</p>
            </a>
        </div>

        <footer>
            SQL Injection Training Lab &mdash; For Educational Purposes Only
        </footer>
    </div>
</body>
</html>
