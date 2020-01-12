
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">BINFO Internships (Admin)</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL . 'admin/home.php' ?>">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL . 'admin/all_users.php' ?>">All Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL . 'admin/create_user.php' ?>">Create User</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL . 'admin/all_internships.php' ?>">Internships</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL . 'admin/create_internship.php' ?>">Create Internship</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL . 'index.php?logout=1' ?>" style="color: red;">Logout</a>
            </li>
    </div>
</nav>
<div class="container">