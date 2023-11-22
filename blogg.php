<?php
    require_once "database/user_model.php";

    session_start();

    if (!isset($_GET['id'])) {
        header('Location: home.php');
        exit;
    }

    if (isset($_SESSION['authenticated'])) {
        $user_id = $_SESSION['user']->id;
    } else {
        $user_id = false;
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
        <title>Blogg</title>
    </head>
    <body>
        <header class="p-3 sticky-top text-light bg-dark">
            <div class="container-md">
                <div class="row justify-content-between align-items-center">
                    <!-- Site name col -->
                    <div class="col-3 p-0">
                        <h2 class="p-0 fw-semibold"><a class="text-light text-decoration-none" href="home.php">Bloggers</a></h2>
                    </div>

                    <!-- Spacer col -->
                    <div class="col-6 p-0"></div>

                    <!-- Profile / Buttons -->
                    <div class="w-100 d-block d-md-none"></div>
                    <div class="col-md-3">
                        <div class="row justify-content-end">
                            <a class="col-md-3 btn mx-md-1 mx-0 mt-md-0 mt-1 btn-outline-secondary" href="login.php">Login</a>
                            <a class="col-md-3 btn mt-md-0 mt-1 btn-primary" href="register.php">Sign up</a>
                        </div>
                    </div>
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
                            <div class="card-body">
                                <div class="container-md">
                                    <div class="row align-items-center justify-content-center">
                                        <div class="offset-md-1 col-4">
                                            <div class="d-flex justify-content-center">
                                                <img class="rounded-circle shadow" style="max-width: 20vw; width: 100%;" src="/uploads/images/profile_pictures/default.jpg">
                                            </div>
                                        </div>
                                        <div class="col-7">
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
                        if ($user_id) {
                    ?>
                        <div class="card w-100">
                            <div class="card-body py-1">
                                <form id="comment-form">
                                    <div class="d-flex w-100 justify-content-between my-1">
                                        <lable for="comment-input" class="fs-3">Leave a comment:</lable>
                                        <button type="button" class="btn btn-primary fw-semibold" onclick="SendComment()" form="comment-form">Comment</button>
                                    </div>
                                    <textarea id="comment-input" class="form-control mb-1"></textarea>
                                </form>
                            </div>
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
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div>
        </div>
    </body>
</html>