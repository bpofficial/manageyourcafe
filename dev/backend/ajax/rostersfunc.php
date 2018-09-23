<?php
#region -- STUFF --
session_start();
header('Content-type: text/html');
require('../php/config.php');
$error = new errorHandle;
if ($_SESSION['debug']) { $error->add_error("%cServer-side info: %c", ['font-size:large;', 'color:black;'], true); }
$uppername = ucfirst($_SESSION['uname']);
$name = $_SESSION['uname'];
$store_id = $_SESSION['store_id'];
#endregion

if ($_REQUEST['message'] === "REQ_PAGE") {
    #region
    $time_pre = microtime(true);
    global $error; global $r; $rosters = new page;
    try {
        $st = $conn->prepare("SELECT `roster_auto_populate` FROM `settings` WHERE `store_id`='$store_id'");
        $st->execute();
        $populate = $st->fetchColumn();
    } catch (PDOException $e) {
        exitf(null, true, "GET", $e, false);
    }
    try {
        $st = $conn->prepare("SELECT * FROM `rosters` WHERE `store_id`='$store_id' ORDER BY id DESC LIMIT 1");
        $st->execute();
        if ($populate == 1) { $populate = true; } else { $populate = false; }
        if ($st->rowCount() < 1 && $populate === true) { $populate = false; }
    } catch (PDOExeption $e) {
        exitf(null, true, "GET", $e);
    }
    if(isset($_REQUEST['stress']) && $_REQUEST['stress'] == "true") {
        $time_post = microtime(true);
        $exec_time = $time_post - $time_pre;
        $error->add_error("%cExecution Time: %c" . $exec_time, ['font-weight:bold;','color:green;'], true);
        exit(json_encode(array('success'=>true,'value'=>$rosters->generateRosterPage($store_id,$name,$populate),'time'=>$exec_time)));
    } else {
        exit(json_encode(array('success'=>true,'value'=>$rosters->generateRosterPage($store_id,$name,$populate))));
    }
    #endregion
} else if ($_POST['message'] === "UPDATE_ROSTER") {
    #region
    $roster = json_decode(stripslashes($_POST['data']), true);
    error_log(print_r($roster,true) . PHP_EOL,3,'logloggggg.log');                                         
    $rdate = date('Y-m-d', strtotime(str_replace('/', '-', $roster['startDate'])));
    error_log($rdate . PHP_EOL,3,'logloggggg.log');
    $st = $conn->prepare("SELECT `date_from` FROM `rosters` WHERE `store_id`='$store_id' ORDER BY `id` DESC LIMIT 1");
    $st->execute();
    $date_check = $st->fetchColumn();
    $monday = json_encode($roster['monday']);
    $tuesday = json_encode($roster['tuesday']);
    $wednesday = json_encode($roster['wednesday']);
    $thursday = json_encode($roster['thursday']);
    $friday = json_encode($roster['friday']);
    $saturday = json_encode($roster['saturday']);
    $sunday = json_encode($roster['sunday']);
    $comments = sanitize($roster['comments']);
    if ($rdate == $date_check) {
        try {
            $st = $conn->prepare("UPDATE `rosters` SET monday='$monday', tuesday='$tuesday', wednesday='$wednesday', thursday='$thursday', friday='$friday', saturday='$saturday', sunday='$sunday', comments='$comments', edited='1' WHERE `date_from`='$rdate' AND `store_id`='$store_id'");
            $st->execute();
        } catch (PDOException $e) {
            echo exitf("Failed to create rosters", true, "POST", $e); 
        }
        echo json_encode(array('success' => true));
    } else {
        try {
            $st = $conn->prepare("INSERT INTO `rosters` (store_id, date_from, monday, tuesday, wednesday, thursday, friday, saturday, sunday, comments) VALUES ('$store_id', '$rdate', '$monday', '$tuesday', '$wednesday', '$thursday', '$friday', '$saturday', '$sunday', '$comments')");
            $st->execute();
            $data = new roster;
            $html = $data->generateUser($name, $store_id, "email");
            $css = "<style> .m-b-30{margin-bottom:30px}.table{margin:0}.table-responsive{padding-right:1px}.table-responsive .table--no-card{-webkit-border-radius:10px; -moz-border-radius:10px; border-radius:10px; -webkit-box-shadow:0 2px 5px 0 rgba(0,0,0,.1); -moz-box-shadow:0 2px 5px 0 rgba(0,0,0,.1); box-shadow:0 2px 5px 0 rgba(0,0,0,.1)}.table-earning thead th{background:#333; font-size:16px; color:#fff; vertical-align:middle; font-weight:400; text-transform:capitalize; line-height:1; padding:22px 40px; white-space:nowrap}.table-earning thead th.text-right{padding-left:15px; padding-right:65px}.table-earning tbody td{color:gray; padding:12px 40px; white-space:nowrap}.table-earning tbody td.text-right{padding-left:15px; padding-right:65px}.table-earning tbody tr:hover td{color:#555; cursor:pointer}.table-bordered{border:1px solid #dee2e6}.table-bordered td,.table-bordered th{border:1px solid #dee2e6}.table-bordered thead td,.table-bordered thead th{border-bottom-width:2px}@media(max-width:575.98px){.table-responsive-sm{display:block; width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; -ms-overflow-style:-ms-autohiding-scrollbar}.table-responsive-sm>.table-bordered{border:0}}@media(max-width:767.98px){.table-responsive-md{display:block; width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; -ms-overflow-style:-ms-autohiding-scrollbar}.table-responsive-md>.table-bordered{border:0}}@media(max-width:991.98px){.table-responsive-lg{display:block; width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; -ms-overflow-style:-ms-autohiding-scrollbar}.table-responsive-lg>.table-bordered{border:0}}@media(max-width:1199.98px){.table-responsive-xl{display:block; width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; -ms-overflow-style:-ms-autohiding-scrollbar}.table-responsive-xl>.table-bordered{border:0}}.table-responsive{display:block; width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; -ms-overflow-style:-ms-autohiding-scrollbar}.table-responsive>.table-bordered{border:0} </style>";
            $mail = json_encode(array('title' => 'Roster for week starting ' . $roster['startDate'], 'content' => base64_encode($css . $html)));
            if(email($mail, "roster")) {
                echo json_encode(array('success' => true));
            } else {
                echo exitf("Failed to email rosters", false, "POST"); 
            }
        } catch (PDOException $e) {
            echo exitf(null, true, "POST", $e); 
        }
    }
    #endregion
} else if ($_POST['message'] === "REMOVE_ROSTER") {
    #region
    $id = $_POST['data'];
    $priv = $_SESSION['priv'];
    if($priv['remove_rosters'] === "true") {
        try {
            $st = $conn->prepare("DELETE FROM `rosters` WHERE `id`='$id' AND `store_id`='$store_id'");
            $st->execute();
            echo json_encode(array('success'=>true));
        } catch (PDOException $e) {
            echo exitf(null, true, "POST", $e); 
        }
    } else {
        echo exitf("Nice try", true, "POST");
    }
    #endregion
} else if ($_POST['message'] === "SAVE_ROSTER") {
    #region Shows the most recent saved roster
    $page = new page;
    try {
        $save_data = $page->saveData("roster", $_POST['data'], $store_id);
        if ($save_data != null || $save_data != false) {
            if($_SESSION['debug']) {
                echo json_encode(array('success'=>true,'errors'=>$error->generate(), 'option_data'=>array("id"=>$save_data[0],"text"=>$uppername." ".$save_data[1])));
            } else {
                echo json_encode(array('success'=>true)); 
            }
        } else {
            if($_SESSION['debug']) {
                echo json_encode(array('success'=>false,'errors'=>$error->generate()));
            } else {
                echo json_encode(array('success'=>false)); 
            }
        }
    } catch (Exception $e) {
        if($_SESSION['debug']) {
            $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            echo json_encode(array('success'=>false,'errors'=>$error->generate()));
        } else {
            error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
            echo json_encode(array('success'=>false)); 
        }
    }
    #endregion
} else if ($_REQUEST['message'] === "REQ_SAVED") {
    #region
    $id = $_REQUEST['data']['id'];
    try {
        $st = $conn->prepare("SELECT `data` FROM `saved` WHERE `id`='$id' AND `store_id`='$store_id'");
        $st->execute();
        if($st->rowCount() < 1) {
            exit(json_encode(array('success'=>false)));
        } else {
            exit(json_encode(array('success'=>true,'data'=>base64_encode($st->fetchColumn()))));
        }
    } catch (PDOException $e) {
        echo exitf(null, true, "POST", $e); 
    }
    #endregion
} else { 
    #region
    error_log($time." Nothing?".PHP_EOL); 
    #endregion
}
?>