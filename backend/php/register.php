<?php
require('config.php');
require('classes.php');
require('functions.php');

if ((isset($_POST["desired_password"])) && (isset($_POST["desired_username"])) && (isset($_POST["desired_password1"]))) {
    $desired_username = sanitize($_POST["desired_username"]);
    $desired_password = sanitize($_POST["desired_password"]);
    $desired_password_copy = sanitize($_POST["desired_password1"]);
    $store_id = "ca660cbc33a11517998b6039e125e1a01712e1ba";
    if($u = new user($desired_username,$desired_password,$store_id)) {
    } else {
    }
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Register as a Valid User</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <style type="text/css">
            .invalid {
                border: 1px solid #000000;
                background: #FF00FF;
            }
        </style>
    </head>
    <body >
        <h2>User registration Form</h2>
        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
            Username: <input type="text" id="desired_username" name="desired_username"><br /><br />
            Password: <input name="desired_password" type="password" id="desired_password" ><br /><br />
            Password again: <input name="desired_password1" type="password" id="desired_password1"><br />
            <input type="submit" value="Register">
            <br /><br />
        </form>
    </body>
</html>