<?php
// Includes and Heads {
session_start();
include('../php/config.php');
$name = $_SESSION['uname'];
$store_id = $_SESSION['store_id'];
header('Content-type: text/html');
if($_SESSION['debug']) {
    include('../php/classes.php');
    $er = new errorHandle;
    $errors = "";
}
include('../php/roster.php');

//}
// Long shit {
$select_time = <<<EOT
<option value="05:00 am">05:00 am</option><option value="05:30 am">05:30 am</option><option value="05:45 am">05:45 am</option><option value="06:00 am">06:00 am</option><option value="06:15 am">06:15 am</option><option value="06:30 am">06:30 am</option><option value="06:45 am">06:45 am</option><option value="07:00 am">07:00 am</option><option value="07:15 am">07:15 am</option><option value="07:30 am">07:30 am</option><option value="07:45 am">07:45 am</option><option value="08:00 am">08:00 am</option><option value="08:15 am">08:15 am</option><option value="08:30 am">08:30 am</option><option value="08:45 am">08:45 am</option><option value="09:00 am">09:00 am</option><option value="09:15 am">09:15 am</option><option value="09:30 am">09:30 am</option><option value="09:45 am">09:45 am</option><option value="10:00 am">10:00 am</option><option value="10:15 am">10:15 am</option><option value="10:30 am">10:30 am</option><option value="10:45 am">10:45 am</option><option value="11:00 am">11:00 am</option><option value="11:15 am">11:15 am</option><option value="11:30 am">11:30 am</option><option value="11:45 am">11:45 am</option><option value="12:00 pm">12:00 pm</option><option value="12:15 pm">12:15 pm</option><option value="12:30 pm">12:30 pm</option><option value="12:45 pm">12:45 pm</option><option value="1:00 pm">1:00 pm</option><option value="1:15 pm">1:15 pm</option><option value="1:30 pm">1:30 pm</option><option value="1:45 pm">1:45 pm</option><option value="2:00 pm">2:00 pm</option><option value="2:15 pm">2:15 pm</option><option value="2:30 pm">2:30 pm</option><option value="2:45 pm">2:45 pm</option><option value="3:00 pm">3:00 pm</option><option value="3:15 pm">3:15 pm</option><option value="3:30 pm">3:30 pm</option><option value="3:45 pm">3:45 pm</option><option value="4:00 pm">4:00 pm</option><option value="Close">Close</option>
EOT;
//}
// Globals {
    $days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
    if($_SESSION['debug']) { $er->add_error("%cServer-side info: %c", ['font-size:large;', 'color:black;'], true); }
//}
if($_REQUEST['message'] == "REQ_PAGE" && $_SESSION['priv']['level'] == 3) {
// Admin rosters {
    // Prefetch {
    global $er;
    $st = $conn->prepare("SELECT `uname`  FROM `staff`");
    $st->execute();
    $array = $st->fetchAll(PDO::FETCH_COLUMN);
    $st = $conn->prepare("SELECT `roster_auto_populate` FROM `settings`");
    $st->execute();
    $populate = $st->fetchColumn();
    $staffCount = count($array);
    $Roster_Head = <<<EOT
    <div class="section__content section__content--p30">
        <div class="container-fluid" id="content">
            <div class="col-md-12">
                <div class="overview-wrap">
                    <h2 class="title-1">Rosters</h2>
                </div></br>
            </div>
EOT;
//}
    // Functions {
    function days_until($date){
        return (isset($date)) ? floor((strtotime($date) - time())/60/60/24) : FALSE;
    }
    
    function check_roster($date) {
        global $conn;
        global $er;
        $format_date = date('Y-m-d', strtotime($date));
        try {
            $st = $conn->prepare("SELECT * FROM `rosters` WHERE `date_from`='$format_date'");
            $st->execute();
            if($st->rowCount() >= 1) {
                if($_SESSION['debug']) { $er->add_error("%cRoster set for %c" . $date . "%c? %cTrue",['font-style:italic;','color:blue;','color:black;', 'color:red;'],true); }
                return true;
            } else {
                if($_SESSION['debug']) { $er->add_error("%cRoster set for %c" . $date . "%c? %cFalse",['font-style:italic;','color:blue;','color:black;', 'color:red;'],true); }
                return false;
            }
            $st = null; 
        } catch (Exception $e) {
            return false;
        }
    }
    
    //}
    $days_to = days_until('next monday');
    $check = check_roster('next monday');
    if($_SESSION['debug']) { $er->add_error("%cDays until %c" . date('d/m/Y', strtotime('next monday')) . '%c: %c' . $days_to, ['font-style:italic;','color:blue;', 'color:black;', 'color:red;'], true); }
    // Less than 2 and not set {
    if($days_to <= 2 && $check == false) { 
        // RosterCreator {
            $RosterCreator = $Roster_Head . <<<EOT
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card" style="border-radius: 10px;">
                                    <div class="card-header">
                                        <strong class="card-title">Roster for week starting:<span class="input-group date" style="width: 20%;" id="date" data-target-input="nearest">
                                        	<input id="date-input" type="text" placeholder="Week starting" class="form-control datetimepicker-input" data-target="#date"/>
                                        	<span class="input-group-append" data-target="#date" data-toggle="datetimepicker">
                                        		<span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        	</span>
                                        </span></strong>
                                        <script>
                                        	$(function() {
                                        		$("#date").datetimepicker({
                                        			format: 'D/M/YYYY',
                                        			daysOfWeekDisabled: [0,2,3,4,5,6]
                                        		});
                                        	});
                                        </script>
                                    </div>
                                    <div class="card-body">
                                    <form id="roster-form" action="">
                                        <div class="form-group  table-responsive table--no-card m-b-30">
                                           <table id="roster" for="roster-form" class="table table-bordered table-earning" align="left">
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
            //}
        if($populate && $staffCount) {
        // Populate (True) && StaffCount (Not null) {
            $st = $conn->prepare("SELECT * FROM `rosters` ORDER BY id DESC LIMIT 1");
            $st->execute();
            $prev_roster = $st->fetchAll(PDO::FETCH_ASSOC);
            $st = null;
            for ($x = 0; $x < $staffCount; $x++) {
                $name = ucfirst($array[$x]);
                $RosterCreator .= "<tr><td>$name</td>";
                for ($day = 0; $day < 7; $day++) {
                    $namestart = $days[$day] . '_' . ucfirst($array[$x]) . '_start';
                    $namefinish = $days[$day] . '_' . ucfirst($array[$x]) . '_finish';
                    $tday = $days[$day];
                    $today_roster = json_decode($prev_roster[0][$tday], true);
                    $pstime = $today_roster[$name]['start'];
                    $pftime = $today_roster[$name]['finish'];
                    if ($pstime != "startTime") {
                        $pstime = str_replace('"', "'",$pstime);
                        $part = "<th id=\"$days[$day]\" name=\"$days[$day]\"><div class=\"rs-select2--trans rs-select2--md\" style=\"background-color: transparent;\"><select value=\"$name\" name=\"$namestart\" class=\"js-select2 select2-hidden-accessible\" tabindex=\"-1\" aria-hidden=\"true\" style=\"background-color: transparent;max-width:5%;\"><option value=\"startTime\">Start Time</option><option selected=\"selected\" value=\"$pstime\">$pstime</option>";             
                    } else {
                        $part = <<<EOT
                        <th id="$days[$day]" name="$days[$day]">
                            <div class="rs-select2--trans rs-select2--md" style="background-color: transparent;">
                                <select value="$name" name="$namestart" class="js-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" style=" background-color: transparent;">
                                    <option value="startTime">Start Time</option>
EOT;
                    }
                    $RosterCreator .= $part . $select_time . "</select></div>";
                            if ($pftime != "finishTime") {
                                $pftime = str_replace('"', "'", $pftime);
                                $part2 = "<div class=\"rs-select2--trans rs-select2--md\" style=\"background-color: transparent;\"><select value=\"$name\" name=\"$namefinish\" class=\"js-select2 select2-hidden-accessible\" style=\"background-color: transparent;max-width:5%;\"><option value=\"finishTime\">Finish Time</option><option selected=\"selected\" value=\"$pstime\" >$pftime</option>";             
                            } else {
                                $part2 = <<<EOT
                                <div class="rs-select2--trans rs-select2--md" style="background-color: transparent;">
                                    <select value="$name" name="$namefinish" class="js-select2 select2-hidden-accessible" style="background-color: transparent;max-width:5%;"> 
                                        <option value="finishTime">Finish Time</option>
EOT;
                            }
                            $RosterCreator .= $part2 . $select_time . "</select><span class=\"dropdown-wrapper\" style=\"background-color: transparent;\" aria-hidden=\"true\"></span></div></th>";
                }
                $RosterCreator .= "</tr>";
            }
        //}
        // Roster foot {
            $RosterCreator .= <<<EOT
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card" style="border-radius: 10px;">
                                        <div class="card-header">
                                            <strong class="card-title">
                                                Comments
                                            </strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <textarea class="form-control" id="roster-comments" rows="4"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" form="roster-form" class="btn btn-primary btn-lg btn-block">Submit Roster</button>
                                    <a href="#" id="download">Download Roster</a></br>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
EOT;
        //}
        } else if($staffCount) {
        // Populate (False) && StaffCount (Not null) {
            for ($x = 0; $x < $staffCount; $x++) {
                $name = ucfirst($array[$x]);
                $RosterCreator .= "<tr><td>$name</td>";
                for ($day = 0; $day < 7; $day++) {
                    $namestart = $days[$day] . '_' . ucfirst($array[$x]) . '_start';
                    $namefinish = $days[$day] . '_' . ucfirst($array[$x]) . '_finish';
                    $RosterCreator .= <<<EOT
                    <th id="$days[$day]" name="$days[$day]">
                        <div class="rs-select2--trans rs-select2--md" style="background-color: transparent;">
                            <select value="$name" name="$namestart" class="js-select2 select2-hidden-accessible" style="background-color: transparent; max-width:5%;">
                                <option selected="selected" value="startTime">Start Time</option>
EOT;
                    $RosterCreator .= $select_time . <<<EOT
                            </select>
                            <select value="$name" name="$namefinish" class="js-select2 select2-hidden-accessible" style="background-color: transparent;">
                            	<option selected="selected" value="finishTime">Finish Time</option>
EOT;
                    $RosterCreator .= $select_time . <<<EOT
                            </select> 
                            <span class="dropdown-wrapper" style="background-color: transparent;" aria-hidden="true"></span>
                        </div> 
                    </th>
EOT;
                }
            $RosterCreator .= "</tr>";
            } 
        // Roster foot {
            $RosterCreator .= <<<EOT
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card" style="border-radius: 10px;">
                                        <div class="card-header">
                                            <strong class="card-title">
                                                Comments
                                            </strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <textarea class="form-control" id="roster-comments" rows="4"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" form="roster-form" class="btn btn-primary btn-lg btn-block">Submit Roster</button>
                                    <a href="#" id="download">Download Roster</a></br>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
EOT;
            //}
        }
    //}
        $PrevRosters = "";
        // Grab rosters { Rosters are grabbed from last to first.
        try {
            $st = $conn->prepare("SELECT * FROM `rosters` WHERE `rosters`.`store_id` = '$store_id' ORDER BY `rosters`.`date_from` DESC");
            $st->execute();
            $All_Rosters = $st->fetchAll(PDO::FETCH_ASSOC);
            $st = null;
            if($_SESSION['debug']) { $er->add_error("%cGot rosters for store: %c" . $store_id, ['font-style:italic;', 'color:blue;'], true); }
        } catch (Exception $e) {
            if($_SESSION['debug']) { $er->add_error("%Failed to get rosters for store: %c" . $store_id . ' %creason: ' . $e, ['font-style:italic;', 'color:blue;', 'color:black;font-style:italic;','color:red;'], true); }
        }
        //}
        // Create rosters {
        try {
            $st = $conn->prepare("SELECT * FROM `staff` WHERE `store_id`='$store_id'");
            $st->execute();
            $staffCount = $st->rowCount();
            $staff = $st->fetchAll(PDO::FETCH_ASSOC);
            $st = null;
            if($_SESSION['debug']) { $er->add_error("%cGot staff for store: %c" . $store_id, ['font-style:italic;', 'color:blue;'], true); }
        } catch (PDOException $e) {
            if($_SESSION['debug']) { $er->add_error("%Failed to get staff for store: %c" . $store_id . ' %creason: ' . $e, ['font-style:italic;', 'color:blue;', 'color:black;font-style:italic;','color:red;'], true); }
        }
        if($_SESSION['debug']) { $er->add_error("%cStarting to create previous rosters.", ['color:black;'], true); }
        foreach ($All_Rosters as $key => $value) {
            $id = $value['id'];
            $date = date("d/m/Y", strtotime($value['date_from'])); 
            if($_SESSION['debug']) { $er->add_error("%cRoster with id %c". $id . " %cand date: %c" . $date, ['font-style:italic','color:blue','color:black;font-style:italic;','color:blue'], true); }
            // PrevRosters Head {
            $PrevRosters .= <<<EOT
            </div>
                <div id="$id" class="row">
                    <div class="col-lg-12">
                        <div class="card" style="border-radius: 10px;">
                            <div class="card-header">
                                <span class="badge badge-success">Completed <i class="fas fa-check"></i></span><strong style="margin-left:1vw;" class="card-title">Roster for week starting: $date</strong>
                                <span id="roster-show" style="cursor:pointer;" data-toggle="collapse" data-target="#roster-$id" name="$id"><i id="arrow" class="float-right fas fa-angle-down"></i></span>
                            </div>
                            <div class="card-body collapse" id="roster-$id">
                            <form id="roster-form" action="">
                                <div class="form-group  table-responsive table--no-card m-b-30">
                                    <table id="roster" for="roster-form" class="table table-bordered table-earning" align="left">
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
            //}
            // PrevRosters Body {
            $body = "";
            for ($i = 0; $i < $staffCount; $i++) {
                $nname = ucfirst($staff[$i]['uname']);
                $body .= "<tr><td>$nname</td>";
                for ($x = 0; $x < 7; $x++) {
                    $data = json_decode($value[$days[$i]], true);
                    $name_start = $data[$nname]["start"];
                    $name_finish = $data[$nname]["finish"];
                    if($name_start == "startTime" || $name_finish == "finishTime") {
                        $name_start = "Not working";
                        $name_finish = "";
                    }
                    $body .= "<td><div>$name_start</div><div>$name_finish</div></td>";
                }
                $body .= "</tr>";
            }
            $PrevRosters .= $body . "</tr></tbody></table></div></form></div></div></div>";
            //}
        }
//}
        // End of file data {
        $RosterData = $RosterCreator . $PrevRosters . <<<EOT
            </div><script>
                $(function(){
                    $("#roster-form").submit(function(e) {
                        e.preventDefault();
                        var roster = {};
                        var postData = $("#roster-form").serializeArray();
                        var len = postData.length, i, elem;
                        for(i = 0; i < len; i++) {
                            var path = postData[i].name.split("_");
                            elem = roster;
                            while(path.length) {
                                key = path.shift();
                                if(path.length) {
                                    if(typeof elem[key] === "undefined") elem[key] = {};
                                    elem = elem[key];    
                                }
                            }
                            elem[key] = postData[i].value;
                        }
                        roster.startDate = document.getElementById('date-input').value;
                        roster.comments = document.getElementById('roster-comments').value;
                        $.ajax({
                            type: 'POST',
                            url: 'backend/ajax/rostersfunc.php',
                            dataType: 'json',
                            data:
                                {
                                    message: "UPDATE_ROSTER",
                                    data: JSON.stringify(roster)
                                },
                            success: function(result){
                                if(result.success) {
                                    $('#error').attr('data-title', 'Roster status');
                                    $('#error').attr('data-content', result.value);
                                    $('#error').popover('show');
                                } else if (!result.success) {
                                    $('#error').attr('data-title', 'Roster status');
                                    $('#error').attr('data-content', result.errors);
                                    $('#error').popover('show');
                                } else {
                                    console.log('error here');
                                }
                            }, 
                            error: function(){
                                console.log('error here');
                            }
                        });
                        /*$.ajax({
                            type: 'POST',
                            url: 'backend/ajax/noticesfunc.php',
                            dataType: 'json',
                            data:
                                {
                                    message: "ROSTER_POST",
                                    data: JSON.stringify(roster)
                                },
                            success: function(result){
                                window.client.send("UP_NOTI");
                            }, 
                            error: function(){}
                        });*/
                    });
                    
                    $("#download").on('click', function() {
                        var container = document.getElementById("content");
                		html2canvas(container).then(function(canvas) {
                            var link = document.createElement("a");
                            document.body.appendChild(link);
                            link.download = "html_image.png";
                            link.href = canvas.toDataURL("image/png");
                            link.target = '_blank';
                            link.click();
                        }); 
                    });
                    $(document).ready(function() {
                        $('.js-select2').select2({
                            minimumResultsForSearch: -1
                        });
                    });
                    $('#arrow').on('click', function() {
                        if ($(this).prop('class') == "float-right fas fa-angle-down") {
                            $(this).prop('class', "float-right fas fa-angle-up");
                        } else {
                            $(this).prop('class', "float-right fas fa-angle-down");
                        }
                    });
                });
            </script>
EOT;
    //}
        if($_SESSION['debug']) {
            exit(
                json_encode(
                    array(
                        'success' => true,
                        'value' => base64_encode($RosterData),
                        'errors' => $er->generate()
                    )
                )
            );
        } else {
            exit(
                json_encode(
                    array(
                        'success' => true,
                        'value' => base64_encode($RosterData)
                    )
                )
            );
        }
    //}
    // More than 2 and not set {
    } else if ($days_to > 2 && $check == false) {
        // RosterCreator {
            $RosterCreator_Head = $Roster_Head . <<<EOT
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card" style="border-radius: 10px;">
                            <div class="card-header">
                                <strong class="card-title">Roster for week starting:<span class="input-group date" style="width: 20%;" id="date" data-target-input="nearest">
                                	<input id="date-input" type="text" placeholder="Week starting" class="form-control datetimepicker-input" data-target="#date"/>
                                	<span class="input-group-append" data-target="#date" data-toggle="datetimepicker">
                                		<span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                	</span>
                                </span></strong>
                                <script>
                                	$(function() {
                                		$("#date").datetimepicker({
                                			format: 'D/M/YYYY',
                                			daysOfWeekDisabled: [0,2,3,4,5,6]
                                		});
                                	});
                                </script>
                            </div>
                            <div class="card-body">
                            <form id="roster-form" action="">
                                <div class="form-group  table-responsive table--no-card m-b-30">
                                   <table id="roster" for="roster-form" class="table table-bordered table-earning" align="left">
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
            //}
        if($populate && $staffCount) {
        // Populate (True) && StaffCount (Not null) {
            $RosterCreator = $RosterCreator_Head;
            $st = $conn->prepare("SELECT * FROM `rosters` ORDER BY id DESC LIMIT 1");
            $st->execute();
            $prev_roster = $st->fetchAll(PDO::FETCH_ASSOC);
            $st = null;
            for ($x = 0; $x < $staffCount; $x++) {
                $name = ucfirst($array[$x]);
                $RosterCreator .= "<tr><td>$name</td>";
                for ($day = 0; $day < 7; $day++) {
                    $namestart = $days[$day] . '_' . ucfirst($array[$x]) . '_start';
                    $namefinish = $days[$day] . '_' . ucfirst($array[$x]) . '_finish';
                    $tday = $days[$day];
                    $today_roster = json_decode($prev_roster[0][$tday], true);
                    $pstime = $today_roster[$name]['start'];
                    $pftime = $today_roster[$name]['finish'];
                    if ($pstime != "startTime") {
                        $pstime = str_replace('"', "'",$pstime);
                        $part = "<th id=\"$days[$day]\" name=\"$days[$day]\"><div class=\"rs-select2--trans rs-select2--md\" style=\"background-color: transparent;\"><select value=\"$name\" name=\"$namestart\" class=\"js-select2 select2-hidden-accessible\" tabindex=\"-1\" aria-hidden=\"true\" style=\"background-color: transparent;max-width:5%;\"><option value=\"startTime\">Start Time</option><option selected=\"selected\" value=\"$pstime\">$pstime</option>";             
                    } else {
                        $part = <<<EOT
                        <th id="$days[$day]" name="$days[$day]">
                            <div class="rs-select2--trans rs-select2--md" style="background-color: transparent;">
                                <select value="$name" name="$namestart" class="js-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" style=" background-color: transparent;">
                                    <option value="startTime">Start Time</option>
EOT;
                    }
                    $RosterCreator .= $part . $select_time . <<<EOT
                            </select>
                        </div>
EOT;
                            if ($pftime != "finishTime") {
                                $pftime = str_replace('"', "'", $pftime);
                                $part2 = "<div class=\"rs-select2--trans rs-select2--md\" style=\"background-color: transparent;\"><select value=\"$name\" name=\"$namefinish\" class=\"js-select2 select2-hidden-accessible\" style=\"background-color: transparent;max-width:5%;\"><option value=\"finishTime\">Finish Time</option><option selected=\"selected\" value=\"$pstime\" >$pftime</option>";             
                            } else {
                                $part2 = <<<EOT
                                <div class="rs-select2--trans rs-select2--md" style="background-color: transparent;">
                                    <select value="$name" name="$namefinish" class="js-select2 select2-hidden-accessible" style="background-color: transparent;max-width:5%;"> 
                                        <option value="finishTime">Finish Time</option>
EOT;
                            }
                            $RosterCreator .= $part2 . $select_time . <<<EOT
                            </select>  
                            <span class="dropdown-wrapper" style="background-color: transparent;" aria-hidden="true"></span>
                        </div>
                    </th>
EOT;
                }
                $RosterCreator .= "</tr>";
            }
        //}
        } else if($staffCount) {
        // Populate (False) && StaffCount (Not null) {
            $RosterCreator = $RosterCreator_Head;
            for ($x = 0; $x < $staffCount; $x++) {
                $name = ucfirst($array[$x]);
                $RosterCreator .= "<tr><td>$name</td>";
                for ($day = 0; $day < 7; $day++) {
                    $namestart = $days[$day] . '_' . ucfirst($array[$x]) . '_start';
                    $namefinish = $days[$day] . '_' . ucfirst($array[$x]) . '_finish';
                    $RosterCreator .= <<<EOT
                    <th id="$days[$day]" name="$days[$day]">
                        <div class="rs-select2--trans rs-select2--md" style="background-color: transparent;">
                            <select value="$name" name="$namestart" class="js-select2 select2-hidden-accessible" style="background-color: transparent; max-width:5%;">
                                <option selected="selected" value="startTime">Start Time</option>
EOT;
                    $RosterCreator .= $select_time . <<<EOT
                            </select>
                            <select value="$name" name="$namefinish" class="js-select2 select2-hidden-accessible" style="background-color: transparent;">
                            	<option selected="selected" value="finishTime">Finish Time</option>
EOT;
                    $RosterCreator .= $select_time . <<<EOT
                            </select> 
                            <span class="dropdown-wrapper" style="background-color: transparent;" aria-hidden="true"></span>
                        </div> 
                    </th>
EOT;
                }
            $RosterCreator .= "</tr>";
            } 
        }
    // Roster foot {
    $RosterCreator .= <<<EOT
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card" style="border-radius: 10px;">
                                <div class="card-header">
                                    <strong class="card-title">
                                        Comments
                                    </strong>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <textarea class="form-control" id="roster-comments" rows="4"></textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" form="roster-form" class="btn btn-primary btn-lg btn-block">Submit Roster</button>
                            <a href="#" id="download">Download Roster</a></br>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div></div>
EOT;
        //}
    //}
        // Grab rosters {
        $PrevRosters = "";
        try {
            $st = $conn->prepare("SELECT * FROM `rosters` WHERE `rosters`.`store_id` = '$store_id' ORDER BY `rosters`.`date_from` DESC");
            $st->execute();
            $All_Rosters = $st->fetchAll(PDO::FETCH_ASSOC);
            $st = null;
            if($_SESSION['debug']) { $er->add_error("%cGot rosters for store: %c" . $store_id, ['font-style:italic;', 'color:blue;'], true); }
        } catch (Exception $e) {
            if($_SESSION['debug']) { $er->add_error("%Failed to get rosters for store: %c" . $store_id . ' %creason: ' . $e, ['font-style:italic;', 'color:blue;', 'color:black;font-style:italic;','color:red;'], true); }
        }
        //}
        // Create rosters {
        try {
            $st = $conn->prepare("SELECT * FROM `staff` WHERE `store_id`='$store_id'");
            $st->execute();
            $staffCount = $st->rowCount();
            $staff = $st->fetchAll(PDO::FETCH_ASSOC);
            $st = null;
            if($_SESSION['debug']) { $er->add_error("%cGot staff for store: %c" . $store_id, ['font-style:italic;', 'color:blue;'], true); }
        } catch (PDOException $e) {
            if($_SESSION['debug']) { $er->add_error("%Failed to get staff for store: %c" . $store_id . ' %creason: ' . $e, ['font-style:italic;', 'color:blue;', 'color:black;font-style:italic;','color:red;'], true); }
        }
        if($_SESSION['debug']) { $er->add_error("%cStarting to create previous rosters.", ['color:black;'], true); }
        foreach ($All_Rosters as $key => $value) {
            $id = $value['id'];
            $date = date("d/m/Y", strtotime($value['date_from'])); 
            if($_SESSION['debug']) { $er->add_error("%cRoster with id %c". $id . " %cand date: %c" . $date, ['font-style:italic','color:blue','color:black;font-style:italic;','color:blue'], true); }
            // PrevRosters Head {
            $PrevRosters .= <<<EOT
                <div id="$id" class="row">
                    <div class="col-lg-12">
                        <div class="card" style="border-radius: 10px;">
                            <style>
                                .roster-head {
                                    height:8vh;
                                    padding-top: 25px;
                                    padding-bottom: 25px;
                                }
                                .roster-head strong {
                                    margin-left: 1vw;
                                }
                                .roster-head #arrow {
                                    font-size: 2em;
                                }
                                .roster-head #roster-show {
                                    cursor: pointer;
                                }
                            </style>
                            <div class="card-header roster-head">
                                <span class="badge badge-success">Completed <i class="fas fa-check"></i></span><strong class="card-title">Roster for week starting: $date</strong>
                                <span id="roster-show" data-toggle="collapse" data-target="#roster-$id" name="$id"><i id="arrow" class="float-right fas fa-angle-down"></i></span>
                            </div>
                        <div class="collapse" id="roster-$id">
                            <div class="card-body">
                                <div class="table-responsive table--no-card m-b-30">
                                    <table id="roster" class="table table-bordered table-earning" align="left">
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
            //}
            // PrevRosters Body {
            $body = "";
            for ($i = 0; $i < $staffCount; $i++) {
                $nname = ucfirst($staff[$i]['uname']);
                $body .= "<tr><td>$nname</td>";
                for ($x = 0; $x < 7; $x++) {
                    $data = json_decode($value[$days[$x]], true);
                    $name_start = $data[$nname]["start"];
                    $name_finish = $data[$nname]["finish"];
                    if($name_start == "startTime" || $name_finish == "finishTime") {
                        $name_start = "Not working";
                        $name_finish = "";
                    }
                    $body .= "<td><div>$name_start</div><div>$name_finish</div></td>";
                }
                $body .= "</tr>";
            }
            $PrevRosters .= $body . "</tr></tbody></table></div></div></div></div></div></div>";
            //}
        }
        $RosterData = $RosterCreator . $PrevRosters . <<<EOT
            <script>
                $(document).ready(function(){
                    $("#roster-form").submit(function(e) {
                        e.preventDefault();
                        var roster = {};
                        var postData = $("#roster-form").serializeArray();
                        var len = postData.length, i, elem;
                        for(i = 0; i < len; i++) {
                            var path = postData[i].name.split("_");
                            elem = roster;
                            while(path.length) {
                                key = path.shift();
                                if(path.length) {
                                    if(typeof elem[key] === "undefined") elem[key] = {};
                                    elem = elem[key];    
                                }
                            }
                            elem[key] = postData[i].value;
                        }
                        roster.startDate = document.getElementById('date-input').value;
                        roster.comments = document.getElementById('roster-comments').value;
                        $.ajax({
                            type: 'POST',
                            url: 'backend/ajax/rostersfunc.php',
                            dataType: 'json',
                            data:
                                {
                                    message: "UPDATE_ROSTER",
                                    data: JSON.stringify(roster)
                                },
                            success: function(result){
                                if(result.success) {
                                    $('#error').attr('data-title', 'Roster status');
                                    $('#error').attr('data-content', result.value);
                                    $('#error').popover('show');
                                    console.log(result.errors);
                                } else if (!result.success) {
                                    $('#error').attr('data-title', 'Roster status');
                                    $('#error').attr('data-content', result.errors);
                                    $('#error').popover('show');
                                    console.log(result.errors);
                                } else {
                                    console.log('error here');
                                }
                            }, 
                            error: function(){
                                console.log('error here');
                            }
                        });
                        /*$.ajax({
                            type: 'POST',
                            url: 'backend/ajax/noticesfunc.php',
                            dataType: 'json',
                            data:
                                {
                                    message: "ROSTER_POST",
                                    data: JSON.stringify(roster)
                                },
                            success: function(result){
                                window.client.send("UP_NOTI");
                            }, 
                            error: function(){}
                        });*/
                    });
                    
                    $("#download").on('click', function() {
                        var container = document.getElementById("content");
                		html2canvas(container).then(function(canvas) {
                            var link = document.createElement("a");
                            document.body.appendChild(link);
                            link.download = "html_image.png";
                            link.href = canvas.toDataURL("image/png");
                            link.target = '_blank';
                            link.click();
                        }); 
                    });
                    $(document).ready(function() {
                        $('.js-select2').select2({
                            minimumResultsForSearch: -1
                        });
                    });
                    $('#arrow').on('click', function() {
                        if ($(this).prop('class') == "float-right fas fa-angle-down") {
                            $(this).prop('class', "float-right fas fa-angle-up");
                        } else {
                            $(this).prop('class', "float-right fas fa-angle-down");
                        }
                    });
                });
            </script>
EOT;
//}
        if($_SESSION['debug']) {
            exit(
                json_encode(
                    array(
                        'success' => true,
                        'value' => base64_encode($RosterData),
                        'errors' => $er->generate()
                    )
                )
            );
        } else {
            exit(
                json_encode(
                    array(
                        'success' => true,
                        'value' => base64_encode($RosterData)
                    )
                )
            );
        }
    //}
    // Any and set {
    } else if ($check == true) {
        $RosterCreator = <<<EOT
            <div id="creator" class="row">
                <div class="col-lg-12">
                    <div class="card" style="border-radius: 10px;">
                        <div class="card-header">
                            <span class="badge badge-success">Create new roster</span>
                            <span id="roster-show" style="cursor:pointer;" data-toggle="collapse" data-target="#roster-creator" name="creator"><i id="arrow" class="float-right fas fa-angle-down"></i></span>
                        </div>
                        <div class="card-body collapse" id="roster-creator">
                            <div class="card" style="border-radius: 10px;">
                                <div class="card-header">
                                    <strong class="card-title">Roster for week starting:<span class="input-group date" style="width: 20%;" id="date" data-target-input="nearest">
                                        <input id="date-input" type="text" placeholder="Week starting" class="form-control datetimepicker-input" data-target="#date"/>
                                        <span class="input-group-append" data-target="#date" data-toggle="datetimepicker">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </span>
                                    </span></strong>
                                    <script>
                                        $(function() {
                                            $("#date").datetimepicker({
                                                format: 'D/M/YYYY',
                                                daysOfWeekDisabled: [0,2,3,4,5,6]
                                            });
                                        });
                                    </script>
                                </div>
                                <div class="card-body">
                                <form id="roster-form" action="">
                                    <div class="form-group  table-responsive table--no-card m-b-30">
                                    <table id="roster" for="roster-form" class="table table-bordered table-earning" align="left">
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
        if($populate && $staffCount) {
            $st = $conn->prepare("SELECT * FROM `rosters` ORDER BY id DESC LIMIT 1");
            $st->execute();
            $prev_roster = $st->fetchAll(PDO::FETCH_ASSOC);
            $st = null;
            for ($x = 0; $x < $staffCount; $x++) {
                $name = ucfirst($array[$x]);
                $RosterCreator .= "<tr><td>$name</td>";
                for ($day = 0; $day < 7; $day++) {
                    $namestart = $days[$day] . '_' . ucfirst($array[$x]) . '_start';
                    $namefinish = $days[$day] . '_' . ucfirst($array[$x]) . '_finish';
                    $tday = $days[$day];
                    $today_roster = json_decode($prev_roster[0][$tday], true);
                    $pstime = $today_roster[$name]['start'];
                    $pftime = $today_roster[$name]['finish'];
                    if ($pstime != "startTime") {
                        $pstime = str_replace('"', "'",$pstime);
                        $part = "<th id=\"$days[$day]\" name=\"$days[$day]\"><div class=\"rs-select2--trans rs-select2--md\" style=\"background-color: transparent;\"><select value=\"$name\" name=\"$namestart\" class=\"js-select2 select2-hidden-accessible\" tabindex=\"-1\" aria-hidden=\"true\" style=\"background-color: transparent;max-width:5%;\"><option value=\"startTime\">Start Time</option><option selected=\"selected\" value=\"$pstime\">$pstime</option>";             
                    } else {
                        $part = <<<EOT
                        <th id="$days[$day]" name="$days[$day]">
                            <div class="rs-select2--trans rs-select2--md" style="background-color: transparent;">
                                <select value="$name" name="$namestart" class="js-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" style=" background-color: transparent;">
                                    <option value="startTime">Start Time</option>
EOT;
			        }
                    $RosterCreator .= $part . $select_time . "</select></div>";
                            if ($pftime != "finishTime") {
                                $pftime = str_replace('"', "'", $pftime);
                                $part2 = "<div class=\"rs-select2--trans rs-select2--md\" style=\"background-color: transparent;\"><select value=\"$name\" name=\"$namefinish\" class=\"js-select2 select2-hidden-accessible\" style=\"background-color: transparent;max-width:5%;\"><option value=\"finishTime\">Finish Time</option><option selected=\"selected\" value=\"$pstime\" >$pftime</option>";             
                            } else {
                                $part2 = <<<EOT
                                <div class="rs-select2--trans rs-select2--md" style="background-color: transparent;">
                                    <select value="$name" name="$namefinish" class="js-select2 select2-hidden-accessible" style="background-color: transparent;max-width:5%;"> 
                                        <option value="finishTime">Finish Time</option>
EOT;
					        }       
					        $RosterCreator .= $part2 . $select_time . "</select><span class=\"dropdown-wrapper\" style=\"background-color: transparent;\" aria-hidden=\"true\"></span></div></th>";
		        }
		        $RosterCreator .= "</tr>";
	        }
	        $RosterCreator .= <<<EOT
							</tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<div class="card" style="border-radius: 10px;">
								<div class="card-header">
									<strong class="card-title">
										Comments
									</strong>
								</div>
								<div class="card-body">
									<div class="form-group">
										<textarea class="form-control" id="roster-comments" rows="4"></textarea>
									</div>
								</div>
							</div>
							<button type="submit" form="roster-form" class="btn btn-primary btn-lg btn-block">Submit Roster</button>
							<a href="#" id="download">Download Roster</a></br>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
EOT;
//}
        } else if($staffCount) {
        // Populate (False) && StaffCount (Not null) {
            for ($x = 0; $x < $staffCount; $x++) {
                $name = ucfirst($array[$x]);
                $RosterCreator .= "<tr><td>$name</td>";
                for ($day = 0; $day < 7; $day++) {
                    $namestart = $days[$day] . '_' . ucfirst($array[$x]) . '_start';
                    $namefinish = $days[$day] . '_' . ucfirst($array[$x]) . '_finish';
                    $RosterCreator .= <<<EOT
                    <th id="$days[$day]" name="$days[$day]">
                        <div class="rs-select2--trans rs-select2--md" style="background-color: transparent;">
                            <select value="$name" name="$namestart" class="js-select2 select2-hidden-accessible" style="background-color: transparent; max-width:5%;">
                                <option selected="selected" value="startTime">Start Time</option>
EOT;
                    $RosterCreator .= $select_time . <<<EOT
                            </select>
                            <select value="$name" name="$namefinish" class="js-select2 select2-hidden-accessible" style="background-color: transparent;">
                                <option selected="selected" value="finishTime">Finish Time</option>
EOT;
                    $RosterCreator .= $select_time . <<<EOT
                            </select> 
                            <span class="dropdown-wrapper" style="background-color: transparent;" aria-hidden="true"></span>
                        </div> 
                    </th>
EOT;
		        }
	            $RosterCreator .= "</tr>";
	        } 
// Roster foot {
	        $RosterCreator .= <<<EOT
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card" style="border-radius: 10px;">
                                        <div class="card-header">
                                            <strong class="card-title">
                                                Comments
                                            </strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <textarea class="form-control" id="roster-comments" rows="4"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" form="roster-form" class="btn btn-primary btn-lg btn-block">Submit Roster</button>
                                    <a href="#" id="download">Download Roster</a></br>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
EOT;
	//}
        }
        $PrevRosters = "";
        try {
            $st = $conn->prepare("SELECT * FROM `rosters` WHERE `rosters`.`store_id` = '$store_id' ORDER BY `rosters`.`date_from` DESC");
            $st->execute();
            $All_Rosters = $st->fetchAll(PDO::FETCH_ASSOC);
            $st = null;
            if($_SESSION['debug']) { $er->add_error("%cGot rosters for store: %c" . $store_id, ['font-style:italic;', 'color:blue;'], true); }
        } catch (Exception $e) {
            if($_SESSION['debug']) { $er->add_error("%Failed to get rosters for store: %c" . $store_id . ' %creason: ' . $e, ['font-style:italic;', 'color:blue;', 'color:black;font-style:italic;','color:red;'], true); }
        }
        //}
        // Create rosters {
        try {
            $st = $conn->prepare("SELECT * FROM `staff` WHERE `store_id`='$store_id'");
            $st->execute();
            $staffCount = $st->rowCount();
            $staff = $st->fetchAll(PDO::FETCH_ASSOC);
            $st = null;
            if($_SESSION['debug']) { $er->add_error("%cGot staff for store: %c" . $store_id, ['font-style:italic;', 'color:blue;'], true); }
        } catch (PDOException $e) {
            if($_SESSION['debug']) { $er->add_error("%Failed to get staff for store: %c" . $store_id . ' %creason: ' . $e, ['font-style:italic;', 'color:blue;', 'color:black;font-style:italic;','color:red;'], true); }
        }
        if($_SESSION['debug']) { $er->add_error("%cStarting to create previous rosters.", ['color:black;'], true); }
        $PrevRosters = $Roster_Head;
        foreach ($All_Rosters as $key => $value) {
            $id = $value['id'];
            $date = date("d/m/Y", strtotime($value['date_from'])); 
            if($_SESSION['debug']) { $er->add_error("%cRoster with id %c". $id . " %cand date: %c" . $date, ['font-style:italic','color:blue','color:black;font-style:italic;','color:blue'], true); }
            // PrevRosters Head {
            $PrevRosters .= <<<EOT
                <div id="$id" class="row">
                    <div class="col-lg-12">
                        <div class="card" style="border-radius: 10px;">
                            <style>
                                .roster-head {
                                    height:8vh;
                                    padding-top: 25px;
                                    padding-bottom: 25px;
                                }
                                .roster-head strong {
                                    margin-left: 1vw;
                                }
                                .roster-head #arrow {
                                    font-size: 2em;
                                }
                                .roster-head #roster-show {
                                    cursor: pointer;
                                }
                            </style>
                            <div class="card-header roster-head">
                                <span class="badge badge-success">Completed <i class="fas fa-check"></i></span><strong class="card-title">Roster for week starting: $date</strong>
                                <span id="roster-show" data-toggle="collapse" data-target="#roster-$id" name="$id"><i id="arrow" class="float-right fas fa-angle-down"></i></span>
                            </div>
                            <script>
                                $(function() {
                                    $('#arrow').on('click', function() {
                                        if ($(this).prop('class') == "float-right fas fa-angle-down") {
                                            $(this).prop('class', "float-right fas fa-angle-up");
                                        } else {
                                            $(this).prop('class', "float-right fas fa-angle-down");
                                        }
                                    });
                                });
                            </script>
                        <div class="collapse" id="roster-$id">
                            <div class="card-body">
                                <div class="table-responsive table--no-card m-b-30">
                                    <table id="roster" class="table table-bordered table-earning" align="left">
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
            //}
            // PrevRosters Body {
            $body = "";
            for ($i = 0; $i < $staffCount; $i++) {
                $nname = ucfirst($staff[$i]['uname']);
                $body .= "<tr><td>$nname</td>";
                for ($x = 0; $x < 7; $x++) {
                    $data = json_decode($value[$days[$x]], true);
                    $name_start = $data[$nname]["start"];
                    $name_finish = $data[$nname]["finish"];
                    if($name_start == "startTime" || $name_finish == "finishTime") {
                        $name_start = "Not working";
                        $name_finish = "";
                    }
                    $body .= "<td><div>$name_start</div><div>$name_finish</div></td>";
                }
                $body .= "</tr>";
            }
            $PrevRosters .= $body . "</tr></tbody></table></div></div></div></div></div></div>";
            //}
        }
        if($_SESSION['debug']) {
            exit(
                json_encode(
                    array(
                        'success' => true,
                        'value' => base64_encode($PrevRosters),
                        'errors' => $er->generate()
                    )
                )
            );
        } else {
            exit(
                json_encode(
                    array(
                        'success' => true,
                        'value' => base64_encode($PrevRosters)
                    )
                )
            );
        }
    //}
    //}
    // Else {
    } else {
        $RosterData = "Rip";
        if($_SESSION['debug']) {
            exit(
                json_encode(
                    array(
                        'success' => true,
                        'value' => base64_encode($RosterData),
                        'errors' => $er->generate()
                    )
                )
            );
        } else {
            exit(
                json_encode(
                    array(
                        'success' => true,
                        'value' => base64_encode($RosterData)
                    )
                )
            );
        }
    }
    //}
    
//}
} elseif ($_REQUEST['message'] == "REQ_PAGE" && $_SESSION['priv']['level'] < 3) {
// Staff Rosters {
    $Roster_Head = <<<EOT
        <div class="section__content section__content--p30">
                <div class="container-fluid" id="content">
                    <div class="col-md-12">
                        <div class="overview-wrap">
                            <h2 class="title-1">Rosters</h2>    
                        </div></br>
                    </div>
                    <div class="row" style=\"cursor: default;\">
                        <div class="col-lg-12" style=\"cursor: default;\">
                            <div class="card" style="border-radius: 10px;">
                                <div class="card-header">
                                </div> //fix funky shit here
                            <div class="card-body">
        				   <table id="roster" class="table table-borderless table-striped table-earning" align="left">
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
        					  <tbody align="left" style=\"cursor: default;\">
EOT;
                            $st = $conn->prepare("SELECT `uname`  FROM `staff`");
                            $st->execute();
                            $array = $st->fetchAll(PDO::FETCH_COLUMN);
                            $staffCount = count($array);
                            $st = null;
                            $RosterCreator = "";
                            $st = $conn->prepare("SELECT * FROM `rosters` ORDER BY id DESC LIMIT 1");
                            $st->execute();
                            $prev_roster = $st->fetchAll(PDO::FETCH_ASSOC);
                            $st = null;
                            $days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
                            for ($x = 0; $x < $staffCount; $x++) {
                            	$name = ucfirst($array[$x]);
                            	$RosterCreator .= "<tr style=\"cursor: default;\"><td style=\"font-weight: bold; cursor: default;\">$name</td>";
                            	$namestart = ucfirst($array[$x]) . '_start';
                            	$namefinish = ucfirst($array[$x]) . '_finish';
                            	for ($day = 0; $day < 7; $day++) {
                            		$tday = $days[$day];
                            		$roster = json_decode($prev_roster[0][$tday], TRUE);
                            		$pstime = $roster[$name]['start'];
                            		$pftime = $roster[$name]['finish'];
                            		if ($pstime != "startTime") {
                            			//$pstime = str_replace('"', "'",$pstime);
                            			$RosterCreator .= "<td><p style=\"cursor: default;\">$pstime</p>";             
                            		} else {
                            			$RosterCreator .= "<td><p style=\"cursor: default;\">Not working</p>";
                            		}
                            		if ($pftime != "finishTime") {
                            			//$pftime = str_replace('"', "'",$pftime);
                            			$RosterCreator .= "<p style=\"cursor: default;\">$pftime</p></td>";             
                            		} else {
                            			$RosterCreator .= "</td>";
                            		}
                            	}
                            	$RosterCreator .= "</tr>";
                            }
                            $TableFoot = <<<EOT
                            					</tbody>
                            				</table>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
EOT;
    $html = base64_encode($Roster_Head . $RosterCreator . $TableFoot);    
    if($_SESSION['debug']) {
        exit(
            json_encode(
                array(
                    'success' => true,
                    'value' => base64_encode($html),
                    'errors' => $er->generate()
                )
            )
        );
    } else {
        exit(
            json_encode(
                array(
                    'success' => true,
                    'value' => base64_encode($html)
                )
            )
        );
    }
//}

//AJAX POST
} elseif ($_POST['message'] == "UPDATE_ROSTER") {
// Update Roster {
    $roster = json_decode(stripslashes($_POST['data']), true);
    $rdate = DateTime::createFromFormat('d/m/Y', $roster['startDate']);
    $rdate = $rdate->format('Y-m-d');
    $st = $conn->prepare("SELECT `date_from` FROM `rosters` WHERE `store_id`='$store_id' ORDER BY `id` DESC LIMIT 1");
    $st->execute();
    $date_check = $st->fetchColumn();
    $monday = json_encode($roster['monday']);
    $error .= "\n" . $monday;
    $tuesday = json_encode($roster['tuesday']);
    $wednesday = json_encode($roster['wednesday']);
    $thursday = json_encode($roster['thursday']);
    $friday = json_encode($roster['friday']);
    $saturday = json_encode($roster['saturday']);
    $sunday = json_encode($roster['sunday']);
    $comments = $roster['comments'];
    if($rdate == $date_check) {
        try {
            $st = $conn->prepare("UPDATE `rosters` SET monday='$monday', tuesday='$tuesday', wednesday='$wednesday', thursday='$thursday', friday='$friday', saturday='$saturday', sunday='$sunday', comments='$comments', edited='1' WHERE `date_from`='$rdate' AND `store_id`='$store_id'");
            $st->execute();
            $st = null;
            $msg = 'Roster for ' . $roster['startDate'] . ' updated.';
            echo json_encode(array('success' => true, 'value' => $msg, 'errors' => addslashes(str_replace('"', "'", $error))));
        } catch (PDOException $e) {
            echo json_encode(array('success' => false, 'value' => 'Failed to update roster.', 'errors' => addslashes(str_replace('"', "'", $error))));
        }
    } else {
        try {
            $st = $conn->prepare("INSERT INTO `rosters` (store_id, date_from, monday, tuesday, wednesday, thursday, friday, saturday, sunday, comments) VALUES ('$store_id', '$rdate', '$monday', '$tuesday', '$wednesday', '$thursday', '$friday', '$saturday', '$sunday', '$comments')");
            $st->execute();
            $st = null;
            echo json_encode(array('success' => true, 'value' => 'New roster uploaded.', 'errors' => addslashes(str_replace('"', "'", $error))));
        } catch (PDOException $e) {
            $error .= "\nPDO Exception on line: " . $e->getLine() . " saying: " . $e->getMessage();
            echo json_encode(array('success' => false, 'value' => 'Failed to insert new roster.', 'errors' => addslashes(str_replace('"', "'", $error))));
        }
    }
//}
} else {
    error_log($time . "Nothing?".PHP_EOL, 3, "rosterfuncs.log");
}

?>