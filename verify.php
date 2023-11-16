<?php
    require 'database/mail_manager.php';

    session_start();

    if (!isset($_SESSION['user'])) {
        header('Location: /register.php');
        exit;
    }

    $verification_call = isset($_GET['t']);
    $link_expired = false;
    $success = false;

    $_SESSION['user']->update();

    // if the link to here is a get request with token
    if ($verification_call) {
        $expiry_time = $_SESSION['user']->verification_expiry;

        // check if the link has expired
        if (new DateTime('now') > $expiry_time) {
            $link_expired = true;
        } else {
            $_SESSION['user']->set_verified();
            $success = true;

            $_SESSION['authenticated'] = true;
            $_SESSION['user'] = UserModel::get_user_by_id($_SESSION['user']->id);
        }
    } else { // regenerate the verification code and send again.
        if ($_SESSION['user']->renew_verification()) {  
            send_verification_email($_SESSION['user']);
        } else {
            header("http");
            exit;
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
    <title>Verify Email</title>
    </head>
    <body class="container-sm">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-6" style="padding-top: 12.5vh">
                <div class="card panel">
                    <div class="card-body">
                        <h2 class="card-title mt-2 pb-2">
                            <?php
                                if ($success) {
                                    echo "Verification complete!";
                                } elseif ($link_expired) {
                                    echo "Verification link expired";
                                } else {
                                    echo "Please verify your email";
                                }
                            ?>
                        </h2>
                        <hr/>
                        <p class="fs-4 text-center my-2">
                            <?php
                                $email = $_SESSION['user']->email;
                                
                                if ($success) {
                                    echo "
                                        The email <b>$email</b> has been verified!<br/>
                                        you may now continue to the site!</br>
                                        <a class='btn btn-primary' href='home.php'>Continue</a>
                                        </p>
                                    ";
                                } elseif ($link_expired) {
                                    echo "
                                        The verification link has passed it's expiry time.</br>
                                        click <a href='verify.php'>here</a> to resend the verification email.
                                        </p>
                                    ";
                                } else {
                                    echo "
                                        You're almost there! We sent an email to <br/>
                                        <b class='d-inline-block mt-1 mb-5'>$email</b><br/>
                                        Just click the button in that email to complete your sign up.<br>
                                        If you don't see the email, <b>check your spam</b> folder.
                                        </p>
                                        <hr/>
                                        <h6>Still can't find the email? <a href='verify.php'>Send again</a></h6>
                                    ";
                                }
                            ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>