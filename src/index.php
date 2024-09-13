<?php
session_start();
include('includes/config.php');
if(isset($_POST['login']))
{
    $username=$_POST['username'];
    $password=md5($_POST['password']);
    $sql ="SELECT UserName,Password FROM admin WHERE UserName=:username and Password=:password";
    $query= $dbh -> prepare($sql);
    $query-> bindParam(':username', $username, PDO::PARAM_STR);
    $query-> bindParam(':password', $password, PDO::PARAM_STR);
    $query-> execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
    
    if($query->rowCount() > 0)
    {
        $_SESSION['alogin']=$_POST['username'];
        echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";
    } else{
        echo "<script>alert('Invalid Details');</script>";
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>YBRCC - LIBRARY MANAGEMENT SYSTEM</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <link rel="icon" href="assets/img/cropped-fav-32x32.png" sizes="32x32">
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css' />
    <style>
        body {
            background-color: #2c3e50;
            font-family: 'Open Sans', sans-serif;
        }
        .content-wrapper {
            padding-top: 40px;
        }
        .panel {
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
            margin: 0 auto;
            max-width: 400px;
        }
        .panel-heading {
            color: white;
            font-weight: 600;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            padding: 20px;
            font-size: 20px;
        }
        .panel-heading-green {
            background-color: #75b948;
            font-weight: 600;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    padding: 20px;
    font-size: 20px;
    color: white;
        }
        .form-control {
            border-radius: 6px;
            height: 45px;
            font-size: 16px;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 20px;
        }
        .system-title {
            color: #ecf0f1;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        @media (max-width: 768px) {
            .content-wrapper {
                padding-top: 20px;
            }
            .panel {
                margin: 0 15px;
            }
            .logo {
                max-width: 150px;
            }
            .system-title {
                font-size: 16px;
                margin-bottom: 20px;
            }
            .panel-heading-green {
                background-color: #75b948;
            }
        }
    </style>
</head>
<body>
<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="text-center">
                <img src="assets/img/logo.png" class="img-responsive center-block logo" alt="Logo" />
                <h1 class="system-title">LIBRARY MANAGEMENT SYSTEM</h1>
            </div>
        </div>
             
        <!--LOGIN PANEL START-->           
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="text-center panel-heading-green">
                        Please Login
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input class="form-control" type="text" name="username" id="username" required />
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input class="form-control" type="password" name="password" id="password" required />
                            </div>
                            <button type="submit" name="login" class="btn btn-info btn-block">LOGIN</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>  
        <!---LOGIN PANEL END-->            
    </div>
</div>
<!-- BOOTSTRAP SCRIPTS  -->
<script src="assets/js/bootstrap.js"></script>
</body>
</html>
