<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 
if(isset($_GET['del']))
{
$id=$_GET['del'];
$sql = "delete from tblbooks  WHERE id=:id";
$query = $dbh->prepare($sql);
$query -> bindParam(':id',$id, PDO::PARAM_STR);
$query -> execute();
$_SESSION['delmsg']="Category deleted successfully ";
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
    <title>YBRCC Library Management System | Manage Books</title>
    <link rel="icon" href="assets/img/cropped-fav-32x32.png" sizes="32x32">
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- DATATABLE STYLE  -->
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <style>
        .book-panel {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .book-panel:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .book-panel h5 {
            font-weight: 600;
            color: #333;
            font-size: 20px;
        }
        .book-panel .row {
            margin-bottom: 15px;
        }
        .book-panel strong {
            color: #555;
        }
        .btn {
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        /* Added styles for right alignment of action buttons */
        .book-panel .row .col-md-3 {
            text-align: right;
        }
        .book-panel .row .col-md-3 button {
            margin-left: 10px;
        }
    </style>
</head>
<body>
      <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->
    <div class="content-wrapper">
         <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="text" class="form-control" id="searchBar" placeholder="Search books..." onkeyup="searchBooks()">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <select class="form-control" id="bookStatus">
                                <option value="">All Books</option>
                                <option value="available">Available</option>
                                <option value="issued">Issued</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <a href="add-book.php" class="btn btn-primary"><i class="fa fa-plus"></i> Book</a>
                        <a href="add-author.php" class="btn btn-primary" style="margin-left: 10px;"><i class="fa fa-plus"></i> Author</a>
                        <a href="add-category.php" class="btn btn-primary" style="margin-left: 10px;"><i class="fa fa-plus"></i> Category</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <?php if($_SESSION['error']!="")
            {?>
            <div class="col-md-12">
                <div class="alert alert-danger" >
                    <strong>Error :</strong> 
                    <?php echo htmlentities($_SESSION['error']);?>
                    <?php echo htmlentities($_SESSION['error']="");?>
                </div>
            </div>
            <?php } ?>
            <?php if($_SESSION['msg']!="")
            {?>
            <div class="col-md-12">
                <div class="alert alert-success" >
                    <strong>Success :</strong> 
                    <?php echo htmlentities($_SESSION['msg']);?>
                    <?php echo htmlentities($_SESSION['msg']="");?>
                </div>
            </div>
            <?php } ?>
            <?php if($_SESSION['updatemsg']!="")
            {?>
            <div class="col-md-12">
                <div class="alert alert-success" >
                    <strong>Success :</strong> 
                    <?php echo htmlentities($_SESSION['updatemsg']);?>
                    <?php echo htmlentities($_SESSION['updatemsg']="");?>
                </div>
            </div>
            <?php } ?>
            <?php if($_SESSION['delmsg']!="")
            {?>
            <div class="col-md-12">
                <div class="alert alert-success" >
                    <strong>Success :</strong> 
                    <?php echo htmlentities($_SESSION['delmsg']);?>
                    <?php echo htmlentities($_SESSION['delmsg']="");?>
                </div>
            </div>
            <?php } ?>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?php 
                $sql = "SELECT tblbooks.BookName,tblcategory.CategoryName,tblauthors.AuthorName,tblbooks.ISBNNumber,tblbooks.BookPrice,tblbooks.id as bookid from  tblbooks join tblcategory on tblcategory.id=tblbooks.CatId join tblauthors on tblauthors.id=tblbooks.AuthorId";
                $query = $dbh -> prepare($sql);
                $query->execute();
                $results=$query->fetchAll(PDO::FETCH_OBJ);
                if($query->rowCount() > 0)
                {
                    foreach($results as $result)
                    {               
                ?>                                      
                <div class="book-panel">
                    
                    <div class="row">
                        <div class="col-md-8">
                        <h5><?php echo htmlentities($result->BookName);?> <small>[<?php echo htmlentities($result->ISBNNumber);?>]</small></h5>
                           <?php echo htmlentities($result->CategoryName) . " by " . $result->AuthorName;?>
                        </div>
                        <div class="col-md-1">
                            
                        </div>
        
                        <div class="col-md-3">
                            <a title="Issue Book" href="issue-book.php?bookid=<?php echo htmlentities($result->bookid);?>"><button class="btn btn-success btn-sm"><i class="fa fa-book"></i> Issue</button></a>
                            <a title="Edit Book" href="edit-book.php?bookid=<?php echo htmlentities($result->bookid);?>"><button class="btn btn-primary btn-sm"><i class="fa fa-edit "></i> Edit</button></a>
                            <a title="Delete Book" href="manage-books.php?del=<?php echo htmlentities($result->bookid);?>" onclick="return confirm('Are you sure you want to delete?');"><button class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Delete</button></a>
                        </div>
                    </div>
                </div>
                <?php }} ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    </div>

     <!-- CONTENT-WRAPPER SECTION END-->
  <?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY  -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- DATATABLE SCRIPTS  -->
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
      <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
    <script>
    function searchBooks() {
        var input, filter, panels, panel, title, i, txtValue;
        input = document.getElementById("searchBar");
        filter = input.value.toUpperCase();
        panels = document.getElementsByClassName("book-panel");
        for (i = 0; i < panels.length; i++) {
            panel = panels[i];
            title = panel.getElementsByTagName("h5")[0];
            txtValue = title.textContent || title.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                panel.style.display = "";
            } else {
                panel.style.display = "none";
            }
        }
    }
    </script>
</body>
</html>
<?php } ?>
