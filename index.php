<?php
include('functions.php');

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="bootstrap.css">
</head>
<body>
<?php include("nav/navbar.php") ?>
<div class="header">
    <h2>Home Page</h2>
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
    <div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Hey there!</h4>
        <p>Summer internship deadlines are getting closer, it's about time to enroll to an internship!
            If you haven't done so, please do by navigating to <kbd>Internships</kbd> > <kbd>View</kbd> > <kbd>Enroll</kbd> (enroll button will appear only if possible)</p>
        <hr>
        <p class="mb-0">Once you have enrolled you will see <kbd>My Internship</kbd> blue button on your navigation bar on top, click it and make sure to read the messages!</p>
    </div>
        <div>
            <?php  if (isset($_SESSION['user'])) : ?>
                <strong><?php echo $_SESSION['user']['username']; ?></strong>

                <small>
                    <i  style="color: #888;">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i>
                    <br>
                    <a href="index.php?logout='1'" style="color: red;">logout</a>
                </small>

            <?php endif ?>
        </div>

</div>
</body>
</html>