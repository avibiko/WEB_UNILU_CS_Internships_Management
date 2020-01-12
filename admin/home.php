<?php
include('../functions.php');

// validate user is admin
if (!isAdmin()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user']);
    header("location: ../login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="../bootstrap.css">
</head>
<body>
<?php include("../nav/admin_navbar.php") ?>
<div class="header">
    <h2>Admin</h2>
</div>
<div class="content">
    <!-- notification message -->
    <?php if (isset($_SESSION['success'])) : ?>
        <div class="alert alert-success" role="alert">
            <h3>
                <?php
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
            </h3>
        </div>
    <?php endif ?>

    <!-- logged in user information -->
        <div>
            <?php  if (isset($_SESSION['user'])) : ?>
                <h1>Welcome</h1>
                <strong><?php echo $_SESSION['user']['username']; ?></strong>

                <small>
                    <i  style="color: #888;">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i>
                    <br>

                    <a href="home.php?logout='1'" style="color: red;">logout</a>
                </small>

            <?php endif ?>
        </div>
</div>
</body>
</html>