<!DOCTYPE html>
<html lang="en">
<?php require 'auth.php'; ?>
<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>
<?php
    if (is_connected()) {
        header('Location: index.php');
        exit();
    }
?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S/O | Sign Up</title>
    <link rel="stylesheet" href="bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="bootstrap/bootstrap.min.js"></script> 
    <?php 
        require 'user.class.php';
        $user_o = new User();
        require_once 'db_connect.php';
        $connexion = connect_to_db();
    ?>
</head>
<body>
    <main>
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom"> 
        <a href="index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none"> 
            <span class="fs-4">Share Out</span> 
        </a> 
        <ul class="nav nav-pills"> 
            <li class="nav-item"><a href="sign-up.php" class="nav-link active ">Sing Up</a></li> 
            <li class="nav-item"><a href="login.php" class="nav-link">Login</a></li> 
        </ul> 
    </header>
        <?php if(!empty($_SESSION['message'])) { ?>
            <?php if ($_SESSION['message-type'] == 'Success') { ?>
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                        <use xlink:href="#check-circle-fill"/></use>
                    </svg>
                    <div>
                        <?php 
                            echo $_SESSION['message'];
                            unset($_SESSION['message']);
                            unset($_SESSION['message-type']);
                        ?>
                    </div> 
                </div>
            <?php } else { ?>
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                        <use xlink:href="#exclamation-triangle-fill"/>
                    </svg>
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
    <div class="col-md-10 mx-auto col-lg-5"> 
        <form action="" method="post" class="p-4 p-md-5 border rounded-3 bg-light">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" autocomplete="off" aria-describedby="" required>
                <label for="username" >Username</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email address" aria-describedby="" autocomplete="off" required>
                <label for="email" class="form-label">Email address</label>
            </div>
            <div class="form-floating mb-3">
            <select class="form-select" name="storage">
                <option>Select storage</option>
                <option value="1000000000" selected>1GB (Free) </option>
                <option value="5000000000">5GB (100 DH)</option>
                <option value="10000000000">10GB (200 DH)</option>
            </select>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" aria-describedby="passwordhelp" required>
                <label for="password">Password</label>
                <div id="passwordhelp" class="form-text">We'll never share your password with anyone else.</div>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Confirm password" aria-describedby="cpasswordhelp" required>
                <label for="cpassword" class="form-label">Confirm password</label>
                <div id="cpasswordhelp" class="form-text">Confirm your password here.</div>
            </div>
            <button class="w-100 btn btn-lg btn-primary" name="create-account" type="submit">Sign up</button> 
            <hr class="my-4"> 
            <small class="text-muted">By clicking Sign up, you agree to the terms of use.</small>        
        </form>
    </div>
    </main>
    <?php
        if (isset($_POST['create-account'])) {
            $username = $_POST['username'];
            if (!$user_o->username_is_already_existe($username)) {
                $password = $_POST['password']; 
                if ($_POST['password'] != $_POST['cpassword']) {
                    $_SESSION['message-type'] = 'Erreur';
                    $_SESSION['message'] = 'Password confirmation is incorrect. ';
                } else {
                    if (!empty($_POST['storage'])) {
                        $storage = $_POST['storage'];
                        $password = md5($password);
                        $email = $_POST['email'];
                        if($user_o->add_user_to_database($email, $username, $password, $storage)) {
                            $_SESSION['message-type'] = 'Success';
                            $_SESSION['message'] = 'Account created with successfuly. '; 
                        } else {
                            $_SESSION['message-type'] = 'Erreur';
                            $_SESSION['message'] = 'Something wrong happen';    
                        }
                          
                    } else {
                        $_SESSION['message-type'] = 'Erreur';
                        $_SESSION['message'] = 'Please select storage. ';
                    }
                    
                } 
            } else {
                $_SESSION['message-type'] = 'Erreur';
                $_SESSION['message'] = 'Username already existe. ';
            }
            header('Location: sign-up.php');
        }
    ?>
    
    
</body>
</html>