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
$sql = "delete from tblcategory  WHERE id=:id";
$query = $dbh->prepare($sql);
$query -> bindParam(':id',$id, PDO::PARAM_STR);
$query -> execute();
$_SESSION['delmsg']="Category deleted successfully ";
header('location:manage-categories.php');

}


    ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>YBRCC Library Management System | Manage Categories</title>
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
        .category-panel {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .category-panel:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .category-panel h5 {
            font-weight: 600;
            color: #333;
            font-size: 20px;
        }
        .category-panel .row {
            margin-bottom: 15px;
        }
        .table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
            font-size: 16px;
            text-align: left;
        }
        .table th, .table td {
            padding: 12px 20px; /* Increased padding for wider cells */
            border-top: 1px solid #e0e0e0; /* Horizontal border */
            border-bottom: 1px solid #e0e0e0; /* Horizontal border */
        }
        .table th {
            background-color: #f5f5f5;
            color: #333;
            font-weight: bold;
        }
        .table tr {
            transition: background-color 0.3s;
        }
        .table tr:hover {
            background-color: #f1f1f1;
        }
        .table .btn {
            border-radius: 4px;
            padding: 6px 12px;
            font-size: 14px;
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
                
    </div>
     <div class="row">
    <?php if($_SESSION['error']!="")
    {?>
<div class="col-md-6">
<div class="alert alert-danger" >
 <strong>Error :</strong> 
 <?php echo htmlentities($_SESSION['error']);?>
<?php echo htmlentities($_SESSION['error']="");?>
</div>
</div>
<?php } ?>
<?php if($_SESSION['msg']!="")
{?>
<div class="col-md-6">
<div class="alert alert-success" >
 <strong>Success :</strong> 
 <?php echo htmlentities($_SESSION['msg']);?>
<?php echo htmlentities($_SESSION['msg']="");?>
</div>
</div>
<?php } ?>
<?php if($_SESSION['updatemsg']!="")
{?>
<div class="col-md-6">
<div class="alert alert-success" >
 <strong>Success :</strong> 
 <?php echo htmlentities($_SESSION['updatemsg']);?>
<?php echo htmlentities($_SESSION['updatemsg']="");?>
</div>
</div>
<?php } ?>


   <?php if($_SESSION['delmsg']!="")
    {?>
<div class="col-md-6">
<div class="alert alert-success" >
 <strong>Success :</strong> 
 <?php echo htmlentities($_SESSION['delmsg']);?>
<?php echo htmlentities($_SESSION['delmsg']="");?>
</div>
</div>
<?php } ?>

</div>


        </div>
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-4">
                            Categories Listing
                            </div>
                            <div class="col-md-4">
                        
                            </div>
                            <div class="col-md-4 text-right">
                        <a href="add-category.php" class="btn btn-primary" style="margin-left: 10px;"><i class="fa fa-plus"></i>Add new Category</a>
                            </div>
                            </div>
                          


                   
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Status</th>
                                            
                                            <th>BookCount</th>
                                            <th>Creation Date</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php $sql = "SELECT c.*, (SELECT COUNT(*) FROM tblbooks b WHERE b.CatId = c.id) AS BookCount FROM tblcategory c";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>                                      
                                        <tr class="odd gradeX">
                                           
                                            <td class="center"><?php echo htmlentities($result->CategoryName);?></td>
                                            <td class="center"><?php if($result->Status==1) {?>
                                            <a href="#" class="btn btn-success btn-xs">Active</a>
                                            <?php } else {?>
                                            <a href="#" class="btn btn-danger btn-xs">Inactive</a>
                                            <?php } ?></td>
                                            <td class="center"><?php echo htmlentities($result->BookCount);?></td>
                                            <td class="center"><?php echo htmlentities($result->CreationDate);?></td>
                                            <td class="text-right">

                                            <a href="edit-category.php?catid=<?php echo htmlentities($result->id);?>"><button class="btn btn-primary"><i class="fa fa-edit "></i> Edit</button> 
                                            <?php if ($result->BookCount <= 1) { ?>
                                                <a href="manage-categories.php?del=<?php echo htmlentities($result->id);?>" onclick="return confirm('Are you sure you want to delete?');">
                                                    <button class="btn btn-danger"><i class="fa fa-pencil"></i> Delete</button>
                                                </a>
                                            <?php } ?>
                                            </td>
                                        </tr>
 <?php $cnt=$cnt+1;}} ?>                                      
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                    <!--End Advanced Tables -->
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
</body>
</html>
<?php } ?>