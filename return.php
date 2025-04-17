<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['email'])) {
    header('location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $book_id = intval($_POST['book_id']);


    $update_status_query = "UPDATE books SET status = 'available' WHERE book_id = ?";
    $stmt = $conn->prepare($update_status_query);

    if ($stmt === false) {
        die("Error preparing query: " . $conn->error);
    }

    $stmt->bind_param('i', $book_id);
    if ($stmt->execute()) {
        header("Location: homepage.php?message=Book returned successfully!");
        exit();
    } else {
        echo "Failed to update book status: " . $conn->error;
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}

$conn->close();
?>