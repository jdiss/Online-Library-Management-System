<?php
session_start();
include('includes/config.php');

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookname = $_POST['bookname'];
    $bookid = intval($_POST['bookid']);
    $errors = array();
    
    // Check if book name already exists (excluding current book)
    $sql = "SELECT id FROM tblbooks WHERE BookName = :bookname AND id != :bookid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':bookname', $bookname, PDO::PARAM_STR);
    $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
    $query->execute();
    
    if ($query->rowCount() > 0) {
        $errors[] = "Book with this name already exists";
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode(array('errors' => $errors));
    exit;
}