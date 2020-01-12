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
    <title>Create User</title>
    <link rel="stylesheet" type="text/css" href="../bootstrap.css">
</head>
<body>
<?php include("../nav/admin_navbar.php") ?>
<div class="header">
    <h2>Create User</h2>
</div>

<form method="post" action="create_user.php">

    <?php echo display_error(); ?>

    <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" class="form-control">
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control">
    </div>
    <div class="form-group">
        <label>User type</label>
        <select name="user_type" id="user_type" class="form-control">
            <option value="student">Student</option>
            <option value="lecturer">Lecturer</option>
            <option value="admin">Admin</option>
        </select>
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password_1" class="form-control">
    </div>
    <div class="form-group">
        <label>Confirm password</label>
        <input type="password" name="password_2" class="form-control">
    </div>
        <button type="submit" class="btn btn-primary" name="register_btn"> + Create </button>
</form>
</body>
</html>