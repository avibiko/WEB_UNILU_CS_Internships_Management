<?php
include('../functions.php');

// if username is set and hashed password is set try to login securely
if (isset($_POST['username']) && $_POST['username'] && isset($_POST['hashedPass']) && $_POST['hashedPass']) {
    securedLogin();
}


// secured login with hash (no password in POST)
function securedLogin(){
    global $db, $username, $errors;


    // set parameters from session and POST
    $clientSalt = $_SESSION['clientSalt'];
    $serverSalt = $_SESSION['serverSalt'];
    $username = e($_POST['username']);
    $userHashedPass = e($_POST['hashedPass']);

    // make sure form is filled properly
    if (empty($username)) {
        array_push($errors, "Username is required");
    }


    // attempt login if no errors on form
    if (count($errors) == 0) {
        $query = "SELECT * FROM users WHERE username='$username' LIMIT 1";
        $results = mysqli_query($db, $query);
        $logged_in_user = mysqli_fetch_assoc($results);

        $passwordDB = $logged_in_user['password'];
        $hashedPass = hash('sha256',$clientSalt.$passwordDB.$serverSalt);
        // TODO: sha256hash to hashy here and in login.php

          if ($userHashedPass == $hashedPass && $logged_in_user['password']) { // password is correct
            // check if user is active
            if($logged_in_user['status'] == 'active') {
                // auth OK, check if user is admin or user
                if ($logged_in_user['user_type'] == 'admin') {
                    $_SESSION['user'] = $logged_in_user;
                    $_SESSION['success']  = "You are now logged in";
                    echo json_encode(array(
                        'success' => 1,
                        'admin' => 1,
                    ));
                // auth OK, user is not admin
                }else{
                    $_SESSION['user'] = $logged_in_user;
                    $_SESSION['success']  = "You are now logged in";
                    echo json_encode(array(
                        'success' => 1,
                        'admin' => 0,
                    ));
                }
            }
            // auth OK, user status is disabled
            else {
                echo json_encode(array(
                    'success' => 0,
                    'error' => 'User disabled by Admin, please contact your Admin',
                ));
                array_push($errors, "User disabled by Admin, please contact your Admin");
            }
            // auth failed, update error
        } else {
              echo json_encode(array(
                  'success' => 0,
                  'error' => 'Wrong username/password combination',
              ));
            array_push($errors, "Wrong username/password combination");
        }
    }
}