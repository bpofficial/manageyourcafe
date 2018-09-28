<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
session_start();
include('../php/config.php');
if($_POST['message'] == "REQ_PAGE") {
    $name = $_SESSION['name'];
    $sql = "SELECT email, rights FROM `staff` WHERE `uname`='$name'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $email = $row['email'];
    $priv = $row['rights'];
    
    if($_SESSION['email'] == $email && $_SESSION['pr'] == $priv) {
        /* STRUCTURE
            *1* Check DB for the dashboard layout.
            *2* Grab base html for the corresponding elements from DB.
            *3* Construct html here to send back to user.
            *4* Send back to user.
        */
    } else {
        //error message.
    }
} else {
    
}
?>