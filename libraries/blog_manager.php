<?php
    require_once dirname(__DIR__) .'/database/blog_model.php';
    require_once dirname(__DIR__) .'/database/user_model.php';

    const PER_PAGE = 4;

    session_start();

    /* disable for testing
    if (!(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
        header("HTTP/1.1 403 Forbidden");
        exit;
    }
    /* */
    
    if (isset($_SESSION['authenticated'])) {
        $user_id = $_SESSION['user']->id;
    } else {
        $user_id = false;
    }

    if (isset($_GET['page'])) {
        $page = $_GET['page'];
        $search = isset($_GET['search']) ? $_GET['search'] : "";

        $blogs = BlogModel::GetBlogs($page, PER_PAGE, $search);

        reset($blogs);
        
        // add a liked key to each blog
        if ($user_id != false) { 
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