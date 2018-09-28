<?php
include('config.php');
$st = $conn->prepare("SELECT * FROM `rosters` ORDER BY `date_from` DESC LIMIT 1");
$st->execute();
$prev_roster = $st->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($prev_roster[0]);
/* array structure
    Array (
        [0] (
            ...
            "monday":
                {  
                    "Adam":{  
                        "start":"startTime",
                        "finish":"finishTime"
                    },
                    "Sammi":{  
                        "start":"startTime",
                        "finish":"finishTime"
                    },
                    "Whitney":{  
                        "start":"startTime",
                        "finish":"finishTime"
                    },
                    "Byron":{  
                        "start":"startTime",
                        "finish":"finishTime"
                    },
                    "Miranda":{  
                        "start":"startTime",
                        "finish":"finishTime"
                    },
                    "Brayden":{  
                        "start":"startTime",
                        "finish":"finishTime"
                    },
                    "Tom":{  
                        "start":"startTime",
                        "finish":"finishTime"
                    },
                    "Leo":{  
                        "start":"startTime",
                        "finish":"finishTime"
                    },
                    "Brittany":{  
                        "start":"startTime",
                        "finish":"finishTime"
                    }
                }           
            ...
        )
    )

for ($x = 0; $x < $staffCount; $x++) {
    $name = ucfirst($array[$x]);
    $ROSTER_CREATOR_BODY .= "<tr><th class=\"fixed-sub\">" . $name . "</th>";
    for ($day = 0; $day < 7; $day++) {
        $namestart = $days[$day] . '_' . ucfirst($array[$x]) . '_start';
        $namefinish = $days[$day] . '_' . ucfirst($array[$x]) . '_finish';
        $today_roster = json_decode($prev_roster[0][$days[$day]], true);
        $pstime = $today_roster[$name]['start'];
        $pftime = $today_roster[$name]['finish'];
    }
}
/*
JS:

    var roster = JSON.parse(data);
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
            start_time = todays_roster[current_name]['start'];
            finish_time = todays_roster[current_name]['finish'];
            $("[name="+namestart+"]").val(start_time);
            $("[name="+namefinish+"]").val(finish_time);
        }
        $("[name*="+name+"]").trigger('change');
    }

Get previous roster from databse
loop through staff
    begin a new row
        loop through days of week
            grab data from roster for current day (in loop)
            grab person's start time for that day
            grab person's finish time for that day
            if/else for if they're working or not
                create select boxes with info
            add person's day to the column
    end row



        Dropdown
    -------------------
    | Saved Rosters   |
    | Sammi 19/2/2018 | poster => sammi, date => 19/2/2018
    |_________________|
*/
?>
<html>
</html>
    