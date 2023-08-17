<?php
    $connexion = mysqli_connect("localhost:3307", "user", "user1234", "shareout_db");
    mysqli_query($connexion, " DELETE FROM admin ");
    echo $connexion->error;