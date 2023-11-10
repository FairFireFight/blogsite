<?php
    require 'connection.php';

    class UserModel {
        public int $id;
        public String $username;
        public String $email;
        public String $privilege_level;
        public String $password_hash;
        public bool $verified;

        /**
         * Get the user from their ID
         * @return UserModel object if a user is found
         * @return false if no user is found
         */
        public static function get_user_by_id(int $id) {
            global $conn;

            $sql = "SELECT * FROM users
                    WHERE id = $id";

            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            if ($result->num_rows !== 1) {
                return false;
            }

            $user = new UserModel();
            $user->id = $row['id'];
            $user->username = $row['username'];
            $user->email = $row['email'];
            $user->privilege_level = $row['privilege_level'];
            $user->password_hash = $row['password'];

            return $user;
        }

        /**
         * Get the user from their email address
         * @return UserModel object if a user is found
         * @return false if no user is found
         */
        public static function get_user_by_email(String $email) {
            global $conn;
            
            $email = mysqli_real_escape_string($conn, $email);

            $sql = "SELECT * FROM users
                    WHERE email = '$email'";

            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            if ($result->num_rows !== 1) {
                return false;
            }

            $user = new UserModel();
            $user->id = $row['id'];
            $user->username = $row['username'];
            $user->email = $row['email'];
            $user->privilege_level = $row['privilege_level'];
            $user->password_hash = $row['password'];

            return $user;
        }

        /**
         * Verifies the provided password to the user's
         * @return true if the password matches
         * @return false password doesn't match
         */
        public function verify_user_password(String $password) {
            if (password_verify($password, $this->password_hash)) {
                return true;
            }

            return false;
        }


        /**
         * sets the verified flag of the user to 1
         * @return false if error
         * @return true on success
         */

        /* disabled for now
        public function set_verified() {
            global $conn;
            
            $sql = "UPDATE users 
                    SET verified = 1 
                    WHERE id = " . $this->id;

            try {
                $conn->query($sql);
                return true;
            } catch (mysqli_sql_exception) {
                return false;
            }
        }
        /* */

        /**
         * updates the user's information in the DB
         * @return true on success
         * @return false on error
         */
        public function commit_changes() {
            global $conn;

            $id = $this->id;
            $un = $this->username;
            $ph = $this->password_hash;
            $em = $this->email;
            $pl = $this->privilege_level;
            $vr = $this->verified;

            $sql = "UPDATE users SET 
                    username = $un,
                    password = $ph,
                    email = $em,
                    privilege_level = $pl,
                    verified = $vr
                    WHERE id = $id";

            try {
                $conn->query($sql);
                return true;
            } catch (mysqli_sql_exception) {
                return false;
            }
        }
    }
?>