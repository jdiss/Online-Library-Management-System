<style>
    .navbar {
        background-color: #75b948;
        border-color: #75b948;
    }

    .navbar-inverse .navbar-brand {
        color: white;
    }

    .logo {
        max-width: 100px;
        margin-right: 0px;
    }

    .system-title {
        color: #ecf0f1;
        font-size: 25px;
        font-weight: 700;
        align-self: center; /* Center align with logo */
    }

    .navbar-header {
        display: flex;
        align-items: center;
        justify-content: flex-start; /* Changed to flex-start for better alignment */
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
            <a class="navbar-brand" href="#">
                <img src="assets/img/logoicon.png" class="img-responsive logo" alt="Logo" />
            </a>
            <h2 class="system-title">
                Buddhist Cultural Center 
                <span style="font-size: 15px; display: block;">LIBRARY MANAGEMENT SYSTEM</span>
            </h2>
        </div>
    </div>
</div>
<!-- LOGO HEADER END-->
<?php
if (isset($_SESSION['alogin'])) {
    $current_page = basename($_SERVER['PHP_SELF']);
    ?>
    <nav class="menu-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="navbar-collapse collapse">
                        <ul id="menu-top" class="nav navbar-nav">
                            <li><a href="dashboard.php"
                                    class="<?php echo ($current_page == 'dashboard.php') ? 'menu-top-active' : ''; ?>"><i
                                        class="fa fa-dashboard"></i>
                                    Dashboard</a></li>

                            <li class="dropdown">
                                <a href="manage-books.php"
                                    class="<?php echo ($current_page == 'manage-books.php') ? 'menu-top-active' : ''; ?>"><i
                                        class="fa fa-book"></i> Books</a>
                            </li>
                            <li class="dropdown">
                                <a href="manage-issued-books.php"
                                    class="<?php echo ($current_page == 'manage-issued-books.php') ? 'menu-top-active' : ''; ?>"><i
                                        class="fa fa-exchange"></i>
                                    Inventory
                                </a>
                            </li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-medkit"></i>
                                    Settings <i class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu">
                                    <li><a href="manage-authors.php"><i class="fa fa-pencil"></i>Authors</a></li>
                                    <li><a href="manage-categories.php"><i class="fa fa-list"></i> Categories</a></li>
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