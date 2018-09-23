<?php
require('config.php');
require('functions.php');

class hash {
    public $text = "";
    
    private function create_password_hash($pass) {
        $salt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
        return $salt . hash("sha256", $salt . $pass);
    }
    
    public function generate($text) {
        create_password_hash($text);
    }
}

if ((isset($_POST["desired_password"])) && (isset($_POST["desired_username"])) && (isset($_POST["desired_password1"]))) {
    $desired_username = sanitize($_POST["desired_username"]);
    $desired_password = sanitize($_POST["desired_password"]);
    $desired_password_copy = sanitize($_POST["desired_password1"]);
    $validation = 0;
    if (empty($desired_username)) {
        $validation += 0;
    } else {
        $validation += 1;
    }
    if ((!(ctype_alnum($desired_username)))) {
        $validation += 0;
    } else {
        $validation += 1;
    }
    $stmt = $conn->prepare("SELECT `uname` FROM `staff` WHERE `uname`='$desired_username'");
    $stmt->execute();
    if($stmt->rowCount() >= 1) {
        $validation += 0;
    } else {
        $validation += 1;
    }

    if (empty($desired_password)) {
        $validation += 0;
    } else {
        $validation += 1;
    }
    if ((!(ctype_alnum($desired_password))) || ((strlen($desired_password)) < 8)) {
        $validation += 0;
    } else {
        $validation += 1;
    }
    if ($desired_password == $desired_password_copy) {
        $validation += 1;
    } else {
        $validation += 0;
    }

    if ($validation == 6) {
            $sp = new hash();
            $hashedpassword = $sp->generate($desired_password);
            echo $hashedpassword;
            $sp = null;
            $stmt = $conn->prepare("INSERT INTO `staff` (`uname`, `password`) VALUES ('$desired_username', '$hashedpassword')");
            $stmt->execute();
            exit;
    } else {
        //?
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
        <br />
        Hi! This private website is restricted to public access. If you want to see the content, please register below. You will be redirected to a login page after successful registration.
        <br /><br />
        <!-- Start of registration form -->
        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
            Username: (<i>alphanumeric less than 12 characters</i>) <input type="text" class="<?php if (($usernamenotempty == FALSE) || ($usernamevalidate == FALSE) || ($usernamenotduplicate == FALSE))
        echo "invalid"; ?>" id="desired_username" name="desired_username"><br /><br />
            Password: (<i>alphanumeric greater than 8 characters</i>) <input name="desired_password" type="password" class="<?php if (($passwordnotempty == FALSE) || ($passwordmatch == FALSE) || ($passwordvalidate == FALSE))
        echo "invalid"; ?>" id="desired_password" ><br /><br />
            Type the password again: <input name="desired_password1" type="password" class="<?php if (($passwordnotempty == FALSE) || ($passwordmatch == FALSE) || ($passwordvalidate == FALSE))
        echo "invalid"; ?>" id="desired_password1" ><br />
            <input type="submit" value="Register">
            <br /><br />
            <a href="login.php">Back to Homepage</a><br />
            <?php if ($usernamenotempty == FALSE)
                    echo '<font color="red">You have entered an empty username.</font>'; ?><br />
            <?php if ($usernamevalidate == FALSE)
                    echo '<font color="red">Your username should be alphanumeric and less than 12 characters.</font>'; ?><br />
            <?php if ($usernamenotduplicate == FALSE)
                    echo '<font color="red">Please choose another username, your username is already used.</font>'; ?><br />
            <?php if ($passwordnotempty == FALSE)
                    echo '<font color="red">Your password is empty.</font>'; ?><br />
<?php if ($passwordmatch == FALSE)
        echo '<font color="red">Your password does not match.</font>'; ?><br />
<?php if ($passwordvalidate == FALSE)
        echo '<font color="red">Your password should be alphanumeric and greater 8 characters.</font>'; ?><br />
<?php if ($captchavalidation == FALSE)
        echo '<font color="red">Your captcha is invalid.</font>'; ?><br />
        </form>
        <!-- End of registration form -->
    </body>
</html>