<?php
#region -- STUFF --
session_start();
header('Content-type: text/html');
require('../php/config.php');
$error = new errorHandle;
#endregion
if ($_REQUEST['message'] === "REQ_PAGE") {
    header("Link: </dev/sources/css/roster.css>; rel=preload; as=style");
    $page = new page;
    exit($page->roster());
} else if ($_POST['message'] === "UPDATE_ROSTER") {
    $roster = new roster;
    exit($roster->add($_POST['data']));
} else if ($_POST['message'] === "REMOVE_ROSTER") {
    $roster = new roster;
    exit($roster->delete($_POST['data']));
} else if ($_POST['message'] === "SAVE_ROSTER") {
    $data['data'] = $_POST['data']; $data['la'] = $_POST['la'];
    $roster = new roster;
    exit($roster->save($data));
} else if ($_REQUEST['message'] === "REQ_SAVED") {
    $roster = new roster;
    exit($roster->load($_REQUEST['data']['id']));
} else if ($_REQUEST['message'] === "LIST_SAVED") {
    $roster = new roster;
    exit($roster->list());
} else {
    exit(json_encode(array('success'=>false,'value'=>'Illegal request.')));
}
?>;