<?php
    $LOG = "log.log";
    $DIR = "dev";
    $node_port = '58444';
    $class_dir = '/home/dashboar/public_html/'.$DIR.'/backend/class/';
    $domain = "https://www.manageyour.cafe/";
    $username = "dashboar_user";
    $password = '$$!DBUser!$';
    $hostname = "103.27.32.4";
    $database = ($DIR != "dev") ? "dashboar_main" : "dashboar_dev";

    error_reporting(E_ALL ^ E_NOTICE);

    ini_set('memory_limit', '-1');
    ini_set('display_errors', false);
    ini_set('log_errors', TRUE);
    ini_set('error_log', $LOG);
    
    require_once("PHPMailer/PHPMailer.php");
	require_once("PHPMailer/Exception.php");
	require_once("PHPMailer/SMTP.php");
    require_once('functions.php');
    require_once($class_dir.'system/system.class.php');
    require_once($class_dir.'system/roster.system.class.php');
    require_once($class_dir.'system/calendar.system.class.php');
    require_once($class_dir.'system/notice.system.class.php');
    require_once($class_dir.'page.class.php');
    require_once($class_dir.'error.class.php');
    require_once($class_dir.'notification.class.php');

    try {
        $conn = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e) {
        error_log("Error: " . $e.getMessage() . PHP_EOL,3,"config.log");
    }

    defined("TAB1") or define("TAB1", "\t");
    defined("TAB2") or define("TAB2", "\t\t");
    defined("TAB3") or define("TAB3", "\t\t\t");
    defined("TAB4") or define("TAB4", "\t\t\t\t");
    defined("TAB5") or define("TAB5", "\t\t\t\t\t");
    $error = new errorHandle;
    (isset($_SESSION)) ? ((session_check('store_id', $_SESSION)) ? true : exit(json_encode(array('redirect'=>"https://manageyour.cafe/".$DIR."/login")))) : false;
?>