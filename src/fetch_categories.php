<?php
require_once("includes/config.php");
if (!empty($_GET["category"])) {
    $category = $_GET["category"];
    
    $sql = "SELECT id, CategoryName FROM tblcategory WHERE CategoryName LIKE :category";
    $query = $dbh->prepare($sql);
    $query->bindValue(':category', '%' . $category . '%', PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    
    $response = array();
    if ($result) {
        do {
            $response[] = array(
                'id' => htmlentities($result->id),
                'name' => htmlentities($result->CategoryName)
            );
        } while ($result = $query->fetch(PDO::FETCH_OBJ));
        echo json_encode($response);
    } else {
        echo json_encode([]);
    }
}?>