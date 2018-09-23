<?php
session_start();
header('Content-type: text/html');
include('../php/config.php');
include('../php/classes.php');
$error = new errorHandle;
if ($_SESSION['debug']) { $error->add_error("%cServer-side info: %c", ['font-size:large;', 'color:black;'], true); }
include('../php/functions.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../../../php/vendor/phpmailer/phpmailer/src/Exception.php';
require '../../../../php/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../../../php/vendor/phpmailer/phpmailer/src/SMTP.php';
$uppername = ucfirst($_SESSION['uname']);
$name = $_SESSION['uname'];
function email($data, $type) {
    global $uppername, $name, $conn, $error, $store_id;
    if($type == "roster") {
        $data = json_decode($data,true);
        try {
            $stv = $conn->prepare("SELECT * FROM `staff` WHERE `store_id`='$store_id'");
            $stv->execute();
            $staff = $stv->fetchAll(PDO::FETCH_ASSOC);
        } catch ( PDOException $e ) {
            if($_SESSION['debug']) {
                $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                $error->add_error("%cDetails: %c" . print_r($e), ['font-weight:bold', 'color:black;'], true);
                exit(json_encode(array('success' =>  false,'value' => 'Errors.','errors' =>  $$error->generate())));
            } else {
                exit(json_encode(array('success' =>  false,'value' =>  '500 Server-side error.')));
            }
        }
        try {
            $mail = new PHPMailer(true);
            $mail->setFrom('mail@manageyour.cafe', $uppername);                           
            $mail->isSMTP();                                     
            $mail->Host = 's121.syd2.hostingplatform.net.au';
            $mail->SMTPAuth = true;                       
            $mail->Username = 'mail@manageyour.cafe';      
            $mail->Password = '$$!MailPassword!$';          
            $mail->SMTPSecure = 'ssl';                        
            $mail->Port = 465;        
            $count = 0;
            foreach($staff as $key => $val) {
                $settings = json_decode($val['settings'],true);
                if($settings['getEmails'] === "true" || $settings['getEmails'] === true) {
                    $mail->addAddress($val['email']);
                    $count++;
                } else {
                    continue;
                }
            }
            if($count == 0) {
                return true;
            }
            $mail->isHTML(true);
            $mail->Subject = $data['title'];
            $mail->Body = base64_decode($data['content']);
            $mail->send();
        } catch (Exception $e) {
            if($_SESSION['debug']) {
                $error->add_error("%cError: ". '%cMessage could not be sent. %cMailer Error: %c' . $e->getMessage() . '%c', ['font-weight:bold;', 'color:black;', 'color:red;', 'font-style:italic;','color:black'], true);
                return false;
            } else {
                error_log('Message could not be sent. Mailer Error: ' . $e->getMessage(), 3, "mail.log");
                return false;
            }
        } catch (phpmailerException $e) {
            if($_SESSION['debug']) {
                $error->add_error("%cError: ". '%cMessage could not be sent. %cMailer Error: %c' . $e->errorMessage() . '%c', ['font-weight:bold;', 'color:black;', 'color:red;', 'font-style:italic;','color:black'], true);
                return false;
            } else {
                error_log('Message could not be sent. Mailer Error: ' . $e->errorMessage(), 3, "mail.log");
                return false;
            }
        }
        return true;
    } else {
        return;
    }
}
$name = $_SESSION['uname'];
$store_id = $_SESSION['store_id'];
if ($_REQUEST['message'] === "REQ_PAGE") {
    $time_pre = microtime(true);
    global $error; global $r; $rosters = new page;
    try {
        $st = $conn->prepare("SELECT `roster_auto_populate` FROM `settings`");
        $st->execute();
        $populate = $st->fetchColumn();
    } catch (PDOException $e) {
        if ($_SESSION['debug']) {
            $error->add_error("%cError: ". '%c' . $e.getMessage() ." %con line: " . '%c' . $e.getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            exit(json_encode(array('success' => false, 'errors' => $error->generate())));
        } else {
            exit(json_encode(array('success' => true)));
        }
    }
    try {
        $st = $conn->prepare("SELECT * FROM `rosters` WHERE `store_id`='$store_id' ORDER BY id DESC LIMIT 1");
        $st->execute();
        if ($populate == 1) { $populate = true; } else { $populate = false; }
        if ($st->rowCount() < 1 && $populate === true) { $populate = false; }
    } catch (PDOExeption $e) {
        if ($_SESSION['debug']) {
            $error->add_error("%cError: ". '%c' . $e.getMessage() ." %con line: " . '%c' . $e.getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            exit(json_encode(array('success' => false, 'errors' => $error->generate())));
        } else {
            exit(json_encode(array('success' => false)));
        }
    }
    $time_post = microtime(true);
    $exec_time = $time_post - $time_pre;
    $error->add_error("%cExecution Time: %c" . $exec_time, ['font-weight:bold;','color:green;'], true);
    if ($_SESSION['debug']) {
        exit(json_encode(array('success' => true,'value' => $rosters->generateRosterPage($store_id,$name,$populate),'errors' => $error->generate())));
    } else {
        exit(json_encode(array('success' => true,'value' => $rosters->generateRosterPage($store_id,$name,$populate))));
    }
} elseif ($_POST['message'] === "UPDATE_ROSTER") {
    $roster = json_decode(stripslashes($_POST['data']), true);
    $rdate = DateTime::createFromFormat('d/m/Y', $roster['startDate']);
    $rdate = $rdate->format('Y-m-d');
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
    $comments = $roster['comments'];
    if ($rdate == $date_check) {
        try {
            $st = $conn->prepare("UPDATE `rosters` SET monday='$monday', tuesday='$tuesday', wednesday='$wednesday', thursday='$thursday', friday='$friday', saturday='$saturday', sunday='$sunday', comments='$comments', edited='1' WHERE `date_from`='$rdate' AND `store_id`='$store_id'");
            $st->execute();
            if ($_SESSION['debug']) {
                echo json_encode(array('success' => true, 'errors' => $error->generate()));
            } else {
                echo json_encode(array('success' => true));
            }
        } catch (PDOException $e) {
            $msg = "Failed to create rosters";
            if ($_SESSION['debug']) {
                $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                echo json_encode(array('success' => false,'value' => $msg,'errors' => $error->generate()));
            } else {
                echo json_encode(array('success' => false,'value' => $msg));
            }
        }
    } else {
        try {
            $st = $conn->prepare("INSERT INTO `rosters` (store_id, date_from, monday, tuesday, wednesday, thursday, friday, saturday, sunday, comments) VALUES ('$store_id', '$rdate', '$monday', '$tuesday', '$wednesday', '$thursday', '$friday', '$saturday', '$sunday', '$comments')");
            $st->execute();
            $data = new roster;
            $html = $data->generateUser($store_id,"email");
            $css = "<style> .m-b-30{margin-bottom:30px}.table{margin:0}.table-responsive{padding-right:1px}.table-responsive .table--no-card{-webkit-border-radius:10px; -moz-border-radius:10px; border-radius:10px; -webkit-box-shadow:0 2px 5px 0 rgba(0,0,0,.1); -moz-box-shadow:0 2px 5px 0 rgba(0,0,0,.1); box-shadow:0 2px 5px 0 rgba(0,0,0,.1)}.table-earning thead th{background:#333; font-size:16px; color:#fff; vertical-align:middle; font-weight:400; text-transform:capitalize; line-height:1; padding:22px 40px; white-space:nowrap}.table-earning thead th.text-right{padding-left:15px; padding-right:65px}.table-earning tbody td{color:gray; padding:12px 40px; white-space:nowrap}.table-earning tbody td.text-right{padding-left:15px; padding-right:65px}.table-earning tbody tr:hover td{color:#555; cursor:pointer}.table-bordered{border:1px solid #dee2e6}.table-bordered td,.table-bordered th{border:1px solid #dee2e6}.table-bordered thead td,.table-bordered thead th{border-bottom-width:2px}@media(max-width:575.98px){.table-responsive-sm{display:block; width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; -ms-overflow-style:-ms-autohiding-scrollbar}.table-responsive-sm>.table-bordered{border:0}}@media(max-width:767.98px){.table-responsive-md{display:block; width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; -ms-overflow-style:-ms-autohiding-scrollbar}.table-responsive-md>.table-bordered{border:0}}@media(max-width:991.98px){.table-responsive-lg{display:block; width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; -ms-overflow-style:-ms-autohiding-scrollbar}.table-responsive-lg>.table-bordered{border:0}}@media(max-width:1199.98px){.table-responsive-xl{display:block; width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; -ms-overflow-style:-ms-autohiding-scrollbar}.table-responsive-xl>.table-bordered{border:0}}.table-responsive{display:block; width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; -ms-overflow-style:-ms-autohiding-scrollbar}.table-responsive>.table-bordered{border:0} </style>";
            $mail = json_encode(array('title' => 'Roster for week starting ' . $roster['startDate'], 'content' => base64_encode($css . $html)));
            if(email($mail, "roster")) {
                if ($_SESSION['debug']) {
                    echo json_encode(array('success' => true, 'errors' => $error->generate()));
                } else {
                    echo json_encode(array('success' => true));
                }
            } else {
                if ($_SESSION['debug']) {
                    echo json_encode(array('success' => false, 'errors' => $error->generate()));
                } else {
                    echo json_encode(array('success' => false));
                }
            }
        } catch (PDOException $e) {
            if ($_SESSION['debug']) {
                $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                echo json_encode(array('success' => false,'value' => "Failed to insert new roster. Reason: " . $e->getMessage(),'errors' => $error->generate()));
            } else {
                echo json_encode(array('success' => false,'value' => "Failed to insert new roster. Reason: " . $e->getMessage()));
            }
        }
    }
} else if ($_POST['message'] === "REMOVE_ROSTER") {
    $id = $_POST['data'];
    try {
        $st = $conn->prepare("DELETE FROM `rosters` WHERE `id`='$id' AND `store_id`='$store_id'");
        $st->execute();
        echo json_encode(array('success' => true));
    } catch (PDOException $e) {
        if ($_SESSION['debug']) {
            $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            echo json_encode(array('success' => false,'errors' => $error->generate()));
        } else {
            echo json_encode(array('success' => false));
        }
    }
} else { error_log($time . " Nothing?".PHP_EOL, 3, "rosterfuncs.log"); }
?>