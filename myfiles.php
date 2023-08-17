<!DOCTYPE html>
<html lang="en">
<?php require 'auth.php'; ?>
<?php connect_user() ?>
<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>
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
    <title>My Files</title>
</head>
<body>
    
    <main>
    <?php 
        @include 'header.php';
        if(!empty($_SESSION['message'])) { ?>
            <?php if($_SESSION['message-type'] == 'Danger') { ?>
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                    <div>
                        <?php 
                            echo $_SESSION['message'];
                            unset($_SESSION['message']);
                            unset($_SESSION['message-type']);
                        ?>
                    </div>
                </div>
            <?php } else { ?>
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                    <div>
                    <?php 
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                        unset($_SESSION['message-type']);
                    ?>                    
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
        <?php
            $full_size = mysqli_query($connexion, "SELECT sum(file_size) as 'full_size' FROM files WHERE ID_user = '$id_user'; ");
            $storage_ = mysqli_query($connexion, "SELECT * FROM users WHERE ID = '$id_user'; ");
            while ($f = $full_size->fetch_assoc()) {
                $storage_used = $f['full_size'];
            }
            while ($u = $storage_->fetch_assoc()) {
                $user_storage = $u['user_storage'];
            }
        ?>
        <div class="card"  style="margin: 20px;">
            <div class="card-body">
                Storage used: <?= $file_o->get_file_size($storage_used) ?> / <?= $file_o->get_file_size($user_storage) ?>
            </div>
        </div>
        <?php 
            $files = mysqli_query($connexion, "SELECT * FROM files WHERE ID_user = '$id_user'; ");
            
            if ($files->num_rows >= 1) { ?>
                <table class="table">
                    <tr>
                        <th scope="col">File Name</th>
                        <th scope="col">Type</th>
                        <th scope="col">Size</th>
                        <th scope="col">Link</th>
                        <th scope="col">Upload date</th>
                        <th scope="col">Last update</th>
                        <th scope="col">Delete file</th>
                        <th scope="col">Reset link</th>
                    </tr>
                <?php while($file = $files->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $file['file_name'] ?></td>
                        <td><?= $file['file_type'] ?></td>
                        <td><?= $file_o->get_file_size($file['file_size']) ?></td>
                        <td><a href="file/?id=<?= $file['ID'] ?>"> Link </a></td>
                        <td><?= $file['upload_date'] ?></td>
                        <td><?= $file['last_update'] ?></td>
                        <td>
                            <button onclick=" confirm('You really want delete this file?') && location.replace(`?delete=<?= $file['ID'] ?>`) " class="btn btn-outline-danger"> Delete file </button>
                        </td>
                        <td>
                            <button onclick="confirm('You really want reset the link of this file?') && location.replace(`?reset=<?= $file['ID'] ?>`) " class="btn btn-outline-warning"> Reset link </button>
                        </td>
                    </tr>
                <?php } ?>
                </table>
            <?php } else { ?>
                <p class="text-center lead mb-4">No file.</p> 
            <?php } ?>
            <?php
                if(isset($_GET['delete'])) {
                    $id_file = $_GET['delete'];
                    $verify = mysqli_query($connexion, "SELECT * FROM files WHERE ID = '$id_file' AND ID_user = '$id_user'");
                    if($verify->num_rows == 1) {
                        unlink($file_o->get_path_from_id($id_file));
                        mysqli_query($connexion, "DELETE FROM files WHERE ID = '$id_file' AND ID_user = '$id_user'");
                        $_SESSION['message-type'] = 'Danger';
                        $_SESSION['message'] = 'File deleted with successfuly. ';
                        header('Location: myfiles.php');
                    } else {
                        header('Location: error-delete-file.html');
                        die();
                    }
                }
                if(isset($_GET['reset'])) {
                    $id_file = $_GET['reset'];
                    $verify = mysqli_query($connexion, "SELECT * FROM files WHERE ID = '$id_file' AND ID_user = '$id_user'");
                    if($verify->num_rows == 1) {
                        $random_id = $file_o->generate_random_id();
                        mysqli_query($connexion, "UPDATE files SET ID = '$random_id', last_update = NOW() WHERE ID = '$id_file' AND ID_user = '$id_user'");
                        $file_link = 'http://localhost/shareout/file/?id=' . $random_id;
                        $_SESSION['message-type'] = 'Warning';
                        $_SESSION['message'] = "New link: <a href='$file_link' target='_blank' rel='noopener noreferrer'> $file_link </a>. ";
                        header('Location: myfiles.php');
                    } else {
                        header('Location: error-reset-link.html');
                        die();
                    }
                }
               
            ?>
    
</body>
</html>