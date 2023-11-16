<?php
    require_once 'blog_model.php';
    require_once 'user_model.php';

    session_start();

    if (isset($_SESSION['authenticated'])) {
        $user_id = $_SESSION['user']->id;
    } else {
        $user_id = false;
    }

    if (isset($_GET['page'])) {
        $page = $_GET['page'];
        
        $blogs = BlogModel::GetBlogs($page, 5);
        reset($blogs);

        if ($user_id != false) { // add a liked key to each blog
            $liked_blogs = $_SESSION['user']->get_liked_blogs();

            foreach ($blogs as &$blog) {
                $blog['liked'] = in_array($blog['id'], $liked_blogs);
            }
        } else {
            foreach ($blogs as &$blog) {
                $blog['liked'] = false;
            }
        }

        echo json_encode($blogs);
    } else {
        header("HTTP/1.1 400 Bad Request");
        exit;
    }
?>