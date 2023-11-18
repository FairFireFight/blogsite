<?php
    require 'connection.php';

    class BlogModel {
        /**
         * @return array | false the blog, false if not found
         */
        public static function getBlogById($id) {
            global $conn;

            $sql = "SELECT * FROM blogs WHERE id = $id";

            $result = $conn->query($sql);

            if ($result->num_rows == 0) {
                return false;
            }

            $row = $result->fetch_assoc();
            $row['liked'] = false;
            $row['likes'] = BlogModel::GetHeartsCount($row['id']);

            $row['image_count'] = 0;
            $row['comments'] = 4;

            return $row;
        }

        /**
         * @return array | false the blog, false if not found
         */
        public static function getBlogsByAuthorId($author_id) {
            global $conn;

            $sql = "SELECT * FROM blogs WHERE author_id = $author_id";

            $result = $conn->query($sql);

            if ($result->num_rows == 0) {
                return false;
            }

            $blogs = array();

            while ($row = $result->fetch_assoc()) {
                $row['liked'] = false;
                $row['likes'] = BlogModel::GetHeartsCount($row['id']);
                
                $row['image_count'] = 0;
                $row['comments'] = 4;

                array_push($blogs, $row);
            }

            return $blogs;
        }

        /**
         * @return bool success or failure
         */
        public static function createNewBlog($author_id, $title, $content) {
            global $conn;

            $author_id = mysqli_real_escape_string($conn, $author_id);
            $title = mysqli_real_escape_string($conn, $title);
            $content = mysqli_real_escape_string($conn, $content);

            $sql = "INSERT INTO blogs(author_id, title, content)
                    VALUES ($author_id, '$title', '$content')";
            
            try {
                $conn->query($sql);
                return true;
            } catch (mysqli_sql_exception) {
                return false;
            }
        }

        public static function GetBlogs($page, $per_page, String $search = "") {
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
                $row['liked'] = false;
                $row['likes'] = BlogModel::GetHeartsCount($row['id']);
                
                $row['image_count'] = 0;
                $row['comments'] = 4;

                $blogs[] = $row;
            }

            return $blogs;
        }

        /**
         * hearts the blog.
         * @return bool success status
         */
        public static function HeartBlog(int $blog_id, int $user_id) {
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

        public static function GetHeartsCount(int $blog_id) {
            global $conn;

            $sql = "SELECT count(blog_id) 'count' FROM likes WHERE blog_id = $blog_id";

            try {
                $result = $conn->query($sql);

                return $result->fetch_assoc()['count'];
            } catch (mysqli_sql_exception) {

            }
        }
    }
?>