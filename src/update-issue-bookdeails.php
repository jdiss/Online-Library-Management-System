<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else { 

    if(isset($_POST['return'])) {
        $rid = intval($_GET['rid']);
        $fine = $_POST['fine'];
        $rstatus = 1;
        $sql = "UPDATE tblissuedbookdetails SET fine=:fine, RetrunStatus=:rstatus WHERE id=:rid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':rid', $rid, PDO::PARAM_STR);
        $query->bindParam(':fine', $fine, PDO::PARAM_STR);
        $query->bindParam(':rstatus', $rstatus, PDO::PARAM_STR);
        $query->execute();

        $_SESSION['msg'] = "Book Returned successfully";
        header('location:manage-issued-books.php');
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>YBRCC Library Management System | Issued Book Details</title>
    <link rel="icon" href="assets/img/cropped-fav-32x32.png" sizes="32x32">
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <style>
        .panel {
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .panel-heading {
            background-color: #007bff;
            color: white;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            font-weight: bold;
        }
        .btn {
            border-radius: 5px;
        }
        .go-back {
            margin-top: 20px;
        }
        .form-group label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include('includes/header.php'); ?>
    <div class="content-wrapper">
        <div class="container">
           
            <div class="row">
                <div class="col-md-10 col-sm-6 col-xs-12 col-md-offset-1">
                    <div class="panel panel-info">
                        <div class="panel-heading">Issued Book Information</div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <?php 
                                $rid = intval($_GET['rid']);
                                $sql = "SELECT tblstudents.FullName,tblstudents.StudentId, tblbooks.BookName, tblbooks.ISBNNumber, tblissuedbookdetails.IssuesDate, tblissuedbookdetails.ReturnDate, tblissuedbookdetails.id as rid, tblissuedbookdetails.fine, tblissuedbookdetails.RetrunStatus FROM tblissuedbookdetails JOIN tblstudents ON tblstudents.StudentId=tblissuedbookdetails.StudentId JOIN tblbooks ON tblbooks.id=tblissuedbookdetails.BookId WHERE tblissuedbookdetails.id=:rid";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':rid', $rid, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                if($query->rowCount() > 0) {
                                    foreach($results as $result) { ?>                                      
                                        <div class="form-group">
                                            <label style="font-weight: bold;">Borrower Name:</label>
                                            <p style="display: inline;padding-left: 10px;"><?php echo htmlentities($result->FullName); ?> (<?php echo htmlentities($result->StudentId); ?>)</p>
                                        </div>
                                        <div class="form-group">
                                            <label style="font-weight: bold;">Book Name:</label>
                                            <p style="display: inline;padding-left: 10px;"><?php echo htmlentities($result->BookName); ?></p>
                                        </div>
                                        <div class="form-group">
                                            <label style="font-weight: bold;">ISBN:</label>
                                            <p style="display: inline;padding-left: 10px;"><?php echo htmlentities($result->ISBNNumber); ?></p>
                                        </div>
                                        <div class="form-group">
                                            <label style="font-weight: bold;">Book Issued Date:</label>
                                            <p style="display: inline;padding-left: 10px;"><?php echo htmlentities($result->IssuesDate); ?></p>
                                        </div>
                                        <div class="form-group">
                                            <label style="font-weight: bold;">Book Returned Date:</label>
                                            <p style="display: inline;padding-left: 10px;"><?php echo ($result->ReturnDate == "") ? "Not Returned Yet" : htmlentities($result->ReturnDate); ?></p>
                                        </div>
                                        <div class="form-group" style="display: none;">
                                            <label style="font-weight: bold;">Comments:</label>
                                            <input class="form-control" type="text" name="fine" id="fine" value="<?php echo ($result->fine == "") ? '0' : htmlentities($result->fine); ?>" />
                                        </div>
                                        <?php if($result->RetrunStatus == 0) { ?>
                                            <button type="submit" name="return" id="submit" class="btn btn-info">Return Book</button>
                                            <a href="manage-issued-books.php" class="btn btn-secondary">Go Back</a>
                                        <?php } 
                                    }
                                } ?>
                            </form>
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('includes/footer.php'); ?>
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>
<?php } ?>
