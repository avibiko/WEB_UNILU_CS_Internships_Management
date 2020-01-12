<?php
include('functions.php');
$randomX = bin2hex(random_bytes(32));

?>
<!doctype html>
<html>
<head>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="api/sha256.jquery.debug.js" type="text/javascript"></script>
    <title>BINFO Internships LOGIN</title>
    <link rel="stylesheet" type="text/css" href="bootstrap.css">
</head>
<body>
<div class="container">
    <div class="header">
        <h2>Welcome to BINFO Internship Safe Login Page!</h2>
    </div>
    <form id="loginform" method="post">

        <?php echo display_error(); ?>

        <input type="text" name="clientSalt" id="clientSalt" value="<?php echo $randomX ?>" hidden/>
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" id="username" class="form-control"/>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" id="password" class="form-control" />
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="loginBtn" id="loginBtn" value="Login" />
        </div>
        <p>
            Not yet a member? <a href="register.php" class="btn btn-secondary">Sign up</a>
        </p>
    </form>
<script type="text/javascript">
    $(document).ready(function() {
        $('#loginform').submit(function(e) {
            e.preventDefault();
            // POST to server safely no password is sent! just 'x' random number

            $.ajax({
                type: "POST",
                url: 'api/getsalt.php',
                // data: $(this).serialize(), ** PASSWORD IS NOT SENT ON POST **
                data: {username: document.getElementById("username").value, clientSalt: document.getElementById("clientSalt").value},
                success: function(response)
                {
                    var jsonData = JSON.parse(response);
                    // received server salt, creating hash to send for auth
                    if (jsonData.success == "1")
                    {
                        // generating the hashed password: SHA256(x + pwd + d)
                        var hashy = document.getElementById("clientSalt").value + document.getElementById("password").value + jsonData.serverSalt;
                        var hashy = $.sha256(hashy);
                        $.ajax({
                            type: 'POST',
                            url: 'api/authlogin.php',
                            data: {hashedPass : hashy, username: document.getElementById("username").value},
                            success: function(msg) {
                                jsonDataAuth = JSON.parse(msg);
                                console.log(jsonDataAuth); // sanity check for getsalt response
                                if (jsonDataAuth.success == "1"){
                                    if(jsonDataAuth.admin == '1') {
                                        // user is admin
                                        window.location.href = "admin/home.php";
                                    }
                                    else {
                                        // user is not admin
                                        window.location.href = "index.php";
                                    }
                                }

                            else {
                                    // auth failed
                                    alert(jsonDataAuth.error);
                                }
                            }
                        });
                    }
                    else
                    {
                        // no credentials
                        alert('Please fill username and password');
                    }
                }
            });
        });
    });
</script>
</body>
</html>