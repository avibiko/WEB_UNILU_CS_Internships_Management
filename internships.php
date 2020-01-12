<?php
include('functions.php');
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
}

if (isAdmin()) {
    $_SESSION['msg'] = "Redirected to Admin Page";
    header('location: admin/all_internships.php');
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user']);
    header("location: login.php");
}
?>
<?php $results = mysqli_query($db, "SELECT * FROM internships"); ?>
<!DOCTYPE html>
<html>

<head>
    <title>BINFO Internships</title>
    <link rel="stylesheet" type="text/css" href="bootstrap.css">
</head>
<body>
<?php include("nav/navbar.php") ?>
<div class="header">
    <h2>All BINFO Internships</h2>
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


    <?php $results = mysqli_query($db, "SELECT * FROM internships"); ?>
    <div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">Title</th>
                <th scope="col">Description</th>
                <th scope="col">Status</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_array($results)) { ?>
            <?php
            if($row['student'] == 0) {
                $status = 'Free';
            }
            else {
                $status = 'Assigned';
            }
            ?>
            <tr scope="col">
                <td><?php echo $row['title']; ?></td>
                <td class="text-truncate" style="max-width: 300px;"><?php echo $row['description']; ?></td>
                <td><?php echo $status; ?></td>
                <td>
                    <a href="internship.php?id=<?php echo $row['id']; ?>" class="btn btn-info" >View</a>
                </td>
            </tr>
            </tbody>
            <?php } ?>
        </table>
    </div>
</body>
</html>
