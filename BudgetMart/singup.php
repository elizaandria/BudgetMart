<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password =$_POST['password'];

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);

    try {
        $stmt->execute();
        echo "<script>alert('Signup successful!');</script>";
        header("Location: account.php");
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Duplicate entry
            echo "<script>alert('Username or email already exists');</script>";
        } else {
            echo "<script>alert('Something went wrong');</script>";
        }
    }
}
?>
