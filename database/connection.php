<?php
    $jsonData = file_get_contents(dirname(__DIR__) . '\app_settings.json');
    $config = json_decode($jsonData, true);
    
    $host = $config['database']['host'];
    $user = $config['database']['user'];
    $pass = $config['database']['pass'];
    $name = $config['database']['name'];

    try {
        $conn = mysqli_connect($host, $user, $pass, $name);
    } catch (mysqli_sql_exception) {
        header("Location: ../errors/500.html");
        exit;
    }
?>