<?php
    require 'connection.php';

    class UserModel {
        public int $id;
        public String $username;
        public String $email;
        public String $privilage_level;
        public String $password_hash;
        public bool $verified;
    }

    /**
     * Get the user from their ID
     * @return UserModel object if a user is found
     * @return false if no user is found
     */
    function get_user_by_id(int $id) {
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
        $user->privilage_level = $row['privilage_level'];
        $user->password_hash = $row['password'];

        return $user;
    }

    /**
     * Verifies the provided password to the user's
     * @return true if the password matches
     * @return false password doesn't match
     */
    function verify_user_password(UserModel $user, String $password) {
        if (password_verify($password, $user->password_hash)) {
            return true;
        }

        return false;
    }

    /**
     * sets the verified flag of the 
     * provided user to 1
     * @return false if error or invalid id
     * @return true on success
     */
    function set_user_verified(int $id) {
        global $conn;
        
        $sql = "UPDATE users 
                SET verified = 1 
                WHERE id = $id";

        try {
            $conn->query($sql);
            return true;
        } catch (mysqli_sql_exception) {
            return false;
        }
    }
?>