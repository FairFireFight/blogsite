<?php
    require 'connection.php';

    class UserModel {
        public int $id;
        public String $username;
        public String $email;
        public String $privilege_level;
        public String $password_hash;
        public bool $verified;
        public String $verification_code;
        public DateTime $verification_expiry;

        /**
         * Get the user from their ID
         * @return UserModel object if a user is found
         * @return false if no user is found
         */
        public static function get_user_by_id(int $id) {
            global $conn;

            $id = mysqli_real_escape_string($conn, $id);

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
            $user->verified = $row['verified'];

            if ($row['verification_code']) {
                $user->verification_code = $row['verification_code'];
            } else {
                unset($user->verification_code);
            }
            
            $user->verification_expiry = new DateTime($row['verification_expiry']);

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
            $user->verified = $row['verified'];
            $user->verification_code = $row['verification_code'] ? $row['verification_code'] : false ;
            $user->verification_expiry = $row['verification_expiry'] ? new DateTime($row['verification_expiry']) : new DateTime('now');
            
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
        public function set_verified() {
            global $conn;
            
            $sql = "UPDATE users 
                    SET verified = 1, verification_code = NULL
                    WHERE id = " . $this->id;

            try {
                $conn->query($sql);
                return true;
            } catch (mysqli_sql_exception) {
                return false;
            }
        }

        public function renew_verification() {
            global $conn;

            // generate 6 random digits for verification
            $v_code = strtoupper(bin2hex(random_bytes(3)));

            // 24 hour expiration time
            $expiry_date = new DateTime('now');
            $expiry_date->modify('+24 hours');
            $expiry_date_string = $expiry_date->format('Y-m-d H:i:s');

            $sql = "UPDATE users 
                    SET verification_code = '$v_code', 
                    verification_expiry = '$expiry_date_string'
                    WHERE id = $this->id";

            try {
                $conn->query($sql);

                $this->verification_code = $v_code;
                $this->verification_expiry = $expiry_date;

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

            $id = mysqli_real_escape_string($conn, $this->id);
            $un = mysqli_real_escape_string($conn, $this->username);
            $ph = mysqli_real_escape_string($conn, $this->password_hash);
            $em = mysqli_real_escape_string($conn, $this->email);
            $pl = mysqli_real_escape_string($conn, $this->privilege_level);
            $vr = mysqli_real_escape_string($conn, $this->verified);

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

        /**
         * updates the current object to match the same data stored
         * in the database
         * 
         * yes this is really bad code now
         */
        public function update() {
            $new_user = UserModel::get_user_by_email($this->email);

            $this->username = $new_user->username;
            $this->email = $new_user->email;
            $this->privilege_level = $new_user->privilege_level;
            $this->password_hash = $new_user->password_hash;
            $this->verified = $new_user->verified;
            $this->verification_code = $new_user->verification_code;
            $this->verification_expiry = $new_user->verification_expiry;
        }

        /**
         * Inserts the provided UserModel into the database
         * @return UserModel of the user if inserted successfully
         * @return false if failed to insert
         */
        public static function create_user(UserModel $user) {
            global $conn;

            $un = mysqli_real_escape_string($conn, $user->username);
            $ph = mysqli_real_escape_string($conn, $user->password_hash);
            $em = mysqli_real_escape_string($conn, $user->email);

            $sql = "INSERT INTO users (username, password, email) 
                    VALUES('$un', '$ph', '$em')";

            try {
                $conn->query($sql);
                return UserModel::get_user_by_email($em);
            } catch (mysqli_sql_exception) {
                return false;
            }
        }
    }
?>