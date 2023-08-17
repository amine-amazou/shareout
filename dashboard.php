<!DOCTYPE html>
<html lang="en">
<?php require 'auth.php'; ?>
<?php connect_user() ?>
<?php connect_admin() ?>
<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="bootstrap/bootstrap.min.js"></script>
    <script src="jquery.min.js"></script>
    <title>Admin | Dashboard </title>
    <?php
        require 'file.class.php';
        $file_o = new File();
        require_once 'db_connect.php'; 
        $connexion = connect_to_db();
    ?>
</head>
<body>
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom"> 
        <a href="index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none"> 
            <span class="fs-4">Admin Panel</span> 
        </a> 
        <ul class="nav nav-pills"> 
            <li class="nav-item">
                <a href="index.php" class="nav-link"> Go back </a>
            </li>

        </ul> 
    </header>
<main style="padding: 15px;">
    <?php if(!empty($_SESSION['message'])) { ?>
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
        <?php } ?>
    <?php } ?>

    <?php $user_accounts = 0; ?>
    <?php $file_uploaded = 0; ?>
    <?php
        $stmt = $connexion->prepare("SELECT count(*) FROM users");
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_array(MYSQLI_NUM)) {
            foreach ($row as $r) {
                $user_accounts = $r;
            }
        }
        $stmt = $connexion->prepare("SELECT count(*) FROM files");
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_array(MYSQLI_NUM)) {
            foreach ($row as $r) {
                $file_uploaded = $r;
            }
        }
    ?>
    <div class="card" style="width: 18rem;">
    <div class="card-header">
        Analytics
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">Users accounts: <?= $user_accounts ?></li>
        <li class="list-group-item">Files uploaded: <?= $file_uploaded ?></li>
    </ul>
    </div>
    <br>
    <h2>Users: </h2>

    <?php 
        $users = mysqli_query($connexion, "SELECT users.*, sum(file_size) as 'used_storage' FROM users INNER JOIN files ON files.ID_user = users.ID GROUP BY files.ID_user");
         if ($users->num_rows >= 1) { ?>
            <table class="table">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Used storage</th>
                    <th scope="col">Creation date</th>
                    <th scope="col">Last login</th>
                    <th scope="col">Delete user</th>
                </tr>
                <?php while($user = $users->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $user['ID'] ?></td>
                        <td><?= $user['username'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= $file_o->get_file_size($user['used_storage'])?> / <?= $file_o->get_file_size($user['user_storage'])?></td>
                        <td><?= $user['creation_date'] ?></td>
                        <td><?= $user['last_login'] ?></td>
                        <td>
                            <button onclick=" confirm(' You really want delete this user ? ') && location.replace(`?delete=<?= $user['ID'] ?>`) " class="btn btn-outline-danger"> Delete user </button>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
              <p class="text-center fs-5"> No user. </p>;
        <?php } ?>

    </main>
    

        <?php
            if(isset($_GET['delete'])) {
                $id_user = $_GET['delete'];
                if ($_SESSION['admin'] == 1) {
                    $user_files = mysqli_query($connexion, "SELECT * FROM files WHERE ID_user = '$id_user'");
                    if ($user_files->num_rows >= 1) {
                        while($file = $user_files->fetch_assoc()) {
                            unlink($file_o->get_path_from_id($file['id']));
                        }
                        mysqli_query($connexion, "DELETE FROM files WHERE ID_user = '$id_user'");
                    }
                    mysqli_query($connexion, "DELETE FROM users WHERE ID = '$id_user'");
                    $_SESSION['message-type'] = 'Danger';
                    $_SESSION['message'] = 'User deleted with successfuly. ';
                    header('Location: dashboard.php');
                } else {
                    header('Location: error-delete-file.html');
                    die();
                }
            }

        ?>
    
</body>
</html>