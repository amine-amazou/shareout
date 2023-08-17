<?php
    class User {
        public $connexion;
        function __construct()
        {
            $this->connexion = mysqli_connect("localhost:3307", "user", "user1234", "shareout_db");
        }

        public function generate_id() {
            $files = mysqli_query($this->connexion, 'SELECT * FROM files');
            $IDS = array();
            while($file = $files->fetch_assoc()) {
                array_push($IDS, $file['ID_user']);
            }
            $characters = '0123456789';
            do {
                $rand_ = '';
                for ($i = 0; $i < 8; $i++) {
                    $rand_ .= $characters[rand(0, (strlen($characters) - 1))];
                }
            } while (in_array($rand_, $IDS));
            
            return $rand_;
        }
        public function add_user_to_database($email, $username, $password, $storage) {
            $id = $this->generate_id();
            return mysqli_query($this->connexion, "INSERT INTO users (ID, email, username, password_, user_storage)  VALUES ('$id', '$email', '$username', '$password', '$storage')");
        }
        public function username_is_already_existe($username) {
            $result = mysqli_query($this->connexion, "SELECT * FROM users WHERE username = '$username'");
            if ($result->num_rows == 0) {
                return false;
            } else {
                return true;
            }

        }
    }

    //$u = new User();
    //var_dump($u->username_is_already_existe('amazou'));
?>