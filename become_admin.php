<?php 
    require '../db_connect.php';
    if(mysqli_query($connexion, "UPDATE users SET isAdmin = 1 WHERE ID = '8804700'")) {
        echo "Congratulations, You're admin now";
    } else {
        echo $connexion->error;
    }


?>