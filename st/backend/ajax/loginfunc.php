<?php
session_start();
require('../php/config.php');
require('../php/functions.php');

if($_POST['message'] == 'LOGIN_REQ') {
    $data = array();
    parse_str($_POST['data'], $data);
    $user = $data['user']; $pass = $data['pass'];
    if ((isset($user)) && (isset($pass))) {
        $user = sanitize($user);
        $pass = sanitize($pass);
        $registered = FALSE;
        $correcthash = "";
        $userhash = "";
        try {
            $sql = "SELECT `uname` FROM `staff` WHERE `uname`='$user'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            if ($stmt->rowCount() < 1) {
                echo json_encode(array('success' => false, 'value' => 'This user doesn\'t exist. <span><style>.reg:hover{text-decoration:underline;}</style><a class="reg" style="font-family:inherit;font-weight:inherit;font-size:inherit;line-height:inherit;color:inherit;" href="https://manageyour.cafe/registeruser">Register?</a></span>'));
            } else if ($stmt->rowCount() > 0) {
                try {
                    $sql = "SELECT `password` FROM `staff` WHERE `uname`='$user'";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $correctpassword = $stmt->fetchColumn();
                    $salt = substr($correctpassword, 0, 64);
                    $correcthash = substr($correctpassword, 64, 64);
                    $userhash = hash("sha256", $salt . $pass);
                } catch(PDOException $e) {
                    echo json_encode(array('success' => false, 'value' => 'An unknown error has occured. LGNF_03'));
                }
            } else {
               echo json_encode(array('success' => false, 'value' => 'An unknown error has occured. LGNF_04'));
            }
            if (!($userhash == $correcthash)) {
                $validationresults = FALSE;
                echo json_encode(array('success' => false, 'value' => 'Username or password is incorrect.'));
            } else if (isset($userhash) && isset($correcthash) && ($userhash == $correcthash) && $userhash != "" && $correcthash != ""){
                try {
                    $sql = "SELECT `email` FROM `staff` WHERE `uname`='$user'";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $email = $stmt->fetchColumn();
                    $sql = "SELECT `rights` FROM `staff` WHERE `uname`='$user'";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $priv = $stmt->fetchColumn();
                    $_SESSION['pr'] = $priv;
                    $_SESSION['uname'] = $user;
                    $_SESSION['email'] = $email;
                    echo json_encode(array('success' => true, 'value' => 'accepted'));
                } catch(PDOException $e) {
                    echo json_encode(array('success' => false, 'value' => 'An unknown error has occured. LGNF_05'));
                }
            }
        } catch (PDOException $e) {
            echo json_encode(array('success' => false, 'value' => 'An unknown error has occured. LGNF_05'));
        }
    } else {
        echo json_encode(array('success' => false, 'value' => 'There must be a valid username and password used to login.'));
    }
} else {
    //
}
?>