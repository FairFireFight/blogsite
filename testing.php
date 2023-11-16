<?php
    require 'database/blog_model.php';

    $blogs = BlogModel::getBlogsByAuthorId(1200430);

    if ($blogs === false) {
        exit;
    }

    foreach ($blogs as $blog) {
        var_dump($blog);
        echo '<br>';
    }
?>