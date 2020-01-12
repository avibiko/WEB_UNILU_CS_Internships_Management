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
<?php $results = mysqli_query($db, "SELECT * FROM users"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Modify Users</title>
    <link rel="stylesheet" type="text/css" href="../bootstrap.css">
</head>
<body>
<?php include("../nav/admin_navbar.php") ?>
<div class="header">
    <h2>Modify Users</h2>
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


    <div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Username</th>
                <th>User Title</th>
                <th>User Status</th>
                <th>Action</th>
            </tr>
            </thead>

            <?php while ($row = mysqli_fetch_array($results)) { ?>
                <tr>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo ucfirst($row['user_type']); ?></td>
                    <td><?php echo ucfirst($row['status']); ?></td>
                    <td>
                        <a href="edit_user.php?edit=<?php echo $row['id']; ?>" class="btn btn-light" >Edit</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
</body>
</html>
