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
        $isbn = $_POST['isbn'];

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
            } else {
                $result = $catQuery->fetch(PDO::FETCH_OBJ);
                $categoryId = $result->id;
            }

        $author = $_POST['author'];
        $authorId = $_POST['authorId'];

        $authorSql = "SELECT id FROM tblauthors where AuthorName=:author";
        $authorQuery = $dbh->prepare($authorSql);
        $authorQuery->bindParam(':author', $author, PDO::PARAM_STR);
        $authorQuery->execute();

        if ($authorQuery->rowCount() == 0){
            $sql = "INSERT INTO  tblauthors(AuthorName) VALUES(:author)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':author', $author, PDO::PARAM_STR);
            $query->execute();
            $authorId = $dbh->lastInsertId();
        } else {
            $result = $authorQuery->fetch(PDO::FETCH_OBJ);
            $authorId = $result->id;
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
        <style>
            .wizard-steps {
                display: flex;
                border-bottom: 1px solid #ddd;
                margin-bottom: 20px;
            }
            .wizard-step {
                padding: 12px 24px;
                cursor: pointer;
                position: relative;
                background: transparent;
                border: none;
                color: #666;
                font-weight: 500;
            }
            .wizard-step.active {
                color: #333;
                background: transparent;
                border-bottom: 2px solid #333;
                margin-bottom: -1px;
            }
        </style>
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
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <div class="panel panel-info">
                            <div>
                                <div class="wizard-steps">
                                    <div class="wizard-step active" id="step1-indicator">Basic Info</div>
                                    <div class="wizard-step" id="step2-indicator">Additional Details</div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <form role="form" method="post" id="addBookForm">
                                    <!-- Step 1: Basic Info -->
                                    <div id="step1" class="wizard-section">
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
                                        
                                        <button type="button" class="btn btn-info" onclick="nextStep()">Next</button>
                                        <a href="manage-books.php" class="btn btn-secondary">Go Back</a>
                                    </div>

                                    <!-- Step 2: Additional Details -->
                                    <div id="step2" class="wizard-section" style="display: none;">
                                        <div class="form-group">
                                            <label>ISBN Number</label>
                                            <input class="form-control" type="text" name="isbn" />
                                            <p class="help-block">An ISBN is an International Standard Book Number.ISBN Must be unique</p>
                                        </div>
                                        <div class="form-group">
                                            <label>Classification Number</label>
                                            <input class="form-control" type="text" name="classification_number"/>
                                            <p class="help-block">Classification number helps in organizing books in the library.</p>
                                        </div>
                                        <div class="form-group">
                                            <label>Number of books<span style="color:red;">*</span></label>
                                            <input class="form-control" type="text" name="price" autocomplete="off" required="required" value="0" />
                                        </div>
                                        <button type="button" class="btn btn-secondary" onclick="previousStep()">Previous</button>
                                        <button type="submit" name="add" class="btn btn-info">Add Book</button>
                                    </div>
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

            <script>
            function showValidationError(message) {
                document.getElementById('validationMessage').textContent = message;
                $('#validationModal').modal('show');
            }

            function nextStep() {
                // Validate required fields in step 1
                var bookname = document.querySelector('input[name="bookname"]').value;
                var category = document.querySelector('input[name="category"]').value;
                var author = document.querySelector('input[name="author"]').value;

                var errors = [];
                
                if (!bookname.trim()) {
                    errors.push('Please enter the book name');
                } else if (bookname.length < 2) {
                    errors.push('Book name must be at least 2 characters long');
                } else if (bookname.length > 255) {
                    errors.push('Book name cannot exceed 255 characters');
                }

                if (!category.trim()) {
                    errors.push('Please select a category');
                }

                if (!author.trim()) {
                    errors.push('Please select an author');
                }

                if (errors.length > 0) {
                    showValidationError(errors.join('\n'));
                    return;
                }

                // Check if book name already exists using jQuery AJAX
                jQuery.ajax({
                    url: 'check-book-exists.php',
                    method: 'POST',
                    data: { bookname: bookname, category : category, author : author  },
                    success: function(response) {
                        if (response.exists) {
                            showValidationError('A book with this name already exists');
                        } else {
                            // Hide step 1 and show step 2
                            document.getElementById('step1').style.display = 'none';
                            document.getElementById('step2').style.display = 'block';
                        }
                            
                            // Update indicators
                document.getElementById('step1-indicator').classList.remove('active');
                document.getElementById('step2-indicator').classList.add('active');
                    }
                });
            }

            function previousStep() {
                // Hide step 2 and show step 1
                document.getElementById('step2').style.display = 'none';
                document.getElementById('step1').style.display = 'block';
                
                // Update indicators
                document.getElementById('step2-indicator').classList.remove('active');
                document.getElementById('step1-indicator').classList.add('active');
            }
        </script>
    </body>

    </html>
<?php } ?>