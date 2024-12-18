<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('db.php'); // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = trim($_POST['role']);

    error_log("Role: " . $role); // Log role

    try {
        // Choose query based on role
        if ($role === 'admin') {
            $stmt = $conn->prepare("SELECT * FROM admins WHERE username = :username");
        } elseif ($role === 'user') {
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        } else {
            $_SESSION['error_message'] = "Invalid role selected.";
            header("Location: account.php");
            exit;
        }

        error_log("Executing query for username: " . $username); // Log username

        // Bind parameters and execute query
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists and password is correct (plain text comparison)
        if ($user && $password === $user['password']) {
            error_log("Login successful for username: " . $username); // Log success
            session_regenerate_id(true); // Regenerate session ID to prevent session fixation
            $_SESSION['username'] = $user['username'];

            if ($role === 'admin') {
                $_SESSION['admin_id'] = $user['id'];
                header("Location: admindashboard.php");
                exit;
            } else {
                $_SESSION['user_id'] = $user['id'];
                header("Location: userdashboard.php");
                exit;
            }
        } else {
            error_log("Invalid login for username: " . $username); // Log invalid login
            $_SESSION['error_message'] = "Invalid username or password.";
            header("Location: account.php");
            exit;
        }
    } catch (PDOException $e) {
        // Handle database connection or query errors
        $_SESSION['error_message'] = "An error occurred while processing your request. Please try again.";
        error_log("Database error: " . $e->getMessage()); // Log the error for debugging
        header("Location: account.php");
        exit;
    }
} else {
    // Redirect if accessed without POST request
    header("Location: account.php");
    exit;
}
?>
