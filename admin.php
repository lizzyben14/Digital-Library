<?php
session_start();

include 'connect.php';



if(!isset($_SESSION['email']) || strtolower($_SESSION['role']) !== 'admin') {
    header('location: login.php');
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['add_book'])) {
        $title = trim($_POST['title']);
        $author = trim($_POST['author']);
        $isbn = trim($_POST['isbn']);
        $genre = trim($_POST['genre']);
        $quantity = intval($_POST['quantity']);
        $published_year = intval($_POST['published_year']);
        $status = trim($_POST['status']);

        $query = "INSERT INTO books (title, author, isbn, genre, quantity, published_year, status) VALUES('$title', '$author', '$isbn', '$genre', '$quantity', $published_year, '$status')";
        if ($conn->query($query)) {
            echo "<p style='color: green;'>Book added successfully!</p>";
        } else {
            echo "<p style='color: red;'>Error adding book: " . $conn->error . "</p>";  
        }
}

if(isset($_POST['edit_book'])) {
    $book_id = trim($_POST['book_id']);
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $isbn = trim($_POST['isbn']);
    $genre = trim($_POST['genre']);
    $quantity = intval($_POST['quantity']);
    $published_year = intval($_POST['published_year']);
    $status = trim($_POST['status']);   

    $query = "UPDATE books SET title = '$title', author = '$author', isbn = '$isbn', genre = '$genre', quantity = '$quantity', published_year = '$published_year', status = '$status' WHERE book_id = $book_id";
       
    if ($conn->query($query)) {
            echo "<p style='color: green;'>Book updated successfully!</p>";
        } else {
            echo "<p style='color: red;'>Error updating book: " . $conn->error . "</p>";  
        }   
        }
    }    

if(isset($_POST['delete_book'])) {
$book_id = intval($_POST['book_id']);
$query = "DELETE FROM books WHERE book_id = $book_id";
if ($conn->query($query)) {
    echo "<p style='color: green;'>Book deleted successfully!</p>";
} else {
    echo "<p style='color: red;'>Error deleting book: " . $conn->error . "</p>";  
}   
}

if (isset($_POST['delete_user'])) {
    $user_id = intval($_POST['user_id']);
    $query = "DELETE FROM users WHERE user_id = '$user_id'"; 
    if ($conn->query($query)) {
        echo "<p style='color: green;'>User deleted successfully!</p>";
       
    } else {
        echo "<p style='color: red;'>Error deleting user: " . $conn->error . "</p>";
    }
}
    
    if(isset($_POST['edit_user'])) {
        $user_id = intval($_POST['user_id']);
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $role = trim($_POST['role']);

        
        $query = "UPDATE users SET name = '$name', email = '$email', role = '$role' WHERE user_id = $user_id";
        if ($conn->query($query)) {
                echo "<p style='color: green;'>User updated successfully!</p>";
            
        } else {
            echo "<p style='color: red;'>Error updating user: " . $conn->error . "</p>";  
        }   
    }

?>


<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" link href="assets/admin.css">
        <title>Admin Panel</title>
    </head>
    <body>
        <header>
        <h1>Admin Dashboard</h1>
        <nav>
            <ul class="nav-links">
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section>
        <h2>Manage Books</h2>
        <form method="POST">
        <h3>Add Books</h3>
        <input type="text" name="title" placeholder="Book Title" required>
        <input type="text" name="author" placeholder="Author" required>
        <input type="text" name="isbn" placeholder="ISBN" required>
        <input type="text" name="genre" placeholder="Genre" required>
        <input type="number" name="quantity" placeholder="Quantity" required>
        <input type="number" name="published_year" placeholder="Published Year" required>
        <select name="status" required>
            <option value="available">Available</option>
            <option value="unavailable">Unavailable</option>
        </select>
        <button type="submit" name="add_book" class="btn">Add Book</button>
</form>

<h3>Edit Books</h3>
<form method="POST">
    <select name="book_id" required>
    <option value="">Select Book</option>   
    <?php
    $books = $conn->query("SELECT * FROM books");
while ($book = $books->fetch_assoc()) {
    echo "<option value='{$book['book_id']}'>{$book['title']} by {$book['author']}</option>";
}
?>
   </select>
        <input type="text" name="title" placeholder="Book Title" required>
        <input type="text" name="author" placeholder="Author" required>
        <input type="text" name="isbn" placeholder="ISBN" required>
        <input type="text" name="genre" placeholder="Genre" required>
        <input type="number" name="quantity" placeholder="Quantity" required>
        <input type="number" name="published_year" placeholder="Published Year" required>
        <select name="status" required>
            <option value="available">Available</option>
            <option value="unavailable">Unavailable</option>
        </select>
        <button type="submit" name="edit_book" class="btn">Edit Book</button>
</form>

<h3>Delete Book</h3>
 <form method="POST">
    <select name="book_id" required>
        <option value="">Select Book</option>
        <?php
        $books = $conn->query("SELECT *FROM books");
        while ($book= $books->fetch_assoc()) {
            echo "<option value='{$book['book_id']}'>{$book['title']} by {$book['author']}</option>";
        }
        ?>
    </select>
    <button type="submit" name="delete_book" class="btn">Delete Book</button>
 </form>
        </section>

        <section>
            <h2>Manage Users</h2>
            <h3>Delete User</h3>
            <form method="POST">
                <select name="user_id" required>
                    <option value="">Select User</option>
                    <?php
                    $users = $conn->query("SELECT * FROM users WHERE role !='user'");
                    while ($user= $users->fetch_assoc()) {
                        echo "<option value='{$user['user_id']}'>{$user['name']} by {$user['email']}</option>";
                    }
                    ?>
</select>
<button type="submit" name="delete_user" class="btn">Delete User</button>
            </form>

            <h3>Edit User</h3>
            <form method="POST">
                <select name="user_id" required>
                    <option value="">Select User</option>
                    <?php
                    $users = $conn->query("SELECT * FROM users WHERE role != 'user'");
                    while ($user = $users->fetch_assoc()) {
                        echo "<option value='{$user['user_id']}'>{$user['name']} ({$user['email']})</option>";
                    }
                    ?>

                    <input type="text" name="name" placeholder="Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <select name="role" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select> 
                <button type="submit" name="edit_user" class="btn">Edit User</button>
            </form>
        </section>

        <section>
            <h2>View Books</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>ISBN</th>
                        <th>Genre</th>
                        <th>Quantity</th>
                        <th>Published Year</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $books = $conn->query("SELECT *FROM books");
                     while ($book= $books->fetch_assoc()) {
            echo "<tr>
            <td>{$book['book_id']}</td>
            <td>{$book['title']}</td>
            <td>{$book['author']}</td>
             <td>{$book['isbn']}</td>
            <td>{$book['genre']}</td>
             <td>{$book['quantity']}</td>
            <td>{$book['published_year']}</td>
             <td>{$book['status']}</td>
            </tr>";
                     }
                     ?>
                </tbody>
            </table>
        </section>
    </main>
    </body>
</html>






