<?php
include 'db.php';

$action = $_GET['action'];

try {
    $con = new PDO("mysql:host=localhost;dbname=library", "root", ""); // Adjust these settings based on your configuration
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    switch ($action) {
        case 'fetch':
            // Fetch all books
            $query = "SELECT * FROM books";
            $stmt = $con->prepare($query);
            $stmt->execute();
            $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($books);
            break;

        case 'add':
            // Add a new book
            $title = $_POST['title'] ?? null;
            $author = $_POST['author'] ?? null;
            $genre = $_POST['genre'] ?? null;
            $published_year = $_POST['published_year'] ?? null;
            $stock = $_POST['stock'] ?? null;

            if ($title && $author && $genre && $published_year && $stock) {
                $query = "INSERT INTO books (title, author, genre, published_year, stock) VALUES (:title, :author, :genre, :published_year, :stock)";
                $stmt = $con->prepare($query);
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':author', $author);
                $stmt->bindParam(':genre', $genre);
                $stmt->bindParam(':published_year', $published_year);
                $stmt->bindParam(':stock', $stock);
                $stmt->execute();

                echo json_encode(["message" => "Book added successfully"]);
            } else {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(["message" => "Invalid input data"]);
            }
            break;

        case 'update':
            // Update a book
            parse_str(file_get_contents("php://input"), $_PUT);

            $id = $_PUT['id'] ?? null;
            $title = $_PUT['title'] ?? null;
            $author = $_PUT['author'] ?? null;
            $genre = $_PUT['genre'] ?? null;
            $published_year = $_PUT['published_year'] ?? null;
            $stock = $_PUT['stock'] ?? null;

            if ($id && $title && $author && $genre && $published_year && $stock) {
                $query = "UPDATE books SET title = :title, author = :author, genre = :genre, published_year = :published_year, stock = :stock WHERE id = :id";
                $stmt = $con->prepare($query);
                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':author', $author);
                $stmt->bindParam(':genre', $genre);
                $stmt->bindParam(':published_year', $published_year);
                $stmt->bindParam(':stock', $stock);
                $stmt->execute();

                echo json_encode(["message" => "Book updated successfully"]);
            } else {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(["message" => "Invalid input data"]);
            }
            break;

        case 'delete':
            // Delete a book
            $id = $_GET['id'] ?? null;

            if ($id) {
                $query = "DELETE FROM books WHERE id = :id";
                $stmt = $con->prepare($query);
                $stmt->bindParam(':id', $id);
                $stmt->execute();

                echo json_encode(["message" => "Book deleted successfully"]);
            } else {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(["message" => "Invalid book ID"]);
            }
            break;

        default:
            header("HTTP/1.0 405 Method Not Allowed");
            echo json_encode(["message" => "Method Not Allowed"]);
            break;
    }
} catch (PDOException $e) {
    // Error handling
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(["message" => "Database error: " . $e->getMessage()]);
}
?>
