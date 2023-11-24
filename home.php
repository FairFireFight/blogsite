<?php
    require 'database/user_model.php';
    session_start();

    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
    } else {
        $user = false;
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php require 'common/bootstraplink.html'?>
        <link href="styles/home.css" rel="stylesheet"/>
        <link href="styles/common.css" rel="stylesheet"/>

        <script defer src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script defer src="/common.js"></script>
        <script defer src="/home.php.js"></script>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Home | Bloggers</title>
    </head>

    <body>
        <header class="px-3 py-1 sticky-top text-light bg-dark">
            <div class="container-md">
                <div class="row justify-content-between align-items-center">
                    <!-- Site name col -->
                    <div class="col-3 p-0">
                        <h2 class="p-0 fw-semibold">Bloggers</h2>
                    </div>

                    <!-- Search box col -->
                    <div class="col-md-6 order-md-1 order-last p-0">
                        <form action="home.php" method="get">
                            <div class="input-group">
                                <input id="search-bar" class="form-control" type="text" name="search" placeholder="Search Bloggs..." value='<?= isset($_GET['search']) ? $_GET['search'] : "" ?>'/>
                                <input type="submit" class="input-group-text fw-semibold" value="Go 🔎" />
                            </div>
                        </form>
                    </div>

                    <!-- Profile / Buttons -->
                    <div class="col-3 order-md-1">
                        <?php if (!$user) { ?>
                        <!-- User not logged in, show login buttons-->
                        <div class="row justify-content-end align-items-center">
                            <a class="col-md-4 col-5 btn mx-1  mt-1 btn-outline-secondary" href="login.php">Login</a>
                            <a class="col-md-4 col-5 btn mt-1 btn-primary" href="register.php">Sign up</a>
                        </div>

                        <?php } else { ?>
                        <!-- User logged in, show profile buttons-->
                        <div class="d-flex justify-content-end align-items-center">
                            <div class="btn-group">
                                <button class="btn dropdown-toggle fw-semibold" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img class="rounded-circle" src="uploads/images/profile_pictures/default.jpg" style="max-width: 50px"/>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="profile?id=<?= $user->id ?>">My Profile</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
                                </ul>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="w-100 d-block d-md-none"></div>
                </div>
            </div>
        </header>

        <div class="container-md">
            <div class="row">
                <!-- Panel 1 (Larger on Medium and Larger Screens) -->
                <div class="col-md-8 full-height">
                    <div class="panel panel-primary">
                        <div class="panel-heading d-flex justify-content-between align-items-center  mt-4">
                            <h2 class="panel-title">
                                <?php
                                    if (isset($_SESSION['authenticated'])) {
                                        echo "Welcome, " . $_SESSION['user']->username . "!";
                                    } else {
                                        echo "Welcome to Bloggers!";
                                    }
                                ?>
                            </h2>
                                <?php // blogg button
                                    if (isset($_SESSION['authenticated'])) {
                                        echo '<a class="btn btn-primary" href="create_blogg.php">Blogg Something</a>';
                                    }
                                ?>
                        </div>
                        <hr>
                        <div class="panel-body">
                            <div id="content-container">
                                <!-- content loaded with JS goes here -->
                            </div>

                            <!-- blog loading spinner -->
                            <div id="container-bottom" class="d-flex align-items-center justify-content-center my-5">
                                <div class="spinner-border"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel 2 (Smaller on Medium and Larger Screens) -->
                <div class="col-md-4 border-start d-none d-md-inline full-height">
                    <div class="panel panel-secondary">
                        <div class="panel-heading">
                            <h3 class="mt-3 panel-title">Secondary Panel</h3>
                        </div>
                        <div class="panel-body">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </body>
</html>
