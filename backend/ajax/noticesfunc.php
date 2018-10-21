<?php
#region -- STUFF --
session_start();
require('../php/config.php');
$error = new errorHandle;
if ($_SESSION['debug']) { $error->add_error("%cServer-side info: %c", ['font-size:large;', 'color:black;'], true); }
$name = $_SESSION['uname'];
$uppername = ucfirst($name);
$store_id = $_SESSION['store_id'];
header('Content-type: text/html');  
#endregion 
if($_REQUEST['message'] == "REQ_PAGE") {
    #region
    $page = new page;
    $page = $page->notice();
    exit($page);
    #endregion
} else if ($_REQUEST['message'] == "NOTICE_UPDATE") {
    #region
    $notice = new page;
    $html = $notice->generateNoticePage($store_id, $name, true);
    if($_SESSION['debug']) {
        exit(json_encode(array('success' => true,'value' => base64_encode($html),'errors' => $error->generate())));
    } else {
        exit(json_encode(array('success' => true,'value' => base64_encode($html))));
    }
    #endregion
} else if ($_POST['message'] == "NOTICE_POST") {
    #region
    (isset($_SESSION)) ? ((session_check('store_id', $_SESSION)) ? true : exit(json_encode(array('redirect'=>"https://manageyour.cafe/".$DIR."/login")))) : false;
    $notice = base64_decode($_POST['data']);
    $datetime = date("Y-m-d H:i:s");
    $user_can_post = $_SESSION['priv']['post'];
    $type = "notice";
    try {
        if($user_can_post) {
            try {
                $st = $conn->prepare("INSERT INTO `notices` (store_id, date, posted_by, content, type) VALUES ('$store_id','$datetime', '$name', '$notice', '$type')");
                $st->execute();
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
            if($_SESSION['debug']) {
                echo json_encode(array('success' => false,'value' => '500 Server-side error.','errors' => $error->generate()));
            } else {
                echo json_encode(array('success' => false,'value' => '500 Server-side error.'));
            }
        }
    } catch (Exception $e) {
        exitf($e);
        if($_SESSION['debug']) {
            $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            echo json_encode(array('success' => false,'value' => '500 Server-side error.','errors' => $error->generate()));
        } else {
            error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
            echo json_encode(array('success' => false,'value' => '500 Server-side error.'));
        }
    }
    echo json_encode(array('success' => true));
    #endregion
} else if ($_POST['message'] == "NTC_RM") {
    #region
    (isset($_SESSION)) ? ((session_check('store_id', $_SESSION)) ? true : exit(json_encode(array('redirect'=>"https://manageyour.cafe/".$DIR."/login")))) : false;
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
    #endregion
} else {
    #region
    (isset($_SESSION)) ? ((session_check('store_id', $_SESSION)) ? true : exit(json_encode(array('redirect'=>"https://manageyour.cafe/".$DIR."/login")))) : false;
    if($_SESSION['debug']) {
        exit(json_encode(array('success' => false,'errors' => $error->generate())));
    } else {
        exit(json_encode(array('success' => false,)));
    }
    #endregion
}
?>