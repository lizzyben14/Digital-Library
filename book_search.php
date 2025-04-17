<html>
<body>
 
<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_dbs";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if a search term is provided
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

if (!empty($searchTerm)) {
    // Prepare the SQL query to search for books
    $query = "SELECT * FROM books WHERE title LIKE ? OR author LIKE ?";
    $stmt = $conn->prepare($query);
    $likeSearch = "%" . $searchTerm . "%";
    $stmt->bind_param("ss", $likeSearch, $likeSearch);
    $stmt->execute();
    $result = $stmt->get_result();

    // Display the search results
    if ($result->num_rows > 0) {
        echo "<h2>Search Results for: " . htmlspecialchars($searchTerm) . "</h2>";
        while ($row = $result->fetch_assoc()) {
            echo "<p>Book ID: " . $row["book_id"] . 
                 " - Title: " . $row["title"] . 
                 " - Author: " . $row["author"] . 
                 " - Genre: " . $row["genre"] . 
                 " - Year Published: " . $row["published_year"] . "</p>";
        }
    } else {
        echo "<p>No results found for: " . htmlspecialchars($searchTerm) . "</p>";
    }
} else {
    echo "<p>Please enter a search term.</p>";
}

$conn->close();
?>
 
</body>
</html>
