<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {

    if (isset($_POST['issue'])) {
        $studentid = strtoupper($_POST['studentid']);
        $sql = "SELECT FullName, Status FROM tblstudents WHERE StudentId = :studentid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
        $query->execute();

        if ($query->rowCount() == 0) {
            // Student not found, insert new student
            $studentName = $_POST['studentname']; // Assuming student name is sent in the POST request
            $insertSql = "INSERT INTO tblstudents (StudentId, FullName, Status) VALUES (:studentid, :fullname, 1)";
            $insertQuery = $dbh->prepare($insertSql);
            $insertQuery->bindParam(':studentid', $studentid, PDO::PARAM_STR);
            $insertQuery->bindParam(':fullname', $studentName, PDO::PARAM_STR);
            $insertQuery->execute();
        }



        $bookid = $_POST['bookid'];
        $sql = "INSERT INTO  tblissuedbookdetails(StudentID,BookId) VALUES(:studentid,:bookid)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
        $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
        if ($lastInsertId) {
            $_SESSION['msg'] = "Book issued successfully";
            header('location:manage-issued-books.php');
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again";
            header('location:manage-issued-books.php');
        }

    } else {
        $bookid = $_GET['bookid'];
        $sql = "SELECT tblbooks.id,tblbooks.BookName,tblbooks.ISBNNumber,tblbooks.BookPrice from  tblbooks WHERE id=:bookid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
    }

    ?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>YBRCC Library Management System | Issue a new Book</title>
        <link rel="icon" href="assets/img/cropped-fav-32x32.png" sizes="32x32">
        <!-- BOOTSTRAP CORE STYLE  -->
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FONT AWESOME STYLE  -->
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLE  -->
        <link href="assets/css/style.css" rel="stylesheet" />
        <!-- GOOGLE FONT -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
        <script>
            // function for get student name
            function getstudent() {
                $("#loaderIcon").show();
                jQuery.ajax({
                    url: "get_student.php",
                    data: 'studentid=' + $("#studentid").val(),
                    type: "POST",
                    success: function (data) {
                        var studentData = JSON.parse(data);
                        $("#studentname").val(studentData.name);
                        $("#loaderIcon").hide();
                    },
                    error: function () { }
                });
            }

            //function for book details
            function getbook() {
                $("#loaderIcon").show();
                jQuery.ajax({
                    url: "get_book.php",
                    data: 'bookid=' + $("#bookid").val(),
                    type: "POST",
                    success: function (data) {
                        $("#get_book_name").html(data);
                        $("#loaderIcon").hide();
                    },
                    error: function () { }
                });
            }

        </script>
        <style type="text/css">
            .others {
                color: red;
            }

            .line-separator {
                border-bottom: 1px solid #ccc;
                width: 100%;
                margin: 20px 0;
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
                    <div class="col-md-10 col-sm-6 col-xs-12 col-md-offset-1"">
<div class=" panel panel-info">
                        <div class="panel-heading">
                            Issue a Book
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <div class="form-group">

                                    <?php
                                    if ($query->rowCount() > 0) {
                                        echo "<h3>" . $result['BookName'] . " (" . $result['ISBNNumber'] . ")</h3>";

                                    } else {
                                        echo "Book not found";
                                    }
                                    echo "<div class='line-separator'></div>";
                                    ?>
                                    <input class="form-control" type="hidden" name="bookid" id="bookid"
                                        value="<?php echo $result['id']; ?>" />
                                </div>
                                <div class="form-group">
                                    <label>Id ( mobile number )<span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" name="studentid" id="studentid"
                                        onBlur="getstudent()" autocomplete="off" required />
                                </div>

                                <div class="form-group">
                                    <span id="get_student_name" style="font-size:16px;"></span>
                                </div>


                                <div class="form-group">
                                    <label>Name<span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" name="studentname" id="studentname" required />
                                </div>
                                <button type="submit" name="issue" id="submit" class="btn btn-info">Issue Book </button>
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