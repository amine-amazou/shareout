<!DOCTYPE html>
<html lang="en">
<?php require 'auth.php'; ?>
<?php connect_user() ?>
<head>
    <?php 
        require 'file.class.php';
        $file_o = new File();
        require_once 'db_connect.php';
        $connexion = connect_to_db();
        $id_user = $_SESSION['login'];
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="bootstrap/bootstrap.min.js"></script>
    <script src="jquery.min.js"></script>
    <script>
        var MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB
        $(document).ready(function() {
            $('#file').change(function() {
                fileSize = this.files[0].size;
                if (fileSize > MAX_FILE_SIZE) {
                    this.setCustomValidity("File must not exceed 5 MB!");
                    this.reportValidity();
                } else {
                    this.setCustomValidity("");
                }
            });
        });
    </script>
    <title>S/O</title>
</head>
<body>
    <main>
    <?php @include 'header.php' ?>
    <div class="px-4 pt-5 my-5 text-center border-bottom"> 
    <h1 class="display-4 fw-bold">S/O</h1> 
    <div class="col-lg-6 mx-auto"> 
     <p class="lead mb-4">Share-out is the simplest way to send you files around the world, Share short files and photos. </p> 
     <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mb-5"> 
     <form action="" method="POST" enctype="multipart/form-data">
        <div class="input-group mb3">
            <input type="file" class="form-control" id="file" name="file" aria-describedby="create-link" aria-label="Uplaod" required>
            <button class="btn btn-outline-secondary" type="submit" id="create-link" name="create-link">Create link</button>
        </div>
    </form>
    </div>
    <?php
        if(isset($_POST["create-link"])) {
            $dir = "files/" . basename($_FILES["file"]["name"]);
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $dir)) {
                $random_id = $file_o->generate_random_id();
                $file_size = $_FILES["file"]["size"];
                $file_name = basename($_FILES["file"]["name"]);
                $file_type = $_FILES["file"]["type"];
                mysqli_query($connexion, "INSERT INTO files (ID_user, ID, file_name, file_type, file_size, path)  VALUES ('$id_user', '$random_id', '$file_name', '$file_type', '$file_size', '$dir')");
                echo $connexion->error;
            ?>
            <div class="container"> 
                <p class="lead mb-4">The file <?= basename($_FILES["file"]["name"]); ?> has been uploaded. </p>
                <p class="lead mb-4">File link: <a href="<?= $file_o->create_link_using_id($random_id); ?>"><?= $file_o->create_link_using_id($random_id); ?></a>  </p>
            </div> 
            </div>
            <?php } else { ?>
                <div class="col-lg-6 mx-auto"> 
                    <p class="lead mb-4"> Sorry, there was an error uploading your file. </p>
                </div> 
            <?php } ?>
        <?php } ?>
    
     <div class="container col-lg-6 mx-auto px-5"> 
      <img src="img/picture.jpg" class="img-fluid border rounded-3 shadow-lg mb-4" alt="Example image" width="700" height="500" loading="lazy"> 
     </div> 
    </div> 
   </div>
</div> 
    
    <br><br>
    
    
</body>
</html>