<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {

    if (isset($_POST['add'])) {
        $bookname = $_POST['bookname'];
        $category = $_POST['category'];
        $categoryId = $_POST['categoryId'];

        if ($categoryId == "0"){
            $status = 1;
            $sql = "INSERT INTO  tblcategory(CategoryName,Status) VALUES(:category,:status)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':category', $category, PDO::PARAM_STR);
            $query->bindParam(':status', $status, PDO::PARAM_STR);
            $query->execute();
            $categoryId = $dbh->lastInsertId();
        }

        $author = $_POST['author'];
        $authorId = $_POST['authorId'];

        if ($authorId == "0"){
            $author = $_POST['author'];
            $sql = "INSERT INTO  tblauthors(AuthorName) VALUES(:author)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':author', $author, PDO::PARAM_STR);
            $query->execute();
            $authorId = $dbh->lastInsertId();
        }

        $isbn = $_POST['isbn'];
        $price = $_POST['price'];
        $classification_number = $_POST['classification_number']; // New line for classification number
        $sql = "INSERT INTO tblbooks(BookName, CatId, AuthorId, ISBNNumber, BookPrice, classification_number) VALUES(:bookname, :category, :author, :isbn, :price, :classification_number)"; // Updated SQL query
        $query = $dbh->prepare($sql);
        $query->bindParam(':bookname', $bookname, PDO::PARAM_STR);
        $query->bindParam(':category', $categoryId, PDO::PARAM_STR);
        $query->bindParam(':author', $authorId, PDO::PARAM_STR);
        $query->bindParam(':isbn', $isbn, PDO::PARAM_STR);
        $query->bindParam(':price', $price, PDO::PARAM_STR);
        $query->bindParam(':classification_number', $classification_number, PDO::PARAM_STR); // Binding the new parameter
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();

        if ($lastInsertId) {
            $_SESSION['msg'] = "Book Listed successfully";
            header('location:manage-books.php');
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again";
            header('location:manage-books.php');
        }

    }
    ?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <?php include('includes/meta.php'); ?>
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
                    <?php if ($_SESSION['error'] != "") { ?>
                        <div class="col-md-6">
                            <div class="alert alert-danger">
                                <strong>Error :</strong>
                                <?php echo htmlentities($_SESSION['error']); ?>
                                <?php echo htmlentities($_SESSION['error'] = ""); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($_SESSION['msg'] != "") { ?>
                        <div class="col-md-6">
                            <div class="alert alert-success">
                                <strong>Success :</strong>
                                <?php echo htmlentities($_SESSION['msg']); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($_SESSION['updatemsg'] != "") { ?>
                        <div class="col-md-6">
                            <div class="alert alert-success">
                                <strong>Success :</strong>
                                <?php echo htmlentities($_SESSION['updatemsg']); ?>
                                <?php echo htmlentities($_SESSION['updatemsg'] = ""); ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3"">
<div class=" panel panel-info">
                        <div class="panel-heading">
                            Book Info
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <div class="form-group">
                                    <label>Book Name<span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" name="bookname" autocomplete="off" required />
                                </div>

                                <div class="form-group">
                                    <?php $categoryName = ''?>
                                    <?php $categoryId = 0?>
                                    <?php include 'includes/category.php' ; ?>
                                </div>


                                <div class="form-group">
                                    <?php $authorName = ''?>
                                    <?php $authorId = 0?>
                                    <?php include 'includes/authors.php' ; ?>
                                </div>

                                <div class="form-group">
                                    <label>ISBN Number</label>
                                    <input class="form-control" type="text" name="isbn" />
                                    <p class="help-block">An ISBN is an International Standard Book Number.ISBN Must be
                                        unique</p>
                                </div>
                                <div class="form-group">
                                    <label>Classification Number</label>
                                    <input class="form-control" type="text" name="classification_number"/>
                                    <p class="help-block">Classification number helps in organizing books in the library.</p>
                                </div>

                                <div class="form-group">
                                    <label>Number of books<span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" name="price" autocomplete="off"
                                        required="required" value="0" />
                                </div>
                                <button type="submit" name="add" class="btn btn-info">Add </button>
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