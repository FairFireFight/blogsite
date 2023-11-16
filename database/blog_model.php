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

        public static function createNewBlog($author_id, $title, $content = "") {

        }
    }
?>