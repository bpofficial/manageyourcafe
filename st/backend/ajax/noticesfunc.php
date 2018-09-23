<?php
session_start();
$LOG = "error_log.log";
include_once('../php/config.php');
include_once('../php/classes.php');
$error = new errorHandle;
if ($_SESSION['debug']) { $error->add_error("%cServer-side info: %c", ['font-size:large;', 'color:black;'], true); }
$name = $_SESSION['uname'];
$uppername = ucfirst($name);
$store_id = $_SESSION['store_id'];
header('Content-type: text/html');  
if($_REQUEST['message'] == "REQ_PAGE") {
    $notice = new page;
    $html = $notice->generateNoticePage($store_id, $name);
    if($_SESSION['debug']) {
        exit(json_encode(array('success' => true,'value' => base64_encode($html),'errors' => $error->generate())));
    } else {
        exit(json_encode(array('success' => true,'value' => base64_encode($html))));
    }
} else if ($_REQUEST['message'] == "NOTICE_UPDATE") {
    //---------------------------------------------------------THIS SHIT RIGHT HERE NEEDS A'DOIN -----------------------------------------------------------//
    if($_SESSION['debug']) {
        exit(json_encode(array('success' => true,'value' => base64_encode($html),'errors' => $error->generate())));
    } else {
        exit(json_encode(array('success' => true,'value' => base64_encode($html))));
    }
} else if ($_POST['message'] == "NOTICE_POST") {
    $notice = base64_decode($_POST['data']);
    $datetime = date("Y-m-d H:i:s");
    $user_can_post = $_SESSION['priv']['post'];
    $type = "notice";
    if($user_can_post) {
        try {
            $st = $conn->prepare("INSERT INTO `notices` (store_id, date, posted_by, content, type) VALUES ('$store_id','$datetime', '$name', '$notice', '$type')");
            $st->execute();
            echo json_encode(array('success' => true));
        } catch (PDOException $e) {
            if($_SESSION['debug']) {
                $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                echo json_encode(array('success' => false,'value' => '500 Server-side error.','errors' => $error->generate()));
            } else {
                error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
                echo json_encode(array('success' => false,'value' => '500 Server-side error.'));
            }
        }
    } else {
        echo json_encode(array('success' => false,'value' => "RGT_CNT_POST",'errors' => $error->generate()));
    }
} else if ($_POST['message'] == "NTC_RM") {
    $id = $_POST['value'];
    try {
        $st = $conn->prepare("DELETE FROM `notices` WHERE `id`='$id' AND `store_id`='$store_id'");
        $st->execute();
        echo json_encode(array('success' => true));
    } catch (PDOException $e) {
        if($_SESSION['debug']) {
            $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            echo json_encode(array('success' => false,'value' => '500 Server-side error.','errors' => $error->generate()));
        } else {
            error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
            echo json_encode(array('success' => false,'value' => '500 Server-side error.'));
        }
    }
} else {
    exit(json_encode(array('success' => false,'value' => "Unknown",'errors' => 'none')));
}
?>