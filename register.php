<?php
    require 'database/mail_manager.php';

    session_start();

    // the language of satan here don't fucking touch it
    const EMAIL_PATTERN = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    const PASSWORD_PATTERN = '/^(?=.*[A-Z])(?=.*\d).{6,}$/';

    $email_isvalid = true;
    $email_inuse = false;

    $passwords_match = true;
    $password_isvalid = true;

    $req_email = '';
    $req_username = '';
    $req_password = '';
    $req_conf_password = '';

    if (isset($_POST['submit'])) {
        $req_email = trim($_POST['email']);
        $req_username = trim($_POST['username']);
        $req_password = trim($_POST['password']);
        $req_conf_password = $_POST['conf-password'];
        
        // set email and password validity flags based on regex
        $email_isvalid = preg_match(EMAIL_PATTERN, $req_email);
        $password_isvalid = preg_match(PASSWORD_PATTERN, $req_password);

        // verifiy that the user typed both passwords correctly
        if ($req_password != $req_conf_password) {
            $passwords_match = false;
        }

        // check if email is already registered
        if (!($email_isvalid && (UserModel::get_user_by_email($req_email) === false))) {
            $email_inuse = true;
        }

        // all good, proceed with creating a DB entry
        if ($passwords_match && $password_isvalid && $email_isvalid && !$email_inuse) {
            $user = new UserModel();

            $user->username = $req_username;
            $user->email = $req_email;
            $user->password_hash = password_hash($req_password, PASSWORD_BCRYPT);
            $user->privilege_level = 'user';

            $_SESSION['user'] = UserModel::create_user($user);

            header("Location: verify.php");
            exit;
        }
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
                        <form id="form" method="post" action="register.php">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="floatingEmailInput" placeholder="name@example.com" name="email" value="<?php echo $req_email ?>" required>
                                <label for="floatingEmailInput">Email address</label>
                            </div>
                            <?php 
                                if (!$email_isvalid) {
                                    echo '
                                    <div class="alert alert-danger alert-dismissible p-2 mt-2" role="alert">
                                        Invalid Email.
                                        <button class="btn-close m-1 p-2" aria-label="Close" data-bs-dismiss="alert"></button>
                                    </div>
                                    ';
                                } elseif ($email_inuse) {
                                    echo '
                                    <div class="alert alert-danger alert-dismissible p-2 mt-2" role="alert">
                                        Email already registered.
                                        <button class="btn-close m-1 p-2" aria-label="Close" data-bs-dismiss="alert"></button>
                                    </div>
                                    ';
                                }
                            ?>

                            <div class="form-floating mt-3">
                                <input type="text" class="form-control" id="floatingUserInput" placeholder="Username" name="username" value="<?php echo $req_username ?>" required>
                                <label for="floatingUserInput">Username</label>
                            </div>

                            <div class="container-fluid">
                                <div class="row p-0">
                                    <div class="col p-0" style="margin-right: 5px">
                                        <div class="form-floating mt-3">
                                            <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password" value="<?php echo $req_password ?>" required>
                                            <label for="floatingPassword">Password</label>
                                        </div>
                                    </div>
                                    <div class="col p-0" style="margin-left: 5px">
                                        <div class="form-floating mt-3">
                                            <input type="password" class="form-control" id="floatingConfirmPassword" placeholder="Confirm Password" name="conf-password" value="<?php echo $req_conf_password ?>" required>
                                            <label for="floatingConfirmPassword">Confirm Password</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <?php 
                                if (!$password_isvalid) {
                                    echo '
                                    <div class="alert alert-danger alert-dismissible p-2 mt-2" role="alert">
                                        Password does not satisfy criteria.
                                        <button class="btn-close m-1 p-2" aria-label="Close" data-bs-dismiss="alert"></button>
                                    </div>
                                    ';
                                }
                                if (!$passwords_match) {
                                    echo '
                                    <div class="alert alert-danger alert-dismissible p-2 mt-2" role="alert">
                                        Passwords do not match.
                                        <button class="btn-close m-1 p-2" aria-label="Close" data-bs-dismiss="alert"></button>
                                    </div>
                                    ';
                                }
                            ?>

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