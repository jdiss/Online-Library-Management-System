<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {

    if (isset($_POST['update'])) {
        $bookname = $_POST['bookname'];
        $category = $_POST['category'];
        $categoryId = $_POST['categoryId'];

        $catSql = "SELECT id FROM tblcategory where CategoryName=:category";
        $catQuery = $dbh->prepare($catSql);
        $catQuery->bindParam(':category', $category, PDO::PARAM_STR);
        $catQuery->execute();

        if ($catQuery->rowCount() == 0){
            $status = 1;
            $sql = "INSERT INTO  tblcategory(CategoryName,Status) VALUES(:category,:status)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':category', $category, PDO::PARAM_STR);
            $query->bindParam(':status', $status, PDO::PARAM_STR);
            $query->execute();
            $categoryId = $dbh->lastInsertId();
        }

        $author = $_POST['author'];
        $isbn = $_POST['isbn'];
        $price = $_POST['price'];
        $classification_number = $_POST['classification_number']; // New line for classification number
        $bookid = intval($_GET['bookid']);
        $sql = "update  tblbooks set BookName=:bookname,CatId=:category,AuthorId=:author,ISBNNumber=:isbn,BookPrice=:price,classification_number=:classification_number where id=:bookid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':bookname', $bookname, PDO::PARAM_STR);
        $query->bindParam(':category', $categoryId, PDO::PARAM_STR);
        $query->bindParam(':author', $author, PDO::PARAM_STR);
        $query->bindParam(':isbn', $isbn, PDO::PARAM_STR);
        $query->bindParam(':price', $price, PDO::PARAM_STR);
        $query->bindParam(':classification_number', $classification_number, PDO::PARAM_STR); // Binding the new parameter
        $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
        $query->execute();
        $_SESSION['msg'] = "Book info updated successfully";
        header('location:manage-books.php');
    }
?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>YBRCC Library Management System | Manage Issued Books</title>
    <link rel="icon" href="assets/img/cropped-fav-32x32.png" sizes="32x32">
        <!-- BOOTSTRAP CORE STYLE  -->
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FONT AWESOME STYLE  -->
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLE  -->
        <link href="assets/css/style.css" rel="stylesheet" />
        <!-- GOOGLE FONT -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

    </head>

    <body>
        <!------MENU SECTION START-->
        <?php include('includes/header.php'); ?>
        <!-- MENU SECTION END-->
        <div class="content-wra
    <div class=" content-wrapper">
            <div class="container">
                <div class="row pad-botm">
                    <div class="col-md-12">
                       

                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3"">
<div class=" panel panel-info">
                        <div class="panel-heading">
                            Book Info
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <?php
                                $bookid = intval($_GET['bookid']);
                                $sql = "SELECT tblbooks.BookName,tblcategory.CategoryName,tblcategory.id as cid,tblauthors.AuthorName,tblauthors.id as athrid,tblbooks.ISBNNumber,tblbooks.BookPrice,tblbooks.id as bookid,tblbooks.classification_number from  tblbooks join tblcategory on tblcategory.id=tblbooks.CatId join tblauthors on tblauthors.id=tblbooks.AuthorId where tblbooks.id=:bookid";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                $cnt = 1;
                                if ($query->rowCount() > 0) {
                                    foreach ($results as $result) {               ?>

                                        <div class="form-group">
                                            <label>Book Name<span style="color:red;">*</span></label>
                                            <input class="form-control" type="text" name="bookname" value="<?php echo htmlentities($result->BookName); ?>" required />
                                        </div>

                                        <div class="form-group">
                                            <?php $categoryName = $result->CategoryName;?>
                                            <?php $categoryId = $result->cid;?>
                                        <?php include('includes/category.php'); ?>
                                        </div>


                                        <div class="form-group">
                                            <label> Author<span style="color:red;">*</span></label>
                                            <select class="form-control" name="author" required="required">
                                                <option value="<?php echo htmlentities($result->athrid); ?>"> <?php echo htmlentities($athrname = $result->AuthorName); ?></option>
                                                <?php

                                                $sql2 = "SELECT * from  tblauthors ";
                                                $query2 = $dbh->prepare($sql2);
                                                $query2->execute();
                                                $result2 = $query2->fetchAll(PDO::FETCH_OBJ);
                                                if ($query2->rowCount() > 0) {
                                                    foreach ($result2 as $ret) {
                                                        if ($athrname == $ret->AuthorName) {
                                                            continue;
                                                        } else {

                                                ?>
                                                            <option value="<?php echo htmlentities($ret->id); ?>"><?php echo htmlentities($ret->AuthorName); ?></option>
                                                <?php }
                                                    }
                                                } ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>ISBN Number</label>
                                            <input class="form-control" type="text" name="isbn" value="<?php echo htmlentities($result->ISBNNumber); ?>" />
                                            <p class="help-block">An ISBN is an International Standard Book Number. ISBN must be unique</p>
                                        </div>
                                        <div class="form-group">
                                            <label>Classification Number</label>
                                            <input class="form-control" type="text" name="classification_number" value="<?php echo htmlentities($result->classification_number); ?>" />
                                            <p class="help-block">Classification number helps in organizing books in the library.</p>
                                        </div>

                                        <div class="form-group">
                                        <label>Number of books<span style="color:red;">*</span></label>
                                            <input class="form-control" type="text" name="price" value="<?php echo htmlentities($result->BookPrice); ?>" required="required" />
                                        </div>
                                <?php }
                                } ?>
                                <button type="submit" name="update" class="btn btn-info">Update </button>
                                <a href="manage-books.php" class="btn btn-secondary">Go Back</a>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        </div>
        <!-- CONTENT-WRAPPER SECTION END-->
        <?php include('includes/footer.php'); ?>
        <!-- FOOTER SECTION END-->
        <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
        <!-- CORE JQUERY  -->
        <script src="assets/js/jquery-1.10.2.js"></script>
        <!-- BOOTSTRAP SCRIPTS  -->
        <script src="assets/js/bootstrap.js"></script>
        <!-- CUSTOM SCRIPTS  -->
        <script src="assets/js/custom.js"></script>
    </body>

    </html>
<?php } ?>