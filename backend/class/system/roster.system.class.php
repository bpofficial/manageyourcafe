<?php
class roster extends system {
    #region -- Instantiation --
    public function __construct() {
        global $error;
        $error->add_error("%cServer-side info: _Rosters System_%c", ['font-size:large;', 'color:black;'], true);
        system::__construct($_SESSION);
    }

    public function build(string $date = 'next monday') {
        #region Variables
        global $conn, $LOG, $error;
        $time = microtime(true);
        $error->add_error("%cBuilding roster page.", ['color:black;'], true);
        $ret; $success = true;
        #endregion
        #region Autopopulate
        try {
            $error->add_error("%cGathering auto-populate settings.", ['color:black;'], true);
            $st = $conn->prepare("SELECT `roster_auto_populate` FROM `settings` WHERE `store_id`='$this->store_id'");
            $st->execute();
            $populate = $st->fetchColumn();
            $error->add_error("%c\tSetting: ".$populate, ['color:black;'], true);
            (!$success) ?? $success = true; 
        } catch (Exception $e) {
            $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            $success = false; 
        }
        #endregion
        #region Previous roster
        if($populate) {
            try {
                $error->add_error("%cGathering previous roster.", ['color:black;'], true);
                $st = $conn->prepare("SELECT * FROM `rosters` WHERE `store_id`='$this->store_id' and `saved`='0' ORDER BY id DESC LIMIT 1");
                $st->execute();
                if ($st->rowCount() < 1 && $populate) { $populate = false; }
                (!$success) ?? $success = true; 
            } catch (PDOExeption $e) {
                $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                $success = false; 
            }
        } else {
            $populate = false;
        }
        #endregion
        #region User data
        try {
            $error->add_error("%cGathering user data.", ['color:black;'], true);
            $st = $conn->prepare("SELECT * FROM `staff` WHERE `uname`='$this->user' AND `store_id`='$this->store_id'");
            $st->execute();
            $user_data = $st->fetchAll(PDO::FETCH_ASSOC);
            $user_data = $user_data[0];
            $user_priv = json_decode(stripslashes($user_data["rights"]),true);
            (!$success) ?? $success = true;
        } catch (Exception $e) {
            $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            $success = false; 
        }
        #endregion
        #region Logic
        $error->add_error("Checking user privileges.");
        if ($user_priv['rosters'] === "true") {
            $error->add_error("User allowed.");
            $check = $this->check_roster($date);
            $days_left = $this->days_until($date);
            #region notification
            $noti = new notification("sidenav-bubble",$this->user,$this->store_id,"rosters");
            $count = $noti->count;
            $js = "$(\"#roster-noti\").text(\"$count\");$(\"#roster-noti\").prop(\"class\",\"quantity\")";
            #endregion
            if ($check === false && $days_left < 3) {
                $error->add_error("URGENT: Roster needs completing! ".$days_left." days remaining.");
                try {
                    $RosterData = $this->generateCreator($populate) . $this->generatePrevious(["JS"=>$js]);
                    $ret = base64_encode($RosterData);
                    (!$success) ?? $success = true; 
                } catch (Exception $e) {
                    $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                    $success = false;
                }
            }
            else if ($check === false && $days_left >= 3 && $days_left <= 7) {
                $error->add_error("Roster needs completing. ".$days_left." days remaining.");
                try {
                    $RosterData = $this->generateCreator($populate,true) . $this->generatePrevious(["JS"=>$js]);
                    $ret = base64_encode($RosterData);
                    (!$success) ?? $success = true; 
                } catch (Exception $e) {
                    $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                    $success = false;
                }
            }
            else if ($check === true) {
                $error->add_error("Roster for ".$date." complete. ".$days_left." days remaining.");
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
                    $error->add_error("Generating HTML for the Roster Creator (collapsed) & Previous rosters.");
                    $RosterData = $this->generateCreator($populate, true) . $this->generatePrevious(["JS"=>$js]);
                    $ret = base64_encode($RosterData);
                    (!$success) ?? $success = true; 
                } catch (Exception $e) {
                    $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                    $success = false;
                }
            }
        } else {
            $settings = json_decode($user_data["settings"],true);
            ($settings["blur_rosters"] === "true" || $settings["blur_rosters"] === true) ? $blur_check = true : $blur_check = false;
            $ret = base64_encode($this->generateUser("all", ["BLUR_OTHER_ROSTERS"=>$blur_check]));
            (!$success) ?? $success = true; 
        }
        #endregion
        #region Output
        $errors = $error->generate();
        $error->add_error("%cExecution Time: %c" . $time, ['font-weight:bold;','color:green;'], true);
        $o = <<<JS
    'output' : {
        'success' : $success,
        'value' : '...',
        'errors' : 'ignored',
        'time' : microtime(true) - $time
    }
JS;
        $error->add_error($o);
        $error->generate();
        return  
            json_encode(
                array(
                    'success' => $success,
                    'value' => $ret,
                    'errors' => $errors,
                    'time' => microtime(true) - $time
                )
            );
        #endregion
    }
    #endregion
    #region -- VARIABLES -- {
    #region -- JAVASCRIPT -- {
    private $ROSTER_CREATOR_FOOT_SCRIPT = <<<JS
        $(document).on('hide.bs.modal','#lrm-wrapper', function () {
            roster.closeModal("#lrm-wrapper");
        });

        $(document).on('hide.bs.modal','#prm-wrapper', function () {
            roster.closeModal("#prm-wrapper");
        });

        $(document).on('click','#lrm-select-all',function() {
            $(this).prop("checked") ? $("input.lrt-select").prop("checked", true) : $("input.lrt-select").prop("checked", false);
        });

        $(document).on("click", "#roster-show",function(){
            $("#roster-show").closest('div').find('.openable').toggleClass('openable','open');
            var b=$(this).attr("name");
            if($("#roster-"+b).prop("class")=="collapse"){ 
                $(this).html('Hide <i class="fas fa-angle-up"></i>')
            }else{
                if($("#roster-"+b).prop("class")=="collapse show"){ 
                    $(this).html('Show <i class="fas fa-angle-down"></i>')
                }
            }
        });
        
        $(function(){ 
            $("#roster-form").submit(function(event){
                event.preventDefault();
                roster.submit();
            });

            $("#roster-options-bar--desktop button#modal").on('click', function() {
                roster.loader();
            });

            $("#roster-options-bar--desktop button#save").on('click',function(j){
                roster.save();
            });

            $("#roster-options-bar--desktop button#clear").on('click',function(){
                roster.update();
            });

            $("#roster-options-bar--desktop button#preview").on('click',function(){
                roster.preview(true);
            });

            $("#roster-options-bar--desktop button#print").on('click',function(){
                roster.preview(false, function(){
                    window.print();
                });
            });

            $(".js-select2").select2({minimumResultsForSearch:-1});
            $('[data-toggle="popover"]').popover(); 
        });
JS;
    #endregion }
    #region -- NON-COLLAPSEABLE -- {
    private $ROSTER_CREATOR = <<<HTML
    <div class="section__content section__content--p30">
        <div class="container-fluid" id="content"> 
            <div class="col-md-12"> 
                <div class="overview-wrap"> 
                    <h2 class="title-1">Rosters</h2> 
                </div></br> 
            </div>
            <div class="row"> 
                <div class="col-12"> 
                    <div class="card"> 
                        <div class="card-header"> 
                            <strong class="card-title">
                                Roster for week starting:
                                <span class="input-group date col-12" style="padding-left:0px;" id="date" data-target-input="nearest"> 
                                    <input id="date-input" type="text" placeholder="Week starting" class="form-control datetimepicker-input col-xs-12 col-sm-6 col-md-4 col-lg-4" data-target="#date"/> 
                                    <span class="input-group-append" style="padding:0px;" data-target="#date" data-toggle="datetimepicker"> 
                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span> 
                                    </span>
                                    <span class="roster-options-display--desktop r-opt-wrapper">
                                        <button class="btn btn-secondary btn-xs btn-block r-opt" data-toggle="collapse" data-target="#roster-options-content--desktop" aria-expanded="false" aria-controls="#roster-options-content--desktop">Options</button>
                                    </span>
                                </span>
                                <hr>
                                <!-- -->
                                    <div id="roster-options-bar--desktop" class="col-12">
                                        <div id="roster-options-content--desktop" class="collapse">
                                            <div class="row">
                                                <div class="col-12">
                                                    <span id="load-button-wrapper--desktop" class="float-right r-opt-wrapper">
                                                        <button id="modal" class="btn btn-primary btn-xs btn-block r-opt">Load</button>
                                                    </span>
                                                    <span id="preview-button-wrapper--desktop" class="float-left r-opt-wrapper">
                                                        <button id="preview" class="btn btn-primary btn-xs btn-block r-opt">Preview</button>
                                                    </span>
                                                    <span id="clear-button-wrapper--desktop" class="float-left r-opt-wrapper">
                                                        <button id="clear" class="btn btn-primary btn-xs btn-block r-opt">Clear</button>
                                                    </span>
                                                </div>
                                                <div class="col-12">
                                                    <span id="save-button-wrapper--desktop" class="float-right r-opt-wrapper">
                                                        <button id="save" class="btn btn-primary btn-xs btn-block r-opt">Save</button>
                                                    </span>
                                                    <span id="print-button-wrapper--desktop" class="float-left r-opt-wrapper">
                                                        <button id="print" class="btn btn-primary btn-xs btn-block r-opt">Print</button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <!-- -->
                            </strong> 
                            <script>$(function(){ $("#date").datetimepicker({format: "D/M/YYYY", daysOfWeekDisabled: [0,2,3,4,5,6]});}); </script> 
                        </div>
                        <div class="card-body"> 
                            <form id="roster-form" class="table-scroll" action="">
                                <div id="roster-wrapper" class="form-group table-responsive table--no-card m-b-30 table-wrap"> 
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
                                        <strong class="card-title"> 
                                            Comments 
                                        </strong>
                                        <div class="form-group"> 
                                            <textarea class="form-control" id="comments" rows="4"></textarea>
                                        </div>
                                        <button id="submit-button" type="submit" form="roster-form" class="btn btn-primary btn-lg btn-block">Submit Roster</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
HTML;
    #endregion }
    #region -- COLLAPSEABLE -- {
    private $ROSTER_CREATOR_COLLAPSEABLE = <<<HTML
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
                    <div class="card " style="margin-bottom: 10px;">
                        <div class="card-header openable">   
                            <div class="row">
                                <div class="col-12">
                                    <strong class="card-title">
                                        Create a new Roster
                                        <span id="roster-show" name="creator" class="badge badge-primary float-right" style="cursor:pointer;margin-top:0.16rem;margin-right:1.3rem;" data-toggle="collapse" data-target="#roster-creator">Show&nbsp;<i class="fas fa-angle-down"></i></span>
                                    </strong>  
                                </div>
                            </div>
                        </div>
                        <div class="collapse" id="roster-creator">
                            <div class="card-body">
                                <div class="row">
                                    <div id="date-wrapper" class="col-12">
                                        <span class="input-group date col-12" style="padding-left:0px;" id="date" data-target-input="nearest"> 
                                            <input id="date-input" type="text" placeholder="Week starting" class="form-control datetimepicker-input col-xs-12 col-sm-6 col-md-4 col-lg-4" data-target="#date"/> 
                                            <span class="input-group-append" data-target="#date" data-toggle="datetimepicker"> 
                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span> 
                                            </span>
                                            <span class="roster-options-display--desktop r-opt-wrapper">
                                                <button class="btn btn-secondary btn-xs btn-block r-opt" data-toggle="collapse" data-target="#roster-options-content--desktop" aria-expanded="false" aria-controls="#roster-options-content--desktop">Options</button>
                                            </span>
                                        </span>
                                        <hr>
                                        <!-- -->
                                            <div id="roster-options-bar--desktop" class="col-12">
                                                <div id="roster-options-content--desktop" class="collapse">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <span id="load-button-wrapper--desktop" class="float-right r-opt-wrapper">
                                                                <button id="modal" class="btn btn-primary btn-xs btn-block r-opt">Load</button>
                                                            </span>
                                                            <span id="preview-button-wrapper--desktop" class="float-left r-opt-wrapper">
                                                                <button id="preview" class="btn btn-primary btn-xs btn-block r-opt">Preview</button>
                                                            </span>
                                                            <span id="clear-button-wrapper--desktop" class="float-left r-opt-wrapper">
                                                                <button id="clear" class="btn btn-primary btn-xs btn-block r-opt">Clear</button>
                                                            </span>
                                                        </div>
                                                        <div class="col-12">
                                                            <span id="save-button-wrapper--desktop" class="float-right r-opt-wrapper">
                                                                <button id="save" class="btn btn-primary btn-xs btn-block r-opt">Save</button>
                                                            </span>
                                                            <span id="print-button-wrapper--desktop" class="float-left r-opt-wrapper">
                                                                <button id="print" class="btn btn-primary btn-xs btn-block r-opt">Print</button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- -->
                                        <script>$(function(){ $("#date").datetimepicker({format: "D/M/YYYY", daysOfWeekDisabled: [0,2,3,4,5,6]});}); </script> 
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
                                            <strong class="card-title"> 
                                                Comments
                                            </strong>
                                            <div class="form-group"> 
                                                <textarea class="form-control" id="comments" rows="4"></textarea>
                                            </div>
                                            <button id="submit-button" type="submit" form="roster-form" class="btn btn-primary btn-lg btn-block">Submit Roster</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
HTML;
        #endregion }
    private $ROSTER_MAIN_FOOT = "</tbody></table></div></div></div></div></div>"; //ends the ROW
    private $SELECT_TIME = "<option value=\"05:00 am\">05:00 am</option><option value=\"05:30 am\">05:30 am</option><option value=\"05:45 am\">05:45 am</option><option value=\"06:00 am\">06:00 am</option><option value=\"06:15 am\">06:15 am</option><option value=\"06:30 am\">06:30 am</option><option value=\"06:45 am\">06:45 am</option><option value=\"07:00 am\">07:00 am</option><option value=\"07:15 am\">07:15 am</option><option value=\"07:30 am\">07:30 am</option><option value=\"07:45 am\">07:45 am</option><option value=\"08:00 am\">08:00 am</option><option value=\"08:15 am\">08:15 am</option><option value=\"08:30 am\">08:30 am</option><option value=\"08:45 am\">08:45 am</option><option value=\"09:00 am\">09:00 am</option><option value=\"09:15 am\">09:15 am</option><option value=\"09:30 am\">09:30 am</option><option value=\"09:45 am\">09:45 am</option><option value=\"10:00 am\">10:00 am</option><option value=\"10:15 am\">10:15 am</option><option value=\"10:30 am\">10:30 am</option><option value=\"10:45 am\">10:45 am</option><option value=\"11:00 am\">11:00 am</option><option value=\"11:15 am\">11:15 am</option><option value=\"11:30 am\">11:30 am</option><option value=\"11:45 am\">11:45 am</option><option value=\"12:00 pm\">12:00 pm</option><option value=\"12:15 pm\">12:15 pm</option><option value=\"12:30 pm\">12:30 pm</option><option value=\"12:45 pm\">12:45 pm</option><option value=\"1:00 pm\">1:00 pm</option><option value=\"1:15 pm\">1:15 pm</option><option value=\"1:30 pm\">1:30 pm</option><option value=\"1:45 pm\">1:45 pm</option><option value=\"2:00 pm\">2:00 pm</option><option value=\"2:15 pm\">2:15 pm</option><option value=\"2:30 pm\">2:30 pm</option><option value=\"2:45 pm\">2:45 pm</option><option value=\"3:00 pm\">3:00 pm</option><option value=\"3:15 pm\">3:15 pm</option><option value=\"3:30 pm\">3:30 pm</option><option value=\"3:45 pm\">3:45 pm</option><option value=\"4:00 pm\">4:00 pm</option><option value=\"Close\">Close</option>";
    #endregion }
    #region -- Generator Functions -- {
    private function generateCreator(bool $pop = false, bool $collapse = false) {
        #region
        global $conn, $error, $LOG;
        $error->add_error("\t<--- (START) BUILDING ROSTER CREATOR --->");
        $st = $conn->prepare("SELECT `uname`  FROM `staff` WHERE `store_id`='$this->store_id'");
        $st->execute();
        $array = $st->fetchAll(PDO::FETCH_COLUMN);
        $staffCount = count($array);
        $days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
        $ROSTER_CREATOR_BODY = "";
        $error->add_error("Auto-populate disabled.");
        for ($x = 0; $x < $staffCount; $x++) {
            $name = ucfirst($array[$x]);
            $error->add_error("\t\t-- Beginning build loop (".$name.") --");
            $ROSTER_CREATOR_BODY .= <<<EOT
                <tr style="position:flex;">
                    <td class="fixed-sub">
                        $name
                    </td>
EOT;
            for ($day = 0; $day < 7; $day++) {
                $namestart = $days[$day] . '_' . ucfirst($array[$x]) . '_start';
                $namefinish = $days[$day] . '_' . ucfirst($array[$x]) . '_finish';
                $ROSTER_CREATOR_BODY .= <<<HTML
                    <td id="$days[$day]" name="$days[$day]">
                        <div class="roster-select-wrapper rs-select2--trans rs-select2--md"style="background-color: transparent;">
                            <select value="$name" name="$namestart" class="roster-select js-select2 select2-hidden-accessible"style="background-color: transparent; max-width:5%;">
                                <option selected="selected"value="startTime">Start Time</option>
                                $this->SELECT_TIME
                            </select>
                            <select value="$name" name="$namefinish" class="roster-select js-select2 select2-hidden-accessible" style="background-color:transparent;">
                                <option selected="selected" value="finishTime">Finish Time</option>
                                $this->SELECT_TIME
                            </select>
                            <span class="dropdown-wrapper" style="background-color:transparent;" aria-hidden="true"></span>
                        </div>
                    </td>
HTML;
            }
        $ROSTER_CREATOR_BODY .= "</tr>";
        } 
        $error->add_error("\t<--- (FINISH) BUILDING ROSTER CREATOR --->");
        if(!$collapse) {
            return strtr(
                    $this->ROSTER_CREATOR, 
                    array(
                        "**_TABLE_BODY_**" => $ROSTER_CREATOR_BODY
                    )
                );
        } else {
            return strtr(
                $this->ROSTER_CREATOR_COLLAPSEABLE, 
                array(
                    "**_TABLE_BODY_**" => $ROSTER_CREATOR_BODY
                )
            );
        }
        #endregion
    }

    public function generatePrevious(array $options = []) {
        #region
        global $conn, $error;
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
            $st = $conn->prepare("SELECT * FROM `rosters` WHERE `rosters`.`store_id`='$this->store_id' AND `rosters`.`saved`='0' ORDER BY `rosters`.`date_from` DESC");
            $st->execute();
            $All_Rosters = $st->fetchAll(PDO::FETCH_ASSOC);
            $st = null;
            $error->add_error("%cGot rosters for store: %c" . $this->store_id, ['font-style:italic;', 'color:blue;'], true);
        } catch (Exception $e) {
            $error->add_error("%Failed to get rosters for store: %c" . $this->store_id . ' %creason: ' . $e, ['font-style:italic;', 'color:blue;', 'color:black;font-style:italic;','color:red;'], true);
        }
        try {
            $st = $conn->prepare("SELECT * FROM `staff` WHERE `store_id`='$this->store_id'");
            $st->execute();
            $staffCount = $st->rowCount();
            $staff = $st->fetchAll(PDO::FETCH_ASSOC);
            $st = null;
            $error->add_error("%cGot staff for store: %c" . $this->store_id, ['font-style:italic;', 'color:blue;'], true);
        } catch (Exception $e) {
            $error->add_error("%Failed to get staff for store: %c" . $this->store_id . ' %creason: ' . $e, ['font-style:italic;', 'color:blue;', 'color:black;font-style:italic;','color:red;'], true);
        }
        $error->add_error("%cStarting to create previous rosters.", ['color:black;'], true);
        foreach ($All_Rosters as $key => $value) {
            $roster = json_decode($value['data'],true);
            $body = "";
            $id = $value['id'];
            $date = date("d/m/Y", strtotime($value['date_from'])); 
            $error->add_error("%cRoster with id %c". $id . " %cand date: %c" . $date, ['font-style:italic','color:blue','color:black;font-style:italic;','color:blue'], true);
            $PREVIOUS_ROSTERS .= "<div id=\"" . $id . "\" class=\"row\"><div class=\"col-lg-12\"><div class=\"card\" style=\"margin-bottom: 10px;\"> <div class=\"card-header\"><span style=\"cursor:pointer;\" class=\"float-right\"> <a class=\"remove-roster\" onclick=\"roster.delete('".$id."');\" name=\"" . $id . "\"> &nbsp&nbsp<i class=\"fas fa-times\"></i> </a> </span><span id=\"roster-show\" class=\"badge badge-primary float-right\" style=\"cursor:pointer;margin-top:0.16rem;\" data-toggle=\"collapse\" data-target=\"#roster-" . $id . "\" name=\"" . $id . "\">Show&nbsp;<i class=\"fas fa-angle-down\"></i></span><span class=\"badge badge-success\">Completed <i class=\"fas fa-check\"></i></span><span style=\"margin-left:0.5rem;\" class=\"badge badge-danger\">$poster</span><strong style=\"margin-left:0.5rem;\" class=\"card-title\">Roster for week starting: " . $date . "</strong></span></div><div class=\"collapse\" id=\"roster-" . $id . "\"><div class=\"card-body\"><div class=\"table-responsive table--no-card m-b-30\" style=\"margin-bottom: 0px;\"> <table id=\"roster\" class=\"table table-earning\" align=\"left\"> <thead align=\"left\"> <tr><th>Name</th><th>Monday</th><th>Tuesday</th> <th>Wednesday</th> <th>Thursday</th> <th>Friday</th> <th>Saturday</th> <th>Sunday</th> </tr></thead> <tbody align=\"left\">";
            for ($i = 0; $i < $staffCount; $i++) {
                $nname = ucfirst($staff[$i]['uname']);
                $body .= "<tr><td>" . $nname . "</td>";
                for ($x = 0; $x < 7; $x++) {
                    $data = $roster[$days[$x]];
                    $name_start = $data[$nname]["start"];
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
                $this->ROSTER_CREATOR_FOOT_SCRIPT
                $extra_js
            </script>
EOT;
        return $PREVIOUS_ROSTERS . $PREVIOUS_ROSTER_FOOT;
        #endregion
    }

    public function generateUser(string $req = "all", $options = []) {
        #region
        global $conn, $error;
        try {
            $st = $conn->prepare("SELECT `uname`  FROM `staff` WHERE `store_id`='$this->store_id'");
            $st->execute();
            $array = $st->fetchAll(PDO::FETCH_COLUMN);
            $staffCount = count($array);
        } catch (Exception $e) {
            $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
        }
        try {
            $st = $conn->prepare("SELECT * FROM `rosters` WHERE `store_id`='$this->store_id' AND `saved`='0' ORDER BY `date_from` DESC");
            $st->execute();
            $count = $st->rowCount();        
            $prev_roster = $st->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);       
        }
        if($count > 0) {     
            #region   
            $days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
            $ROSTER_MAIN_BODY = "";
            $data = json_decode($prev_roster[0]['data'], true);
            for ($x = 0; $x < $staffCount; $x++) {
                $name = ucfirst($array[$x]);
                ($array[$x] == $this->user) ? $ROSTER_MAIN_BODY .= "<tr class=\"personal\"><td style=\"font-weight: bold; cursor: default;\">" . $name . "</td>" : $ROSTER_MAIN_BODY .= "<tr><td style=\"font-weight: bold;\">" . $name . "</td>";
                $namestart = ucfirst($array[$x]) . '_start';
                $namefinish = ucfirst($array[$x]) . '_finish';
                for ($day = 0; $day < 7; $day++) {
                    $roster = $data[$days[$day]];
                    $pstime = $roster[$name]['start'];
                    $pftime = $roster[$name]['finish'];
                    ($pstime != "startTime") ? $ROSTER_MAIN_BODY .= "<td><p>" . $pstime . "</p>" : $ROSTER_MAIN_BODY .= "<td><p>Not working</p>";
                    ($pftime != "finishTime") ? $ROSTER_MAIN_BODY .= "<p>" . $pftime . "</p></td>" : $ROSTER_MAIN_BODY .= "</td>";
                }
                $ROSTER_MAIN_BODY .= "</tr>";
            }
            $date = $prev_roster[0]["date_from"];
            $date = date("d/m/Y", strtotime($date));
            (strlen($prev_roster[0]["comments"]) > 10) ? $COMMENTS = "<div id=\"comments\" class=\"comments\">" . nl2br(base64_decode($prev_roster[0]["comments"])) . "</div><br/>" : $COMMENTS = "";
            ($options["BLUR_OTHER_ROSTERS"] === true) ? $css_head = "<style>td,th{padding:10px 15px;position:relative}table{box-shadow:inset 0 1px 0 #fff}th{background:url(https://jackrugile.com/images/misc/noise-diagonal.png),linear-gradient(#777,#444);box-shadow:inset 0 1px 0 #999;color:#fff;font-weight:700;text-shadow:0 1px 0 #000}th:after{background:linear-gradient(rgba(255,255,255,0),rgba(255,255,255,.08));content:'';display:block;height:25%;left:0;margin:1px 0 0;position:absolute;top:25%;width:100%}th:first-child{box-shadow:inset 1px 1px 0 #999}th:last-child{box-shadow:inset -1px 1px 0 #999}td{transition:all .3s}td:first-child{box-shadow:inset 1px 0 0 #fff}td:last-child{box-shadow:inset -1px 0 0 #fff}tr{background:url(https://jackrugile.com/images/misc/noise-diagonal.png)}tr:nth-child(odd) td{background:url(https://jackrugile.com/images/misc/noise-diagonal.png) #f1f1f1}tr:last-of-type td{box-shadow:inset 0 -1px 0 #fff}tr:last-of-type td:first-child{box-shadow:inset 1px -1px 0 #fff}tr:last-of-type td:last-child{box-shadow:inset -1px -1px 0 #fff}tbody:hover td{color:transparent;text-shadow:0 0 3px #aaa}tbody:hover tr:hover td{color:#444;text-shadow:0 1px 0 #fff}tr.personal > td:first-child {border-left:4px solid rgba(66, 114, 215, 0.8)!important;}tr.personal > td {border-bottom:2px solid rgba(66, 114, 215, 0.8)!important;}</style>" : $css_head = "<style>tr.personal > td:first-child {border-left:4px solid rgba(66, 114, 215, 0.8)!important;}tr.personal > td {border-bottom:2px solid rgba(66, 114, 215, 0.8)!important;}</style>";
            $ROSTER_MAIN_HEAD = <<<HTML
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
HTML;
            $USER_PREVIOUS_ROSTERS = "";
            $first = true;
            foreach ($prev_roster as $key => $value) {
                if($first === true){ $first=false; continue;}  
                $roster = json_decode($value['data'], true);
                $body = "";
                $id = $value['id'];
                $date = date("d/m/Y", strtotime($value['date_from'])); 
                $error->add_error("%cRoster with id %c". $id . " %cand date: %c" . $date, ['font-style:italic','color:blue','color:black;font-style:italic;','color:blue'], true);
                $USER_PREVIOUS_ROSTERS .= "<div id=\"" . $id . "\" class=\"row\"><div class=\"col-lg-12\"><div class=\"card\"> <div class=\"card-header\"><strong style=\"margin-left:1vw;\" class=\"card-title\">Roster for week starting: " . $date . "</strong> <span id=\"roster-show\" style=\"cursor:pointer;\" data-toggle=\"collapse\" data-target=\"#roster-" . $id . "\" name=\"" . $id . "\"><i id=\"arrow\" x=\"" . $id . "\" class=\"float-right fas fa-angle-down\"></i></span> </div><div class=\"card-body collapse\" id=\"roster-" . $id . "\"><div class=\"form-group table-responsive table--no-card m-b-30\"> <table id=\"roster\" for=\"roster-form\" class=\"table table-bordered table-earning\" align=\"left\"> <thead align=\"left\"> <tr><th>Name</th><th>Monday</th><th>Tuesday</th> <th>Wednesday</th> <th>Thursday</th> <th>Friday</th> <th>Saturday</th> <th>Sunday</th> </tr></thead> <tbody align=\"left\">";
                for ($i = 0; $i < $staffCount; $i++) {
                    $nname = ucfirst($array[$i]);
                    ($array[$i] == $this->user) ? $body .= "<tr><td style=\"border-left:2px solid rgba(66, 114, 215, 0.8);\">" . $nname . "</td>" : $body .= "<tr><td>" . $nname . "</td>";                     
                    for ($x = 0; $x < 7; $x++) {
                        $data = $roster[$days[$x]];
                        $name_start = $data[$nname]["start"]; 
                        $name_finish = $data[$nname]["finish"];
                        if($name_start == "startTime" || $name_finish == "finishTime") {
                            $body .= "<td>Not working</td>";
                        } else {
                            if($req !== "email") {
                                $body .= "<td><div>" . $name_start . "</div><div>" . $name_finish . "</div></td>";
                            } else {
                                $body .= "<td><div>" . $name_start . " - " . $name_finish . "</div></td>"; 
                            }
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
                $return = <<<HTML
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
HTML;
                return $return;
            }
        #endregion
        } else {
            #region NO ROSTERS AVAILABLE
            $html = <<<HTML
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
HTML;
            return $html;
            #endregion
        }
        #endregion
    }
    #endregion }
    #region -- Calculator Functions -- {
    public function calculate(string $data) {
        global $conn, $error;
        $error->add_error("Beginning calculations on provided data.");
        try {
            $st = $conn->prepare("SELECT `uname`,`wage` FROM `staff` WHERE `store_id`='$this->store_id'");
            $st->execute();
            $staff = $st->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            return false;
        }
        $total = array(
            "hours" => (float)0,
            "cost" => (float)0
        );
        foreach($staff as $key => $val) {
            $total[$val['uname']] = array(
                "hours" => (float)0,
                "cost" => (float)0, 
                "super" => (float)0,
                "wage" => (float)$val['wage'],
                "penalty" => ($val['wage'] > (float)0) ? (float)2 : (float)0,
                "wk_hours" => (float)0,
                "pn_hours" => (float)0
            );
        }
        $data = json_decode($data, true);
        foreach($data as $day => $info) {
            if (is_array($info) || is_object($info)) {
                //$error->add_error("Data: ".$info);
                foreach($info as $name => $shift) {
                    $name = lcfirst($name);
                    $wage = $total[$name]['wage'];
                    $s = ($shift['start'] != "startTime") ? date("Hi", strtotime($shift['start'])) : false;
                    $f = ($shift['finish'] != "finishTime") ? (($shift['finish'] == "close" || $shift['finish'] == "Close") ? date("Hi", strtotime("16:00")) : date("Hi",strtotime($shift['finish']))) : false;
                    if($s !== false && $f !== false) {
                        $len = ($f-$s)/100;
                        if($day == "saturday" || $day == "sunday") {
                            $cost = $len * ($total[$name]['wage'] + $total[$name]['penalty']);
                            $total[$name]['pn_hours'] += $len;
                        } else {
                            $cost = $len * $total[$name]['wage'];
                            $total[$name]['wk_hours'] += $len;
                        }
                        $total['hours'] += $len;
                        $total['cost'] += $cost;
                        $total[$name]['hours'] += $len;
                        $total[$name]['cost'] += $cost;
                    }
                }
            } else {
                $error->add_error("Not array or object.");
            }
        }
        $error->add_error("Done.");
        $total['super'] = round(($total['cost'] * 0.095),1);
        return $total;
    }

    private function check_roster($date) {
        #region
        global $conn;
        global $error;
        $format_date = date('Y-m-d', strtotime($date));
        try {
            $st = $conn->prepare("SELECT * FROM `rosters` WHERE `date_from`='$format_date' AND `store_id`='$this->store_id'");
            $st->execute();
            if($st->rowCount() >= 1) {
                $error->add_error("%cRoster set for %c" . $date . "%c? %cTrue%c",['font-style:italic;','color:blue;','color:black;', 'color:red;','color:black;'],true);
                return true;
            } else {
                $error->add_error("%cRoster set for %c" . $date . "%c? %cFalse%c",['font-style:italic;','color:blue;','color:black;', 'color:red;','color:black;'],true);
                return false;
            }
            $st = null; 
        } catch (Exception $e) {
            $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            return false;
        }
        #endregion
    }

    private function days_until($date) {
        #region
        return (isset($date)) ? floor((strtotime($date) - time())/60/60/24) : FALSE;
        #endregion
    }
    #endregion }
    #region -- Utility Functions {
    public function save($data) {
        global $error, $conn;
        $time = microtime(true);
        $success = true;
        $date = date('Y-m-d', strtotime("now Australia/Brisbane"));
        $edits[] = array(
            "by" => ucfirst($this->user),
            "label" => $data['la'],
            "time" => time(),
            "prev" => ""
        );
        $edits = json_encode($edits);
        $data = $data['data'];
        try {
            $st = $conn->prepare("INSERT INTO `rosters` (store_id, date_from, data, edits, saved) VALUES ('$this->store_id', '$date', '$data', '$edits', '1')");
            $st->execute();
            $st = $conn->prepare("SELECT `id` FROM `rosters` WHERE `store_id`='$this->store_id' AND `date_from`='$date' AND `saved`='1' ORDER BY `id` DESC LIMIT 1");
            $st->execute();
            $id = $st->fetchColumn();
            if ($id != null) {
                (!$success) ?? $success = true;
            } else {
                $id = null;
                $success = false;
            }
        } catch (Exception $e) {
            $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            $success = false;
        }
        return json_encode(
            array(
                'success' => $success,
                'errors' => $error->generate(),
                'id' => $id,
                'time' => microtime(true) - $time
            )
        );
    }

    public function load($id) {
        global $conn, $error;
        $success = true;
        try {
            $st = $conn->prepare("SELECT `data` FROM `rosters` WHERE `id`='$id' AND `store_id`='$this->store_id'");
            $st->execute();
            if($st->rowCount() > 0) {
                $data = base64_encode($st->fetchColumn()) ?? null;
                (!$success) ?? $success = true;
            } else {
                $success = false;
            }
        } catch (Exception $e) {
            $success = false;
        }
        $time = microtime(true) - $this->time;
        return json_encode(
            array(
                'success' => $success,
                'errors' => $error->generate(),
                'data' => $data,
                'time' => microtime(true) - $this->time
            )
        );
    }

    public function list() {
        global $error, $conn;
        $success = true;
        $time = microtime(true);
        try {
            $st = $conn->prepare("SELECT `id`,`data`,`date_from`,`edits` FROM `rosters` WHERE `store_id`='$this->store_id'");
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
                    "md" => ($this->calculate($val['data'])) ?? null
                );
            }
            $ext = json_encode($ext);
            if($st->rowCount() > 0) {
                (!$success) ?? $success = true;
            } else {
                $success = false;
            }
        } catch (PDOException $e) {
            $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            $success = false;
        }
        $ext = $ext ?? null;
        return json_encode(
            array(
                'success' => $success,
                'errors' => $error->generate(),
                'time' => microtime(true) - $time,
                'value' => $ext
            )
        );
    }

    public function delete($id) {
        global $error, $conn;
        $success = true;
        $time = microtime(true);
        if($this->priv['remove_rosters'] === "true") {
            try {
                $st = $conn->prepare("DELETE FROM `rosters` WHERE `id`='$id' AND `store_id`='$this->store_id'");
                $st->execute();
                (!$success) ?? $success = true;
            } catch (PDOException $e) {
                $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                $success = false;
            }
        } else {
            $success = false;
        }
        return json_encode(
            array(
                'success' => $success,
                'errors' => $error->generate(),
                'time' => microtime(true) - $time
            )
        );
    }

    public function add($data) {
        #region
        global $error, $conn;
        $time = microtime(true);
        $success = true;
        $roster = json_decode(stripslashes($data), true);                                       
        $rdate = date('Y-m-d', strtotime(str_replace('/', '-', $roster['startDate'])));
        try {
            $st = $conn->prepare("SELECT `date_from`,`edits`,`data` FROM `rosters` WHERE `store_id`='$this->store_id' AND `saved`='0' ORDER BY `id` DESC LIMIT 1");
            $st->execute();
            $d = $st->fetchAll(PDO::FETCH_ASSOC);
            (!$success) ?? $success = true;
        } catch (Exception $e) {
            $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            $success = false;
        }
        $date_check = $d[0]['date_from'];
        $edits = (gettype($d[0]['edits']) != "string") ? $d[0]['edits'] : "{}";
        $data = json_encode($roster);
        if ($rdate == $date_check) {
            try {
                $edits = (json_decode($edits,true)) ?? (function(){throw new Exception("Couldn't decode edits.");})();
                $edits[] = array(
                    "by" => ucfirst($this->user),
                    "label" => "done",
                    "time" => time(),
                    "prev" => base64_encode($d[0]['data'])
                );
                $u_edits = json_encode($edits);
                (!$success) ?? $success = true;
            } catch (Exception $e) {
                $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                $success = false;
            }
            try {
                $st = $conn->prepare("UPDATE `rosters` SET data='$data', edits='$u_edits' WHERE `date_from`='$rdate' AND `store_id`='$this->store_id' AND `saved`='0'");
                $st->execute();
                (!$success) ?? $success = true;
            } catch (Exception $e) {
                $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                $success = false;
            }
        } else {
            try {
                $edits[] = array(
                    "by" => ucfirst($this->user) or "Unknown",
                    "label" => "done",
                    "time" => time(),
                    "prev" => base64_encode($d[0]['data']) or ""
                );
                $u_edits = json_encode($edits);
                $st = $conn->prepare("INSERT INTO `rosters` (store_id, date_from, data, edits) VALUES ('$this->store_id', '$rdate', '$data', '$u_edits')");
                $st->execute();
                try {
                    $stv = $conn->prepare("SELECT * FROM `staff` WHERE `store_id`='$this->store_id'");
                    $stv->execute();
                    $staff = $stv->fetchAll(PDO::FETCH_ASSOC);
                    (!$success) ?? $success = true;
                } catch ( Exception $e ) {
                    $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
                    $success = false;
                }
                $css = <<<HTML
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
HTML;
                $send_count = $sent_count = 0;
                foreach($staff as $key => $val) {
                    $settings = json_decode($val['settings'],true);
                    if($settings['getEmails'] === "true" || $settings['getEmails'] === true) {
                        $send_count++;
                        $html = $this->generateUser("email");
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
                if ($sent_count <= $send_count) {
                    (!$success) ?? $success = true;
                } else {
                    $success = false;
                }
            } catch (Exception $e) {
                $success = false;
                $error->add_error("%cError: ". '%c' . $e->getMessage() ." %con line: " . '%c' . $e->getLine() . '%c', ['font-weight:bold;', 'color:red;', 'color:black;', 'color:blue;','color:black'], true);
            }
        }
        return json_encode(
            array(
                'success' => $success,
                'errors' => $error->generate(),
                'time' => microtime(true) - $time
            )
        );
        #endregion
    }

    #endregion }
}
?>