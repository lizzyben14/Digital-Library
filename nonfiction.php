<?php
include 'connect.php';

$query = "SELECT * FROM books WHERE genre='Nonfiction'";
$result = $conn->query($query);

if (!$result) {
    die("Error retrieving books: " . $conn->error);
}
?> 


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
        <title>Homepage</title>
         <link rel="stylesheet" link href="assets/book.css"/>
    </head>
    <body>
        <h2>Nonfiction Books</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['author']); ?></td>
                        <td><?php echo ucfirst($row['status']); ?></td>
                        <td>
                            <form method="POST" action="<?php echo ($row['status'] === 'available') ? 'borrow.php' : 'return.php'; ?>">
                                <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                               <?php if ($row['status'] == 'available') { ?>
                                    <button type="submit" name="borrow_book" class="borrow-btn">Borrow</button>
                                    <?php } else { ?>
                                    <button type="submit" name="return_book" class="return-btn">Return</button>
                                <?php } ?>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php $conn->close(); ?>
    </body>
</html>
    