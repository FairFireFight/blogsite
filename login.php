<?php
    session_start();

    $email_isvalid = true;
    $password_isvalid = true;

    if (isset($_POST['submit'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // check if email pattern is valid
        $pattern = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        if (!preg_match($pattern, $email)) {
            $email_isvalid = false;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include("database/bootstraplink.html"); ?>
        <link href="styles/login.css" rel="stylesheet"/>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign in</title>
    </head>
    <body class="container-sm">
        <div class="row align-items-center">
            <div class="col-lg-5">
                <div class="card panel">
                    <div class="card-body">
                        <h2 class="card-title">Sign in</h2>
                        <hr/>
                        <form id="form" method="post" action="login.php">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email" required>
                                <label for="floatingInput">Email address</label>
                            </div>
                            <?php
                                if (!$email_isvalid) {
                                    echo 
                                    '<div class="alert alert-danger alert-dismissible p-2 mt-2" role="alert">
                                        Invalid Email
                                        <button class="btn-close m-1 p-2" aria-label="Close" data-bs-dismiss="alert"></button>
                                    </div>';
                                }
                            ?>

                            <div class="form-floating mt-3">
                                <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password" required>
                                <label for="floatingPassword">Password</label>
                            </div>

                            <?php
                                if (!$password_isvalid) {
                                    echo 
                                    '<div class="alert alert-danger alert-dismissible p-2 mt-2" role="alert">
                                        Wrong Password
                                        <button class="btn-close m-1 p-2" aria-label="Close" data-bs-dismiss="alert"></button>
                                    </div>';
                                }
                            ?>
                            
                            <div class="mb-3">
                                <a href="reset_password.php">Forgot Password?</a>
                            </div>

                            <button class="btn btn-primary w-100 p-2 mb-3" name="submit">Sign in</button>
                        </form>
                        <hr/>
                        <h6>New user? <a href="register.php">Sign up</a></h6>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>