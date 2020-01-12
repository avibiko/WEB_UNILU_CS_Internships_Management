<?php
session_start();

// connect to webporg database
$database = "webprog";
$user = $password = "webprog";
$host = "mysql";
$db = mysqli_connect($host, $user, $password, $database);

// validate connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// define global constants
define('BASE_URL', 'http://localhost/twitty/');  // the home url of the website

// global variables
$username = "";
$email    = "";
$errors   = array();

// call the register() function if register_btn is clicked
if (isset($_POST['register_btn'])) {
    register();
}

// Register user
function register(){

    // make these variables available
    global $db, $errors, $username, $email;

    // receive input inform using e() function defined later on
    $username    =  e($_POST['username']);
    $email       =  e($_POST['email']);
    $password_1  =  e($_POST['password_1']);
    $password_2  =  e($_POST['password_2']);

    // validate form input (secured login is using another method)
    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($password_1)) {
        array_push($errors, "Password is required");
    }
    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
    }


    // if no errors, register
    if (count($errors) == 0) {
        $password = $password_1; // password saved plain-text as required (not safe)

        if (isset($_POST['user_type'])) {
            $user_type = e($_POST['user_type']);
            $query = "INSERT INTO users (username, email, user_type, password, status) 
					  VALUES('$username', '$email', '$user_type', '$password', 'active')";
            mysqli_query($db, $query);
            $_SESSION['success']  = "User $username was successfully created!";
            header('location: home.php');
        }else{
            $query = "INSERT INTO users (username, email, user_type, password, status) 
					  VALUES('$username', '$email', 'student', '$password', 'active')";
            mysqli_query($db, $query);

            // get id of the created user
            $logged_in_user_id = mysqli_insert_id($db);

            $_SESSION['user'] = getUserById($logged_in_user_id); // put logged in user in session
            $_SESSION['success']  = "You are now logged in";
            header('location: index.php');
        }
    }
}

// return user by id
function getUserById($id){
    global $db;
    $query = "SELECT * FROM users WHERE id=" . $id;
    $result = mysqli_query($db, $query);

    $user = mysqli_fetch_assoc($result);
    return $user;
}

// the escape string function, for simplicity
function e($val){
    global $db;
    return mysqli_real_escape_string($db, trim($val));
}

// display errors from ajax
if (isset($_POST['display_error'])) {
    display_error();
}

function display_error() {
    global $errors;

    if (count($errors) > 0){
        echo '<div class="alert alert-warning" role="alert">';
        foreach ($errors as $error){
            echo $error .'<br>';
        }
        echo '</div>';
    }
}


// validate if user is logged in
function isLoggedIn() {
    if (isset($_SESSION['user'])) {
        return true;
    }else{
        return false;
    }
}

// log user out if logout button clicked
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user']);
    header("location: login.php");
}

// call the login() function if register_btn is clicked
if (isset($_POST['login_btn'])) {
    login();
}

// LOGIN USER
function login(){
    global $db, $username, $errors;

    // get input
    $username = e($_POST['username']);
    $password = e($_POST['password']);

    // make sure form is filled properly
    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }


    // attempt login if no errors on form
    if (count($errors) == 0) {
        $password = md5($password);

        $query = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
        $results = mysqli_query($db, $query);

        $logged_in_user = mysqli_fetch_assoc($results);
        if (mysqli_num_rows($results) == 1) { // user found

            // check if user is active
            if($logged_in_user['status'] == 'active') {
                // check if user is admin or user
                if ($logged_in_user['user_type'] == 'admin') {

                    $_SESSION['user'] = $logged_in_user;
                    $_SESSION['success']  = "You are now logged in";
                    header('location: admin/home.php');
                }else{
                    $_SESSION['user'] = $logged_in_user;
                    $_SESSION['success']  = "You are now logged in";
                    header('location: index.php');
                }
            }
            else {
                array_push($errors, "User disabled by Admin, please contact your Admin");
            }
        } else {
            array_push($errors, "Wrong username/password combination");
        }
    }
}

function isAdmin()
{
    if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'admin' ) {
        return true;
    }else{
        return false;
    }
}

// call the edit() function if edit_btn is clicked
if (isset($_POST['edit_btn'])) {
    edit();
}

function edit()
{
    // call these variables with the global keyword to make them available in function
    global $db, $errors, $id, $username, $email, $status, $user_type;

    // receive all input values from the form.
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $status = mysqli_real_escape_string($db, $_POST['status']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $user_type = mysqli_real_escape_string($db, $_POST['user_type']);

    // form validation: ensure that the form is correctly filled
    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    }


    // register user if there are no errors in the form
    if (count($errors) == 0) {

        $insert = mysqli_query($db, "UPDATE users SET username='$username',
        email='$email', user_type='$user_type', status='$status' WHERE id='$id'")
        or die(mysqli_error($db));

        if ($insert) {
            $_SESSION['success'] = "User $username was edited successfully!";
            header("location: all_users.php");
        } else {
            echo 'Edit failed';
        }
    }
}

// call the createInternship() function if create_internship_btn is clicked
if (isset($_POST['create_internship_btn'])) {
    createInternship();
}

// CREATE INTERNSHIP
function createInternship() {
    // call these variables with the global keyword to make them available in function
    global $db, $errors;

    // receive all input values from the form. Call the e() function
    // defined below to escape form values
    $title    =  e($_POST['title']);
    $description       =  e($_POST['description']);
    $student = e($_POST['student']);
    $local_supervisor  =  e($_POST['local_supervisor']);
    $academic_supervisor  =  e($_POST['academic_supervisor']);
    $date_posted = date("Y-m-d");

    // form validation: ensure that the form is correctly filled
    if (empty($title)) {
        array_push($errors, "Title is required");
    }
    if (empty($description)) {
        array_push($errors, "Description is required");
    }


    // create internships if no errors
    if (count($errors) == 0) {

            $query = "INSERT INTO internships (title, description, student, local_supervisor, academic_supervisor, date_posted) 
					  VALUES('$title', '$description', '$student', '$local_supervisor', '$academic_supervisor', '$date_posted')";
            mysqli_query($db, $query);

            $_SESSION['success']  = "Internship $title was created successfully!";
            header('location: all_internships.php');
    }
}


// edit internship
if (isset($_POST['edit_internship_btn'])) {
    editInternship();
}

function editInternship()
{
    global $db, $errors;

    // receive all input values from the form.
    $id = e($_POST['id']);
    $title    =  e($_POST['title']);
    $description       =  e($_POST['description']);
    $student = e($_POST['student']);
    $local_supervisor  =  e($_POST['local_supervisor']);
    $academic_supervisor  =  e($_POST['academic_supervisor']);

    if (empty($title)) {
        array_push($errors, "Title is required");
    }
    if (empty($description)) {
        array_push($errors, "Description is required");
    }

    if (count($errors) == 0) {

        $update = mysqli_query($db, "UPDATE internships SET title='$title', description='$description', student='$student',
        local_supervisor='$local_supervisor', academic_supervisor='$academic_supervisor' WHERE id='$id'")
        or die(mysqli_error($db));

        if ($update) {
            $_SESSION['success'] = "Internship $title edited successfully!";
            header("Location: all_internships.php");
        } else {
            echo 'fail';
        }
    }
}

// if delete internship was pressed
if (isset($_POST['delete_internship_btn'])) {
    deleteInternship();
}

// DELETE INTERNSHIP
function deleteInternship()
{
    global $db;
    $internship_id = e($_POST['internship_id']);

    $sql = mysqli_query($db, "DELETE FROM internships WHERE id='$internship_id'")
    or die(mysqli_error($db));

    if ($sql) {
        $_SESSION['success'] = "Internship deleted successfully!";
        header("Location: all_internships.php");
    } else {
        echo 'fail';
    }

}

function getInternship($user_id)
{
    global $db;
    $query = "SELECT * FROM internships WHERE student='$user_id'";
    $result = mysqli_query($db, $query);

    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_array($result);
        $internship_id = $row['id'];
        return $internship_id;
    } else {
        return 0;
    }
}

// if enroll button was pressed
if (isset($_POST['enroll_btn'])) {
    enroll();
}

// ENROLL INTERN
function enroll()
{
    global $db;
    $id = $_SESSION['user']['id'];
    $internship_id = e($_POST['internship_id']);

    $update = mysqli_query($db, "UPDATE internships SET student='$id' WHERE id='$internship_id'")
    or die(mysqli_error($db));

    if ($update) {
        $_SESSION['success'] = "Enrolled Successfully!";
        header('Location: '.$_SERVER['PHP_SELF']."?id=".$internship_id);
    } else {
        echo 'fail';
    }
}


// if unenroll button was pressed
if (isset($_POST['unenroll_btn'])) {
    unenroll();
}

// UNENROLL INTERN
function unenroll()
{
    global $db;
    $internship_id = e($_POST['internship_id']);

    $update = mysqli_query($db, "UPDATE internships SET student='0' WHERE id='$internship_id'")
    or die(mysqli_error($db));

    if ($update) {
        $_SESSION['success'] = "Un-enrolled Successfully!";
        header('Location: '.$_SERVER['PHP_SELF']."?id=".$internship_id);
    } else {
        echo 'fail';
    }
}

if (isset($_POST['msg_btn'])) {
    sendMsg();
}

function sendMsg()
{
    // call these variables with the global keyword to make them available in function
    global $db, $errors;

    // receive all input values from the form.
    $text = e($_POST['text']);

    // form validation: ensure that the form is correctly filled
    if (empty($text)) {
        array_push($errors, "Your message is empty");
    }

    if (count($errors) == 0) {
        $user_id = e($_SESSION['user']['id']);
        $internship_id = e($_POST['internship_id']);
        $date = date("Y-m-d H:i:s");

        $insert = mysqli_query($db, "INSERT INTO tweets (user_id, internship_id, text, date_posted) 
            VALUES ('$user_id', '$internship_id', '$text', '$date');")
        or die(mysqli_error($db));

        if ($insert) {
            header('Location: '.$_SERVER['REQUEST_URI']);
        }
    }
}

