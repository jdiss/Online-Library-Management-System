<?php
function validateBook($dbh, $bookname, $isbn) {
    $errors = array();
    
    // Check if book name already exists
    $sql = "SELECT id FROM tblbooks WHERE BookName = :bookname";
    $query = $dbh->prepare($sql);
    $query->bindParam(':bookname', $bookname, PDO::PARAM_STR);
    $query->execute();
    if ($query->rowCount() > 0) {
        $errors[] = "Book with this name already exists";
    }
    
    // Check if ISBN already exists (if provided)
    if (!empty($isbn)) {
        $sql = "SELECT id FROM tblbooks WHERE ISBNNumber = :isbn";
        $query = $dbh->prepare($sql);
        $query->bindParam(':isbn', $isbn, PDO::PARAM_STR);
        $query->execute();
        if ($query->rowCount() > 0) {
            $errors[] = "ISBN number already exists";
        }
    }
    
    return $errors;
}

function validateBookUpdate($dbh, $bookname, $isbn, $bookid) {
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
    
    // Check if ISBN already exists (if provided, excluding current book)
    if (!empty($isbn)) {
        $sql = "SELECT id FROM tblbooks WHERE ISBNNumber = :isbn AND id != :bookid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':isbn', $isbn, PDO::PARAM_STR);
        $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
        $query->execute();
        if ($query->rowCount() > 0) {
            $errors[] = "ISBN number already exists";
        }
    }
    
    return $errors;
}
?>