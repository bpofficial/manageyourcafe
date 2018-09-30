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
    
    public function generate(bool $encode = true, bool $clear = true) {
        #region
        global $LOG;
        if ($this->error_css != "" && $this->error_text != "") {
            $return = "console.log('".$this->error_text."',";
            if ($clear) { $return = "<script>console.clear();" . $return; } else { $return = "<script>" . $return; }
            foreach ($this->error_css as $key => $value) {
                $return .= "'" . $value . "',";
            }
            error_log(substr($return, 0, -1) . ");var t1=performance.now();console.log(\"AJAX took \"+(t1 - t0)+\" ms.\");</script>".PHP_EOL,3,$LOG);
            if($encode) {
                return base64_encode(substr($return, 0, -1) . ");var t1=performance.now();console.log(\"AJAX took \"+(t1 - t0)+\" ms.\");</script>");
            } else {
                return substr($return, 0, -1) . ");var t1=performance.now();console.log(\"AJAX took \"+(t1 - t0)+\" ms.\");</script>";
            }
        } else if($this->error_text != "" || $this->error_text != null) {
            $return = "console.log('".$this->error_text."');var t1=performance.now();console.log(\"AJAX took \"+(t1 - t0)+\" ms.\");</script>";
            if ($clear) { $return = "<script>console.clear();" . $return; } else { $return = "<script>" . $return; }
            if ($encode) {
                return base64_encode($return);
            } else {
                return $return;
            }
        } else {
            $return = "console.log('nothing to display');var t1=performance.now();console.log(\"AJAX took \"+(t1 - t0)+\" ms.\");</script>";
            if ($clear) { $return = "<script>console.clear();" . $return; } else { $return = "<script>" . $return; }
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
    private $ROSTER_CREATOR = <<<EOT
    <style>
        .fixed-sub{position: absolute; height: 81px; width: 124px; background-color: white; z-index: 5; border-right: 0px;} 
        .rs-select2--trans .select2-container--default .select2-selection--single { background-color: transparent !important; }
        #loadr span .select2-selection {border-bottom: 1px solid grey;border-bottom-left-radius: 0px; border-bottom-right-radius: 0px;}
    </style> 
    <div class="section__content section__content--p30"> 
        <div class="container-fluid" id="content"> 
            <div class="col-md-12"> <div class="overview-wrap"> 
                <h2 class="title-1">Rosters</h2> 
            </div></br> 
        </div>
        <div class="row"> 
            <div class="col-12"> 
                <div class="card"> 
                    <div class="card-header"> 
                        <strong class="card-title">
                            <div class="col-6 float-right">
                                <div class="row">
                                    <div class="col-12">
                                        <span class="float-right" style="height: 20px;">
                                            <button id="loadr-button" style="padding: 2px 8px;" class="btn btn-primary btn-xs btn-block">Load</button>
                                        </span>
                                        <span class="float-right" style="margin-right:10px;">
                                            <div id="loadr" class="rs-select2--trans rs-select2--md">
                                                <select id="load-a-roster" class="js-select2 select2-hidden-accessible" style="border: 1px solid grey;max-width:5%;">
                                                    **_DATA_1_**
                                                </select>
                                                <span class="dropdown-wrapper" aria-hidden="true"></span>
                                            </div>
                                        </span>
                                    </div>
                                    <div class="col-12">
                                        <button id="savr" class="btn btn-primary btn-xs" style="float: right; margin-top: 5px; padding: 2px 8px;">
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                            Roster for week starting:
                            <span class="input-group date" style="width: 20%;" id="date" data-target-input="nearest"> 
                                <input id="date-input" type="text" placeholder="Week starting" class="form-control datetimepicker-input" data-target="#date"/> 
                                <span class="input-group-append" data-target="#date" data-toggle="datetimepicker"> 
                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span> 
                                </span> 
                            </span>
                        </strong> 
                        <script>$(function(){ $("#date").datetimepicker({format: "D/M/YYYY", daysOfWeekDisabled: [0,2,3,4,5,6]});}); </script> 
                    </div>
                    <div class="card-body"> 
                        <form id="roster-form" class="table-scroll" action="">
                            <div class="form-group table-responsive table--no-card m-b-30 table-wrap"> 
                                <table id="roster" for="roster-form" class="table table-earning main-table" align="left"> 
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
                                        **_TABLE_BODY_**
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <strong class="card-title"> Comments</strong>
                                    <div class="form-group"> 
                                        <textarea class="form-control" id="roster-comments" rows="4"></textarea>
                                    </div>
                                    <button id="submit-button" type="submit" form="roster-form" class="btn btn-primary btn-lg btn-block">Submit Roster</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
EOT;

    private $ROSTER_CREATOR_COLLAPSEABLE = <<<EOT
    <style>
        .fixed-sub{position: absolute; height: 81px; width: 124px; background-color: white; z-index: 5; border-right: 0px;} 
        .rs-select2--trans .select2-container--default .select2-selection--single { background-color: transparent !important; }
        #loadr span .select2-selection {border-bottom: 1px solid grey;border-bottom-left-radius: 0px; border-bottom-right-radius: 0px;}
    </style> 
    <div class="section__content section__content--p30">
        <div class="container-fluid" id="content">
            <div class="col-md-12">
                <div class="overview-wrap">
                    <h2 class="title-1">Rosters</h2>
                </div>
                </br> 
            </div>
            <div class="row" id="roster-creator-wrapper">
                <div class="col-lg-12">
                    <div class="card" style="margin-bottom: 10px;">
                        <div class="card-header">   
                            <div class="row">
                                <div class="col-12">
                                    <strong class="card-title">
                                        Create a new Roster:
                                        <span id="roster-show" name="creator" class="badge badge-primary float-right" style="cursor:pointer;margin-top:0.16rem;margin-right:1.3rem;" data-toggle="collapse" data-target="#roster-creator">Show&nbsp;<i class="fas fa-angle-down"></i></span>
                                    </strong>  
                                </div>
                            </div>
                        </div>
                        <div class="collapse" id="roster-creator">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <span class="input-group date" style="width: 40%;" id="date" data-target-input="nearest"> 
                                            <input id="date-input" type="text" placeholder="Week starting" class="form-control datetimepicker-input" data-target="#date"/> 
                                            <span class="input-group-append" data-target="#date" data-toggle="datetimepicker"> 
                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span> 
                                            </span>
                                        </span>
                                        <script>$(function(){ $("#date").datetimepicker({format: "D/M/YYYY", daysOfWeekDisabled: [0,2,3,4,5,6]});}); </script> 
                                    </div>
                                    <div class="col-6">
                                        <div class="row">
                                            <div class="col-12">
                                                <span class="float-right" style="height: 20px;">
                                                    <button id="loadr-button" style="padding: 2px 8px;" class="btn btn-primary btn-xs btn-block">Load</button>
                                                </span>
                                                <span class="float-right" style="margin-right:10px;">
                                                    <div id="loadr" class="rs-select2--trans rs-select2--md">
                                                        <select id="load-a-roster" class="js-select2 select2-hidden-accessible" style="border: 1px solid grey;max-width:5%;">
                                                            **_DATA_1_**
                                                        </select>
                                                        <span class="dropdown-wrapper" aria-hidden="true"></span>
                                                    </div>
                                                </span>
                                            </div>
                                            <div class="col-12">
                                                <button id="savr" class="btn btn-primary btn-xs" style="float: right; margin-top: 5px; padding: 2px 8px;">
                                                    Save
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div><br />
                                <form id="roster-form" class="table-scroll" action="">
                                    <div class="form-group table-responsive table--no-card m-b-30 table-wrap">
                                        <table id="roster" for="roster-form" class="table table-earning main-table" align="left">
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
                                                **_TABLE_BODY_**
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <strong class="card-title"> Comments</strong>
                                            <div class="form-group"> 
                                                <textarea class="form-control" id="roster-comments" rows="4"></textarea>
                                            </div>
                                            <button id="submit-button" type="submit" form="roster-form" class="btn btn-primary btn-lg btn-block">Submit Roster</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
EOT;
    private $ROSTER_MAIN_FOOT = "</tbody></table></div></div></div></div></div>"; //ends the ROW
    private $SELECT_TIME = "<option value=\"05:00 am\">05:00 am</option><option value=\"05:30 am\">05:30 am</option><option value=\"05:45 am\">05:45 am</option><option value=\"06:00 am\">06:00 am</option><option value=\"06:15 am\">06:15 am</option><option value=\"06:30 am\">06:30 am</option><option value=\"06:45 am\">06:45 am</option><option value=\"07:00 am\">07:00 am</option><option value=\"07:15 am\">07:15 am</option><option value=\"07:30 am\">07:30 am</option><option value=\"07:45 am\">07:45 am</option><option value=\"08:00 am\">08:00 am</option><option value=\"08:15 am\">08:15 am</option><option value=\"08:30 am\">08:30 am</option><option value=\"08:45 am\">08:45 am</option><option value=\"09:00 am\">09:00 am</option><option value=\"09:15 am\">09:15 am</option><option value=\"09:30 am\">09:30 am</option><option value=\"09:45 am\">09:45 am</option><option value=\"10:00 am\">10:00 am</option><option value=\"10:15 am\">10:15 am</option><option value=\"10:30 am\">10:30 am</option><option value=\"10:45 am\">10:45 am</option><option value=\"11:00 am\">11:00 am</option><option value=\"11:15 am\">11:15 am</option><option value=\"11:30 am\">11:30 am</option><option value=\"11:45 am\">11:45 am</option><option value=\"12:00 pm\">12:00 pm</option><option value=\"12:15 pm\">12:15 pm</option><option value=\"12:30 pm\">12:30 pm</option><option value=\"12:45 pm\">12:45 pm</option><option value=\"1:00 pm\">1:00 pm</option><option value=\"1:15 pm\">1:15 pm</option><option value=\"1:30 pm\">1:30 pm</option><option value=\"1:45 pm\">1:45 pm</option><option value=\"2:00 pm\">2:00 pm</option><option value=\"2:15 pm\">2:15 pm</option><option value=\"2:30 pm\">2:30 pm</option><option value=\"2:45 pm\">2:45 pm</option><option value=\"3:00 pm\">3:00 pm</option><option value=\"3:15 pm\">3:15 pm</option><option value=\"3:30 pm\">3:30 pm</option><option value=\"3:45 pm\">3:45 pm</option><option value=\"4:00 pm\">4:00 pm</option><option value=\"Close\">Close</option>";
    #endregion

    public function generateCreator(string $store_id, bool $pop = false, bool $collapse = false) {
        #region
        global $conn, $error, $LOG;
        $st = $conn->prepare("SELECT `uname`  FROM `staff` WHERE `store_id`='$store_id'");
        $st->execute();
        $array = $st->fetchAll(PDO::FETCH_COLUMN);
        $staffCount = count($array);
        $days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
        $ROSTER_CREATOR_BODY = "";
        try {
            $st = $conn->prepare("SELECT * FROM `saved` WHERE `type`='roster' AND `store_id`='$store_id' ORDER BY `date` DESC");
            $st->execute();
            $st->rowCount() > 0 ? $load = true : $load = false;
            $saved = $st->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $load = false;
            if($_SESSION['debug']) {
                $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            } else {
                error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL.PHP_EOL,3,$this->LOG);
            }
        }
        if($load) {
            $loadr = "<option disabled selected>Load a roster</option>";
            foreach($saved as $key => $value) {
                $loadr .= "<option value=\"" . $saved[$key]['id'] . "\">" . ucfirst($saved[$key]['poster']) ." " . date('d/m H:i', strtotime($saved[$key]['date'])) . "</option>";
            }
        } else {
            $loadr = "<option disabled selected>Load a roster</option><option value=\"none\" disabled>-- None --</option>";
        }
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
            for ($x = 0; $x < $staffCount; $x++) {
                $name = ucfirst($array[$x]);
                $ROSTER_CREATOR_BODY .= "<tr style=\"position:flex;\"><th class=\"fixed-sub\">" . $name . "</th>";
                for ($day = 0; $day < 7; $day++) {
                    $namestart = $days[$day] . '_' . ucfirst($array[$x]) . '_start';
                    $namefinish = $days[$day] . '_' . ucfirst($array[$x]) . '_finish';
                    $today_roster = json_decode($prev_roster[0][$days[$day]], true);
                    $pstime = $today_roster[$name]['start'];
                    $pftime = $today_roster[$name]['finish'];
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
                $ROSTER_CREATOR_BODY .= <<<EOT
                    <tr style="position:flex;">
                        <th class="fixed-sub">
                            $name
                        </th>
EOT;
                for ($day = 0; $day < 7; $day++) {
                    $namestart = $days[$day] . '_' . ucfirst($array[$x]) . '_start';
                    $namefinish = $days[$day] . '_' . ucfirst($array[$x]) . '_finish';
                    $ROSTER_CREATOR_BODY .= <<<EOT
                        <td id="$days[$day]" name="$days[$day]">
                            <div class="rs-select2--trans rs-select2--md"style="background-color: transparent;">
                                <select value="$name" name="$namestart" class="js-select2 select2-hidden-accessible"style="background-color: transparent; max-width:5%;">
                                    <option selected="selected"value="startTime">Start Time</option>
                                    $this->SELECT_TIME 
                                </select>
                                <select value="$name" name="$namefinish" class="js-select2 select2-hidden-accessible" style="background-color:transparent;">
                                    <option selected="selected" value="finishTime">Finish Time</option>
                                    $this->SELECT_TIME
                                </select>
                                <span class="dropdown-wrapper" style="background-color:transparent;" aria-hidden="true"></span>
                            </div>
                        </td>
EOT;
                }
            $ROSTER_CREATOR_BODY .= "</tr>";
            } 
        }
        if(!$collapse) {
            return strtr(
                    $this->ROSTER_CREATOR, 
                    array(
                        "**_DATA_1_**" => $loadr,
                        "**_TABLE_BODY_**" => $ROSTER_CREATOR_BODY
                    )
                );
        } else {
            return strtr(
                $this->ROSTER_CREATOR_COLLAPSEABLE, 
                array(
                    "**_DATA_1_**" => $loadr,
                    "**_TABLE_BODY_**" => $ROSTER_CREATOR_BODY
                )
            );
        }
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
        if(isset($options["JS"])) {
            $extra_js = $options["JS"];
        } else {
            $extra_js = "";
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
            $PREVIOUS_ROSTERS .= "<div id=\"" . $id . "\" class=\"row\"><div class=\"col-lg-12\"><div class=\"card\" style=\"margin-bottom: 10px;\"> <div class=\"card-header\"><span style=\"cursor:pointer;\" class=\"float-right\"> <a class=\"remove-roster\" name=\"" . $id . "\"> &nbsp&nbsp<i class=\"fas fa-times\"></i> </a> </span><span id=\"roster-show\" class=\"badge badge-primary float-right\" style=\"cursor:pointer;margin-top:0.16rem;\" data-toggle=\"collapse\" data-target=\"#roster-" . $id . "\" name=\"" . $id . "\">Show&nbsp;<i class=\"fas fa-angle-down\"></i></span><span class=\"badge badge-success\">Completed <i class=\"fas fa-check\"></i></span><span style=\"margin-left:0.5rem;\" class=\"badge badge-danger\">$poster</span><strong style=\"margin-left:0.5rem;\" class=\"card-title\">Roster for week starting: " . $date . "</strong></span></div><div class=\"collapse\" id=\"roster-" . $id . "\"><div class=\"card-body\"><div class=\"table-responsive table--no-card m-b-30\" style=\"margin-bottom: 0px;\"> <table id=\"roster\" class=\"table table-earning\" align=\"left\"> <thead align=\"left\"> <tr><th>Name</th><th>Monday</th><th>Tuesday</th> <th>Wednesday</th> <th>Thursday</th> <th>Friday</th> <th>Saturday</th> <th>Sunday</th> </tr></thead> <tbody align=\"left\">";
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
            $PREVIOUS_ROSTERS .= $body . "</tr></tbody></table></div></div></div></div></div></div>";
        }
        $PREVIOUS_ROSTER_FOOT = <<<EOT
                </div>
            <script>
                $(function(){ 
                    $("#roster-form").submit(function(j){
                        j.preventDefault();
                        var k={};
                        var e=$("#roster-form").serializeArray();
                        var i=e.length,n,m;
                        for(n=0;n<i;n++){
                            var l=e[n].name.split("_");
                            m=k;
                            while(l.length){
                                key=l.shift();
                                if(l.length){
                                    if(typeof m[key]==="undefined"){
                                        m[key]={}
                                    }
                                    m=m[key]
                                }
                            }
                            m[key]=e[n].value
                        }
                        k.startDate=document.getElementById("date-input").value;
                        k.comments=document.getElementById("roster-comments").value;
                        $.ajax({
                            type:"POST",
                            url:"backend/ajax/rostersfunc.php",
                            dataType:"json",
                            data:{message:"UPDATE_ROSTER",data:JSON.stringify(k)},
                            success:function(a){
                                if(!a.success){
                                    console.log(a);
                                    $("#submit-button").prop("class","btn btn-danger btn-lg btn-block");
                                    $("#submit-button").text("Failed to submit")
                                }else{
                                    if("errors" in a){ 
                                        $("#error").html(window.atob(a.errors))
                                    }
                                    $("#submit-button").prop("class","btn btn-success btn-lg btn-block");
                                    $("#submit-button").text("Roster Submitted!");
                                    update("rostersfunc");
                                }
                            },
                            error:function(){ 
                                $("#submit-button").prop("class","btn btn-danger btn-lg btn-block");
                                $("#submit-button").text("Failed to submit")
                            }
                        })
                    });
                    $(".js-select2").select2({minimumResultsForSearch:-1});
                    $(".remove-roster").on("click",function(){
                        var b=$(this).attr("name");
                        $("#"+b).hide("slide", {direction: "right"}, 300,function(){ $("#"+b).remove(); update("rostersfunc");});
                        $.ajax({
                            type:"POST",
                            url:"backend/ajax/rostersfunc.php",
                            dataType:"json",
                            data:{message:"REMOVE_ROSTER",data:b}
                        });
                    });
                    $("#content").on("click","#roster-show",function(){
                        var b=$(this).attr("name");
                        if($("#roster-"+b).prop("class")=="collapse"){ 
                            $(this).html('Hide <i class="fas fa-angle-up"></i>')
                        }else{
                            if($("#roster-"+b).prop("class")=="collapse show"){ 
                                $(this).html('Show <i class="fas fa-angle-down"></i>')
                            }
                        }
                    });

                    $("#loadr-button").on('click', function() {
                        $.ajax({
                            type : "GET",
                            url : "backend/ajax/rostersfunc.php",
                            dataType : "text",
                            data : {
                                message:"REQ_SAVED",
                                data : {
                                    id : $("#load-a-roster").val()
                                }
                            },
                            success: function(a) {
                                a = JSON.parse(a);
                                if(!a.success){
                                    console.log(a);
                                }else{
                                    if("errors" in a){ 
                                        $("#error").html(window.atob(a.errors))
                                    }
                                    var roster = JSON.parse(window.atob(a.data));
                                    var staffCount = 0;
                                    var staff = [];
                                    for (var key in roster['monday']) {
                                        staff.push(key);
                                        staffCount++;
                                    }
                                    var days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
                                    var name, namestart, namefinish, todays_roster, start_time, finish_time;
                                    for(var i = 0; i < staffCount; i++) {
                                        name = staff[i];
                                        for(var day = 0; day < 7; day++) {
                                            namestart = days[day] + '_' + name.replace(/^\w/, c => c.toUpperCase()) + '_start';
                                            namefinish = days[day] + '_' + name.replace(/^\w/, c => c.toUpperCase()) + '_finish';
                                            todays_roster = roster[days[day]];
                                            start_time = todays_roster[name]['start'];
                                            finish_time = todays_roster[name]['finish'];
                                            $("[name="+namestart+"]").val(start_time);
                                            $("[name="+namefinish+"]").val(finish_time);
                                        }
                                        $("[name*="+name+"]").trigger('change');
                                    }
                                    $(this).html("&nbsp;&nbsp;<i class=\"fas fa-check\">&nbsp;&nbsp;<i>"); 
                                }
                            }
                        });
                    });
                    $("#savr").on('click',function(j){
                        var k={}, e=$("#roster-form").serializeArray(),i=e.length,n,m;
                        for(n=0;n<i;n++){var l=e[n].name.split("_");m=k;while(l.length){key=l.shift();if(l.length){if(typeof m[key]==="undefined"){m[key]={}}m=m[key]}}m[key]=e[n].value}
                        k.startDate=document.getElementById("date-input").value;
                        k.comments=document.getElementById("roster-comments").value;
                        $.ajax({
                            type:"POST",
                            url:"backend/ajax/rostersfunc.php",
                            dataType:"json",
                            data:{message:"SAVE_ROSTER",data:JSON.stringify(k)},
                            success:function(a){
                                if(!a.success){
                                    console.log(a);
                                } else {
                                    if("errors" in a){ 
                                        $("#error").append(window.atob(a.errors));
                                    }
                                    if("option_data" in a) {
                                        var data = a.option_data;
                                        var newOption = new Option(data.text, data.id, false, false);
                                        $('#load-a-roster option:first-child').after(newOption).trigger('change');
                                        $('#load-a-roster').find("option[value=none]").remove();
                                    }
                                }
                            },
                            error:function(){ 
                                
                            }
                        })
                    });
                });
                $extra_js
                </script>
EOT;
        return $PREVIOUS_ROSTERS . $PREVIOUS_ROSTER_FOOT;
        #endregion
    }

    public function generateUser(string $user, string $store_id, string $req = "all", $options = []) {
        #region
        global $conn, $error;
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
            $count = $st->rowCount();        
            $prev_roster = $st->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            if($_SESSION['debug']) {
                $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            } else {
                error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL.PHP_EOL,3,$this->LOG);
            }        
        }
        if($count > 0) {     
            #region   
            $days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
            $ROSTER_MAIN_BODY = "";
            for ($x = 0; $x < $staffCount; $x++) {
                $name = ucfirst($array[$x]);
                ($array[$x] == $user) ? $ROSTER_MAIN_BODY .= "<tr class=\"personal\"><td style=\"font-weight: bold; cursor: default;\">" . $name . "</td>" : $ROSTER_MAIN_BODY .= "<tr><td style=\"font-weight: bold;\">" . $name . "</td>";
                $namestart = ucfirst($array[$x]) . '_start';
                $namefinish = ucfirst($array[$x]) . '_finish';
                for ($day = 0; $day < 7; $day++) {
                    $tday = $days[$day];
                    $roster = json_decode($prev_roster[0][$tday], TRUE);
                    $pstime = $roster[$name]['start'];
                    $pftime = $roster[$name]['finish'];
                    ($pstime != "startTime") ? $ROSTER_MAIN_BODY .= "<td><p>" . $pstime . "</p>" : $ROSTER_MAIN_BODY .= "<td><p>Not working</p>";
                    ($pftime != "finishTime") ? $ROSTER_MAIN_BODY .= "<p>" . $pftime . "</p></td>" : $ROSTER_MAIN_BODY .= "</td>";
                }
                $ROSTER_MAIN_BODY .= "</tr>";
            }
            $date = $prev_roster[0]["date_from"];
            $date = date("d/m/Y", strtotime($date));
            isset($prev_roster[0]["comments"]) ? $COMMENTS = "<div id=\"comments\" class=\"comments\">" . $prev_roster[0]["comments"] . "</div><br/>" : $COMMENTS = "";
            ($options["BLUR_OTHER_ROSTERS"] === true) ? $css_head = "<style>td,th{padding:10px 15px;position:relative}table{box-shadow:inset 0 1px 0 #fff}th{background:url(https://jackrugile.com/images/misc/noise-diagonal.png),linear-gradient(#777,#444);box-shadow:inset 0 1px 0 #999;color:#fff;font-weight:700;text-shadow:0 1px 0 #000}th:after{background:linear-gradient(rgba(255,255,255,0),rgba(255,255,255,.08));content:'';display:block;height:25%;left:0;margin:1px 0 0;position:absolute;top:25%;width:100%}th:first-child{box-shadow:inset 1px 1px 0 #999}th:last-child{box-shadow:inset -1px 1px 0 #999}td{transition:all .3s}td:first-child{box-shadow:inset 1px 0 0 #fff}td:last-child{box-shadow:inset -1px 0 0 #fff}tr{background:url(https://jackrugile.com/images/misc/noise-diagonal.png)}tr:nth-child(odd) td{background:url(https://jackrugile.com/images/misc/noise-diagonal.png) #f1f1f1}tr:last-of-type td{box-shadow:inset 0 -1px 0 #fff}tr:last-of-type td:first-child{box-shadow:inset 1px -1px 0 #fff}tr:last-of-type td:last-child{box-shadow:inset -1px -1px 0 #fff}tbody:hover td{color:transparent;text-shadow:0 0 3px #aaa}tbody:hover tr:hover td{color:#444;text-shadow:0 1px 0 #fff}</style>" : $css_head = "";
            $ROSTER_MAIN_HEAD = <<<EOT
            $css_head
            <div class="section__content section__content--p30">
                <div class="container-fluid" id="content">
                    <div class="col-md-12">
                        <div class="overview-wrap">
                            <h2 class="title-1">Rosters</h2>
                        </div>
                        </br> 
                    </div>
                    <div class="row">
                        <div class="col-lg-12" style="cursor: default;">
                            <div class="card">
                                <div class="card-header">
                                    <strong class="card-title">Roster for week starting $date</strong>
                                </div>
                                <div class="card-body"> $COMMENTS
                                    <div class="table-responsive table--no-card m-b-30 table-wrap" style="margin-bottom: 0px;">
                                        <table id="roster" class="table table-earning main-table" align="left">
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
                                            <tbody align="left" style="cursor: default;">
EOT;
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
                    ($array[$i] == $user) ? $body .= "<tr><td style=\"border-left:2px solid rgba(66, 114, 215, 0.8);\">" . $nname . "</td>" : $body .= "<tr><td>" . $nname . "</td>";                     
                    for ($x = 0; $x < 7; $x++) {
                        $data = json_decode($value[$days[$x]], true);
                        $name_start = $data[$nname]["start"]; //e.g. 
                        $name_finish = $data[$nname]["finish"];
                        if($name_start == "startTime" || $name_finish == "finishTime") {
                            $body .= "<td><div>Not working</td>";
                        } else {
                            $body .= "<td><div>" . $name_start . "</div><div>" . $name_finish . "</div></td>";
                        }
                    }
                    $body .= "</tr>";
                }
                $USER_PREVIOUS_ROSTERS .= $body . "</tbody></table></div></div></div></div></div>";
            }

            if($req === "all") {
                return $ROSTER_MAIN_HEAD. $ROSTER_MAIN_BODY . $this->ROSTER_MAIN_FOOT . $USER_PREVIOUS_ROSTERS . "</div></div>"; //the 2 end-divs are for section content and container fluid :)
            } else if ($req === "email") {
                $header_string = "Stones Throw Cafe";
                $padding_string = "";
                if(strlen($header_string) < 150) {
                    $x = 150 - strlen($header_string);
                    for($i = 0; $i < $x; $i++) {
                        $padding_string .= "&nbsp;&zwnj;";
                    }
                }
                $return = <<<EOT
                <div style="display: none; max-height: 0px; overflow: hidden;">
                    $header_string
                </div>
                <div style="display: none; max-height: 0px; overflow: hidden;">
                    $padding_string;
                </div>
                <div>
                    $COMMENTS
                </div>
                <br/>
                <div class="table-responsive table--no-card m-b-30">
                    <table id="roster" class="table table-borderless table-striped table-earning" style="border-radius:5px;border-collapse:collapse;" align="left">
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
                        <tbody align="left" style="cursor: default;">
                            $ROSTER_MAIN_BODY
                        </tbody>
                    </table>
                </div>
EOT;
                return $return;
            }
        #endregion
        } else {
            #region NO ROSTERS AVAILABLE
            $html = <<<EOT
            <div class="section__content section__content--p30"> 
                <div class="container-fluid" id="content"> 
                    <div class="col-md-12"> <div class="overview-wrap"> 
                        <h2 class="title-1">Rosters</h2> 
                    </div></br> 
                </div><br/><br/><br/><br/>
                <div class="row"> 
                    <div class="col-12"> 
                        <div class="d-flex justify-content-center">
                            No rosters currently available&nbsp;<i class="far fa-frown" style="margin-top: 0.3rem;" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
EOT;
            return $html;
            #endregion
        }
        #endregion
    }

}

class page {
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
    
    public function generateNoticePage(string $store_id, string $user, array $options = null, bool $notices_only = false) {
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
        $body = "";
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
            if(!$notices_only) {
                if($user_priv['edit'] === "true") {
                    $body = "<div id=\"" . $id . "\" class=\"row\"> <div class=\"col-12\"> <div class=\"card\" id=\"card-" . $id . "\"> <div class=\"card-header\"> <div class=\"row\"> <div class=\"col-8\"> <strong class=\"card-title\">" . $title . "</strong> </div><div class=\"col-4\"> <span style=\"cursor:pointer;\" class=\"float-right\"> <a id=\"remove-notice\" name=\"" . $id . "\"> &nbsp;&nbsp;<i class=\"fas fa-times\"></i> </a> </span> <strong> <span class=\"" . $badge . " float-right mt-1\">" . $poster . "</span> </strong> </div></div><div class=\"row\"> <div class=\"col-lg-12 col-md-8 col-sm-6 col-xs-3\"> <span> <small>" . $since . "</small> </span> </div></div></div><div class=\"card-body\"> <div class=\"row\"> <div id=\"post-card-" . $id . "\" class=\"col-lg-12 col-md-8 col-sm-6 col-xs-3\">" . $content . "</div></div></div></div></div></div>" . $body;
                } else {
                    $body = "<div id=\"" . $id . "\" class=\"row\"> <div class=\"col-12\"> <div class=\"card\" id=\"card-" . $id . "\"> <div class=\"card-header\"> <div class=\"row\"> <div class=\"col-8\"> <strong class=\"card-title\">" . $title . "</strong> </div><div class=\"col-4\"> <strong> <span class=\"" . $badge . " float-right mt-1\">" . $poster . "</span> </strong> </div></div><div class=\"row\"> <div class=\"col-lg-12 col-md-8 col-sm-6 col-xs-3\"> <span> <small>" . $since . "</small> </span> </div></div></div><div class=\"card-body\"> <div class=\"row\"> <div id=\"post-card-" . $id . "\" class=\"col-lg-12 col-md-8 col-sm-6 col-xs-3\">" . $content . "</div></div></div></div></div></div>" . $body;
                }
            } else { //Notices only, don't need to end the 3 header divs
                if($user_priv['edit'] === "true") {
                    $body = "<div id=\"" . $id . "\" class=\"row\"> <div class=\"col-12\"> <div class=\"card\" id=\"card-" . $id . "\"> <div class=\"card-header\"> <div class=\"row\"> <div class=\"col-8\"> <strong class=\"card-title\">" . $title . "</strong> </div><div class=\"col-4\"> <span style=\"cursor:pointer;\" class=\"float-right\"> <a id=\"remove-notice\" name=\"" . $id . "\"> &nbsp;&nbsp;<i class=\"fas fa-times\"></i> </a> </span> <strong> <span class=\"" . $badge . " float-right mt-1\">" . $poster . "</span> </strong> </div></div><div class=\"row\"> <div class=\"col-lg-12 col-md-8 col-sm-6 col-xs-3\"> <span> <small>" . $since . "</small> </span> </div></div></div><div class=\"card-body\"> <div class=\"row\"> <div id=\"post-card-" . $id . "\" class=\"col-lg-12 col-md-8 col-sm-6 col-xs-3\">" . $content . "</div></div></div>" . $body;
                } else {
                    $body = "<div id=\"" . $id . "\" class=\"row\"> <div class=\"col-12\"> <div class=\"card\" id=\"card-" . $id . "\"> <div class=\"card-header\"> <div class=\"row\"> <div class=\"col-8\"> <strong class=\"card-title\">" . $title . "</strong> </div><div class=\"col-4\"> <strong> <span class=\"" . $badge . " float-right mt-1\">" . $poster . "</span> </strong> </div></div><div class=\"row\"> <div class=\"col-lg-12 col-md-8 col-sm-6 col-xs-3\"> <span> <small>" . $since . "</small> </span> </div></div></div><div class=\"card-body\"> <div class=\"row\"> <div id=\"post-card-" . $id . "\" class=\"col-lg-12 col-md-8 col-sm-6 col-xs-3\">" . $content . "</div></div></div></div></div></div>" . $body;
                }
            }
        }
        $uppername = ucfirst($user);
        if(!isset($options["NOTICE_UPDATE_ALERT_TITLE"])) {
            $NOTICE_UPDATE_ALERT_TITLE = $uppername . " has created a notice.";
        }
        if(!(isset($options["NOTICE_UPDATE_ALERT_CONTENT"]))) {
            $NOTICE_UPDATE_ALERT_CONTENT = "b.title"; //b.title is for the minified JS. 'b' is subject to change!
        } else {
            $NOTICE_UPDATE_ALERT_CONTENT = "'" . $NOTICE_UPDATE_ALERT_CONTENT . "'"; //used in the event that we use a string as the code is escaped for the use of object.title (needs to be escaped otherwise it'll be strung)
        }
        if(!$notices_only) {
            if ($user_priv['edit'] === "true" && $user_priv['post'] === "true") {
                $html .= $body . $MODAL . "</div></div></div>" . "<script> $(document).ready(function(){ $(\"button#submit\").click(function(c){c.preventDefault();var a=$(\"#newpost\").serializeArray();var b={};$(a).each(function(d,e){b[e.name]=e.value});$.ajax({type:\"POST\",url:\"backend/ajax/noticesfunc.php\",dataType:\"json\",data:{message:\"NOTICE_POST\",data:window.btoa(JSON.stringify(b))},success:function(f){var g={proto:\"UP_NOTI\"},d={proto:\"SN_NOTI\",title:\"" . $NOTICE_UPDATE_ALERT_TITLE . "\",body:" . $NOTICE_UPDATE_ALERT_CONTENT . "};window.client.send(JSON.stringify(g));window.client.send(JSON.stringify(d))},error:function(d){}});$(\"#newPostModal\").modal(\"hide\")});$(\"body\").on(\"click\",\"#remove-notice\",function(a){var b=this.name;$(\"div#\"+b).remove();$.ajax({type:\"POST\",url:\"backend/ajax/noticesfunc.php\",dataType:\"json\",data:{message:\"NTC_RM\",value:b},success:function(c){},error:function(c){disError(\"Failed to remove notice.\",true)}})})});</script>" . "</div>";
            } else {
                $html .= $body . "</div></div></div></div>";
            }
        } else {
            $html = $body;
        }
        return $html;
        #endregion
    }

    public function generateRosterPage(string $store_id, string $user, bool $populate = false, $date = 'next monday') {
        #region
        global $conn, $LOG, $error;
        $roster = new roster;
        try {
            $st = $conn->prepare("SELECT * FROM `staff` WHERE `uname`='$user' AND `store_id`='$store_id'");
            $st->execute();
            $user_data = $st->fetchAll(PDO::FETCH_ASSOC);
            $user_data = $user_data[0];
            $user_priv = json_decode(stripslashes($user_data["rights"]),true);
        } catch (PDOException $e) {
            if($_SESSION['debug']) {
                $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            } else {
                error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
            }
            return base64_encode($error->generate());
        }
        if ($user_priv['rosters'] === "true") {
            $check = $this->check_roster($store_id, $date);
            $days_left = $this->days_until($date);
            $noti = new notification("sidenav-bubble",$user,$store_id,"rosters");
            $count = $noti->count;
            $js = "$(\"#roster-noti\").text(\"".$count."\");$(\"#roster-noti\").prop(\"class\",\"quantity\")";
            if ($check === false && $days_left < 3) {
                try {
                    $RosterData = $roster->generateCreator($store_id, $populate) . $roster->generatePrevious($store_id, ["JS"=>$js]);
                    return base64_encode($RosterData);
                } catch (Exception $e) {
                    if($_SESSION['debug']) {
                        $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                    } else {
                        error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
                    }
                    return base64_encode($error->generate());
                }
            }
            else if ($check === false && $days_left > 3 ) {
                $RosterData = $roster->generateCreator($store_id, $populate) . $roster->generatePrevious($store_id, ["JS"=>$js]);
                return base64_encode($RosterData);
            }
            else if ($check === true) {
                if($count > 0) {
                    $count -= 1;
                } else {
                    $count = 0;
                }
                if ($count == 0) {
                    $js = "$(\"#roster-noti\").text(\"0\");$(\"#roster-noti\").prop(\"class\",\"invisible quantity\");";
                } else {
                    $js ="$(\"#roster-noti\").text(\"$count\");$(\"#roster-noti\").prop(\"class\",\"quantity\");";
                }
                try {
                    $RosterData = $roster->generateCreator($store_id, $populate, true) . $roster->generatePrevious($store_id,["JS"=>$js]);
                    return base64_encode($RosterData);
                } catch (PDOException $e) {
                    if($_SESSION['debug']) {
                        $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                    } else {
                        error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
                    }
                    return base64_encode($error->generate());
                }
            }
        } else {
            $settings = json_decode($user_data["settings"],true);
            ($settings["blur_rosters"] === "true" || $settings["blur_rosters"] === true) ? $blur_check = true : $blur_check = false;
            return base64_encode($roster->generateUser($user, $store_id, "all", ["BLUR_OTHER_ROSTERS"=>$blur_check]));
        }
        #endregion
    }

    public function generateStaffSettingsPage() {
        #region
        //stuff
        return base64_encode($data);
        #endregion
    }

    public function generateAdminSettingsPage($page) {
        #region
        if($page == "dashboard") {

        } else if ($page == "users") {
            $select = "<option selected=\"selected\">Finish time</option> <option value=\"05:00 am\">05:00 am</option> <option value=\"05:30 am\">05:30 am</option> <option value=\"05:45 am\">05:45 am</option> <option value=\"06:00 am\">06:00 am</option> <option value=\"06:15 am\">06:15 am</option> <option value=\"06:30 am\">06:30 am</option> <option value=\"06:45 am\">06:45 am</option> <option value=\"07:00 am\">07:00 am</option> <option value=\"07:15 am\">07:15 am</option> <option value=\"07:30 am\">07:30 am</option> <option value=\"07:45 am\">07:45 am</option> <option value=\"08:00 am\">08:00 am</option> <option value=\"08:15 am\">08:15 am</option> <option value=\"08:30 am\">08:30 am</option> <option value=\"08:45 am\">08:45 am</option> <option value=\"09:00 am\">09:00 am</option> <option value=\"09:15 am\">09:15 am</option> <option value=\"09:30 am\">09:30 am</option> <option value=\"09:45 am\">09:45 am</option> <option value=\"10:00 am\">10:00 am</option> <option value=\"10:15 am\">10:15 am</option> <option value=\"10:30 am\">10:30 am</option> <option value=\"10:45 am\">10:45 am</option> <option value=\"11:00 am\">11:00 am</option> <option value=\"11:15 am\">11:15 am</option> <option value=\"11:30 am\">11:30 am</option> <option value=\"11:45 am\">11:45 am</option> <option value=\"12:00 pm\">12:00 pm</option> <option value=\"12:15 pm\">12:15 pm</option> <option value=\"12:30 pm\">12:30 pm</option> <option value=\"12:45 pm\">12:45 pm</option> <option value=\"1:00 pm\">1:00 pm</option> <option value=\"1:15 pm\">1:15 pm</option> <option value=\"1:30 pm\">1:30 pm</option> <option value=\"1:45 pm\">1:45 pm</option> <option value=\"2:00 pm\">2:00 pm</option> <option value=\"2:15 pm\">2:15 pm</option> <option value=\"2:30 pm\">2:30 pm</option> <option value=\"2:45 pm\">2:45 pm</option> <option value=\"3:00 pm\">3:00 pm</option> <option value=\"3:15 pm\">3:15 pm</option> <option value=\"3:30 pm\">3:30 pm</option> <option value=\"3:45 pm\">3:45 pm</option> <option value=\"4:00 pm\">4:00 pm</option>";
        $UserSettings = <<<EOT
<div class="section__content section__content--p30">
	<div class="container-fluid">
	    <div class="row">
    		<div class="col-lg-12">
    			<div class="card">
    				<div class="card-header">
    					<strong>Add Staff Member</strong>
    				</div>
        			<div class="card-body card-block">
        				<div class="row">
    						<div class="col-6">
    							<form action="" id="add-user" method="post" class="form-horizontal">
    							<div class="row form-group">
    								<div class="col col-md-3">
    									<label for="name" class=" form-control-label">First name</label>
    								</div>
    								<div class="col col-md-6">
    									<input type="text" id="name" name="name" placeholder="Enter first name" class="form-control">
    								</div>
    							</div>
    							<div class="row form-group">
    								<div class="col col-md-3">
    									<label for="email" class=" form-control-label">Email</label>
    								</div>
    								<div class="col col-md-6">
    									<input type="email" id="email" name="email" placeholder="Enter email" class="form-control">
    								</div>
    							</div>
    							<div class="row form-group">
    								<div class="col col-md-3">
    									<label for="password" class=" form-control-label">Password</label>
    								</div>
    								<div class="col col-md-6">
    									<input type="password" id="password" name="password" placeholder="Enter a password" class="form-control">
    								</div>
    							</div>
    							<div class="row form-group">
    								<div class="col col-md-3">
    									<label for="password-ver" class=" form-control-label"></label>
    								</div>
    								<div class="col col-md-6">
    									<input type="password" id="password-ver" name="password-ver" placeholder="Re-enter password" class="form-control">
    								</div>
    							</div>
    							<div class="row form-group">
    								<div class="col col-md-3">
    									<label for="rate" class=" form-control-label">Hourly rate</label>
    								</div>
    								<div class="col col-md-6">
    									<input type="text" id="rate" name="rate" placeholder="$/hr" class="form-control">
    								</div>
    							</div>
    							<div class="row form-group">
    								<div class="col col-md-3">
    									<label for="rate" class=" form-control-label">Access</label>
    								</div>
    								<div class="col col-md-6">
    									<select name="access" id="access" class="form-control">
    										<option value="0">Access</option>
    										<option value="1">Staff</option>
    										<option value="2">Supervisor</option>
    										<option value="3">Admin</option>
    									</select>
    								</div>
    							</div>
    						</form>
        					</div>
    						<div class="col-6">
        						<div class="row form-group">
        							<div class="col col-lg-2">
        							    <label for="availability" form-control-label">Availabilities</label>
        							</div>
        							<div class="col col-lg-8" style="margin-top: -1.8%;">
        							    <div class="table-responsive">
                                            <table id="availability" class="table table-borderless">
                                                <tbody>
EOT;
                                            $day = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
                                            for ($x = 0; $x < 7; $x++) {
                                            	$today = str_replace('"', "'",$day[$x]);
                                            	$uday = ucfirst($today);
                                            	$uday = str_replace('"', "'",$uday);
                                            	$UserSettings = $UserSettings . <<<EOT
                                            	<tr id="$today">
                                            		<th scope="row" id="$today">$uday</th>
                                            		<td>
                                            		    <div class="rs-select2--trans rs-select2--md">
                                                        	<select name="Start" class="js-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                                        		$select
                                                        	</select>
                                                            <span class="dropdown-wrapper" aria-hidden="true"></span>
                                                        </div>
                                                        <div class="rs-select2--trans rs-select2--md">
                                                        	<select name="Finish" class="js-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                                        		$select
                                                        	</select>
                                                        </div>
                                            		</td>
                                            	</tr>
EOT;
                                            }
                                                $UserSettings .= <<<EOT
                                                </tbody>
                                            </table>
                                        </div>
        							</div>
        						</div>
        					</div>
    					</div>
    				</div>
    				<div class="card-footer">
    				    <div class="col-12" >
    				        <div class="row" style="display: flex; align-items: center;">
    				            <div class="col-lg-4" id="staffFormStatus" style="margin-top: 1%;"></div>
				                <div class="col-lg-8">
    				                <div style="float:right;">
    						            <button form="add-user" type="submit" class="btn btn-primary btn-sm">Submit</button>
    						        </div>
    						    </div>
				            </div>
    					</div>
					</div>
    			</div>
    		</div>
    	</div>
    	<div class="row">
        	<div class="col-lg-6">
        		<div class="card">
        			<div class="card-header">
        				<strong>Staff Settings</strong>
        			</div>
        			<div class="card-body card-block">
        				<div class="table-responsive table-data">
        					<table class="table">
        						<thead>
                                	<tr>  
                                		<td>Name</td>
                                		<td>Role</td>
                                		<td>Action</td>
                                	</tr>
                                </thead>
EOT;
                                $st = $conn->prepare("SELECT `uname`  FROM `staff`");
                                $st->execute();
                                $array = $st->fetchAll(PDO::FETCH_COLUMN);
                                $staffCount = count($array);
                                
                                if($staffCount != 0) {
                                	for ($x = 0; $x < $staffCount; $x++) {
                                		$name = ucfirst($array[$x]);
                                		$st = $conn->prepare("SELECT `email`  FROM `staff` WHERE `uname`='$name'");
                                		$st->execute();
                                		$email = $st->fetchColumn();
                                		$st = $conn->prepare("SELECT `rights`  FROM `staff` WHERE `uname`='$name'");
                                        $st->execute();
                                        $priv = json_decode(stripslashes($st->fetchColumn()),true);
                                		if ($priv['level'] == 3) {
                                			$priv_text = "Admin";
                                			$class = "role admin";
                                		} else if ($priv['level'] == 2) {
                                			$priv_text = "Supervisor";
                                			$class = "role member";
                                		} else if ($priv['level'] == 1) {
                                			$priv_text = "Staff";
                                			$class = "role user";
                                		} else {
                                			$priv_text = "N/A";
                                		}
                                		$UserSettings = $UserSettings . "<tr><td><div class=\"table-data__info\"><h6>$name</h6><span><a href=\"#\">$email</a></span></div></td><td><span class=\"$class\">$priv_text</span></td><td>" . <<<EOT
                                		<div class="rs-select2--trans rs-select2--lg">
                                					<select class="js-select2 select2-hidden-accessible" name="property" tabindex="-1" aria-hidden="true">
EOT;
                                        $UserSettings = $UserSettings . "<option value=\"action\">Select action</option><option value=\"email\">Send email</option><option value=\"promote\">Promote</option><option value=\"demote\">Demote</option><option value=\"password\">Set password</option><option value=\"change email\">Set email</option><option value=\"remove\">Remove</option>" . <<<EOT
                                					</select>
                                				</div>
                                			</td>
                                		</tr>
EOT;
                                	}
                                }
                            $UserSettings .= <<<EOT
        						</tbody>
        					</table>
        				</div>
        			</div>
        			<div class="card-footer" style="text-align:right;">
    					<button form="add-user" type="submit" class="btn btn-primary btn-sm">
    						Submit
    					</button>
    				</div>
        		</div>
        	</div>
        </div>
	</div>
</div>
<script>
    $(document).ready(function(){
        var avail = {"monday": {}, "tuesday": {}, "wednesday": {}, "thursday": {}, "friday": {}, "saturday": {}, "sunday": {}};
        $("select[name='Start'], select[name='Finish']").on("change", function() {
            if (this.id == "access") {
                //chillin
            } else {
                var day = $(this).closest('tr').attr('id');
                if (day == "monday") {
                    avail.monday[this.id] = this.value;
                } else if (day == "tuesday") {
                    avail.tuesday[this.id] = this.value;
                } else if (day == "wednesday") {
                    avail.wednesday[this.id] = this.value;
                } else if (day == "thursday") {
                    avail.thursday[this.id] = this.value;
                } else if (day == "friday") {
                    avail.friday[this.id] = this.value;
                } else if (day == "saturday") {
                    avail.saturday[this.id] = this.value;
                } else if (day == "sunday") {
                    avail.sunday[this.id] = this.value;
                } else {
                    console.log('some form of issue lmao cya');
                }
            }
        });
        $("#add-user").submit(function(e) {
            e.preventDefault();
            var data = $('#add-user').serializeArray().reduce(function(obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});
            $.ajax({
                type: 'POST',
                url: 'backend/ajax/settingsfunc.php',
                dataType: 'json',
                data:
                    {
                        message: "ADD_USER",
                        data: JSON.stringify(data)
                    },
                success: function(result){}, 
                error: function(){}
            });
        });
        $(document).ready(function() {
            $('.js-select2').select2({
                minimumResultsForSearch: -1
            });
        });
    });
</script>

EOT;
        } else if ($page == "notices") {

        } else if ($page == "rosters") {
            #region
            $st = $conn->prepare("SELECT `roster_auto_populate` FROM `settings` WHERE `id`=1");
            $st->execute();
            $populate = $st->fetchColumn();
            $st = $conn->prepare("SELECT `email_roster` FROM `settings`");
            $st->execute();
            $email_staff = $st->fetchColumn();
            
            if($populate != 0) {
                $populate_status = " alert-success";
                $populate_switch =  " checked=\"true\"";
            } else {
                $populate_status = " alert-danger";
                $populate_switch = " unchecked=\"true\"";
            }
            
            if($email_staff != 0) {
                $email_status = " alert-success";
                $email_switch = " checked=\"true\"";
            } else {
                $email_status = " alert-danger";
                $email_switch = " unchecked=\"true\"";
            }
            
            $html = <<<EOT
            <div class="section__content section__content--p30">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    <strong>Email settings</strong>
                                </div>
                                <div class="card-body">
                                    <div class="col-lg-12">
                                        <div class="row" style="display: block;">
                                            <div id="emailStaff" class="alert $email_status" role="alert">
                                                        Email rosters to staff
                                                <label class="switch switch-3d switch-success mr-3" style="float: right;">
                                                    <input id="emailStaff" type="checkbox" class="switch-input" $email_switch >
                                                    <span class="switch-label"></span>
                                                    <span class="switch-handle"></span>
                                                </label>
                                            </div>
                                            <div id="prevRoster" class="alert $populate_status" role="alert">
                                                        Load previous roster
                                                <label class="switch switch-3d switch-success mr-3" style="float: right;">
                                                    <input id="prevRoster" type="checkbox" class="switch-input" $populate_switch >
                                                    <span class="switch-label"></span>
                                                    <span class="switch-handle"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    $("input#emailStaff").change(function(e) {
                        $.ajax({
                            type: 'GET',
                            url: 'backend/ajax/settingsfunc.php',
                            dataType: 'text',
                            data: {
                                message: 'SET_EML_STAFF_ROS'
                            },
                            success: function(result) {
                                if(result.success && result.value == "enabled") {
                                    $("div#emailStaff").removeClass("alert-danger").addClass("alert-success");
                                } else {
                                    $("div#emailStaff").removeClass("alert-success").addClass("alert-danger");
                                }
                            },
                            error: function(result) {}
                        });
                    });
                    
                    $("input#prevRoster").change(function(e) {
                        $.ajax({
                            type: 'GET',
                            url: 'backend/ajax/settingsfunc.php',
                            dataType: 'text',
                            data: {
                                message: 'SET_ROS_PRE'
                            },
                            success: function(result) {
                                result = JSON.parse(result);
                                if(result.success && result.value == "enabled") {
                                    $("div#prevRoster").removeClass("alert-danger").addClass("alert-success");
                                } else {
                                    $("div#prevRoster").removeClass("alert-success").addClass("alert-danger");
                                }
                            },
                            error: function(result) {}
                        });
                    });
                </script>
            </div>
EOT;
            #endregion
        } else if ($page == "analytics") {

        } else if ($page == "calendar") {

        } else if ($page == "advanced") {

        }
        return base64_encode($data);
    #endregion
    }

    public function saveData(string $type, $data, string $store_id) {
        global $name, $conn;
        try {
            $datetime = new DateTime();
            $date = $datetime->format("Y-m-d H:i:sa");
            $st = $conn->prepare("SELECT * FROM `staff` WHERE `uname`='$name' AND `store_id`='$store_id'");
            $st->execute();
            $user_data = $st->fetchAll(PDO::FETCH_ASSOC);
            $user_data = $user_data[0];
            $user_priv = json_decode(stripslashes($user_data["rights"]),true);
        } catch (PDOException $e) {
            if($_SESSION['debug']) {
                $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            } else {
                error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
            }
            return false;
        }
        if($user_priv['rosters']) {
            try {
                $st = $conn->prepare("INSERT INTO `saved` (store_id, type, date, data, poster) VALUES ('$store_id', '$type', '$date', '$data', '$name')");
                $st->execute();
                $st = $conn->prepare("SELECT `id` FROM `saved` WHERE `store_id`='$store_id' AND `date`='$date' AND `poster`='$name' AND `type`='$type'");
                $st->execute();
                $id = $st->fetchColumn();
            } catch (PDOException $e) {
                if($_SESSION['debug']) {
                    $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                } else {
                    error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
                }
                return false;
            }
            return ($id != null) ? array($id, $datetime->format("d/m H:i")) : false;
        } else {
            if($_SESSION['debug']) {
                $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            } else {
                error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL,3,$LOG);
            }
            return false;
        }
    }
}

class user {
    private function encrypt($text) {
        #region
        $salt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
        return $salt . hash("sha256", $salt . $pass);
        #endregion
    }

    function __construct($name, $password, $email, $store_id) {
        #region
        global $conn, $LOG;
        $rights = "{\"post\":\"false\",\"level\":1,\"comment\":\"true\",\"edit\":\"false\",\"rosters\":\"false\"}";
        $settings = "{\"getEmails\":\"true\"}";
        $stmt = $conn->prepare("SELECT `uname` FROM `staff` WHERE `uname`='$name' AND `store_id`='$store_id'");
        $stmt->execute();
        if ($stmt->rowCount() >= 1) {
            return false;
        } else {
            try {
                $password = $this->encrypt($password);
                $st = $conn->prepare("INSERT INTO `staff` (store_id,rights,uname,email,password,settings) VALUES ('$store_id','$rights','$name','$email','$password','$settings')");
                $st->execute();
                return true;
            } catch (PDOException $e) {
                error_log("Exception on line " . $e->getLine() . ": " . $e->getMessage() . PHP_EOL.PHP_EOL,3,$LOG);
                return false;
            }
        }
        #endregion
    }

    function remove($user, $current_user, $store_id) {
        #region

        #endregion
    }

    function update(string $value, string $type, string $store_id, array $options = null) {
        #region

        #endregion
    }
}

class notification {
    public $count = 0;
    private function increaseNotificationCount(string $type, string $name, string $store_id, string $element, int $count) {
        global $conn;//, $error;
        if ($type === "sidenav-bubble") {
            try {
                $st = $conn->prepare("SELECT `notifications` FROM `staff` WHERE `uname`='$name' AND `store_id`='$store_id'");
                $st->execute();
                $data = $st->fetchColumn();
            } catch (PDOException $e) {
                echo print_r($e);
            }
            if ($data != null || $data != "") {
                $data = json_decode($data,true);
                if ($data[$element] == null) {
                    $data[$element] = $count;
                    $this->count = $count;
                } else if ($data[$element] == "" || $data[$element] == null) {
                    $data[$element] = $count;
                } else if ($data[$element] == "0" || $data[$element] == 0) {
                    $data[$element] = $count;
                    $this->count = $count;
                } else if ((int)$data[$element] > 0) {
                    $data[$element] += (int)$count;
                    $this->count = $data[$element];
                }
            }
        } else {

        }
    }

    private function email(string $name, string $store_id, string $text) {
    }

    function __construct(string $type, string $name, string $store_id, string $element, int $count = 1) {
        if($type === "sidenav-bubble") {
            $this->increaseNotificationCount($type, $name, $store_id, $element, $count);
        } else if ($type === "email") {
            $this->email($name,$store_id,$element);
        }
    }
}

class element {

    public function __construct(string $store_id, string $data = "", $options = null) {

    }

    private function changeInput(string $data, $store_id, $options) {
        if ( empty($options) || $options != null ) {
            $st = $conn->prepare("SELECT `customisation` FROM `staff` WHERE `store_id`='$store_id' AND `uname`='$name'");
            $st->execute();
            $options = $st->fetchColumn();
            $options = json_decode($options,true);
            customisationReplacement($data, $store_id, $options);
        } else {
            $tag_string = "";
            $data = str_replace("\">","\" >", $data);
            foreach($options as $element => $attributes) {
                $e = "<".$element." ";
                $f = "<".$element.">";
                if(strpos($data, $e) || strpos($data,$f)) {
                    $tag_regex_array = array();
                    $regex = '<'.$element.' (.*) >';
                    preg_match($regex, $data, $tag_regex_array);
                    $tag_string = " ".$tag_regex_array[1]." "; //the string that holds all the attributes in the tag selected
                    $attribute_name_string = preg_replace('/="(.*?)"/',null,$tag_string);
                    preg_match_all('/\s(.*?)=/',$tag_string,$tag_regex_array);
                    $attribute_name_string = trim($attribute_name_string," ");
                    $attribute_name_array = preg_split('/\s+/i',$attribute_name_string); //the array that holds all the names of the attributes in the tag selected
                    $s_id = array_search("id",$attribute_name_array);
                    if($s_id == 00 || $s_id > 0 && $s_id != null) {
                        preg_match('/id="(.*?)"/', $tag_string, $id);
                        $id = $id[1];
                        $line_selection_regex = '/<'.$element.'\s*?id="'.$id.'".*?(.*?)>/';
                        preg_match($line_selection_regex, $data, $line_of_focus);
                        $line_of_focus = $line_of_focus[0]; //used to edit and modify to then replace in the original string
                        $LINE_TO_REPLACE = $line_of_focus; //used for regex to find and replace with the newly created line
                    } else if (array_search("name",$attribute_name_array)) {
                        preg_match('/name="(.*?)"/', $tag_string, $name);
                        $name = $name[1];
                        $line_selection_regex = '<'.$element.'\s*?name="'.$name.'".*?(.*?)>';
                        preg_match($line_selection_regex, $data, $line_of_focus);
                        $line_of_focus = $line_of_focus[0]; //used to edit and modify to then replace in the original string
                        $LINE_TO_REPLACE = $line_of_focus; //used for regex to find and replace with the newly created line
                    }
                    foreach($attribute_name_array as $key => $attribute_name) {
                        if(array_key_exists($attribute_name, $attributes) && !is_array($attributes[$attribute_name])) {
                            preg_match('/'.$attribute_name.'="(.*?)" /i', $line_of_focus, $reg);
                            if ((strpos($reg[1], " ".$attributes[$attribute_name]." ") == false) && 
                                (strpos($reg[1], $attributes[$attribute_name]." ") == false) && 
                                (strpos($reg[1], " ".$attributes[$attribute_name]) == false)) {
                                $change = $reg[1] . " " . $attributes[$attribute_name];
                            } else {
                                $change = $reg[1];
                            }
                            $inline_attribute_data = $attribute_name . "=\"" . $reg[1] . "\"";
                            $updated_attribute_data = $attribute_name . "=\"" . $change . "\"";
                            $line_of_focus = str_replace($inline_attribute_data, $updated_attribute_data, $line_of_focus);
                        } else if (is_array($attributes[$attribute_name])) {
                            $change = "";
                            foreach($attributes[$attribute_name] as $option => $setting) {
                                $update = $option . ":" . $setting . ";";
                                preg_match('/'.$attribute_name.'="(.*?)" /i', $line_of_focus, $reg);
                                if ((strpos($reg[1], " ".$update." ") == false) && 
                                    (strpos($reg[1], $update." ") == false) && 
                                    (strpos($reg[1], " ".$update) == false)) {
                                    $change .= $reg[1] . " " . $update;
                                } 
                            }
                            $inline_attribute_data = $attribute_name . "=\"" . $reg[1] . "\"";
                            $updated_attribute_data = $attribute_name . "=\"" . $change . "\"";
                            $line_of_focus = str_replace($inline_attribute_data, $updated_attribute_data, $line_of_focus);
                        }
                    }
                    foreach($attributes as $a_name => $a_value) {
                        if(!is_array($a_value)) {
                            if(strpos($attribute_name_string,$a_name) == false) {
                                $line_of_focus = trim($line_of_focus,">");
                                $line_of_focus .= $a_name . "=\"" . $a_value . "\" >";
                            }
                        } else {
                            if(strpos($attribute_name_string,$a_name) == false) {
                                $update = "";
                                foreach($a_value as $opt => $set) {
                                    $update .= $opt . ":" . $set . ";";
                                }
                                $line_of_focus = trim($line_of_focus,">");
                                $line_of_focus .= $a_name . "=\"" . $update . "\" >";
                            }
                        }
                    }
                    $data = str_replace($LINE_TO_REPLACE, $line_of_focus, $data);
                }
            }
            $data = str_replace("\" >", "\">",$data);
        }
    }

    private function minimiseInput() {

    }

}
?>