<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connect.php';
session_start();

if (isset($_POST['submit-btn'])) {
    $fullname = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $role = strtolower(trim($_POST['role']));


    if ($password !== $cpassword) {
        echo "<p style='color: red;'>Passwords do not match!</p>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        
        $checkEmail = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($checkEmail);

        if ($result->num_rows > 0) {
            echo "<p style='color: red;'>Email already exists!</p>";
        } else {
            
            $insertQuery = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$hashed_password', '$role')";

            if ($conn->query($insertQuery) === TRUE) {
                header("location: login.php"); 
                exit();
            } else {
                echo "<p style='color: red;'>Error: " . $conn->error . "</p>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" link href="assets/dl.css">
        <title>Register</title>
    </head>
    <body>
        <div class="form-container">
            <form method="POST" action="register.php"> 
                <h1>Register</h1>
                <input type="text" name="name" autocomplete="off" placeholder="Enter your name" required>
                <input type="email" name="email" autocomplete="off" placeholder="Enter your Email" required>
                <input type="password" name="password" autocomplete="off" placeholder="Enter your Password" required>
                <input type="password" name="cpassword" autocomplete="off" placeholder="Confirm your Password" required>
                <select name="role" id="role" required>
                <option value="admin">Select Role</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
                <input type="submit" name="submit-btn" value="Register now" class="btn">
                    <p>Already have an account?</p>
                    <a href="login.php">Login now</a>

            </form>
        </div>
        <script src="script.js"></script>
    </body>
</html>
