<?php

class page extends system {

    public function __construct() {
        system::__construct($_SESSION);
    }
    
    public function generateNoticePage(string $store_id, string $user, array $options = null, bool $notices_only = false) {
        
    }

    public function notice() {
        $notice = new notice();
        return $notice->build();
    }

    public function roster() {
        $roster = new roster();
        return $roster->build();
    }

    public function setting() {
        $settings = new setting();
        return $setting->build();
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
        global $name, $conn, $error;
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

?>