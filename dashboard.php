<?php
    require_once 'database/user_model.php';
    require_once 'database/blog_model.php';

    session_start();

    if (!isset($_SESSION['authenticated'])) {
        header('Location: home.php');
        exit();
    }
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <?php include 'common/bootstraplink.html'?>
        <link href="styles/common.css" rel="stylesheet"/>
        
        <script defer src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script defer src="/dashboard.php.js"></script>
        <script defer src="/common.js"></script>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <style>
            .link-unstyled, .link-unstyled:link, .link-unstyled:hover {
                color: inherit;
                text-decoration: inherit;
            }
        </style>

        <title>Dashboard | Bloggers</title>
    </head>
    <body>
        <header class="px-3 py-1 sticky-top text-light bg-dark">
            <div class="container-md">
                <div class="row justify-content-between align-items-center">
                    <!-- Site name col -->
                    <div class="col-3 p-0">
                        <h2 class="p-0 my-2 fw-semibold">
                            <a class="text-light text-decoration-none" href="home.php">Bloggers</a>
                        </h2>
                    </div>
                </div>
            </div>
        </header>
        <div id="user-id" class="visually-hidden"><?= $_SESSION['user']->id ?></div>
        <div class="mt-2 container-md">
            <div class="row justify-content-between">
                <div class="order-md-1 col-md-4 pe-md-0 p-md-2">
                    <div class="border rounded p-1 mb-3">
                        <h1>filler</h1>
                    </div>
                </div>
                <div class="col-md-8 ps-md-0 p-md-2">
                    <div class="border rounded p-3 alert alert-info">
                        <h5 class="m-0"><strong>Welcome to your dashboard!</strong><br> 
                        It's only accessible by you, this is where you customize your profile, see your bloggs & comments,
                        & manage your profile's settings.</h5>
                    </div>
                    <div class="border rounded p-3">
                        <button type="button" class="btn btn-primary m-0" onclick="LoadBlogs()">Bloggs</button>
                        <button type="button" class="btn btn-primary m-0" onclick="LoadComments()">Comments</button>

                        <div id="content-container">
                            <!-- generated HTML goes here -->
                        </div>
                        <div id="container-bottom" class="d-flex align-items-center justify-content-center"><div class='spinner-border'></div></div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>