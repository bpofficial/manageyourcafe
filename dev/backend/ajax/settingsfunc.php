<?php 
#region -- STUFF --
session_start();
include('../php/config.php');
$name = $_SESSION['uname'];
#endregion
if(isset($_REQUEST['message']) && !empty($_REQUEST['message'])) {
    if($_REQUEST['message'] == "REQ_PAGE" && $_REQUEST['page'] == ("" || NULL)) {
        #region DASHBOARD SETTINGS PAGE
        global $error; $page = new page;
        $html = $page->generateStaffSettingsPage();
        if ($_SESSION['debug']) {
            exit(json_encode(array('success' => true,'value' => $rosters->generateRosterPage($store_id,$name,$populate),'errors' => $error->generate())));
        } else {
            exit(json_encode(array('success' => true,'value' => $rosters->generateRosterPage($store_id,$name,$populate))));
        }
        #endregion
    } else if ($_REQUEST['message'] != "REQ_PAGE" && $_REQUEST['page'] != ("" || NULL)) {
        #region ROSTERING SETTINGS PAGE 
        global $error; $page = new page;
        if ($_SESSION['debug']) {
            exit(json_encode(array('success' => true,'value' => $page->generateAdminSettingsPage($_REQUEST['message']),'errors' => $error->generate())));
        } else {
            exit(json_encode(array('success' => true,'value' => $page->generateAdminSettingsPage($_REQUEST['message']))));
        }
        #endregion
    } else if((isset($_POST['message']) && !empty($_POST['message']))) {
        #region
    }
}
?>