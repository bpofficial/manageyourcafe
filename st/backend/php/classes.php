<?php
class errorHandle {
    #region -- Private variables --
    private $error_text = "";
    private $error_css = array();
    #endregion

    public function add_error(string $text, array $css = null, bool $nl = true) {
        #region
        $text = str_replace("'", '"', $text);
        if ($css != null && gettype($css) == "array" && $nl == true) {
            $this->error_text .=  '\n' . $text;
            foreach ($css as $key => $value) {
                if ($value == null || $value == "") {
                    continue;
                } else { 
                    array_push($this->error_css, str_replace("'", '"', $value));
                }
            }
        } else if ($css == null && $nl == true) {
            $this->error_text .= '\n' . $text;
        } else if ($css != null && gettype($css) == "array" && $nl == false) {
            $this->error_text .= $text;
            foreach ($css as $key => $value) {
                if($value == null || $value == "") {
                    continue;
                } else { 
                    array_push($this->error_css, str_replace("'", '"', $value));
                }
            }
        } else if ($css == null && $nl == false) {
            $this->error_text .= $text;
        } else if ($css != null && gettype($css) != "array" && $nl == "true") {
            $this->error_text .= '\n' . $text;
            array_push($this->error_css, str_replace("'", '"', $value));
        } else {
            $this->error_text .= "";
        }
        #endregion
    }
    
    public function generate(bool $encode = true) {
        #region
        if ($this->error_css != "" && $this->error_text != "") {
            $return = "<script>console.log('".$this->error_text."',";
            foreach ($this->error_css as $key => $value) {
                $return .= "'" . $value . "',";
            }
            error_log(substr($return, 0, -1) . ");</script>".PHP_EOL,3,"log.log");
            if($encode) {
                return base64_encode(substr($return, 0, -1) . ");</script>");
            } else {
                return substr($return, 0, -1) . ");</script>";
            }
        } else if($this->error_text != "" || $this->error_text != null) {
            $return = "<script>console.log('".$this->error_text."');</script>";
            error_log($return.PHP_EOL,3,"log.log");
            if ($encode) {
                return base64_encode($return);
            } else {
                return $return;
            }
        } else {
            $return = "<script>console.log('nothing to display');</script>";
            error_log($return.PHP_EOL,3,"log.log");
            if ($encode) {
                return base64_encode($return);
            } else {
                return $return;
            }
        }
        #endregion
    }

    public function clear() {
        #region
        $this->error_text = "";
        $this->error_css = array();
        return true;
        #endregion
    }
}

class roster {
    #region -- Private variables --
    private $ROSTER_CREATOR_HEAD = <<<EOT
    <style>
        .fixed-sub {
            position: absolute;
            height: 81px; 
            width: 124px;
            background-color: white;
            z-index: 5;
            border-right-width: 0px;
        }
    </style>
    <div class="section__content section__content--p30">
        <div class="container-fluid" id="content">
            <div class="col-md-12">
                <div class="overview-wrap">
                    <h2 class="title-1">Rosters</h2>
                </div>
                </br> 
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card" style="border-radius: 10px;">
                        <div class="card-header">
                            <strong class="card-title">Roster for week starting:<span class="input-group date" style="width: 20%;" id="date" data-target-input="nearest"> <input id="date-input" type="text" placeholder="Week starting" class="form-control datetimepicker-input" data-target="#date"/> <span class="input-group-append" data-target="#date" data-toggle="datetimepicker"> <span class="input-group-text"><i class="fa fa-calendar"></i></span> </span> </span></strong> <script>$(function(){ $("#date").datetimepicker({format: 'D/M/YYYY', daysOfWeekDisabled: [0,2,3,4,5,6]});}); </script> 
                        </div>
                        <div class="card-body">
                            <form id="roster-form" class="table-scroll" action="">
                                <div class="form-group table-responsive table--no-card m-b-30 table-wrap">
                                    <table id="roster" for="roster-form" class="table table-bordered table-earning main-table" align="left">
                                        <thead align="left">
                                            <tr>
                                                <th>Name</th>
                                                <th>Monday</th>
                                                <th>Tuesday</th>
                                                <th>Wednesday</th>
                                                <th>Thursday</th>
                                                <th>Friday</th>
                                                <th>Saturday</th>
                                                <th>Sunday</th>
                                            </tr>
                                        </thead>
                                        <tbody align="left">
EOT;
    private $ROSTER_CREATOR_FOOT = "</tbody></table></div><div class=\"row\"><div class=\"col-lg-12\"><strong class=\"card-title\"> Comments</strong><div class=\"form-group\"> <textarea class=\"form-control\" id=\"roster-comments\" rows=\"4\"></textarea></div><button id=\"submit-button\" type=\"submit\" form=\"roster-form\" class=\"btn btn-primary btn-lg btn-block\">Submit Roster</button></div></div></form></div></div></div>";
    private $ROSTER_MAIN_FOOT = "</tbody></table></div></div></div></div></div>"; //ends the ROW
    private $SELECT_TIME = "<option value=\"05:00 am\">05:00 am</option><option value=\"05:30 am\">05:30 am</option><option value=\"05:45 am\">05:45 am</option><option value=\"06:00 am\">06:00 am</option><option value=\"06:15 am\">06:15 am</option><option value=\"06:30 am\">06:30 am</option><option value=\"06:45 am\">06:45 am</option><option value=\"07:00 am\">07:00 am</option><option value=\"07:15 am\">07:15 am</option><option value=\"07:30 am\">07:30 am</option><option value=\"07:45 am\">07:45 am</option><option value=\"08:00 am\">08:00 am</option><option value=\"08:15 am\">08:15 am</option><option value=\"08:30 am\">08:30 am</option><option value=\"08:45 am\">08:45 am</option><option value=\"09:00 am\">09:00 am</option><option value=\"09:15 am\">09:15 am</option><option value=\"09:30 am\">09:30 am</option><option value=\"09:45 am\">09:45 am</option><option value=\"10:00 am\">10:00 am</option><option value=\"10:15 am\">10:15 am</option><option value=\"10:30 am\">10:30 am</option><option value=\"10:45 am\">10:45 am</option><option value=\"11:00 am\">11:00 am</option><option value=\"11:15 am\">11:15 am</option><option value=\"11:30 am\">11:30 am</option><option value=\"11:45 am\">11:45 am</option><option value=\"12:00 pm\">12:00 pm</option><option value=\"12:15 pm\">12:15 pm</option><option value=\"12:30 pm\">12:30 pm</option><option value=\"12:45 pm\">12:45 pm</option><option value=\"1:00 pm\">1:00 pm</option><option value=\"1:15 pm\">1:15 pm</option><option value=\"1:30 pm\">1:30 pm</option><option value=\"1:45 pm\">1:45 pm</option><option value=\"2:00 pm\">2:00 pm</option><option value=\"2:15 pm\">2:15 pm</option><option value=\"2:30 pm\">2:30 pm</option><option value=\"2:45 pm\">2:45 pm</option><option value=\"3:00 pm\">3:00 pm</option><option value=\"3:15 pm\">3:15 pm</option><option value=\"3:30 pm\">3:30 pm</option><option value=\"3:45 pm\">3:45 pm</option><option value=\"4:00 pm\">4:00 pm</option><option value=\"Close\">Close</option>";
    private $LOG = "classes_roster.log";
    #endregion
    
    public function generateCreator(string $store_id, bool $pop = false) {
        #region
        global $conn;
        global $error;
        $st = $conn->prepare("SELECT `uname`  FROM `staff` WHERE `store_id`='$store_id'");
        $st->execute();
        $array = $st->fetchAll(PDO::FETCH_COLUMN);
        $staffCount = count($array);
        $days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
        if ($pop) {
            try {
                $st = $conn->prepare("SELECT * FROM `rosters` WHERE `rosters`.`store_id`='$store_id' ORDER BY `date_from` DESC LIMIT 1");
                $st->execute();
                $prev_roster = $st->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                if($_SESSION['debug']) {
                    $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                } else {
                    error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL.PHP_EOL,3,$this->LOG);
                }
            }
            error_log(print_r($prev_roster,true).PHP_EOL,3,"loglog.log");
            for ($x = 0; $x < $staffCount; $x++) {
                $name = ucfirst($array[$x]);
                $ROSTER_CREATOR_BODY .= "<tr><th class=\"fixed-sub\">" . $name . "</th>";
                for ($day = 0; $day < 7; $day++) {
                    $namestart = $days[$day] . '_' . ucfirst($array[$x]) . '_start';
                    $namefinish = $days[$day] . '_' . ucfirst($array[$x]) . '_finish';
                    $today_roster = json_decode($prev_roster[0][$days[$day]], true);
                    $pstime = $today_roster[$name]['start'];
                    $pftime = $today_roster[$name]['finish'];
                    error_log($pstime.PHP_EOL.$pftime.PHP_EOL,3,"loglog.log");
                    if ($pstime != "startTime") {
                        $part = "<td id=\"" . $days[$day] . "\" name=\"" . $days[$day] . "\"><div class=\"rs-select2--trans rs-select2--md\" style=\"background-color: transparent;\"><select value=\"" . $name . "\" name=\"" . $namestart . "\" class=\"js-select2 select2-hidden-accessible\" tabindex=\"-1\" aria-hidden=\"true\" style=\"background-color: transparent;max-width:5%;\"><option value=\"startTime\">Start Time</option><option selected=\"selected\" value=\"" . $pstime . "\" >" . $pstime . "</option>";             
                    } else {
                        $part = "<td id=\"" . $days[$day] . "\" name=\"" . $days[$day] . "\"><div class=\"rs-select2--trans rs-select2--md\" style=\"background-color: transparent;\"><select value=\"" . $name . "\" name=\"" . $namestart . "\" class=\"js-select2 select2-hidden-accessible\" tabindex=\"-1\" aria-hidden=\"true\" style=\"background-color:transparent;\"><option value=\"startTime\">Start Time</option>";
                    }
                    $ROSTER_CREATOR_BODY .= $part . $this->SELECT_TIME . "</select></div>";
                    if ($pftime != "finishTime") {
                        $part2 = "<div class=\"rs-select2--trans rs-select2--md\" style=\"background-color: transparent;\"><select value=\"" . $name . "\" name=\"" . $namefinish . "\" class=\"js-select2 select2-hidden-accessible\" style=\"background-color: transparent;max-width:5%;\"><option value=\"finishTime\">Finish Time</option><option selected=\"selected\" value=\"" . $pftime . "\" >" . $pftime . "</option>";             
                    } else {
                        $part2 = "<div class=\"rs-select2--trans rs-select2--md\" style=\"background-color: transparent;\"><select value=\"" . $name . "\" name=\"" . $namefinish . "\" class=\"js-select2 select2-hidden-accessible\" style=\"background-color: transparent;max-width:5%;\"><option value=\"finishTime\">Finish Time</option>";
                    }
                    $ROSTER_CREATOR_BODY .= $part2 . $this->SELECT_TIME . "</select><span class=\"dropdown-wrapper\" style=\"background-color: transparent;\" aria-hidden=\"true\"></span></div></td>";
                }
                $ROSTER_CREATOR_BODY .= "</tr>";
            }
        } else {
            for ($x = 0; $x < $staffCount; $x++) {
                $name = ucfirst($array[$x]);
                $ROSTER_CREATOR_BODY .= "<tr><th class=\"fixed-sub\">" . $name . "</th>";
                for ($day = 0; $day < 7; $day++) {
                    $namestart = $days[$day] . '_' . ucfirst($array[$x]) . '_start';
                    $namefinish = $days[$day] . '_' . ucfirst($array[$x]) . '_finish';
                    $ROSTER_CREATOR_BODY .= "<td id=\"" . $days[$day] . "\" name=\"" . $days[$day] . "\"><div class=\"rs-select2--trans rs-select2--md\" style=\"background-color: transparent;\"><select value=\"" . $name . "\" name=\"" . $namestart . "\" class=\"js-select2 select2-hidden-accessible\" style=\"background-color: transparent; max-width:5%;\"><option selected=\"selected\" value=\"startTime\">Start Time</option>";
                    $ROSTER_CREATOR_BODY .= $this->SELECT_TIME . "</select><select value=\"" . $name ."\" name=\"" . $namefinish . "\" class=\"js-select2 select2-hidden-accessible\" style=\"background-color:transparent;\"><option selected=\"selected\" value=\"finishTime\">Finish Time</option>";
                    $ROSTER_CREATOR_BODY .= $this->SELECT_TIME . "</select><span class=\"dropdown-wrapper\" style=\"background-color:transparent;\" aria-hidden=\"true\"></span></div></td>";
                }
            $ROSTER_CREATOR_BODY .= "</tr>";
            } 
        }
        return $this->ROSTER_CREATOR_HEAD . $ROSTER_CREATOR_BODY . $this->ROSTER_CREATOR_FOOT;
        #endregion
    }

    public function generatePrevious(string $store_id, array $options = []) {
        #region
        global $conn;
        global $error;
        if(!isset($options["ROSTER_UPDATE_ALERT_TITLE"])) {
            $ROSTER_UPDATE_ALERT_TITLE = "Work related message.";
        }
        if(!(isset($options["ROSTER_UPDATE_ALERT_CONTENT"]))) {
            $ROSTER_UPDATE_ALERT_CONTENT = "New roster available. Woo!";
        }
        $days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
        $PREVIOUS_ROSTERS = "";
        try {
            $st = $conn->prepare("SELECT * FROM `rosters` WHERE `rosters`.`store_id`='$store_id' ORDER BY `rosters`.`date_from` DESC");
            $st->execute();
            $All_Rosters = $st->fetchAll(PDO::FETCH_ASSOC);
            $st = null;
            if($_SESSION['debug']) { $error->add_error("%cGot rosters for store: %c" . $store_id, ['font-style:italic;', 'color:blue;'], true); }
        } catch (Exception $e) {
            if($_SESSION['debug']) { $error->add_error("%Failed to get rosters for store: %c" . $store_id . ' %creason: ' . $e, ['font-style:italic;', 'color:blue;', 'color:black;font-style:italic;','color:red;'], true); }
        }
        try {
            $st = $conn->prepare("SELECT * FROM `staff` WHERE `store_id`='$store_id'");
            $st->execute();
            $staffCount = $st->rowCount();
            $staff = $st->fetchAll(PDO::FETCH_ASSOC);
            $st = null;
            if($_SESSION['debug']) { $error->add_error("%cGot staff for store: %c" . $store_id, ['font-style:italic;', 'color:blue;'], true); }
        } catch (PDOException $e) {
            if($_SESSION['debug']) { $error->add_error("%Failed to get staff for store: %c" . $store_id . ' %creason: ' . $e, ['font-style:italic;', 'color:blue;', 'color:black;font-style:italic;','color:red;'], true); }
        }
        if($_SESSION['debug']) { $error->add_error("%cStarting to create previous rosters.", ['color:black;'], true); }
        foreach ($All_Rosters as $key => $value) {
            $body = "";
            $id = $value['id'];
            $date = date("d/m/Y", strtotime($value['date_from'])); 
            if($_SESSION['debug']) { $error->add_error("%cRoster with id %c". $id . " %cand date: %c" . $date, ['font-style:italic','color:blue','color:black;font-style:italic;','color:blue'], true); }
            $PREVIOUS_ROSTERS .= "</div><div id=\"" . $id . "\" class=\"row\"><div class=\"col-lg-12\"><div class=\"card\"> <div class=\"card-header\"><span style=\"cursor:pointer;\" class=\"float-right\"> <a class=\"remove-roster\" name=\"" . $id . "\"> &nbsp&nbsp<i class=\"fas fa-times\"></i> </a> </span><span id=\"roster-show\" class=\"badge badge-primary float-right\" style=\"cursor:pointer;margin-top:0.16rem;\" data-toggle=\"collapse\" data-target=\"#roster-" . $id . "\" name=\"" . $id . "\">Show&nbsp;<i class=\"fas fa-angle-down\"></i></span><span class=\"badge badge-success\">Completed <i class=\"fas fa-check\"></i></span><strong style=\"margin-left:1vw;\" class=\"card-title\">Roster for week starting: " . $date . "</strong></span></div><div class=\"collapse\" id=\"roster-" . $id . "\"><div class=\"card-body\"><div class=\"table-responsive table--no-card m-b-30\"> <table id=\"roster\" class=\"table table-bordered table-earning\" align=\"left\"> <thead align=\"left\"> <tr><th>Name</th><th>Monday</th><th>Tuesday</th> <th>Wednesday</th> <th>Thursday</th> <th>Friday</th> <th>Saturday</th> <th>Sunday</th> </tr></thead> <tbody align=\"left\">";
            for ($i = 0; $i < $staffCount; $i++) {
                $nname = ucfirst($staff[$i]['uname']);
                $body .= "<tr><td>" . $nname . "</td>";
                for ($x = 0; $x < 7; $x++) {
                    $data = json_decode($value[$days[$x]], true);
                    $name_start = $data[$nname]["start"]; //e.g. 
                    $name_finish = $data[$nname]["finish"];
                    if($name_start == "startTime" || $name_finish == "finishTime") {
                        $name_start = "Not working";
                        $name_finish = "";
                    }
                    $body .= "<td><div>" . $name_start . "</div><div>" . $name_finish . "</div></td>";
                }
                $body .= "</tr>";
            }
            $PREVIOUS_ROSTERS .= $body . "</tr></tbody></table></div></div></div></div></div>";
        }
        $PREVIOUS_ROSTER_FOOT = "</div><script>$(function(){ $(\"#roster-form\").submit(function(j){j.preventDefault();var k={};var e=$(\"#roster-form\").serializeArray();var i=e.length,n,m;for(n=0;n<i;n++){var l=e[n].name.split(\"_\");m=k;while(l.length){key=l.shift();if(l.length){if(typeof m[key]===\"undefined\"){m[key]={}}m=m[key]}}m[key]=e[n].value}k.startDate=document.getElementById(\"date-input\").value;k.comments=document.getElementById(\"roster-comments\").value;$.ajax({type:\"POST\",url:\"backend\/ajax\/rostersfunc.php\",dataType:\"json\",data:{message:\"UPDATE_ROSTER\",data:JSON.stringify(k)},success:function(a){if(!a.success){console.log(a);$(\"#submit-button\").prop(\"class\",\"btn btn-danger btn-lg btn-block\");$(\"#submit-button\").text(\"Failed to submit\")}else{if(\"errors\" in a){ $(\"#error\").html(window.atob(a.errors))}$(\"#submit-button\").prop(\"class\",\"btn btn-success btn-lg btn-block\");$(\"#submit-button\").text(\"Roster Submitted!\")}},error:function(){ $(\"#submit-button\").prop(\"class\",\"btn btn-danger btn-lg btn-block\");$(\"#submit-button\").text(\"Failed to submit\")}})});$(\".js-select2\").select2({minimumResultsForSearch:-1});$(\".remove-roster\").on(\"click\",function(){var b=$(this).attr(\"name\");$(\"#\"+b).remove();$.ajax({type:\"POST\",url:\"backend\/ajax\/rostersfunc.php\",dataType:\"json\",data:{message:\"REMOVE_ROSTER\",data:b}})});$(\"#content\").on(\"click\",\"#roster-show\",function(){var b=$(this).attr(\"name\");if($(\"#roster-\"+b).prop(\"class\")==\"collapse\"){ $(this).html('Hide \<i class=\"fas fa-angle-up\"\>\<\/i\>')}else{if($(\"#roster-\"+b).prop(\"class\")==\"collapse show\"){ $(this).html('Show \<i class=\"fas fa-angle-down\"\>\<\/i\>')}}})});</script>";
        return $PREVIOUS_ROSTERS . $PREVIOUS_ROSTER_FOOT;
        #endregion
    }

    public function generateUser(string $store_id, string $req = "all") {
        #region
        global $conn;
        global $error;
        try {
            $st = $conn->prepare("SELECT `uname`  FROM `staff` WHERE `store_id`='$store_id'");
            $st->execute();
            $array = $st->fetchAll(PDO::FETCH_COLUMN);
            $staffCount = count($array);
        } catch (PDOException $e) {
            if($_SESSION['debug']) {
                $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            } else {
                error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL.PHP_EOL,3,$this->LOG);
            }
        }
        try {
            $st = $conn->prepare("SELECT * FROM `rosters` WHERE `store_id`='$store_id' ORDER BY `date_from` DESC");
            $st->execute();
            $prev_roster = $st->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            if($_SESSION['debug']) {
                $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            } else {
                error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL.PHP_EOL,3,$this->LOG);
            }        
        }
        $days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
        $ROSTER_MAIN_BODY = "";
        for ($x = 0; $x < $staffCount; $x++) {
            $name = ucfirst($array[$x]);
            $ROSTER_MAIN_BODY .= "<tr style=\"cursor: default;\"><td style=\"font-weight: bold; cursor: default;\">" . $name . "</td>";
            $namestart = ucfirst($array[$x]) . '_start';
            $namefinish = ucfirst($array[$x]) . '_finish';
            for ($day = 0; $day < 7; $day++) {
                $tday = $days[$day];
                $roster = json_decode($prev_roster[0][$tday], TRUE);
                $pstime = $roster[$name]['start'];
                $pftime = $roster[$name]['finish'];
                if ($pstime != "startTime") {
                    //$pstime = str_replace('"', "'",$pstime);
                    $ROSTER_MAIN_BODY .= "<td><p style=\"cursor: default;\">" . $pstime . "</p>";             
                } else {
                    $ROSTER_MAIN_BODY .= "<td><p style=\"cursor: default;\">Not working</p>";
                }
                if ($pftime != "finishTime") {
                    //$pftime = str_replace('"', "'",$pftime);
                    $ROSTER_MAIN_BODY .= "<p style=\"cursor: default;\">" . $pftime . "</p></td>";             
                } else {
                    $ROSTER_MAIN_BODY .= "</td>";
                }
            }
            $ROSTER_MAIN_BODY .= "</tr>";
        }
        $date = $prev_roster[0]["date_from"];
        $date = date("d/m/Y", strtotime($date));
        if (isset($prev_roster[0]["comments"])) {
            $COMMENTS = "<div id=\"comments\" class=\"comments\">" . $prev_roster[0]["comments"] . "</div><br/>";
        } else {
            $COMMENTS = "";
        }
        $ROSTER_MAIN_HEAD = "<div class=\"section__content section__content--p30\"> <div class=\"container-fluid\" id=\"content\"> <div class=\"col-md-12\"> <div class=\"overview-wrap\"> <h2 class=\"title-1\">Rosters</h2> </div></br> </div><div class=\"row\"> <div class=\"col-lg-12\" style=\"cursor: default;\"> <div class=\"card\"> <div class=\"card-header\"><strong class=\"card-title\">Roster for week starting " . $date . "</strong></div><div class=\"card-body\">" . $COMMENTS . "<div class=\"table-responsive table--no-card m-b-30\"><table id=\"roster\" class=\"table table-borderless table-striped table-earning\" style=\"border-radius:px;\" align=\"left\"> <thead align=\"left\"> <tr> <th>Name</th> <th>Monday</th> <th>Tuesday</th> <th>Wednesday</th> <th>Thursday</th> <th>Friday</th> <th>Saturday</th> <th>Sunday</th> </tr></thead> <tbody align=\"left\" style=\"cursor: default;\">";
        $USER_PREVIOUS_ROSTERS = "";
        $first = true;
        foreach ($prev_roster as $key => $value) {
            if($first === true){ $first=false; continue;}   
            $body = "";
            $id = $value['id'];
            $date = date("d/m/Y", strtotime($value['date_from'])); 
            if($_SESSION['debug']) { $error->add_error("%cRoster with id %c". $id . " %cand date: %c" . $date, ['font-style:italic','color:blue','color:black;font-style:italic;','color:blue'], true); }
            $USER_PREVIOUS_ROSTERS .= "<div id=\"" . $id . "\" class=\"row\"><div class=\"col-lg-12\"><div class=\"card\"> <div class=\"card-header\"><strong style=\"margin-left:1vw;\" class=\"card-title\">Roster for week starting: " . $date . "</strong> <span id=\"roster-show\" style=\"cursor:pointer;\" data-toggle=\"collapse\" data-target=\"#roster-" . $id . "\" name=\"" . $id . "\"><i id=\"arrow\" x=\"" . $id . "\" class=\"float-right fas fa-angle-down\"></i></span> </div><div class=\"card-body collapse\" id=\"roster-" . $id . "\"><div class=\"form-group table-responsive table--no-card m-b-30\"> <table id=\"roster\" for=\"roster-form\" class=\"table table-bordered table-earning\" align=\"left\"> <thead align=\"left\"> <tr><th>Name</th><th>Monday</th><th>Tuesday</th> <th>Wednesday</th> <th>Thursday</th> <th>Friday</th> <th>Saturday</th> <th>Sunday</th> </tr></thead> <tbody align=\"left\">";
            for ($i = 0; $i < $staffCount; $i++) {
                $nname = ucfirst($array[$i]);
                $body .= "<tr><td>" . $nname . "</td>";
                for ($x = 0; $x < 7; $x++) {
                    $data = json_decode($value[$days[$x]], true);
                    $name_start = $data[$nname]["start"]; //e.g. 
                    $name_finish = $data[$nname]["finish"];
                    if($name_start == "startTime" || $name_finish == "finishTime") {
                        $name_start = "Not working";
                        $name_finish = "";
                    }
                    $body .= "<td><div>" . $name_start . "</div><div>" . $name_finish . "</div></td>";
                }
                $body .= "</tr>";
            }
            $USER_PREVIOUS_ROSTERS .= $body . "</tbody></table></div></div></div></div></div>";
        }

        if($req === "all") {
            return $ROSTER_MAIN_HEAD. $ROSTER_MAIN_BODY . $this->ROSTER_MAIN_FOOT . $USER_PREVIOUS_ROSTERS . "</div></div>"; //the 2 end-divs are for section content and container fluid :)
        } else if ($req === "email") {
            return "<div>" . $COMMENTS . "</div><br/><div class=\"table-responsive table--no-card m-b-30\"><table id=\"roster\" class=\"table table-borderless table-striped table-earning\" style=\"border-radius:px;\" align=\"left\"> <thead align=\"left\"> <tr> <th>Name</th> <th>Monday</th> <th>Tuesday</th> <th>Wednesday</th> <th>Thursday</th> <th>Friday</th> <th>Saturday</th> <th>Sunday</th> </tr></thead> <tbody align=\"left\" style=\"cursor: default;\">" . $ROSTER_MAIN_BODY . "</tbody></table></div>";
        }
        #endregion
    }
}

class page {
    #region -- Private variables --
    private $LOG = "classes_page.log";
    #endregion

    private function humanTiming($time) {
        #region
        $time = time() - strtotime($time);
        $time = ($time<1)? 1 : $time;
        $tokens = array (
            31536000 => ' yrs',
            2592000 => ' mths',
            604800 => ' wks',
            86400 => ' days',
            3600 => 'hrs',
            60 => 'm',
            1 => 's'
        );
        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits.''.$text;
        }
        #endregion
    }

    private function check_roster($store_id, $date) {
        #region
        global $conn;
        global $error;
        $format_date = date('Y-m-d', strtotime($date));
        try {
            $st = $conn->prepare("SELECT * FROM `rosters` WHERE `date_from`='$format_date' AND `store_id`='$store_id'");
            $st->execute();
            if($st->rowCount() >= 1) {
                if($_SESSION['debug']) { $error->add_error("%cRoster set for %c" . $date . "%c? %cTrue",['font-style:italic;','color:blue;','color:black;', 'color:red;'],true); } else { error_log("Roster set".PHP_EOL,3,$this->LOG); }
                return true;
            } else {
                if($_SESSION['debug']) { $error->add_error("%cRoster set for %c" . $date . "%c? %cFalse",['font-style:italic;','color:blue;','color:black;', 'color:red;'],true); } 
                return false;
            }
            $st = null; 
        } catch (Exception $e) {
            return false;
        }
        #endregion
    }

    private function days_until($date) {
        #region
        return (isset($date)) ? floor((strtotime($date) - time())/60/60/24) : FALSE;
        #endregion
    }
    
    public function generateNoticePage(string $store_id, string $user, array $options = []) {
        #region
        $MODAL = "<div class=\"modal fade show\" id=\"newPostModal\" tabindex=\"-100\" data-backdrop=\"false\" role=\"dialog\" aria-labelledby=\"largeModalLabel\" style=\"display: none;\"><div class=\"modal-dialog modal-lg\" role=\"document\"> <div class=\"modal-content\"> <div class=\"modal-header\"> <h5 class=\"modal-title\" id=\"largeModalLabel\">New post</h5> <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"> <span aria-hidden=\"true\">X</span> </button> </div><div class=\"modal-body\"> <form id=\"newpost\"> <div class=\"form-group\"> <textarea class=\"form-control\" name=\"title\" id=\"post-title\" placeholder=\"Title\" rows=\"1\"></textarea> </div><div class=\"form-group\"> <textarea class=\"form-control\" name=\"content\" id=\"post-content\" placeholder=\"Message\" rows=\"5\"></textarea> </div></form> </div><div class=\"modal-footer\"> <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Cancel</button> <button type=\"button\" id=\"submit\" class=\"btn btn-primary\" type=\"submit\" data-dismiss=\"modal\" form=\"newpost\">Confirm</button></div></div></div></div>";
        $ADMIN_HEAD = "<div id=\"notices\" class=\"section__content section__content--p30\"> <div class=\"container-fluid\" id=\"content\"> <div class=\"col-md-12\"> <div class=\"overview-wrap\"> <h2 class=\"title-1\">Notices</h2> <button class=\"au-btn au-btn-icon au-btn--blue\" data-toggle=\"modal\" data-target=\"#newPostModal\"> <i class=\"zmdi zmdi-plus\"></i>new post</button> </div><br/>";
        $USER_HEAD = "<div id=\"notices\" class=\"section__content section__content--p30\"><div class=\"container-fluid\" id=\"content\"><div class=\"col-md-12\"><div class=\"overview-wrap\"><h2 class=\"title-1\">Notices</h2></div><br />";
    
        global $conn;
        global $error;

        try {
            $st = $conn->prepare("SELECT `rights` FROM `staff` WHERE `uname`='$user' AND `store_id`='$store_id'");
            $st->execute();
            $user_priv = json_decode(stripslashes($st->fetchColumn()),true);
        } catch (PDOException $e) {
            if($_SESSION['debug']) {
                $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            } else {
                error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL.PHP_EOL,3,$this->LOG);
            }
        }
        if ($user_priv['post'] === "true") {
            $html = $ADMIN_HEAD;
        } else {
            $html = $USER_HEAD;
        }
        try {
            $st = $conn->prepare("SELECT * FROM `notices` WHERE `store_id`='$store_id'");
            $st->execute();
            $count = $st->rowCount();
            $data = $st->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            if($_SESSION['debug']) {
                $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            } else {
                error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL.PHP_EOL,3,$this->LOG);
            }
        }
        $inner = ""; $body = "";
        foreach ($data as $key => $value) {
            $id = $value['id'];
            try{
                $poster = $value['posted_by'];
                $type = $value['type'];
                if($type == "notice") {
                    $content = json_decode(stripslashes($value['content']),true);
                    $title = $content['title'];
                    $content = "<p class=\"card-text\">" . $content['content'] . "</p>";
                } else if ($type == "roster") {
                    $content = json_decode(stripslashes($value['content']),true);
                    $title = $content['title'];
                    if(base64_decode($content['content'] == false)) {
                        $error->add_error("Counld't decode content from base64.", ['font-weight:bold;', 'color:red;'], true);
                    } else {
                        $content = base64_decode($content['content']);
                    }
                } else {
                    $error->add_error("%cError: %cUnknown type while generating the notices page.", ['font-weight:bold;', 'color:red;'], true);
                }
                if($title === null || $content === null) {throw new Exception("No content");}
                $since = $this->humanTiming($value['date']);
            } catch (Exception $e){ 
                if($_SESSION['debug']) {
                    $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                } else {
                    error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL.PHP_EOL,3,$this->LOG);
                }
                continue;
            }
            try {
                $sto = $conn->prepare("SELECT `rights` FROM `staff` WHERE `uname`='$poster' AND `store_id`='$store_id'");
                $sto->execute();
                $poster_priv = json_decode(stripslashes($sto->fetchColumn()),true);
            } catch (Exception $e) {
                if ($e instanceof PDOException || $e instanceof Exception) {
                    if($_SESSION['debug']) {
                        $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                    } else {
                        error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL.PHP_EOL,3,$this->LOG);
                    }
                } else {
                    throw $e;
                }
                continue;
            }
            if ($poster_priv['level'] == 3) {
                $badge = "badge badge-danger";
            } else if ($poster_priv['level'] == 2) {
                $badge = "badge badge-success";
            } else {
                $badge = "badge badge-primary";
            }
            $com = $conn->prepare("SELECT * FROM `comments` WHERE `notice_id`='$id' AND `store_id`='$store_id'");
            $com->execute();
            $comment_count = $com->rowCount();
            if($comment_count > 0) {
                $comments = $con->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $comment_count = 0;
            }
            $poster = ucfirst($poster);
            if($user_priv['edit'] === "true") {
                $body = "<div id=\"" . $id . "\" class=\"row\"> <div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12 col-md-12 col-sm-12 col-xs-12\"> <div class=\"card\" id=\"card-" . $id . "\"> <div class=\"card-header\"> <div class=\"row\"> <div class=\"col-lg-8 col-md-8 col-sm-8 col-xs-8\"> <strong class=\"card-title\">" . $title . "</strong> </div><div class=\"col-lg-4 col-md-4 col-sm-4 col-xs-4\"> <span style=\"cursor:pointer;\" class=\"float-right\"> <a id=\"remove-notice\" name=\"" . $id . "\"> &nbsp;&nbsp;<i class=\"fas fa-times\"></i> </a> </span> <strong> <span class=\"" . $badge . " float-right mt-1\">" . $poster . "</span> </strong> </div></div><div class=\"row\"> <div class=\"col-lg-12 col-md-8 col-sm-6 col-xs-3\"> <span> <small>" . $since . "</small> </span> </div></div></div><div class=\"card-body\"> <div class=\"row\"> <div id=\"post-card-" . $id . "\" class=\"col-lg-12 col-md-8 col-sm-6 col-xs-3\">" . $content . "</div></div></div></div></div></div>" . $body;
            } else {
                $body = "<div id=\"" . $id . "\" class=\"row\"> <div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12 col-md-12 col-sm-12 col-xs-12\"> <div class=\"card\" id=\"card-" . $id . "\"> <div class=\"card-header\"> <div class=\"row\"> <div class=\"col-lg-8 col-md-8 col-sm-8 col-xs-8\"> <strong class=\"card-title\">" . $title . "</strong> </div><div class=\"col-lg-4 col-md-4 col-sm-4 col-xs-4\"> <strong> <span class=\"" . $badge . " float-right mt-1\">" . $poster . "</span> </strong> </div></div><div class=\"row\"> <div class=\"col-lg-12 col-md-8 col-sm-6 col-xs-3\"> <span> <small>" . $since . "</small> </span> </div></div></div><div class=\"card-body\"> <div class=\"row\"> <div id=\"post-card-" . $id . "\" class=\"col-lg-12 col-md-8 col-sm-6 col-xs-3\">" . $content . "</div></div></div></div></div></div>" . $body;
            }
        }
        $uppername = ucfirst($user);
        if(!isset($options["NOTICE_UPDATE_ALERT_TITLE"])) {
            $NOTICE_UPDATE_ALERT_TITLE = $uppername . " has created a notice.";
        }
        if(!(isset($options["NOTICE_UPDATE_ALERT_CONTENT"]))) {
            $NOTICE_UPDATE_ALERT_CONTENT = "b.title";
        }
        if ($user_priv['edit'] === "true" && $user_priv['post'] === "true") {
            $html .= $body . $MODAL . "</div></div></div>" . "<script> $(document).ready(function(){ $(\"button#submit\").click(function(c){c.preventDefault();var a=$(\"#newpost\").serializeArray();var b={};$(a).each(function(d,e){b[e.name]=e.value});$.ajax({type:\"POST\",url:\"backend/ajax/noticesfunc.php\",dataType:\"json\",data:{message:\"NOTICE_POST\",data:window.btoa(JSON.stringify(b))},success:function(f){var g={proto:\"UP_NOTI\"},d={proto:\"SN_NOTI\",title:\"" . $NOTICE_UPDATE_ALERT_TITLE . "\",body:\"" . $NOTICE_UPDATE_ALERT_CONTENT . "\"};window.client.send(JSON.stringify(g));window.client.send(JSON.stringify(d))},error:function(d){}});$(\"#newPostModal\").modal(\"hide\")});$(\"body\").on(\"click\",\"#remove-notice\",function(a){var b=this.name;$(\"div#\"+b).remove();$.ajax({type:\"POST\",url:\"backend/ajax/noticesfunc.php\",dataType:\"json\",data:{message:\"NTC_RM\",value:b},success:function(c){},error:function(c){disError(\"Failed to remove notice.\",true)}})})});</script>" . "</div>";
        } else {
            $html .= $body . "</div></div></div></div>";
        }
        return $html;
        #endregion
    }

    public function generateRosterPage(string $store_id, string $user, bool $populate = false, $date = 'next monday') {
        #region
        global $conn;
        global $error;

        //these will be used for the custom shishcabobs related to the time and the status of the rosters :)
            #region
        $ADMIN_HEAD = "";
        $ADMIN_FOOT = "";
        $USER_HEAD = "";
        $USER_FOOT = "";
        #endregion

        $roster = new roster;
        try {
            $st = $conn->prepare("SELECT `rights` FROM `staff` WHERE `uname`='$user' AND `store_id`='$store_id'");
            $st->execute();
            $user_priv = json_decode(stripslashes($st->fetchColumn()),true);
        } catch (PDOException $e) {
            if($_SESSION['debug']) {
                $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            } else {
                error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL.PHP_EOL,3,$this->LOG);
            }
        }
        if ($user_priv['rosters'] === "true") {
            $check = $this->check_roster($store_id, $date);
            $days_left = $this->days_until($date);
            if ($check === false && $days_left <= 2) {
                //panic
                $alert = ""; //javascript function to send a message to the nodejs service and email those that are allocated for rostering.
                $RosterData = $roster->generateCreator($store_id, $populate) . $roster->generatePrevious($store_id);
                return base64_encode($RosterData);
            }
            else if ($check === false && $days_left > 2) {
                $alert = "";
                $RosterData = $roster->generateCreator($store_id, $populate) . $roster->generatePrevious($store_id);
                return base64_encode($RosterData);
            }
            else if ($check === true) {
                //create the page although put the roster creator inside a collapsable element
                $RosterData = "" . $roster->generateCreator($store_id, $populate) . "";
                $RosterData .= $roster->generatePrevious($store_id);
                return base64_encode($RosterData);
            }
        } else {
            return base64_encode($roster->generateUser($store_id));
        }
        #endregion
    }

}
?>