<?php
include('functions.php');

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
}

$internship_id = $_GET['id'];
$results = mysqli_query($db, "SELECT * FROM internships WHERE id='$internship_id'");
$row = mysqli_fetch_array($results);




// transform ids to usernames:
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
//

$academic_supervisor_id = $row['academic_supervisor'];
$userQuery = mysqli_query($db, "SELECT * FROM users WHERE id='$academic_supervisor_id'");
$academicRow = mysqli_fetch_array($userQuery);
if($academic_supervisor_id != '0') {
    $academic_supervisor_username = $academicRow['username'];
}
else {
    $academic_supervisor_username = 'Not Assigned';
}





// get user internship id (0 if not enrolled)
$user_id = $_SESSION['user']['id'];
$myInternshipId = getInternship($user_id);

// internship status
$status = 'This internship is currently available!';
if($student_id != '0') {
    if($myInternshipId == $internship_id) {
        $status = 'You are enrolled to this internship';
    }
    else {
        $status = 'Sorry, this internship is taken';
    }
}

// items visibility settings
$enroll_btn_visibility = "invisible";
$unenroll_btn_visibility = "invisible";
$student_visibility = "invisible";
$alreadyEnrolled = "invisible";
$belongs = false;


// case not student (lecturer / admin)
if( $_SESSION['user']['user_type'] != 'student') {
    $student_visibility = "visible";
}

// case student is already enrolled to different internship
else if(($myInternshipId != '0') && ($myInternshipId != $internship_id)) {
    $alreadyEnrolled = 'visible';
}

// case student enrolled to this internship
else if($myInternshipId == $internship_id) {
    $student_visibility = "visible";
    $unenroll_btn_visibility = "visible";
}

// case student can enroll
if($_SESSION['user']['user_type'] == 'student' && $myInternshipId == '0' && $student_id == '0') {
    $enroll_btn_visibility = 'visible';
}

// case belongs to this internship (staff/student/admin)
if($user_id == $student_id || $user_id == $local_supervisor_id || $user_id == $academic_supervisor_id || isAdmin()) {
    $belongs = true;
}



?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $row['title']; ?></title>
    <link rel="stylesheet" type="text/css" href="bootstrap.css">

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
</head>
<body>
<?php if(isAdmin()) {
    include("./nav/admin_navbar.php");
}   else {
    include("./nav/navbar.php");
}?>
<div class="header">
    <h2>BINFO Internships</h2>
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

    <div class="card text-center">
        <div class="card-header">
            <h2><?php echo $row['title']; ?></h2>
        </div>
        <div class="card-header">
            Status: <strong><?php echo $status ?></strong>
        </div>
        <div class="card-body">
            <p class="card-text"><?php echo $row['description']; ?></p>
            <div class="<?php echo $student_visibility; ?>">
            <p><em>Student: <strong><?php echo $student_username ?></strong></em></p>
            </div>
            <p><em>Local Supervisor: <strong><?php echo $local_supervisor_username; ?></strong></em></p>
            <p><em>Academic Supervisor: <strong><?php echo $academic_supervisor_username; ?></strong></em></p>
            <div class="<?php echo $alreadyEnrolled; ?>">
                <div class="alert alert-danger" role="alert">
                    You are already enrolled to another internship, go to my internship page to un-enroll <br>
                </div>
            </div>
            <div class="<?php echo $enroll_btn_visibility; ?>">
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                    <input type="text" class="form-control" name="internship_id" value="<?php echo $internship_id; ?>" hidden>
                    <button type="submit" name="enroll_btn" class="btn btn-primary">Enroll</button>
                </form>
            </div>
            <div class="<?php echo $unenroll_btn_visibility; ?>">
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                    <input type="text" class="form-control" name="internship_id" value="<?php echo $internship_id; ?>" hidden>
                    <button type="submit" name="unenroll_btn" class="btn btn-danger">Un-enroll</button>
                </form>
            </div>


        </div>
        <div class="card-footer text-muted">
            Date Created: <?php echo $row['date_posted']; ?>
        </div>
        <div class="card-body">
            <?php if($belongs) { ?>
                <form class="form-inline justify-content-center" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" id="msg_form">
                    <?php echo display_error(); ?>
                    <input type="text" class="form-control" name="internship_id" value="<?php echo $internship_id; ?>" hidden>
                    <div class="form-group">
                        <textarea class="form-control" rows="4" name="text" form="msg_form" placeholder="Write your message here"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-secondary " name="msg_btn">Send!</button>
                    </div>
                </form>
        </div>
        <div class="card-footer">
            <span class="alert-info"><strong>View messages from a specific time period: <br></strong></span>
        <input type="text" size="24" name="daterange" value="12/12/2019 - 1/1/2020" />

        <script>
            $(function() {
                $('input[name="daterange"]').daterangepicker({
                    opens: 'left'
                }, function(start, end, label) {
                    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                    window.location.href = location.protocol + '//' + location.host + location.pathname+'?id='+"<?php echo $internship_id?>"+'&start='+start.format('YYYY-MM-DD')+'&end='+end.format('YYYY-MM-DD');
                });
            });

        </script>
        </div>
                <?php
                if(isset($_GET['start'])) {
                    $start = $_GET['start'];
                    $end = $_GET['end'];
                    $results = mysqli_query($db, "SELECT * FROM tweets WHERE internship_id='$internship_id' AND date_posted >= '$start' AND date_posted <= '$end' ORDER BY date_posted");
                }
                else {
                    $results = mysqli_query($db, "SELECT * FROM tweets WHERE internship_id='$internship_id' ORDER BY date_posted");
                }
                if (mysqli_num_rows($results)==0) {
                    ?>
                    <div class="alert alert-info" role="alert">
                        No messages to show<br>
                    </div>
                <?php }
                while ($row = mysqli_fetch_array($results)) {
                    $user_id = $row['user_id'];
                    $userQuery = mysqli_query($db, "SELECT * FROM users WHERE id='$user_id'");
                    $userRow = mysqli_fetch_array($userQuery);
                    $sender = $userRow['username'];
                    ?>

                        <div class="card-footer">
                        <div class="panel panel-default justify-content-center">
                            <div class="panel-heading justify-content-center">
                                <strong><kbd><?php echo $sender; ?></kbd></strong><br>
                                <span class="alert-secondary"><?php echo $row['date_posted']; ?></span>
                            </div>
                            <div class="panel-body justify-content-center">
                                <?php echo $row['text']; ?>
                            </div>
                        </div>
                        </div>

                <?php } } ?>
        </div>

    </div>


</body>
</html>
