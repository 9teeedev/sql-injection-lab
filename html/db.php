<?php
/**
 * Database Connection for SQL Injection Lab
 * WARNING: This connection is intentionally used with vulnerable queries for educational purposes
 */

$host = 'db';  // Docker service name
$user = 'sqli_user';
$pass = 'sqli_password';
$db   = 'sqli_lab';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("<div class='alert alert-error'>Database connection failed. Please ensure Docker containers are running.</div>");
}

$conn->set_charset("utf8mb4");
?>
