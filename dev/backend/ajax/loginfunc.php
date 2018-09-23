<?php
#region -- STUFF --
session_start();
require('../php/config.php');
#endregion
if($_POST['message'] == 'LOGIN_REQ') {
    #region
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
            $st = $conn->prepare("SELECT * FROM `staff` WHERE `uname`='$user'");
            $st->execute();
            if ($st->rowCount() < 1) {
                echo json_encode(array('success' => false, 'value' => 'This user doesn\'t exist. <span><style>.reg:hover{text-decoration:underline;}</style><a class="reg" style="font-family:inherit;font-weight:inherit;font-size:inherit;line-height:inherit;color:inherit;" href="https://manageyour.cafe/registeruser">Register?</a></span>'));
            } else if ($st->rowCount() > 0) {
                $data = $st->fetchAll(PDO::FETCH_ASSOC);
                $user_data = $data[0];
                $correctpassword = $user_data['password'];
                $salt = substr($correctpassword, 0, 64);
                $correcthash = substr($correctpassword, 64, 64);
                $userhash = hash("sha256", $salt . $pass);
            } else {
                if ($_SESSION['debug']) {
                    $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                    echo json_encode(array('success' => false, 'value' => 'Unknown error', 'errors' => $error->generate()));
                } else {
                    echo json_encode(array('success' => false, 'value' => 'Unknown error'));
                }
            }
            if (!($userhash == $correcthash)) {
                $validationresults = FALSE;
                if ($_SESSION['debug']) {
                    $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                    echo json_encode(array('success' => false, 'value' => 'Username or password is incorrect.', 'errors' => $error->generate()));
                } else {
                    echo json_encode(array('success' => false, 'value' => 'Username or password is incorrect.'));
                }
            } else if (isset($userhash) && isset($correcthash) && ($userhash == $correcthash) && $userhash != "" && $correcthash != ""){
                $_SESSION['pr'] = $user_data['priv'];
                $_SESSION['uname'] = $user_data['uname'];
                $_SESSION['email'] = $user_data['email'];
                if ($_SESSION['debug']) {
                    echo json_encode(array('success' => true, 'value' => 'accepted', 'errors' => $error->generate()));
                } else {
                    echo json_encode(array('success' => true, 'value' => 'accepted'));
                }
            }
        } catch (PDOException $e) {
            if ($_SESSION['debug']) {
                $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                echo json_encode(array('success' => false, 'value' => 'Unknown error', 'errors' => $error->generate()));
            } else {
                echo json_encode(array('success' => false, 'value' => 'Unknown error'));
            }
        }
    } else {
        if ($_SESSION['debug']) {
            $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            echo json_encode(array('success' => false, 'value' => 'There must be a valid username and password used to login.', 'errors' => $error->generate()));
        } else {
            echo json_encode(array('success' => false, 'value' => 'There must be a valid username and password used to login.'));
        }
    }
    #endregion
} else {
    //
}
?>