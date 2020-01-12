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

// get internship id from user
$internship_id = $_GET['id'];

// get internship details from database
$results = mysqli_query($db, "SELECT * FROM internships WHERE id='$internship_id'");
$row = mysqli_fetch_array($results);


// transform IDs to usernames:
$student_id = $row['student'];
$userQuery = mysqli_query($db, "SELECT * FROM users WHERE id='$student_id'");
$userRow = mysqli_fetch_array($userQuery);
if($student_id != '0') {
    $student_username = $userRow['username'];
}
else {
    $student_username = 'Not Assigned';
}

$local_supervisor_id = $row['local_supervisor'];
$userQuery = mysqli_query($db, "SELECT * FROM users WHERE id='$local_supervisor_id'");
$localRow = mysqli_fetch_array($userQuery);
if($local_supervisor_id != '0') {
    $local_supervisor_username = $localRow['username'];
}
else {
    $local_supervisor_username = 'Not Assigned';
}


$academic_supervisor_id = $row['academic_supervisor'];
$userQuery = mysqli_query($db, "SELECT * FROM users WHERE id='$academic_supervisor_id'");
$academicRow = mysqli_fetch_array($userQuery);
if($academic_supervisor_id != '0') {
    $academic_supervisor_username = $academicRow['username'];
}
else {
    $academic_supervisor_username = 'Not Assigned';
}

?>

<!DOCTYPE html>
<head>
    <title>Edit Internship</title>
    <link rel="stylesheet" type="text/css" href="../bootstrap.css">
</head>
<body>
<?php include("../nav/admin_navbar.php") ?>

<div class="header">
    <h2>Edit Internship</h2>
</div>



<form method="post" action="edit_internship.php">

    <?php echo display_error(); ?>
    <div class="form-group">
        <input type="text" class="form-control" name="id" value="<?php echo $row['id']; ?>" hidden>
    </div>
    <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" class="form-control" value="<?php echo $row['title']; ?>">
    </div>
    <div class="form-group">
        <label>Description</label>
        <textarea class="form-control" name="description" rows="10"><?php echo $row['description']; ?></textarea>
    </div>
    <div class="form-group">
        <label>Student (<?php echo $student_username; ?>)</label>
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
        <label>Local Supervisor (<?php echo $local_supervisor_username; ?>)</label>
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
        <label>Academic Supervisor (<?php echo $academic_supervisor_username; ?>)</label>
        <select class="form-control" name="academic_supervisor">
            <option value="0">Not assigned</option>
            <?
            $lecturers = mysqli_query($db, "SELECT * FROM users WHERE user_type!='student' AND status='active'");
            while ($lecturerRow = mysqli_fetch_array($lecturers)) { ?>
                <option value="<?php echo $lecturerRow['id']; ?>"><?php echo $lecturerRow['username']; ?></option>
            <?php } ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary" name="edit_internship_btn"> + Update </button>
</form>
</div>
</body>
</html>