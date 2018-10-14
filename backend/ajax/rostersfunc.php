<?php
#region -- STUFF --
session_start();
header('Content-type: text/html');
require('../php/config.php');
$error = new errorHandle;
$error->add_error("%cServer-side info: %c", ['font-size:large;', 'color:black;'], true);
#endregion
if ($_REQUEST['message'] === "REQ_PAGE") {
    $page = new page;
    $page = $page->roster();
    exit($page);
} else if ($_POST['message'] === "UPDATE_ROSTER") {
    $roster = new roster;
    $roster = $roster->add($_POST['data']);
    echo $roster;
    #region 
    /*
    (isset($_SESSION)) ? ((session_check('store_id', $_SESSION)) ? true : exit(json_encode(array('redirect'=>"https://manageyour.cafe/".$DIR."/login")))) : false;
    $roster = json_decode(stripslashes($_POST['data']), true);                                       
    $rdate = date('Y-m-d', strtotime(str_replace('/', '-', $roster['startDate'])));
    $st = $conn->prepare("SELECT `date_from`,`edits`,`data` FROM `rosters` WHERE `store_id`='$store_id' AND `saved`='0' ORDER BY `id` DESC LIMIT 1");
    $st->execute();
    $d = $st->fetchAll(PDO::FETCH_ASSOC);
    $date_check = $d[0]['date_from'];
    $edits = $d[0]['edits'];
    $data = json_encode($roster);
    if ($rdate == $date_check) {
        $edits = json_decode($edits,true);
        $edits[] = array(
            "by"=>$uppername,
            "label" => "done",
            "time"=>time(),
            "prev"=>base64_encode($d[0]['data'])
        );
        $u_edits = json_encode($edits);
        try {
            $st = $conn->prepare("UPDATE `rosters` SET data='$data', edits='$u_edits' WHERE `date_from`='$rdate' AND `store_id`='$store_id'");
            $st->execute();
        } catch (PDOException $e) {
            $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            echo (json_encode(array('success' =>  false,'value' => 'Errors.','errors' =>  $$error->generate())));
        }
        echo json_encode(array('success' => true));
    } else {
        try {
            $edits[] = array(
                "by"=>$uppername,
                "label"=>"done",
                "time"=>time(),
                "prev"=>base64_encode($d[0]['data'])
            );
            $u_edits = json_encode($edits);
            $st = $conn->prepare("INSERT INTO `rosters` (store_id, date_from, data, edits) VALUES ('$store_id', '$rdate', '$data', '$u_edits')");
            $st->execute();
            try {
                $stv = $conn->prepare("SELECT * FROM `staff` WHERE `store_id`='$store_id'");
                $stv->execute();
                $staff = $stv->fetchAll(PDO::FETCH_ASSOC);
            } catch ( PDOException $e ) {
                $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                echo (json_encode(array('success' =>  false,'value' => 'Errors.','errors' =>  $$error->generate())));
            }
            $data = new roster;
            $css = <<<EOT
                <style> 
                
                .m-b-30{
                    margin-bottom:30px
                }
                .table{
                    margin:0
                }
                .table-responsive{
                    padding-right:1px
                }
                .table-responsive .table--no-card{
                    -webkit-border-radius:10px;
                    -moz-border-radius:10px;
                    border-radius:10px;
                    -webkit-box-shadow:0 2px 5px 0 rgba(0,0,0,.1);
                    -moz-box-shadow:0 2px 5px 0 rgba(0,0,0,.1);
                    box-shadow:0 2px 5px 0 rgba(0,0,0,.1)
                }
                .table-earning thead th{
                    background:#333;
                    font-size:16px;
                    color:#fff;
                    vertical-align:middle;
                    font-weight:400;
                    text-transform:capitalize;
                    line-height:1;
                    padding:22px 40px;
                    white-space:nowrap
                }
                .table-earning thead th.text-right{
                    padding-left:15px;
                    padding-right:65px
                }
                .table-earning tbody td{
                    color:gray;
                    padding:12px 40px;
                    white-space:nowrap;
                    border-right: 2px solid #dee2e6;
                    border-bottom: 2px solid #dee2e6;
                }
                .table-earning tbody td.text-right{
                    padding-left:15px;
                    padding-right:65px
                }
                .table-earning tbody tr:hover td{
                    color:#555;
                    cursor:pointer
                }
                .table-bordered{
                    border:1px solid #dee2e6
                }
                .table-bordered td,.table-bordered th{
                    border:1px solid #dee2e6
                }
                .table-bordered thead td,.table-bordered thead th{
                    border-bottom-width:2px
                }
                @media(max-width:575.98px){
                    .table-responsive-sm{
                        display:block;
                        width:100%;
                        overflow-x:auto;
                        -webkit-overflow-scrolling:touch;
                        -ms-overflow-style:-ms-autohiding-scrollbar
                    }
                    .table-responsive-sm>.table-bordered{
                        border:0
                    }
                }
                @media(max-width:767.98px){
                    .table-responsive-md{
                        display:block;
                        width:100%;
                        overflow-x:auto;
                        -webkit-overflow-scrolling:touch;
                        -ms-overflow-style:-ms-autohiding-scrollbar
                    }
                    .table-responsive-md>.table-bordered{
                        border:0
                    }
                }
                @media(max-width:991.98px){
                    .table-responsive-lg{
                        display:block;
                        width:100%;
                        overflow-x:auto;
                        -webkit-overflow-scrolling:touch;
                        -ms-overflow-style:-ms-autohiding-scrollbar
                    }
                    .table-responsive-lg>.table-bordered{
                        border:0
                    }
                }
                @media(max-width:1199.98px){
                    .table-responsive-xl{
                        display:block;
                        width:100%;
                        overflow-x:auto;
                        -webkit-overflow-scrolling:touch;
                        -ms-overflow-style:-ms-autohiding-scrollbar
                    }
                    .table-responsive-xl>.table-bordered{
                        border:0
                    }
                }
                .table-responsive{
                    display:block;
                    width:100%;
                    overflow-x:auto;
                    -webkit-overflow-scrolling:touch;
                    -ms-overflow-style:-ms-autohiding-scrollbar
                }
                .table-responsive>.table-bordered{
                    border:0
                }

                tr.personal > td:first-child {
                    border-left:4px solid rgba(66, 114, 215, 0.8)!important;
                }
                tr.personal > td {
                    border-bottom:2px solid rgba(66, 114, 215, 0.8)!important;
                }

             </style>
EOT;
            $send_count = $sent_count = 0;
            foreach($staff as $key => $val) {
                $settings = json_decode($val['settings'],true);
                if($settings['getEmails'] === "true" || $settings['getEmails'] === true) {
                    $send_count++;
                    $html = $data->generateUser("email");
                    $mail = json_encode(array('title' => 'Roster for week starting ' . $roster['startDate'], 'content' => base64_encode($css . $html)));        
                    if(email($val['email'], $mail, "roster")) {
                        $sent_count++;
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }
            }
            if($send_count == $sent_count) {
                echo json_encode(array('success' => true));
            } else if ($sent_count > 0) {
                echo json_encode(array('success' => true, 'value' => "Sent ".$sent_count." rosters."));
            } else {
                echo json_encode(array('success' => false));
            }
        } catch (PDOException $e) {
            $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            echo json_encode(array('success'=>false,'errors'=>$error->generate()));
        }
    }
    */
    #endregion
} else if ($_POST['message'] === "REMOVE_ROSTER") {
    $roster = new roster;
    $roster = $roster->delete($_POST['data']);
    echo $roster;
    #region
    /*
    $id = $_POST['data'];
    $priv = $_SESSION['priv'];
    if($priv['remove_rosters'] === "true") {
        try {
            $st = $conn->prepare("DELETE FROM `rosters` WHERE `id`='$id' AND `store_id`='$store_id'");
            $st->execute();
            echo json_encode(array('success'=>true));
        } catch (PDOException $e) {
            $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            echo json_encode(array('success'=>false,'errors'=>$error->generate()));
        }
    } else {
        echo json_encode(array('success'=>false,'errors'=>$error->generate()));
    }
    */
    #endregion
} else if ($_POST['message'] === "SAVE_ROSTER") {
    $data['data'] = $_POST['data']; $data['la'] = $_POST['la'];
    $roster = new roster;
    $roster = $roster->save($data);
    echo $roster;
    #region
    /*
    (isset($_SESSION)) ? ((session_check('store_id', $_SESSION)) ? true : exit(json_encode(array('redirect'=>"https://manageyour.cafe/".$DIR."/login")))) : false;
    $date = date('Y-m-d', strtotime("now"));
    $data = $_POST['data'];
    $edits[] = array(
        "by"=>$uppername,
        "label"=>$_POST['la'],
        "time"=>time(),
        "prev"=>""
    );
    $edits = json_encode($edits);
    try {
        $st = $conn->prepare("INSERT INTO `rosters` (store_id, date_from, data, edits) VALUES ('$store_id', '$date', '$data', '$edits')");
        $st->execute();
        $st = $conn->prepare("SELECT `id` FROM `rosters` WHERE `store_id`='$store_id' AND `date_from`='$date' AND `saved`='1' ORDER BY `id` DESC LIMIT 1");
        $st->execute();
        $id = $st->fetchColumn();
        if ($id != null) {
            echo json_encode(array('success'=>true,'errors'=>$error->generate(), 'id'=>$id));
        } else {
            echo json_encode(array('success'=>false,'errors'=>$error->generate()));
        }
    } catch (Exception $e) {
        $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
        echo json_encode(array('success'=>false,'errors'=>$error->generate()));
    }
    */
    #endregion
} else if ($_REQUEST['message'] === "REQ_SAVED") {
    $roster = new roster;
    $roster = $roster->load($_REQUEST['data']['id']);
    exit($roster);
    #region 
    /*
    (isset($_SESSION)) ? ((session_check('store_id', $_SESSION)) ? true : exit(json_encode(array('redirect'=>"https://manageyour.cafe/".$DIR."/login")))) : false;
    $id = $_REQUEST['data']['id'];
    try {
        $st = $conn->prepare("SELECT `data` FROM `rosters` WHERE `id`='$id' AND `store_id`='$store_id'");
        $st->execute();
        if($st->rowCount() < 1) {
            exit(json_encode(array('success'=>false)));
        } else {
            exit(json_encode(array('success'=>true,'data'=>base64_encode($st->fetchColumn()))));
        }
    } catch (PDOException $e) {
        echo exitf(null, true, "POST", $e); 
    }
    */
    #endregion
} else if ($_REQUEST['message'] === "LIST_SAVED") {
    $roster = new roster;
    $roster = $roster->list();
    exit($roster);
    #region
    /*
    (isset($_SESSION)) ? ((session_check('store_id', $_SESSION)) ? true : exit(json_encode(array('redirect'=>"https://manageyour.cafe/".$DIR."/login")))) : false;
    try {
        $st = $conn->prepare("SELECT `id`,`data`,`date_from`,`edits` FROM `rosters` WHERE `store_id`='$store_id'");
        $st->execute();
        $rosters = $st->fetchAll(PDO::FETCH_ASSOC);
        $ext = array();
        foreach($rosters as $key => $val) {
            $e = json_decode($val['edits'],true);
            $ext[] = array(
                "id" => $val['id'] ?? null,
                "label" => $e[0]['label'],
                "poster" => $e[0]['by'],
                "date" => date('d/m/Y g:ia', $e[0]['time']),
                "md" => (roster::calculate($_SESSION['store_id'], $val['data'])) ?? null
            );
        }
        $ext = json_encode($ext);
        if($st->rowCount() > 0) {
            exit(json_encode(array('success'=>true,'value'=>$ext,'errors'=>$error->generate())));
        } else {
            exit(json_encode(array('success'=>false,'value'=>$ext,'errors'=>$error->generate())));
        }
    } catch (PDOException $e) {
        $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
        exit(json_encode(array('success' =>  false,'value' => 'Errors.','errors' =>  $$error->generate())));
    }
    */
    #endregion
} else { 
    #region
    (isset($_SESSION)) ? ((session_check('store_id', $_SESSION)) ? true : exit(json_encode(array('redirect'=>"https://manageyour.cafe/".$DIR."/login")))) : false;
    error_log($time." Nothing?".PHP_EOL); 
    #endregion
}
?>