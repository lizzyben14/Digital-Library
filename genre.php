<?php
session_start(); 
include 'connect.php';


if (!isset($_SESSION['email'])) {
    header('location: login.php'); 
    exit();
}

$genre = isset($_GET['genre']) ? $conn->real_escape_string($_GET['genre']) : '';
$query = "SELECT * FROM books WHERE genre='$genre'";
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
        <title>Homepage</title>
         <link rel="stylesheet" link href="assets/home.css"/>
    </head>
    <body>
        <h2><?php echo htmlspecialchars(ucwords($genre)); ?> Books</h2>
        <table border="1">
    <thead>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Genre</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($book = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($book['title']); ?></td>
                <td><?php echo htmlspecialchars($book['author']); ?></td>
                <td><?php echo htmlspecialchars($book['status']); ?></td>
                <td>
                    <?php if ($book['status'] == 'available'): ?>
                        <form method="POST" action="borrow.php">
                            <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
                            <button type="submit" name="return_book">Return</button>
</form>
<?php endif; ?>
                </td>
            </tr>
<?php endwhile; ?>
    </tbody>
        </table>
        <a href="homepage.php">Back to Home</a>
    </body>
</html>