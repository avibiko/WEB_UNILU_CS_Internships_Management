<?php include('functions.php') ?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration to BINGO - Interns</title>
    <link rel="stylesheet" href="bootstrap.css">
</head>
<body>
<div class="container">
<div class="header">
    <h2>Register</h2>
</div>

<form method="post" action="register.php">
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
        <label>Password</label>
        <input type="password" name="password_1" class="form-control">
    </div>
    <div class="form-group">
        <label>Confirm password</label>
        <input type="password" name="password_2" class="form-control">
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary" name="register_btn">Register</button>
    </div>
    <p>
        Already a member? <a href="login.php">Sign in</a>
    </p>
</form>
</body>
</html>