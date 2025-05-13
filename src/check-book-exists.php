<?php
session_start();
include('includes/config.php');

// Get the book name from POST request
$bookname = $_POST['bookname'];

// Check if book exists
$sql = "SELECT COUNT(*) as count FROM tblbooks WHERE BookName = :bookname";
$query = $dbh->prepare($sql);
$query->bindParam(':bookname', $bookname, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);

// Return JSON response
header('Content-Type: application/json');
echo json_encode(['exists' => $result->count > 0]);