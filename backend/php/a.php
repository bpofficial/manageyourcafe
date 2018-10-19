<?php
header('Content-Type: application/json');
$username = "dashboar_user";
$password = '$$!DBUser!$';
$hostname = "103.27.32.4";
$database = "dashboar_main";
$conn = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$st = $conn->prepare("SELECT * FROM `rosters`");
$st->execute();

$data = $st->fetchAll(PDO::FETCH_ASSOC);
$data2 = [];
$days = ["monday","tuesday","wednesday","thursday","friday","saturday","sunday"];
foreach($data as $key => $val) { 
    foreach($val as $a => $b) {
        if(strpos($a,"day") !== false) {
            $data2[$val['id']][$a] = json_decode($b,true);
        }
    }
    $data2[$val['id']] = json_encode($data2[$val['id']]);
}
echo '104 <br/>';
echo $data2['104'].' <br/>';


//[id][store_id][date_from][data][edits][saved]
//[id][store_id][date_from][$data2][edits][0]
?>