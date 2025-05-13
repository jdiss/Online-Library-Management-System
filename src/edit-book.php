<?php
session_start();
error_reporting(0);
include ('includes/config.php');

function isBookExists($bookname, $category, $author, $bookid)
{
    global $dbh;

    $sql = 'SELECT b.id FROM tblbooks b '
        . 'INNER JOIN tblauthors a ON b.AuthorId = a.id '
        . 'INNER JOIN tblcategory c ON b.CatId = c.id '
        . 'WHERE b.BookName = :bookname '
        . 'AND c.CategoryName = :category '
        . 'AND a.AuthorName = :author '
        . 'AND b.id != :bookid';

    $query = $dbh->prepare($sql);
    $query->bindParam(':bookname', $bookname, PDO::PARAM_STR);
    $query->bindParam(':category', $category, PDO::PARAM_STR);
    $query->bindParam(':author', $author, PDO::PARAM_STR);
    $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);

    $query->execute();
    return $query->rowCount() > 0;
}

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if (isset($_POST['update'])) {
        $bookname = $_POST['bookname'];
        $category = $_POST['category'];
        $categoryId = $_POST['categoryId'];
        $isbn = $_POST['isbn'];
        $bookid = intval($_GET['bookid']);
        $author = $_POST['author'];
        $authorId = $_POST['authorId'];
        $bookid = intval($_GET['bookid']);

        if (isBookExists($bookname, $category, $author, $bookid)) {
            $error = 'Book with this name already exists';
            
        } else {
            $catSql = 'SELECT id FROM tblcategory where CategoryName=:category';
            $catQuery = $dbh->prepare($catSql);
            $catQuery->bindParam(':category', $category, PDO::PARAM_STR);
            $catQuery->execute();

            if ($catQuery->rowCount() == 0) {
                $status = 1;
                $sql = 'INSERT INTO  tblcategory(CategoryName,Status) VALUES(:category,:status)';
                $query = $dbh->prepare($sql);
                $query->bindParam(':category', $category, PDO::PARAM_STR);
                $query->bindParam(':status', $status, PDO::PARAM_STR);
                $query->execute();
                $categoryId = $dbh->lastInsertId();
            }

            $authorSql = 'SELECT id FROM tblauthors where AuthorName=:author';
            $authorQuery = $dbh->prepare($authorSql);
            $authorQuery->bindParam(':author', $author, PDO::PARAM_STR);
            $authorQuery->execute();

            if ($authorQuery->rowCount() == 0) {
                $author = $_POST['author'];
                $sql = 'INSERT INTO  tblauthors(AuthorName) VALUES(:author)';
                $query = $dbh->prepare($sql);
                $query->bindParam(':author', $author, PDO::PARAM_STR);
                $query->execute();
                $authorId = $dbh->lastInsertId();
            }

            $isbn = $_POST['isbn'];
            $price = $_POST['price'];
            $classification_number = $_POST['classification_number'];  // New line for classification number

            $sql = 'update  tblbooks set BookName=:bookname,CatId=:category,AuthorId=:author,ISBNNumber=:isbn,BookPrice=:price,classification_number=:classification_number where id=:bookid';
            $query = $dbh->prepare($sql);
            $query->bindParam(':bookname', $bookname, PDO::PARAM_STR);
            $query->bindParam(':category', $categoryId, PDO::PARAM_STR);
            $query->bindParam(':author', $authorId, PDO::PARAM_STR);
            $query->bindParam(':isbn', $isbn, PDO::PARAM_STR);
            $query->bindParam(':price', $price, PDO::PARAM_STR);
            $query->bindParam(':classification_number', $classification_number, PDO::PARAM_STR);  // Binding the new parameter
            $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
            $query->execute();
            $_SESSION['msg'] = 'Book info updated successfully';
            header('location:manage-books.php');
        }
    }

    ?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <?php include ('includes/meta.php'); ?>

    </head>

    <body>
        <!------MENU SECTION START-->
        <?php include ('includes/header.php'); ?>
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
                        <div class="panel-body">
                            <form role="form" method="post" id="editBookForm">
                                <?php
                                $bookid = intval($_GET['bookid']);
                                $sql = 'SELECT tblbooks.BookName,tblcategory.CategoryName,tblcategory.id as cid,tblauthors.AuthorName,tblauthors.id as athrid,tblbooks.ISBNNumber,tblbooks.BookPrice,tblbooks.id as bookid,tblbooks.classification_number from  tblbooks join tblcategory on tblcategory.id=tblbooks.CatId join tblauthors on tblauthors.id=tblbooks.AuthorId where tblbooks.id=:bookid';
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                $cnt = 1;
                                if ($query->rowCount() > 0) {
                                    foreach ($results as $result) {
                                        ?>
                                       
                                       
                                        <div class="form-group">
                                            <label>Book Name<span style="color:red;">*</span></label>
                                            <input class="form-control" type="text" name="bookname" value="<?php echo htmlentities($result->BookName); ?>" required />
                                        </div>

                                        <div class="form-group">
                                            <?php $categoryName = $result->CategoryName; ?>
                                            <?php $categoryId = $result->cid; ?>
                                            <?php include ('includes/category.php'); ?>
                                        </div>

                                        <div class="form-group">
                                            <?php $authorName = $result->AuthorName; ?>
                                            <?php $authorId = $result->athrid; ?>
                                            <?php include 'includes/authors.php'; ?>
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
                                        <button type="submit" name="update" class="btn btn-info">Update</button>
                                        <a href="manage-books.php" class="btn btn-secondary">Go Back</a>
                                <?php }
                                } ?>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        </div>
        <!-- CONTENT-WRAPPER SECTION END-->
        <?php include ('includes/footer.php'); ?>
        <!-- FOOTER SECTION END-->
        <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
        <!-- CORE JQUERY  -->
        <script src="assets/js/jquery-1.10.2.js"></script>
        <!-- BOOTSTRAP SCRIPTS  -->
        <script src="assets/js/bootstrap.js"></script>
        <!-- CUSTOM SCRIPTS  -->
        <script src="assets/js/custom.js"></script>
        <!-- Modal for validation messages -->
        <div class="modal fade" id="validationModal" tabindex="-1" role="dialog" aria-labelledby="validationModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="validationModalLabel">Validation Error</h4>
                    </div>
                    <div class="modal-body" id="validationMessage">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
                                       if($error){
                                        echo "<script type='text/javascript'>document.getElementById('validationMessage').innerHTML = '$error';</script>";
            echo "<script>\$('#validationModal').modal('show');</script>";
                                       }
                                       ?>

    </body>

    </html>
<?php } ?>