<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';
    require 'database/user_model.php';

    $jsonData = file_get_contents('app_settings.json');
    $config = json_decode($jsonData, true);

    $EMAIL_ADDRESS = $config['mail']['email'];
    $PASSWORD = $config['mail']['pass'];


    /**
     * sends a verification email to the provided user
     * this does not handle the verification process.
     * @return bool status of the email.
     */
    function send_verification_email(UserModel &$user) {
        global $EMAIL_ADDRESS;
        global $PASSWORD;
        
        $username = $user->username;
        $token = $user->verification_code;
 
        $verification_link = $_SERVER['SERVER_NAME'] . "/verify.php?t=$token";

        $html_content = "
            <html>
                <body>
                    <h1>Email Confirmation</h1>
                    <hr/>
                    <h4>Welcome, $username!</h4>
                    <h4>Please click the link below to verify your email address.</h4>
                    <h6>If you did not sign up yourself, <b>please ignore this email</b> and do not click the link.</h6>
                    <a href='$verification_link'>$verification_link</a>
                </body>
            </html>
        ";

        try {
            $mail = new PHPMailer(true);

            //Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $EMAIL_ADDRESS;
            $mail->Password = $PASSWORD;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            //Sender information
            $mail->setFrom($EMAIL_ADDRESS, $_SERVER['SERVER_NAME']);
            $mail->addAddress($user->email, $username);

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'Subject of the email';
            $mail->Body = $html_content;

            $mail_sent = $mail->send();

            return $mail_sent;
        } catch (Exception) {
            return false;
        }
    }
?>