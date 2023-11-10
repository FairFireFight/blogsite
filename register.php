<?php
    require 'database/user_model.php';

    if (isset($_POST['submit'])) {
        
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include 'common/bootstraplink.html'; ?>
        <link href="styles/register.css" rel="stylesheet"/>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register</title>
    </head>
    <body class="container-sm">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-5" style="padding-top: 10vh">
                <div class="card panel">
                    <div class="card-body">
                        <h2 class="card-title mt-2 pb-2">Register</h2>
                        <hr/>
                        <form id="form" method="post" action="login.php">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="floatingEmailInput" placeholder="name@example.com" name="email" required>
                                <label for="floatingEmailInput">Email address</label>
                            </div>

                            <div class="alert alert-danger alert-dismissible p-2 mt-2" role="alert">
                                Invalid Email
                                <button class="btn-close m-1 p-2" aria-label="Close" data-bs-dismiss="alert"></button>
                            </div>

                            <div class="form-floating mt-3">
                                <input type="text" class="form-control" id="floatingUserInput" placeholder="Username" name="Username" required>
                                <label for="floatingUserInput">Username</label>
                            </div>

                            <div class="container-fluid">
                                <div class="row p-0">
                                    <div class="col p-0" style="margin-right: 5px">
                                        <div class="form-floating mt-3">
                                            <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password" required>
                                            <label for="floatingPassword">Password</label>
                                        </div>
                                    </div>
                                    <div class="col p-0" style="margin-left: 5px">
                                        <div class="form-floating mt-3">
                                            <input type="password" class="form-control" id="floatingConfirmPassword" placeholder="Confirm Password" name="cof-password" required>
                                            <label for="floatingConfirmPassword">Confirm Password</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-danger alert-dismissible p-2 mt-2" role="alert">
                                Password does not match criteria
                                <button class="btn-close m-1 p-2" aria-label="Close" data-bs-dismiss="alert"></button>
                            </div>

                            <div class="my-2">
                                <p class="mb-1">Password must contain at least:</p>
                                <ul>
                                    <li>Six characters</li>
                                    <li>One uppercase character</li>
                                    <li>One digit</li>
                                </ul>
                            </div>

                            <button class="btn btn-primary w-100 p-2 mb" name="submit">Register</button>
                        </form>
                        <hr/>
                        <h6>Existing user? <a href="login.php">Sign in</a></h6>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>