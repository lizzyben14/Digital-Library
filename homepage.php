<?php
session_start(); 
include 'connect.php';


if (!isset($_SESSION['email'])) {
    header('location: login.php'); 
    exit();
}

$email = $_SESSION['email'];
$role = $_SESSION['role'];

$query = "SELECT username FROM users WHERE email = '$email'";
$result = $conn->query($query);


if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $username = $user['username'];
} else { 
    $username = "User";
}

$searchTerm = isset($_GET['searchTerm']) ? trim($_GET['searchTerm']) : '';
$searchResults = null;

if (!empty($searchTerm)) {
    $searchTerm = $conn->real_escape_string($searchTerm);
    $searchQuery = "SELECT * FROM books WHERE title LIKE '%$searchTerm%' OR author LIKE '%$searchTerm%'";
    $searchResults = $conn->query($searchQuery);
}

$genreFilter = isset($_GET['genre']) ? $_GET['genre'] : '';
$query ="SELECT * FROM books WHERE status='available'";
if (!empty($genreFilter)) {
    $query .= " AND genre = '$genreFilter'";
}
$books = $conn->query($query);

$genrePages = [
    'scifi' => 'sci-fi.php',
    'fantasy' => 'fantasy.php',
    'mystery' => 'mystery.php',
    'romance' => 'romance.php',
    'horror' => 'horror.php',
    'thriller' => 'thriller.php',
    'non-fiction' => 'non-fiction.php',
    'christian' => 'christian.php',
 ];

 if (isset($genreFilter) && !empty($genreFilter) && array_key_exists($genreFilter, $genrePages)) {
    header("Location: " . $genrePages[$genreFilter]);
    exit();
 }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Homepage</title>
         <link rel="stylesheet" href="assets/home.css"/>
    </head>
    <body class="home">
    <header>
        <div class="logo"><span><h2>MY LIBRARY </h2></span></div>
        <nav>
       <h2>Welcome to the Digital Library, <?php echo htmlspecialchars($username); ?>!</h2>
            <ul class="nav-links">
                <li><a href="homepage.php">Home</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="search-section">
            <form method="GET" action="homepage.php">
                <input type="text" name="searchTerm" id="searchTerm" placeholder="Search for books" value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit">Search</button>
            </form>
        </section>

        <section class="books-section">
            <?php if (!empty($searchTerm)): ?>
                <h2>Search Results for "<?php echo htmlspecialchars($searchTerm); ?>"</h2>
                <?php if ($searchResults && $searchResults->num_rows > 0): ?>
                    <div class="books-container">
                        <?php while ($book = $searchResults->fetch_assoc()): ?>
                            <div class="book-item">
                                <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                                <p>Author: <?php echo htmlspecialchars($book['author']); ?></p>
                                <p>Genre: <?php echo htmlspecialchars($book['genre']); ?></p>
                                <p>Year Published: <?php echo htmlspecialchars($book['published_year']); ?></p>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p>No books found for "<?php echo htmlspecialchars($searchTerm); ?>".</p>
                <?php endif; ?>
            <?php else: ?>
            <?php endif; ?>
        </section>

        <div class="image-container">
        </div>
    </main>
                <div class="filters">
                    <div class="searchBy">
                        <p>Search By:</p>
                    </div>
                    <div class="filterBy-options">
                        <select name="author" id="author">
                            <option value="">Author</option>
                            <option value="John Marrs">John Marrs</option>
                            <option value="Frank Herbert">Frank Herbert</option>
                            <option value="Octavia E. Butler">Octavia E. Butler</option>
                            <option value="Bram Stoker">Bram Stoker</option>
                            <option value="Stephen King">Stephen King</option>
                            <option value="Ray Bradbury">Ray Bradbury</option>
                            <option value="Agatha Christie">Agatha Christie</option>
                            <option value="Gillian Flynn">Gillian Flynn</option>
                            <option value="Nora Roberts">Nora Roberts</option>
                            <option value="Alyssa Cole">Alyssa Cole</option>
                            <option value="Tomi Adeyemi">Tomi Adeyemi</option>
                            <option value="Lucy Foley">Lucy Foley</option>
                            <option value="Ruth Ware">Ruth Ware</option>
                            <option value="J.K. Rowling">J.K. Rowling</option>
                            <option value="Ted Dekker">Ted Dekker</option>
                            <option value="Francine Rivers">Francine Rivers</option>
                        </select>
                    
                    <div class="filterBy">
                        <select name="genre" id="genre"> 
                            <option value="">Genre</option>
                            <option value="scifi">Sci-Fi</option>
                            <option value="fantasy">Fantasy</option>
                            <option value="mystery">Mystery</option>
                            <option value="romance">Romance</option>
                            <option value="horror">Horror</option>
                            <option value="thriller">Thriller</option>
                            <option value="nonfiction">Non-Fiction</option>
                            <option value="christian">Christian</option>
                        </select>
                        <button type="submit" id="filter-button">Filter</button>
                    </div>
                    </div>
                    </div>
                    </form>       
                    <?php 
if (isset($_POST['search']) && !empty($searchTerm)) {

    echo "<p>Searching for: " . htmlspecialchars($searchTerm). "</p>";

    if ($result && $result->num_rows > 0) {
     while($book = $result->fetch_assoc()) {
     echo "<br> Book ID:  ". $row["book_id"]. 
     "  - Title:  ". $row["title"]. 
     " - Year Published: " . $row["published_year"].
     " - Genre: " . $row["genre"].
      " - Author: " . $row["author"]. "<br>";
 }
} else {
 echo "0 results";
}
}
?>
        </section>
<div class="image-container">
    <div class="image-box">
    <a href="sci-fi.php">
        <img src="assets/images/scifilogo.jpg" alt="Sci-Fi" width="200">
        <div class="image-text">Sci-Fi</div>
    </a>
    </div>
    <div class="image-box">
    <a href="fantasy.php">
        <img src="assets/images/fantasylogo.jpg" alt="Fantasy" width="200">
        <div class="image-text">Fantasy</div>   
    </a>
    </div>
    <div class="image-box">
    <a href="mystery.php">
        <img src="assets/images/mysterylogo.jpg" alt="Mystery" width="200">
        <div class="image-text">Mystery</div>
    </a>
    </div>
    <div class="image-box">
    <a href="romance.php">
        <img src="assets/images/romancelogo.jpg" alt="Romance" width="200">
        <div class="image-text">Romance</div>
    </a>
    </div>
    <div class="image-box">
    <a href="horror.php">
        <img src="assets/images/horrorlogo.jpg" alt="Horror" width="200">
        <div class="image-text">Horror</div>
        </a>
    </div>
    <div class="image-box">
    <a href="thriller.php">
        <img src="assets/images/thrillerlogo2.jpg" alt="Thriller" width="200">
        <div class="image-text">Thriller</div>
    </a>
    </div>
    <div class="image-box">
    <a href="nonfiction.php">
        <img src="assets/images/nonfictionlogo.jpg" alt="Non-Fiction" width="200">
        <div class="image-text">Non-Fiction</div>
    </a>
    </div>
    <div class="image-box">
    <a href="christian.php">
        <img src="assets/images/christianlogo.jpg" alt="Christian" width="200">
        <div class="image-text">Christian</div>
    </a>
    </div>
       
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 LMS</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>