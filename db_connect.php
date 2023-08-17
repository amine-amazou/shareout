<?php
    function connect_to_db() {
        return mysqli_connect("localhost:3307", "user", "user1234", "shareout_db");
    }
?>