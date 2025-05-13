<?php
session_start();
include('includes/config.php');

// Get the book details from POST request
$bookname = isset($_POST['bookname']) ? trim($_POST['bookname']) : '';
$category = isset($_POST['category']) ? trim($_POST['category']) : '';
$author = isset($_POST['author']) ? trim($_POST['author']) : '';

$errors = array();
$exists = false;

// Validate input
if (empty($bookname)) {
    $errors[] = 'Book name is required';
} else {
    // Check if book exists with same name, category and author
    $sql = "SELECT COUNT(*) as count FROM tblbooks b "
         . "INNER JOIN tblauthors a ON b.AuthorId = a.id "
         . "INNER JOIN tblcategory c ON b.CatId = c.id "
         . "WHERE b.BookName = :bookname "
         . "AND c.CategoryName = :category "
         . "AND a.AuthorName = :author";
    
    $params = array(':bookname' => $bookname);
    $params[':category'] = $category;
    $params[':author'] = $author;
    
 
    $query = $dbh->prepare($sql);

    foreach ($params as $key => $value) {
        $query->bindValue($key, $value, PDO::PARAM_STR);
    }
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    
    if ($result->count > 0) {
        $exists = true;
        $errors[] = 'A book with these details already exists';
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode([
    'exists' => $exists,
    'errors' => $errors
]);