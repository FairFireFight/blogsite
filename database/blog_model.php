<?php
    require 'connection.php';

    class BlogModel {
        public $id;
        public $author_id;
        public $title;
        public $content;
        public $blog_time;

        /**
         * @return BlogModel | false the blog, false if not found
         */
        public static function getBlogById($id) {
            global $conn;

            $sql = "SELECT * FROM blogs WHERE id = $id";


            $result = $conn->query($sql);

            if ($result->num_rows == 0) {
                return false;
            }

            $row = $result->fetch_assoc();

            $blog = new BlogModel();

            $blog->id = $row["id"];
            $blog->author_id = $row["author_id"];
            $blog->title = $row["title"];
            $blog->content = $row["content"];
            $blog->blog_time = $row["blog-time"];

            return $blog;
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
                $blog = new BlogModel();
    
                $blog->id = $row["id"];
                $blog->author_id = $row["author_id"];
                $blog->title = $row["title"];
                $blog->content = $row["content"];
                $blog->blog_time = $row["blog_time"];

                array_push($blogs, $blog);
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
                $id = $row["id"];
                $author_id = $row["author_id"];
                $title = $row["title"];
                $content = $row["content"];
                $blog_time = $row["blog_time"];

                $blogs[] = array('id' => $id, 'author_id' => $author_id, 'title' => $title, 'content' => $content, 'time' => $blog_time);
            }

            return $blogs;
        }
    }
?>