<!DOCTYPE html>
<html lang="en">
<?php 
    require '../file.class.php';
    $file_o = new File();
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!empty($_GET['id'])) {
        $id_file = $_GET['id'];
        $_SESSION['id'] = $id_file;
    } else {
        $id_file = $_SESSION['id'];
        header('Location: ?id=' . $id_file);
    }
    
    $file_name = "File doesn't existe";
    require_once '../db_connect.php'; 
    $connexion = connect_to_db();
    require_once '../auth.php';
    if (isset($_GET['disconnect'])) {
        unset($_SESSION['login']);
        header('Location: ?id=' . $_SESSION['id']);
        //die();
    }

    $file = mysqli_query($connexion, "SELECT * FROM files WHERE ID = '$id_file'");
    if ($file->num_rows == 1) {
        while($informations = $file->fetch_assoc()) {
            $uploader = $informations['ID_user'];
            $file_size = $informations['file_size'];
            $file_name = $informations['file_name'];
            $file_type = $informations['file_type'];
            $upload_date = $informations['upload_date'];
            $last_update = $informations['last_update'];
            $path = '../' . $informations['path'];
        }
    } else {
        header('Location: file-doesnt-existe.html');
        die();
    }
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="../bootstrap/bootstrap.min.js"></script>
    <title>S/O | <?= $file_name ?></title>
</head>
<body> 
<main>
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom"> 
        <a href="../index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none"> 
            <span class="fs-4">Share Out</span> 
        </a> 
        <ul class="nav nav-pills"> 
        <?php if(is_connected()) { 
            $_SESSION['logged'] = 1;
        ?>
            <li class="nav-item"><a href="../index.php" class="nav-link  ">Share new file</a></li> 
            <li class="nav-item"><a href="../myfiles.php" class="nav-link">My Files</a></li> 
            <li class="nav-item">
                <form action="" method="get">
                    <input type="submit" name="disconnect" value="Disconnect" class="nav-link" ></li> 
                </form>
            
        <?php } else { ?>
            <li class="nav-item"><a href="../sign-up.php" class="nav-link">Sing Up</a></li> 
            <li class="nav-item"><a href="../login.php" class="nav-link">Login</a></li> 
        <?php } ?>

        </ul> 
    </header>
    
    <?php if($path) { ?>
        <h1 class="text-center">File: </h1>
        <p class="text-center fs-6">
            <span class="fw-bolder">File name: </span><?= $file_name ?>
        </p> 
        <p class="text-center fs-6">
            <span class="fw-bolder">File type: </span><?= $file_type ?>
        </p> 
        <p class="text-center fs-6">
            <span class="fw-bolder ">Uploaded by:  </span> <span class="badge bg-dark"> #<?= $uploader ?> </span>
        </p> 
        <p class="text-center fs-6">
            <span class="fw-bolder">Uploading date: </span> <?= $upload_date ?>
        </p> 
        <?php if($upload_date != $last_update) { ?>
            <p class="text-center fs-6"> 
                <span class="fw-bolder">Last update: </span> <?= $last_update ?>
            </p>
        <?php } ?>
        <br>
        <a class="download-button" href="<?= $path ?>" download><button class="btn btn-outline-dark"> Download (<?= $file_o->get_file_size($file_size) ?>) </button> </a>
    <?php } ?>
    
</body>
</html>