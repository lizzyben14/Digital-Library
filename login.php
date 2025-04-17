<?php
include 'connect.php';
session_start();

if (isset($_SESSION['logout_message'])) {
    echo "<p style='color: green;'>" . $_SESSION['logout_message'] . "</p>";
    unset($_SESSION['logout_message']);
}

if (isset($_POST['login-btn'])) {
    $email = trim($_POST['email']); 
    $password = $_POST['password'];
    $role = strtolower(trim($_POST['role']));

  
    $email = $conn->real_escape_string($email);

    
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            if (strtolower($user['role']) === $role) {
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $user['role'];

            if(strtolower($user['role']) === 'admin') {
                header('location: admin.php');
            } else {
            header('location: homepage.php'); 
            }
             exit();
        } else {
            echo "<p style='color: red;'>Invalid role selected!</p>";
        }
    } else{
        echo "<p style='color: red;'>Invalid password!</p>";
    }
    } else {
        echo "<p style='color: red;'>No account found with this email! Please register first.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register & Login</title>
    <link rel="stylesheet" link href="assets/index.css"/>
</head>
<body>
   <div class="login_container" id="signin">
    <h1 class="form-title"></h1>
       <form method="POST" action="login.php">
           <h1>Login</h1>
           <input type="email" name="email" placeholder="Enter your Email" required>
           <input type="password" name="password" placeholder="Enter your Password" required>
           <select name="role" id="role" required>
           <option value="admin">Select Role</option>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
           <input type="submit" name="login-btn" value="Login now" class="btn">   
           <p> Don't have an account?</p> <a href="register.php">Register now</a></p>  <!--Code taken from ChatGPT to link pages-->
        </form>
    </div>

</body>
</html> 