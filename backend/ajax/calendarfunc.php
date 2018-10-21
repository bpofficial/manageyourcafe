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
    $page = $page->calendar();
    exit($page);
} else {

}
?>