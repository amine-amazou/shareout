<?php
    class File {
        public $connexion;
        function __construct()
        {
            $this->connexion = mysqli_connect("localhost:3307", "user", "user1234", "shareout_db");
        }
        
        public function get_path_from_id($id) {
            $files = mysqli_query($this->connexion, "SELECT * FROM files WHERE ID = '$id'");
            while($file = $files->fetch_assoc()) {
                return $file['path'];
            }
        }
        public function create_link_using_id($id) {
            return 'http://localhost/shareout/file/?id=' . $id;
        }
        public function generate_random_id() {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $IDS = array();
            $files = mysqli_query($this->connexion, "SELECT * FROM files");
            while($file = $files->fetch_assoc()) {
                array_push($IDS, $file['ID']);
            }
            do {
                $rand_ = '';
                for ($i = 0; $i < 8; $i++) {
                    $rand_ .= $characters[rand(0, (strlen($characters) - 1))];
                }   
            } while (in_array($rand_, $IDS));
            return $rand_;
            
        }
        public function get_file_size($file) {
            return number_format($file / 1024, 2) . ' KB';
        }
    }

?>