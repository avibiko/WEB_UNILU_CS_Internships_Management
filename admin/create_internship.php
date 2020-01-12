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
    <title>Create Internship</title>
    <link rel="stylesheet" type="text/css" href="../bootstrap.css">
</head>
<body>
<?php include("../nav/admin_navbar.php") ?>
<div class="header">
    <h2>Create Internship</h2>
</div>

<form method="post" action="create_internship.php">

    <?php echo display_error(); ?>

    <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" class="form-control">
    </div>
    <div class="form-group">
        <label>Description</label>
        <textarea class="form-control" name="description" rows="10"></textarea>
    </div>
    <div class="form-group">
        <label>Student</label>
        <select class="form-control" name="student">
            <option value="0">Not assigned</option>
            <?
            $students = mysqli_query($db, "SELECT * FROM users WHERE user_type='student' AND status='active'");
            while ($studentRow = mysqli_fetch_array($students)) { ?>
                <option value="<?php echo $studentRow['id']; ?>"><?php echo $studentRow['username']; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group">
        <label>Local Supervisor</label>
        <select class="form-control" name="local_supervisor">
            <option value="0">Not assigned</option>
            <?
            $lecturers = mysqli_query($db, "SELECT * FROM users WHERE user_type!='student' AND status='active'");
            while ($lecturerRow = mysqli_fetch_array($lecturers)) { ?>
                <option value="<?php echo $lecturerRow['id']; ?>"><?php echo $lecturerRow['username']; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group">
        <label>Academic Supervisor</label>
        <select class="form-control" name="academic_supervisor">
            <option value="0">Not assigned</option>
            <?
            $lecturers = mysqli_query($db, "SELECT * FROM users WHERE user_type!='student' AND status='active'");
            while ($lecturerRow = mysqli_fetch_array($lecturers)) { ?>
                <option value="<?php echo $lecturerRow['id']; ?>"><?php echo $lecturerRow['username']; ?></option>
            <?php } ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary" name="create_internship_btn"> + Create </button>
</form>
</body>
</html>