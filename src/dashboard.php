<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
  header('location:index.php');
} else { ?>
  <!DOCTYPE html>
  <html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <?php include('includes/meta.php'); ?>
    <style>
      .dashboard-item {
        background-color: #fff;
        border-radius: 4px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
      }

      .dashboard-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      }

      .dashboard-item i {
        font-size: 48px;
        margin-bottom: 15px;
      }

      .dashboard-item h3 {
        font-size: 24px;
        margin-bottom: 10px;
      }

      .dashboard-item p {
        font-size: 16px;
        color: #777;
      }

      .header-line {
        border-bottom: 2px solid #75b948;
        padding-bottom: 20px;
        margin-bottom: 30px;
      }
    </style>
  </head>

  <body>
    <?php include('includes/header.php'); ?>
    <div class="content-wrapper">
      <div class="container">
        <div class="row">
          <div class="col-md-3 col-sm-6">
            <div class="dashboard-item text-center">
              <i class="fa fa-book text-primary"></i>
              <?php
              $sql = "SELECT id from tblbooks";
              $query = $dbh->prepare($sql);
              $query->execute();
              $listdbooks = $query->rowCount();
              ?>
              <h3><?php echo htmlentities($listdbooks); ?></h3>
              <p>Books Listed</p>
            </div>
          </div>

          <div class="col-md-3 col-sm-6">
            <div class="dashboard-item text-center">
              <i class="fa fa-bars text-success"></i>
              <?php
              $sql1 = "SELECT id from tblissuedbookdetails ";
              $query1 = $dbh->prepare($sql1);
              $query1->execute();
              $issuedbooks = $query1->rowCount();
              ?>
              <h3><?php echo htmlentities($issuedbooks); ?></h3>
              <p>Times Book Issued</p>
            </div>
          </div>

          <div class="col-md-3 col-sm-6">
            <div class="dashboard-item text-center">
              <i class="fa fa-recycle text-info"></i>
              <?php
              $status = 1;
              $sql2 = "SELECT id from tblissuedbookdetails where RetrunStatus=:status";
              $query2 = $dbh->prepare($sql2);
              $query2->bindParam(':status', $status, PDO::PARAM_STR);
              $query2->execute();
              $returnedbooks = $query2->rowCount();
              ?>
              <h3><?php echo htmlentities($returnedbooks); ?></h3>
              <p>Times Books Returned</p>
            </div>
          </div>

          <div class="col-md-3 col-sm-6">
            <div class="dashboard-item text-center">
              <i class="fa fa-users text-warning"></i>
              <?php
              $sql3 = "SELECT id from tblstudents ";
              $query3 = $dbh->prepare($sql3);
              $query3->execute();
              $regstds = $query3->rowCount();
              ?>
              <h3><?php echo htmlentities($regstds); ?></h3>
              <p>Registered Users</p>
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