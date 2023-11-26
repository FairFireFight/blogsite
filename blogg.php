<?php
    require_once "database/user_model.php";

    session_start();

    if (!isset($_GET['id'])) {
        header('Location: home.php');
        exit;
    }

    if (isset($_SESSION['authenticated'])) {
        $user = $_SESSION['user'];
        $logged_in = true;
    } else {
        $user = false;
        $logged_in = false;
    }

    $id = $_GET['id'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include 'common/bootstraplink.html'?>
        <link href="styles/common.css" rel="stylesheet"/>
        
        <script defer src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script defer src="/common.js"></script>
        <script defer src="/blogg.php.js"></script>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Bloggers</title>
    </head>
    <body>
        <header class="px-3 py-1 sticky-top text-light bg-dark">
            <div class="container-md">
                <div class="row justify-content-between align-items-center">
                    <!-- Site name col -->
                    <div class="col-3 p-0">
                        <h2 class="p-0 fw-semibold">
                            <a class="text-light text-decoration-none" href="home.php">Bloggers</a>
                        </h2>
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
                <div class="col-md-8 border-end">
                    <div id="blog-container" class="d-flex justify-content-center">
                        <!-- generated html goes here -->
                        <div class="spinner-border mt-5"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-secondary">
                        <div class="card my-3">
                            <div class="card-body pe-0">
                                <div class="container-md">
                                    <div class="row align-items-center justify-content-start">
                                        <div class="col-4">
                                            <div class="d-flex justify-content-center">
                                                <img class="rounded-circle shadow" style="max-width: 20vw; width: 100%;" src="/uploads/images/profile_pictures/default.jpg">
                                            </div>
                                        </div>
                                        <div class="col-7 pe-0">
                                            <div class="text-start">   
                                                <h5 class="m-0">Blogged <abbr id="time-since">  ago</abbr> by:</h5>
                                                <a id="authorname" class="fs-4 fw-semibold" href="#"> </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 border-end">
                    <?php
                        if ($logged_in) {
                    ?>
                        
                        <div class="w-100">
                            <form id="comment-form">
                                <div class="d-flex w-100 justify-content-between align-items-end my-1">
                                    <lable for="comment-input" class="fs-5 align-bottom">Comment as <?= $_SESSION['user']->username?></lable>
                                    <button type="button" class="btn btn-primary fw-semibold" onclick="SendComment()" form="comment-form">Comment</button>
                                </div>
                                <textarea id="comment-input" class="form-control mb-1" placeholder="Text" ></textarea>
                            </form>
                        </div>
                        
                    <?php
                        } else {
                    ?>
                        <div class="alert alert-warning w-100 fs-5">
                            💬 Want to leave a comment? <a href="register.php">Sign Up!</a>
                        </div>
                    <?php
                        }
                    ?>
                    <div class="w-100">
                        <h2 id="comment-header">
                            Comments
                        </h2>
                        <hr/>
                        <div id="comment-container">
                            <!-- Generated comments go here-->
                        </div>
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div>
        </div>
    </body>
</html>