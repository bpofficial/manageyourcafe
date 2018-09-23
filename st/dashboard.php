<?php
session_start();
include('backend/php/session.php');
include('backend/php/config.php');
$store_id = $_SESSION['store_id'];
$st = $conn->prepare("SELECT * FROM `settings` WHERE `store_id`='$store_id'");
$st->execute();
$settings = $st->fetchAll(PDO::FETCH_ASSOC);
$settings = $settings[0];
//echo print_r($settings);
?>
<!DOCTYPE html>
<html lang="en" style="overflow-y: scroll;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" media="all">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/hamburgers/0.9.3/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet">
    <link href="cafesuite/css/master.css" rel="stylesheet" media="all">
    <link href="cafesuite/css/custom.css" rel="stylesheet" media="all">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/css/perfect-scrollbar.min.css" rel="stylesheet" media="all">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" media="all">
</head>
<body class="page" style="background-color:#e5e5e5;">
    <div class="page-wrapper">
        <!-- HEADER MOBILE-->
        <header class="header-mobile d-block d-md-none">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                    <!-- This can be added via a php script for customisation etc -->
                        <a class="logo" href="#">
                            <img src="" alt="Stones Throw"/>
                        </a>
                        <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <nav class="navbar-mobile">
                <div class="container-fluid">
                    <ul class="navbar-mobile__list list-unstyled">
                        <?php if($_SESSION['priv']['level'] > 2){ ?>
                            <li class="">
                                <a id="mob-dashboard-button" href="">
                                    <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                            </li>
                            <li>
                                <a id="mob-analytics-button" href="">
                                    <i class="fas fa-chart-bar"></i>Analytics</a>
                            </li>
                            <li>
                                <a id="mob-calendar-button" href="">
                                    <i class="fas fa-calendar-alt"></i>Calendar</a>
                            </li>
                        <?php } ?>
                        <li>
                            <a id="mob-rosters-button" href="">
                                <i class="fas fa-clipboard-list"></i>&nbsp;Rosters</a>
                        </li>
                        <li>
                            <a id="mob-notices-button" href="">
                                <i class="fas fa-comments"></i>Notices</a>
                        </li>
                        <?php if($_SESSION['priv']['level'] < 2){ ?>
                            <li class="has-sub">
                                <a id="mob-settings-button" class="js-arrow open">
                                <i class="fas fa-cog"></i>Settings</a>
                                <ul class="list-unstyled navbar__sub-list js-sub-list" style="display: none;">
                                    <li>
                                        <a href="">Users</a>
                                    </li>
                                    <li>
                                        <a href="">Rosters</a>
                                    </li>
                                    <li>
                                        <a href="">Analytics</a>
                                    </li>
                                    <li>
                                        <a href="">Calendar</a>
                                    </li>
                                </ul>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </nav>
        </header>
        <aside class="menu-sidebar d-none d-lg-block">
            <div class="logo">
                <a href="#">
                    <!-- <img src="cafesuite/images/icon/logo.png"/> -->
                </a>
            </div>
            <div class="menu-sidebar__content js-scrollbar1" >
                <nav class="navbar-sidebar">
                    <ul class="list-unstyled navbar__list">
                        <?php if($_SESSION['priv']['level'] > 2){ ?>
                            <li id="li1">
                        <a id="dashboard-button" onclick="event.preventDefault();" <?php if($settings['home'] == false || $settings['home'] == "0"){?> style="cursor: pointer; pointer-events: none; color: #ccc;" <?php } else { ?> style="cursor: pointer" <?php } ?> >
                                    <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                            </li>
                            <li id="li2" style="active has-sub">
                        <a id="cashflow-button" onclick="event.preventDefault();" <?php if($settings['cashflow'] == false || $settings['cashflow'] == "0"){?> style="cursor: pointer; pointer-events: none; color: #ccc;" <?php } else { ?> style="cursor: pointer;" <?php } ?> >
                                    <i class="fa fa-credit-card"></i>Cash flow</a>
                            </li>
                            <li id="li3" style="active has-sub">
                                <a id="analytics-button" onclick="event.preventDefault();" <?php if($settings['analytics'] == false || $settings['analytics'] == "0"){?> style="cursor: pointer; pointer-events: none; color: #ccc;" <?php } else { ?> style="cursor: pointer;" <?php } ?> >
                                    <i class="fas fa-chart-bar"></i>Analytics</a>
                            </li>
                            <li id="li4">
                                <a id="calendar-button" onclick="event.preventDefault();" <?php if($settings['calendar'] == false || $settings['calendar'] == "0"){?> style="cursor: pointer; pointer-events: none; color: #ccc;" <?php } else { ?> style="cursor: pointer;" <?php } ?> >
                                    <i class="fas fa-calendar-alt"></i>Calendar</a>
                            </li class="active has-sub">
                        <?php } ?>
                        <li id="li11">
                                <a id="rosters-button" <?php if($settings['rosters'] == false || $settings['rosters'] == "0"){?> style="cursor: pointer; pointer-events: none; color: #ccc;" <?php } else { ?> style="cursor: pointer;" <?php } ?> >
                                <i class="fas fa-clipboard-list"></i>&nbsp;Rosters</a>
                        </li>
                        <li id="li22">
                            <a id="notices-button" <?php if($settings['notices'] == false || $settings['notices'] == "0"){?> style="cursor: pointer; pointer-events: none; color: #ccc;" <?php } else { ?> style="cursor: pointer;" <?php } ?> >
                                <i class="fas fa-comments"></i>Notices</a>
                        </li>
                        <?php if($_SESSION['priv']['level'] > 2){ ?>
                            <li class="has-sub">
                        <a id="settings-button" class="js-arrow" <?php if($settings['settings'] == false || $settings['settings'] == "0"){ ?> style="cursor: pointer; pointer-events: none; color: #ccc;" <?php } else { ?> style="cursor: pointer;" <?php } ?> >
                                    <i class="fas fa-cog"></i>Settings</a>
                                <ul class="list-unstyled navbar__sub-list js-sub-list" style="display: none;">
                                    <li id="li31">
                                        <a id="dash-settings" style="cursor: pointer; pointer-events: none; color: #ccc;">&nbsp;&nbsp;&nbsp;Dashboard</a>
                                    </li>
                                    <li id="li32">
                                        <a id="user-settings" style="cursor: pointer;">&nbsp;&nbsp;&nbsp;Users</a>
                                    </li>
                                    <li id="li32">
                                        <a id="notice-settings" style="cursor: pointer;">&nbsp;&nbsp;&nbsp;Notices</a>
                                    </li>
                                    <li id="li33">
                                        <a id="roster-settings" style="cursor: pointer;">&nbsp;&nbsp;&nbsp;Rosters</a>
                                    </li>
                                    <li id="li34">
                                        <a id="analytic-settings" style="cursor: pointer; pointer-events: none; color: #ccc;">&nbsp;&nbsp;&nbsp;Analytics</a>
                                    </li>
                                    <li id="li35">
                                        <a id="calendar-settings" style="cursor: pointer; pointer-events: none; color: #ccc;">&nbsp;&nbsp;&nbsp;Calendar</a>
                                    </li>
                                    <li id="li35">
                                        <a id="advanced-settings" style="cursor: pointer; pointer-events: none; color: #ccc;">&nbsp;&nbsp;&nbsp;Advanced</a>
                                    </li>
                                </ul>
                            </li>
                        <?php } ?>
                    </ul>
                </nav>
            </div>
        </aside>
        <div class="page-container">
            <header class="header-desktop">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap" style="float: right; text-align: left;">
                            <div class="header-button">
                                <div class="noti-wrap">
                                </div>
                                <div class="account-wrap">
                                    <div class="account-item clearfix js-item-menu">
                                        <div class="image">
                                            <img src="cafesuite/images/icon/avatar-01.jpg" alt="user" />
                                        </div>
                                        <div class="content">
                                            <a class="js-acc-btn" style="pointer-events: none;"><?php echo ucfirst($_SESSION['uname']); ?></a>
                                        </div>
                                        <div class="account-dropdown js-dropdown">
                                            <div class="info clearfix">
                                                <div class="image">
                                                    <a href="#">
                                                        <img src="cafesuite/images/icon/avatar-01.jpg" alt="John Doe" />
                                                    </a>
                                                </div>
                                                <div class="content">
                                                    <h5 class="name">
                                                        <a style="pointer-events: none;"> <?php echo ucfirst($_SESSION['uname']);?> </a>
                                                    </h5>
                                                    <span class="email"><?php echo $_SESSION['email'];?></span>
                                                </div>
                                            </div>
                                            <div class="account-dropdown__body">
                                                <div class="account-dropdown__item">
                                                    <a href="" style="text-decoration: none;">
                                                        <i class="zmdi zmdi-account"></i>Account</a>
                                                </div>
                                                <div class="account-dropdown__item">
                                                    <a href="" style="text-decoration: none;">
                                                        <i class="zmdi zmdi-settings"></i>Settings</a>
                                                </div>
                                            </div>
                                            <div class="account-dropdown__footer">
                                                <a href="logout" style="text-decoration: none;">
                                                    <i class="zmdi zmdi-power"></i>Logout</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <div id="main-content" class="main-content"></div>
            <div id="error"></div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="cafesuite/js/header.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/locale/en-au.js"></script>
    <script type="text/javascript" src="cafesuite/js/popper.min.js"></script>
    <script type="text/javascript" src="cafesuite/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/perfect-scrollbar.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script type="text/javascript" src="cafesuite/js/master.js"></script>
    <script type="text/javascript" src="cafesuite/js/custom.js"></script>
    <script>
        function update(dash, mes) {
            var t0 = performance.now();
            if (mes != null) {
                mes = mes;
            } else {
                mes = "REQ_PAGE";
            }
            $.ajax({
                type: "GET",
                url: 'backend/ajax/' + dash + '.php',
                dataType:'text',
                data: 
                    {
                        message: mes
                    },
                success: function(result) {
                    //console.log(result);
                    if(result != '') {
                        result = JSON.parse(result);
                    }
                    if(!result.success) {
                        console.log(result);
                    } else {
                        if('errors' in result) {
                            $('#error').html(window.atob(result.errors));
                        }
                        $('#main-content').html(window.atob(result.value)); 
                        var t1 = performance.now();
                        console.log("Page AJAX took " + (t1 - t0) + " ms.");
                    }
                }, 
                error: function(result) {
                    console.log(result);      
                }
            });
            return false;
            $(this).closest(this.parentNode.id).addClass('active has-sub');
        }
        $(function () {
            <?php if($_SESSION['priv']['level'] == 3) { ?>
            update('rostersfunc');
            document.getElementById("dashboard-button").addEventListener("click", function(){update('dashboardfunc');}, false);
            document.getElementById("analytics-button").addEventListener("click", function(){update('analyticsfunc');}, false);
            document.getElementById("calendar-button").addEventListener("click", function(){update('calendarfunc');}, false);
            document.getElementById("cashflow-button").addEventListener("click", function(){update('cashflowfunc');}, false);
            document.getElementById("rosters-button").addEventListener("click", function(){update('rostersfunc');}, false);
            document.getElementById("notices-button").addEventListener("click", function(){update('noticesfunc');}, false);
            <?php } else { ?>
            update('rostersfunc');
            document.getElementById("rosters-button").addEventListener("click", function(){update('rostersfunc');}, false);
            document.getElementById("notices-button").addEventListener("click", function(){update('noticesfunc');}, false);
            <?php } ?>
            
            <?php if($_SESSION['priv']['level'] == 3) { ?>
            document.getElementById("dash-settings").addEventListener("click", function(){update('settingsfunc','S_DASH_REQ');}, false);
            document.getElementById("notice-settings").addEventListener("click", function(){update('settingsfunc','S_NOT_REQ');}, false);
            document.getElementById("user-settings").addEventListener("click", function(){update('settingsfunc','S_USER_REQ');}, false);
            document.getElementById("roster-settings").addEventListener("click", function(){update('settingsfunc','S_ROS_REQ');}, false);
            document.getElementById("analytic-settings").addEventListener("click", function(){update('settingsfunc','S_ANA_REQ');}, false);
            document.getElementById("calendar-settings").addEventListener("click", function(){update('settingsfunc','S_CAL_REQ');}, false);
            document.getElementById("advanced-settings").addEventListener("click", function(){update('settingsfunc','S_ADV_REQ');}, false);
            <?php } ?>
        });
    </script>
</body>
</html>
<?php unset($settings); ?>