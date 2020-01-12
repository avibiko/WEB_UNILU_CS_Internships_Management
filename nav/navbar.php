<?php
// my internship id variable is set to 0 = no internship
$myinternship_id = 0;
if(isLoggedIn()) {
    $myinternship_id = getInternship($_SESSION['user']['id']);
}

?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">BINFO Internships</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL . 'index.php' ?>">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL . 'internships.php' ?>">Internships</a>
            </li>
            <?php if($myinternship_id != '0') { ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL . 'internship.php?id=' . $myinternship_id ?>" style="color: blue;">My Internship</a>
            </li>
            <?php } ?>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL . 'index.php?logout=1' ?>" style="color: red;">Logout</a>
            </li>
    </div>
</nav>
<div class="container">