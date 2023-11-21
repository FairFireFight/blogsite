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

    switch (true) {
        // gets blog from ID
        case isset($_GET['id']):
        {
            $blog = BlogModel::getBlogById($_GET['id']); 
            
            if ($blog === false) {
                echo "redirect";
                exit;
            }

            if ($user_id != false) { 
                $liked_blogs = $_SESSION['user']->get_liked_blogs();
                $blog['liked'] = in_array($blog['id'], $liked_blogs);
            }

            $blog['author'] = UserModel::get_user_by_id($blog['author_id'])->username;

            echo json_encode($blog);
            break;
        }
        // handles infinite scroll loading of blogs
        case isset($_GET['page']): 
        {
            $page = $_GET['page'];
            $search = isset($_GET['search']) ? $_GET['search'] : "";

            $blogs = BlogModel::get_blogs($page, PER_PAGE, $search);
            
            // add a liked key to each blog
            if ($user_id != false) { 
                $liked_blogs = $_SESSION['user']->get_liked_blogs();

                foreach ($blogs as &$blog) {
                    $blog['liked'] = in_array($blog['id'], $liked_blogs);
                }
            }

            echo json_encode($blogs);
            break;
        }
        // handles hearting a blog
        case isset($_POST['heart_blog']): 
        {
            if (!$user_id) {
                echo "redirect";
                exit;
            }

            $success = BlogModel::heart_blog($_POST['heart_blog'], $user_id);

            if ($success == false) {
                echo "error";
                exit;
            }

            break;
        }
        default:
            header("HTTP/1.1 400 Bad Request");
            exit;
    }
?>