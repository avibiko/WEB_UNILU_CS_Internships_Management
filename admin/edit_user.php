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
<head>
    <title>Edit User</title>
    <link rel="stylesheet" type="text/css" href="../bootstrap.css">
</head>
<body>
<?php include("../nav/admin_navbar.php") ?>

<div class="header">
    <h2>Edit User</h2>
</div>


<?php
$id = $_GET['edit'];
$results = mysqli_query($db, "SELECT * FROM users WHERE id='$id'");
$row = mysqli_fetch_array($results);
?>
<form method="post" action="edit_user.php">

    <?php echo display_error(); ?>

    <div class="form-group">
        <input type="text" class="form-control" name="id" value="<?php echo $row['id']; ?>" hidden>
    </div>
    <div class="form-group">
        <label>Username</label>
        <input type="text" class="form-control" name="username" value="<?php echo $row['username']; ?>">
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="text" class="form-control" name="email" value="<?php echo $row['email']; ?>">
    </div>
    <div class="form-group">
    <?php if($row['user_type'] == 'admin') { ?>
    <div class="form-group">
        <label>Title (Cannot change Admin title)</label>
        <select class="form-control" name="user_type" id="user_type" readonly>
            <option value="admin" selected>Admin</option>
        </select>
    </div>
        <div class="form-group">
            <label>Status (Cannot change Admin status)</label>
            <select class="form-control" name="status" id="status" readonly>
                <option value="active">Active</option>
            </select>
        </div>
    <?php } else { ?>
    <div class="form-group">
        <label>Title (<?php echo ucfirst($row['user_type']); ?>)</label>
        <select class="form-control" name="user_type" id="user_type">
            <option value="student">Student</option>
            <option value="lecturer">Lecturer</option>
            <option value="admin">Admin</option>
        </select>
    </div>
        <div class="form-group">
            <label>Status (<?php echo ucfirst($row['status']); ?>)</label>
            <select class="form-control" name="status" id="status">
                <option value="active">Active</option>
                <option value="disabled">Disabled</option>
            </select>
        </div>
    <?php } ?>
    <div class="form-group">
        <button type="submit" class="btn btn-primary" name="edit_btn">Update</button>
    </div>
</form>
</div>
</body>
</html>