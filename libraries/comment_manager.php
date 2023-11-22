<?php
    require_once dirname(__DIR__) .'/database/comment_model.php';
    require_once dirname(__DIR__) .'/database/user_model.php';

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

    switch(true) {
        // create a comment
        case isset($_GET['comment']):
            if ($user_id) {
                $comment = $_GET['comment'];
                $blog_id = $_GET['blog_id'];

                if ($model = CommentModel::create_comment($blog_id, $user_id, $comment)) {
                    echo json_encode($model);
                } else {
                    echo false;
                }
            } else {
                echo false;
            }
            break;
            
        // get the comments under blog_id
        case isset($_GET['blog_id']):
            $comments = CommentModel::get_comments_by_blog_id($_GET['blog_id']);

            echo json_encode($comments);
            break;

        // get the comments of the user
        case isset($_GET['user_id']):
            $comments = CommentModel::get_comments_by_user_id($_GET['user_id']);

            echo json_encode($comments);
            break;

        // delete the comment
        case isset($_GET['delete']):
            $result = CommentModel::remove_comment($_GET['delete']);

            echo $result;
            break;

        // bad request
        default:
            header("HTTP/1.1 400 Bad Request");
            exit;
    }
?>