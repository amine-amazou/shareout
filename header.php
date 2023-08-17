<header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom"> 
    <a href="index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none"> 
        <span class="fs-4">Share Out</span> 
    </a> 
    <?php if(is_connected()) { ?>
        <ul class="nav nav-pills"> 
            <li class="nav-item">
                <a href="index.php" class="nav-link">Share new file</a>
            </li> 
            <li class="nav-item">
                <a href="myfiles.php" class="nav-link active">My Files</a>
            </li> 
            <?php if(isAdmin()) { ?>
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link">Admin Panel</a>
                </li>
            <?php } ?>
            <li class="nav-item">
                <form action="" method="get">
                    <input type="submit" name="disconnect" value="Disconnect" class="nav-link" >
                </form>
                <?php
                    if (isset($_GET['disconnect'])) {
                        disconnect_session();
                        header('Location: login.php');
                    }
                ?>
            </li> 
        </ul> 
    <?php } else { ?>
        <ul class="nav nav-pills"> 
            <li class="nav-item"><a href="sign-up.php" class="nav-link">Sing Up</a></li> 
            <li class="nav-item"><a href="login.php" class="nav-link active">Login</a></li> 
        </ul> 
    <?php } ?>
</header>
