<?php
    session_start();

    if (!isset($_GET['id'])) {
        header('Location: home.php');
        exit;
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
                <div class="col-md-8 full-height">
                    <div id="blog-container" class="d-flex justify-content-center">
                        <!-- generated html goes here -->
                        <div class="spinner-border mt-5"></div>
                    </div>
                    <div class="w-100">
                        <h2 id="comment-header">
                            Comments
                        </h2>
                        <hr/>
                        <div id="comment-container">
                            <div class="card mt-2 mb-3">
                                <div class="card-body py-1">
                                    <div class="d-flex">
                                        <div class="ms-0 me-2">
                                            <img class="rounded-circle shadow mt-2" style="max-width: 30vw; width: 50px;" src="/uploads/images/profile_pictures/default.jpg">
                                        </div>
                                        <div class="mx-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p class="fs-4 mb-0 mt-1">Username:</p>
                                                <p title="11/19/2023 5pm" class="text-body-secondary fs-6 mb-0 mt-1">1 day ago</p>
                                            </div>
                                            <hr class="my-2">
                                            <p class="fs-5 mb-2">Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo quasi quos ratione, sed animi voluptas?</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 border-start full-height">
                    <div class="panel panel-secondary">
                        <div class="card my-3">
                            <div class="card-body">
                                <div class="container-md">
                                    <div class="row align-items-center justify-content-center">
                                        <div class="col-md-4">
                                            <div class="d-flex justify-content-center">
                                                <img class="rounded-circle shadow" style="max-width: 30vw; width: 100%;" src="/uploads/images/profile_pictures/default.jpg">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="text-center text-md-start">   
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
        </div>
    </body>
</html>