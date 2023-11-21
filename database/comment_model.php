<?php
    require 'connection.php';

    class CommentModel {
        public static function create_comment($blog_id, $author_id, $comment) {
            global $conn;

            $id = rand(1000000, 9999999);

            $comment = mysqli_real_escape_string($conn, $comment);

            $sql = "INSERT INTO comments(id, blog_id, user_id, text) 
                    VALUES ($id, $blog_id, $author_id,'$comment')";

            try {
                $conn->query($sql);
                return CommentModel::get_comment($id);
            } catch (mysqli_sql_exception $e) {
                return false;
            }
        }
        public static function remove_comment($id) {
            global $conn;

            $sql = "DELETE FROM comments WHERE id = $id";
            try {
                $conn->query($sql);
                return true;
            } catch (mysqli_sql_exception) {
                return false;
            }
        }
        public static function get_comments_by_blog_id($blog_id) {
            global $conn;

            $sql = "SELECT * FROM comments 
                    WHERE blog_id = $blog_id
                    ORDER BY time DESC";
            try {
                $result = $conn->query($sql);
            } catch (mysqli_sql_exception) {
                return false;
            }

            $comments = array();
            while ($row = $result->fetch_assoc()) {
                $row['text'] = stripslashes(nl2br($row['text']));

                array_push($comments, $row);
            }

            return $comments;
        }
        public static function get_comments_by_user_id($user_id) {
            global $conn;

            $sql = "SELECT * FROM comments 
                    WHERE user_id = $user_id
                    ORDER BY time DESC";
            try {
                $result = $conn->query($sql);
            } catch (mysqli_sql_exception) {
                return false;
            }

            $comments = array();
            while ($row = $result->fetch_assoc()) {
                $row['text'] = stripslashes(nl2br($row['text']));

                array_push($comments, $row);
            }

            return $comments;
        }
        public static function get_comment($id) {
            global $conn;

            $sql = "SELECT * FROM comments WHERE id = $id";
            try {
                $result = $conn->query($sql);
            } catch (mysqli_sql_exception) {
                return false;
            }

            if ($result->num_rows === 0) {
                return false;
            }

            $row = $result->fetch_assoc();

            $row['text'] = stripslashes(nl2br($row['text']));
            return $row;
        }
    }
?>