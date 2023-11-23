<?php
    require 'connection.php';

    class BlogModel {
        /**
         * @return array | false the blog, false if not found
         */
        public static function get_blog($id) {
            global $conn;

            $sql = "SELECT * FROM blogs WHERE id = $id";

            $result = $conn->query($sql);

            if ($result->num_rows == 0) {
                return false;
            }

            $row = $result->fetch_assoc();

            $row['title'] = stripslashes(nl2br($row['title']));
            $row['content'] = stripslashes(nl2br($row['content']));
            
            $row['liked'] = false;
            $row['author'] = UserModel::get_user_by_id($row['author_id'])->username;
            $row['likes'] = BlogModel::get_heart_count($row['id']);

            $row['images'] = BlogModel::get_images($row['id']);
            $row['comments'] = BlogModel::get_comment_count($row['id']);

            return $row;
        }

        /**
         * @return array | false the blog, false if not found
         */
        public static function get_blogs_by_author_id($author_id) {
            global $conn;

            $sql = "SELECT * FROM blogs WHERE author_id = $author_id";

            $result = $conn->query($sql);

            if ($result->num_rows == 0) {
                return false;
            }

            $blogs = array();

            while ($row = $result->fetch_assoc()) {
                $row['title'] = stripslashes(nl2br($row['title']));
                $row['content'] = stripslashes(nl2br($row['content']));

                $row['liked'] = false;
                $row['likes'] = BlogModel::get_heart_count($row['id']);
                $row['author'] = UserModel::get_user_by_id($row['author_id'])->username;

                $row['images'] = BlogModel::get_images($row['id']);
                $row['comments'] = BlogModel::get_comment_count($row['id']);

                array_push($blogs, $row);
            }

            return $blogs;
        }

        /**
         * @return int | false the ID of the created blog, or failure
         */
        public static function create_blog($author_id, $title, $content) {
            global $conn;

            $author_id = mysqli_real_escape_string($conn, $author_id);
            $title = mysqli_real_escape_string($conn, $title);
            $content = mysqli_real_escape_string($conn, $content);

            $id = rand(1000000, 9999999);

            $sql = "INSERT INTO blogs(id, author_id, title, content)
                    VALUES ($id, $author_id, '$title', '$content')";

            try {
                $conn->query($sql);
                return $id;
            } catch (mysqli_sql_exception) {
                return false;
            }
        }

        public static function get_blogs($page, $per_page, String $search = "") {
            global $conn;

            $search = mysqli_real_escape_string($conn, $search);

            $offset = ($page - 1) * $per_page;

            $sql = "SELECT * FROM blogs 
                    WHERE title LIKE '%$search%'
                       OR content LIKE '%$search%'
                    ORDER BY blog_time DESC 
                    LIMIT $per_page OFFSET $offset";
            try {
                $result = $conn->query($sql);
            } catch (mysqli_sql_exception) {
                return false;
            }

            $blogs = array();

            while ($row = $result->fetch_assoc()) {
                $row['title'] = stripslashes(nl2br($row['title']));
                $row['content'] = stripslashes(nl2br($row['content']));

                $row['liked'] = false;
                $row['likes'] = BlogModel::get_heart_count($row['id']);
                $row['author'] = UserModel::get_user_by_id($row['author_id'])->username;
                
                $row['images'] = BlogModel::get_images($row['id']);
                $row['comments'] = BlogModel::get_comment_count($row['id']);

                $blogs[] = $row;
            }

            return $blogs;
        }

        /**
         * hearts the blog.
         * @return bool success status
         */
        public static function heart_blog(int $blog_id, int $user_id) {
            global $conn;

            $check_heart_sql = "SELECT * FROM likes
                                WHERE user_id = $user_id 
                                AND blog_id = $blog_id";

            $check_heart_result = $conn->query($check_heart_sql);

            if ($check_heart_result->num_rows == 0) {
                // add like record here.
                $sql = "INSERT INTO likes VALUES
                        ($user_id, $blog_id)";
                try {
                    $conn->query($sql);
                } catch (mysqli_sql_exception) {
                    return false;
                }
            } else {
                // remove heart record here.
                $sql = "DELETE FROM likes WHERE
                        user_id = $user_id AND blog_id = $blog_id";
                try {
                    $conn->query($sql);
                } catch (mysqli_sql_exception) {
                    return false;
                }
            }

            return true;
        }

        public static function get_heart_count(int $blog_id) {
            global $conn;

            $sql = "SELECT count(blog_id) 'count' FROM likes WHERE blog_id = $blog_id";

            try {
                $result = $conn->query($sql);

                return $result->fetch_assoc()['count'];
            } catch (mysqli_sql_exception) {
                return 0;
            }
        }

        public static function get_comment_count(int $blog_id) {
            global $conn;

            $sql = "SELECT count(blog_id) 'count' FROM comments WHERE blog_id = $blog_id";

            try {
                $result = $conn->query($sql);

                return $result->fetch_assoc()['count'];
            } catch (mysqli_sql_exception) {
                return 0;
            }
        }

        public static function get_images(int $blog_id) {
            $images = glob(dirname(__DIR__) . "/uploads/images/content/$blog_id/*");

            foreach ($images as &$image) {
                $image = substr($image, strrpos($image, '/') + 1);
            }

            return $images;
        }
    }
?>