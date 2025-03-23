<?php
require_once("includes/config.php");
if (!empty($_GET["author"])) {
    $author = $_GET["author"];
    
    $sql = "SELECT id, AuthorName FROM tblauthors WHERE AuthorName LIKE :author";
    $query = $dbh->prepare($sql);
    $query->bindValue(':author', '%' . $author . '%', PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    
    $response = array();
    if ($result) {
        do {
            $response[] = array(
                'id' => htmlentities($result->id),
                'name' => htmlentities($result->AuthorName)
            );
        } while ($result = $query->fetch(PDO::FETCH_OBJ));
        echo json_encode($response);
    } else {
        echo json_encode([]);
    }
}?>