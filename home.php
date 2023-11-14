<?php
    /*require 'database/user_model.php';

    session_start();


    if (!isset($_SESSION['authenticated'])) {
        header("Location: /login.php");
        exit;
    } 

    $user = $_SESSION['user'];*/
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php require 'common/bootstraplink.html'?>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Home | Bloggers</title>
    </head>

    <body>
        <header class="p-3 sticky-top text-light bg-dark">
            <div class="container-md">
                <div class="row justify-content-between align-items-center">
                    <!-- Site name col -->
                    <div class="col-3 p-0">
                        <h2 class="p-0 fw-semibold">Bloggers</h2>
                    </div>

                    <!-- Search box col -->
                    <div class="col-6 p-0">
                        <input class="form-control" type="text" placeholder="Search..."/>
                    </div>

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
                <!-- Panel 1 (Larger on Medium and Larger Screens) -->
                <div class="col-md-8" style="min-height: 100vh;">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">Panel 1</h3>
                        </div>
                        <div class="panel-body">
                            <!-- Content for Panel 1 goes here -->
                            <!-- Add your content here -->
                        </div>
                    </div>
                </div>

                <!-- Panel 2 (Smaller on Medium and Larger Screens) -->
                <div class="col-md-4 bg-body-secondary" style="min-height: 100vh;">
                    <div class="panel panel-secondary">
                        <div class="panel-heading">
                            <h3 class="panel-title">Panel 2</h3>
                        </div>
                        <div class="panel-body">
                            <!-- Content for Panel 2 goes here -->
                            <!-- Add your content here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS and Popper.js (Optional) -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    </body>
</html>
