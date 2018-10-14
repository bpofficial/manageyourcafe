<?php

class page extends system {

    public function __construct() {
        system::__construct($_SESSION);
    }

    public function notice() {
        $notice = new notice();
        return $notice->build();
    }

    public function roster() {
        $roster = new roster();
        return $roster->build();
    }

    public function calendar() {
        $calendar = new calendar();
        return $calendar->build();
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
            $day = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
            $UserSettings = "";
            for ($x = 0; $x < 7; $x++) {
                $today = $day[$x];
                $uday = ucfirst($today);
                $UserSettings .= <<<HTML
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
HTML;
            }
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
                //TODO: Fix :)
            }
        } else if ($page == "notices") {

        } else if ($page == "rosters") {

        } else if ($page == "analytics") {

        } else if ($page == "calendar") {

        } else if ($page == "advanced") {

        }
        return base64_encode($data);
    #endregion
    }
}

?>