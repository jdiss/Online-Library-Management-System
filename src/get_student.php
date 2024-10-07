<?php
require_once("includes/config.php");
if (!empty($_POST["studentid"])) {
  $studentid = strtoupper($_POST["studentid"]);

  $sql = "SELECT FullName,Status FROM tblstudents WHERE StudentId=:studentid";
  $query = $dbh->prepare($sql);
  $query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
  $query->execute();
  $results = $query->fetchAll(PDO::FETCH_OBJ);
  $response = array();
  if ($query->rowCount() > 0) {
    foreach ($results as $result) {
      if ($result->Status == 0) {
        $response['status'] = 'blocked';
        $response['name'] = $result->FullName;
      } else {
        $response['status'] = 'active';
        $response['name'] = $result->FullName;
      }
    }
  } else {
    $response['status'] = 'not_found';
    $response['name'] = '';
  }
  echo json_encode($response);
}