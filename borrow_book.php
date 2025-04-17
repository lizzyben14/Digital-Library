<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['email'])) {
    header('location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $book_id = intval($_POST['book_id']);
    $email = $_SESSION['email'];

    
    $user_query = "SELECT id FROM users WHERE email = '$email'";
    $user_result = $conn->query($user_query);
    if ($user_result && $user_result->num_rows > 0) {
        $user = $user_result->fetch_assoc();
        $user_id = $user['id'];

       $book_check = $conn->query("SELECT quantity FROM books WHERE id = $book_id");
       if ($book_check && $book = $book_check->fetch_assoc()) {
            if ($book['quantity'] > 0) {
                $borrow_query = "INSERT INTO borrowed_book (user_id, book_id, borrowed_date) VALUES ($user_id, $book_id, NOW())";
                if ($conn->query($borrow_query)) {
                    
                    $update_query = "UPDATE books SET quantity = quantity - 1 WHERE book_id = $book_id AND quantity > 0";
                    $conn->query($update_query);

                    if ($conn->affected_rows > 0) {
                        echo "Quantity reduced!";
                    } else {
                        echo "Quantity NOT reduced. Book ID: $book_id";
                    }

                    header("Location: search_book.php?message=Book borrowed successfully!");
                    exit();
                } else {
                    header("Location: search_book.php?error=Error borrowing book: " . $conn->error);
                    exit();
                }
            } else {
                header("Location: search_book.php?message=Book is currently unavailable!");
                exit();
            }
       } else {
           header("Location: search_book.php?error=Book not found.");
           exit();
       }
    } else {
        header("Location: search_book.php?error=User not found.");
        exit();
    }
} else {
    header("Location: search_book.php?error=Invalid request.");
    exit();
}
?>