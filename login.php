<?php
    require 'database/user_model.php';
    
    session_start();

    const EMAIL_PATTERN = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

    if (isset($_POST['submit'])) {
        $req_email = $_POST['email'];
        $req_password = $_POST['password'];

        $email_isvalid = true;
        $password_isvalid = true;

        // check if email pattern is valid
        $email_isvalid = preg_match(EMAIL_PATTERN, $req_email);

        if ($email_isvalid) {
            $user = UserModel::get_user_by_email($req_email);

            if ($user === false) {
                $email_isvalid = false;
            } else {
                $password_isvalid = $user->verify_user_password($req_password);
            }
        }

        // if everything is correct then assign session
        // vars and redirect to home page.
        if ($email_isvalid && $password_isvalid) {
            $_SESSION['user'] = $user;
            $_SESSION['authorized'] = true;

            header("Location: /home.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include("common/bootstraplink.html"); ?>
        <link href="styles/login.css" rel="stylesheet"/>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign in</title>
    </head>
    <body class="container-sm">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-5 pt-5">
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
                                if (isset($_POST['submit']) && !$email_isvalid) {
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
                                if (isset($_POST['submit']) && !$password_isvalid) {
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