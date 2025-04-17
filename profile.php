<?php
session_start();
include 'connect.php';



if (!isset($_SESSION['email'])) {
    header('location: login.php');
    exit();
}

$target_dir = "uploads/";

$email = $_SESSION['email'];
$query = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($query);
$user = $result->fetch_assoc();

$profile_picture = $user['profile_picture']  ?? 'assets/default-profile.png';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
        $username = trim($_POST['username']);
        $bio = trim($_POST['bio']);
         $target_file = $user['profile_picture'];

        if (!empty($_FILES['profile_picture']['name'])) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES['profile_picture']['name']);  
       
        if  (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
           $profile_picture = $target_file;
           $conn->query("UPDATE users SET profile_picture = '$profile_picture' WHERE email = '$email'");
          } else {
           echo "<p style='color: red; '>Error uploading image.</p>"; 
          }
        }

       $update_query = "UPDATE users SET username = '$username', bio = '$bio' WHERE email = '$email'";

        if ($conn->query($update_query)) {
            echo "<p style='color: green; '>Profile updated successfully!</P>";

            $result = $conn->query($query);
            $user = $result->fetch_assoc();
        } else {
            echo "<p style='color: red; '>Error updating profile: " . $conn->error . "</p>";
        }
    }


$borrowed_books_query = "
SELECT b.title, bb.borrowed_date, bb.returned_date
FROM borrowed_books bb
JOIN books b ON bb.book_id = b.book_id
WHERE bb.user_id = {$user['user_id']}
";
$borrowed_books = $conn->query($borrowed_books_query);
if (!$borrowed_books) {
    die("Error in query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register & Login</title>
    <link rel="stylesheet" link href="assets/profile.css"/>
</head>
<body>
    <header>
        <h1>YOUR PROFILE</h1>
        <nav>
            <ul class="nav-links">
            <li><a href="homepage.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="profile-section"> 
         <div class="profile-container">
            <div class="profile-header">
            <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="profile-avatar">
         </div>

            <form method="POST" enctype="multipart/form-data" class="profile-form">
                <label for="profile_picture">Change Picture:</label>
                <input type="file" name="profile_picture" id="profile_picture">

                <label>Username:</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    
                    <label>Bio:</label>
                    <textarea name="bio" rows="4"><?php echo htmlspecialchars($user['bio']); ?></textarea>

                <button type="submit" name="update_profile" class="btn">Update Profile</button>
            </form>
         </div>
        </section>

        <section>
            <h2>Borrowed Books</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Borrowed Date</th>
                        <th>Returned Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($book = $borrowed_books->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($book['title']); ?></td>
                            <td><?php echo htmlspecialchars($book['borrowed_date']); ?></td>
                            <td><?php echo htmlspecialchars($book['returned_date'] ?: 'Not Returned'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>
</html>