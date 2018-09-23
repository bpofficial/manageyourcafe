<?php
    ini_set('memory_limit', '-1');
    error_reporting(E_ALL); // Error engine - always ON!
    ini_set('display_errors', FALSE); // Error display - OFF in production env or real server
    ini_set('log_errors', TRUE); // Error logging
    ini_set('error_log', "php_logs.log"); // Logging file
    $username = "dashboar_user";
    $password = '$$!DBUser!$';
    $hostname = "103.27.32.4";
    $database = "dashboar_main";
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
    $loginpage_url = $domain . '/st/login.php';
    $forbidden_url = $domain . '/403.php';
    $fourofour_url = $domain . '/404.php';
?>