<?php
    session_start();
    include('config.php');

    if(isset($_SESSION['uname'])){
        $name = $_SESSION['uname'];
        $st = $conn->prepare("SELECT `rights`  FROM `staff` WHERE `uname`='$name'");
        $st->execute();
        $_SESSION['priv'] = json_decode(stripslashes($st->fetchColumn()),true);
        $st = $conn->prepare("SELECT `store_id`  FROM `staff` WHERE `uname`='$name'");
        $st->execute();
        $_SESSION['store_id'] = $st->fetchColumn();
        if($_SESSION['priv']['level'] > 2 && $debug == true) {
            $_SESSION['debug'] = true;
        } else {
            $_SESSION['debug'] = false;
        }
    } else {
        header("location: login");
    }
?>