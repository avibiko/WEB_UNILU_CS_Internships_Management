<?php
include('../functions.php');

// set server salt current time (numbers)
$ssalt = time();

// if username and client salt is set, save in session and respond with the server salt
if (isset($_POST['username']) && $_POST['username'] && isset($_POST['clientSalt']) && $_POST['clientSalt']) {

    // save client and server salt in session
    $_SESSION['serverSalt'] = $ssalt;
    $_SESSION['clientSalt'] = $_POST['clientSalt'];
    echo json_encode(array(
        'success' => 1,
        'serverSalt' => $ssalt,
    ));
} else {
    echo json_encode(array('success' => 0));
}