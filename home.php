<?php
    require 'database/user_model.php';

    session_start();


    if (!isset($_SESSION['authorized'])) {
        header("Location: /login.php");
    } 

    $user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include("common/bootstraplink.html"); ?>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>
    </head>
    <body>
        <h1>Welcome, <?php echo $user->username; ?></h1>
    </body>
</html>