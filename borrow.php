<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['email'])) {
    header('location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $book_id = intval($_POST['book_id']);
    $user_email = $_SESSION['email'];


    $borrow_date = date('Y-m-d');
    $sql = "UPDATE borrowed_books 
            SET status = 'Borrowed', borrow_date = '$borrow_date', return_date = NULL 
            WHERE book_id = '$book_id' AND user_id = '$user_id'";
    

$stmt = $conn->prepare("UPDATE books SET status = 'borrowed' WHERE book_id = ?");

   if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);  
   }
   $stmt->bind_param("i", $book_id);
   if ($stmt->execute()) {
    header("Location: homepage.php?message=Book borrowed successfully");
    } else {
        echo "Error borrowing book: " . mysqli_error($conn);
    } 
    $stmt->close();
} else {
    echo "Invalid request. Please try again.";
    }
    $conn->close();
    ?>
