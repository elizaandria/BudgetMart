<?php
// login.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Example: Check user credentials
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        // Start session and redirect to homepage
        session_start();
        $_SESSION['username'] = $username;
        header("Location: index.html");
        exit();
    } else {
        echo "Invalid username or password.";
    }
}
?>
