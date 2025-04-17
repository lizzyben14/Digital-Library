<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['email'])) {
    header('location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

if (isset($_POST['borrow_id']) && isset($_POST['book_id'])) {
   $borrow_id = intval( $_POST['borrow_id']);
    $book_id = intval($_POST['book_id']);
   

$return_query = "UPDATE borrowed_book SET returned_date = NOW() WHERE id = $borrow_id AND returned_date IS NULL";
if ($conn->query($return_query)) {
    if ($conn->affected_rows > 0) {
        $update_quantity_query = "UPDATE books SET quantity = quantity + 1 WHERE id = $book_id";
   if ($conn->query($update_quantity_query)) {
    header("Location: homepage.php?message=Book returned successfully");
    exit();
} else {
    echo "Error updating book quantity: " . $conn->error;
    exit();
}
} else {
echo "Book already returned or invalid borrow ID.";
exit();
}
} else {
echo "Error updating return date: " . $conn->error;
exit();
}
} else {
echo "Invalid request. Missing borrow_id or book_id.";
exit();
}
} else {
echo "Invalid request method.";
exit();
}

$conn->close();
?>