<style>
    .navbar {
        background-color: #75b948;
        border-color: #75b948;
    }

    .navbar-inverse .navbar-brand {
        color: white;
    }

    .logo {
        max-width: 200px;
        margin-bottom: 20px;
        float: left;
        margin-right: 20px;
    }

    .system-title {
        color: #ecf0f1;
        font-size: 25px;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        margin-left: auto;
        /* Changed from margin-left: 20px; */
    }

    .navbar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        /* Added to push title to right */
        width: 100%;
    }

    .menu-section .navbar-nav {
        float: right;
    }
</style>
<div class="navbar navbar-inverse set-radius-zero">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand">
                <img src="assets/img/logo.png" class="img-responsive logo" alt="Logo" />
            </a>
            <h1 class="system-title">LIBRARY MANAGEMENT SYSTEM</h1>
        </div>
    </div>
</div>
<!-- LOGO HEADER END-->
<?php
if (isset($_SESSION['alogin'])) {
    ?>
    <nav class="menu-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="navbar-collapse collapse">
                        <ul id="menu-top" class="nav navbar-nav">
                            <li><a href="dashboard.php" class="menu-top-active"><i class="fa fa-dashboard"></i>
                                    Dashboard</a></li>


                            
                            <li class="dropdown">
                                <a href="manage-books.php"><i class="fa fa-book"></i> Books</a>

                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-exchange"></i>
                                Ledger </a>
                                
                            </li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i>
                                    Account <i class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu">
                                    <li><a href="change-password.php"><i class="fa fa-key"></i> Change Password</a></li>
                                    <li><a href="logout.php"><i class="fa fa-sign-out"></i> Log Out</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
<?php } ?>