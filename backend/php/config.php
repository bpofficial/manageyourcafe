<?php
    ini_set('memory_limit', '-1');
    $LOG = "log.log";
    error_reporting(E_ALL);
    ini_set('display_errors', false); // Error display - OFF in production env or real server
    ini_set('log_errors', TRUE); // Error logging
    ini_set('error_log', $LOG); // Logging file
    include_once "PHPMailer/PHPMailer.php";
	include_once "PHPMailer/Exception.php";
	include_once "PHPMailer/SMTP.php";
    require_once('functions.php');
    $DIR = "dev";
    $class_dir = '/home/dashboar/public_html/'.$DIR.'/backend/class/';
    /*
    $iti = new RecursiveDirectoryIterator($class_dir);
    foreach(new RecursiveIteratorIterator($iti) as $file){
        if(strpos($file, 'class.php') !== false) {
            include_once($file);
        }
    };
    */
    require_once($class_dir.'system/system.class.php');
    require_once($class_dir.'system/roster.system.class.php');
    require_once($class_dir.'page.class.php');
    require_once($class_dir.'error.class.php');
    require_once($class_dir.'notification.class.php');
    $username = "dashboar_user";
    $password = '$$!DBUser!$';
    $hostname = "103.27.32.4";
    if($DIR != "dev") {
        $database = "dashboar_main";
    } else {
        $database = "dashboar_dev";
    }
    $domain = "https://www.manageyour.cafe/";
    $length_salt = 15;
    $maxfailedattempt = 5;
    $sessiontimeout = 180;
    $time = "[".date("h:i:s Y/m/d")."]";
    $debug = true;
    try {
        $conn = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        //error_log("Error: " . $e.getMessage() . PHP_EOL,3,"logs/config.log");
    }
    $loginpage_url = $domain . '/'.$DIR.'/login.php';
    $forbidden_url = $domain . '/403.php';
    $fourofour_url = $domain . '/404.php';

    defined("TAB1") or define("TAB1", "\t");
    defined("TAB2") or define("TAB2", "\t\t");
    defined("TAB3") or define("TAB3", "\t\t\t");
    defined("TAB4") or define("TAB4", "\t\t\t\t");
    defined("TAB5") or define("TAB5", "\t\t\t\t\t");

    (isset($_SESSION)) ? ((session_check('store_id', $_SESSION)) ? true : exit(json_encode(array('redirect'=>"https://manageyour.cafe/".$DIR."/login")))) : false;
?>