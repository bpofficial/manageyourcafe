<?php
session_start();
include('backend/php/session.php');
?>
<html>
    <!--
        Purpose of this page is to send x concurrent requests to a
        page and calculate the average time to serve them all.
    -->
    <head>
        <title>Stress Test</title>
        <link href="cafesuite/css/master.css" rel="stylesheet" media="all">
        <link href="cafesuite/css/custom.css" rel="stylesheet" media="all">
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    </head>
    <body class="page" style="background-color:#e5e5e5;">
        <div class="page-wrapper">
            <div class="page-container">
                <div class="row" style="margin-top:10%">
                    <div class="col-10">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">Dev. Stress Tester</strong>
                            </div>
                            <div class="card-body">
                                <div class="row form-group" style="height:40%">
                                    <div class="col-6">
                                        <label for="loc">Location</label>
                                        <select class="form-control" id="loc">
                                            <option>Dashboard</option>
                                            <option>Cash Flow</option>
                                            <option>Analytics</option>
                                            <option>Calendar</option>
                                            <option>Rosters</option>
                                            <option>Notices</option>
                                            <option>Settings</option>
                                        </select><br />
                                        <label for="count">Simultaneous Requests</label>
                                        <select class="form-control" id="count">
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
                                            <option>10</option>
                                            <option>15</option>
                                            <option>30</option>
                                            <option>50</option>
                                            <option>100</option>
                                            <option>150</option>
                                            <option>300</option>
                                            <option>500</option>
                                            <option>1000</option>
                                            <option>1500</option>
                                            <option>5000</option>
                                            <option>7500</option>
                                            <option>10000</option>
                                        </select><br />
                                        <label for="message">Message</label>
                                        <select class="form-control" id="message">
                                            <option>REQ_PAGE</option>
                                        </select>
                                        <br />
                                        <label class="bs-switch">Cache
                                            <input type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                        <br />
                                        <button id="submit" type="button" class="col-12 btn btn-primary btn-md btn-block">Submit</button>
                                    </div>
                                    <div class="col-6">
                                        <div style="background-color:rgb(187, 187, 187); height:100%;">
                                            <div id="info" style="padding:5px">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <foot>
        <script>
            var count = parseInt($("#count").val()); 
            var loc = $("#loc").val(); 
            var message = $("#message").val();
            $("select").change(function() {
                var id = $(this).attr("id");
                if (id == "count") {
                    count = parseInt($(this).val());
                } else if (id == "loc") {
                    loc = $(this).val();
                } else if (id == "message") {
                    message = $(this).val();
                }
            })
            $("#submit").on('click', function() {
                $('#info').empty();
                $('#info').append("<strong>Started</strong><br/>");
                var php_times = [];
                var req_times_start = [];
                var req_times_end = [];
                var req_times = [];
                var requests = [];
                var cache = false;
                loc = loc.toLowerCase();
                $('#info').append("> Creating request array<br/>");
                $('#info').append("> Sending requests to backend/ajax/" + loc + ".php<br/>");
                var t_r0 = performance.now();
                $('#info').append("> Beginning to send requests now<br/>");
                for(var i = 0; i < count; i++) {
                    requests.push(
                        $.ajax({
                            cache: cache,
                            type: 'GET',
                            url: 'https://manageyour.cafe/dev/backend/ajax/' + loc + 'func.php',
                            data:
                                {
                                    message: message, 
                                    stress: "true"
                                },
                            beforeSend: function() {
                                req_times_start.push(performance.now());
                            },
                            success: function(result) {
                                if(result != '') {
                                    result = JSON.parse(result);
                                }
                                if(!result.success) {
                                    console.log(result);
                                } else {
                                    php_times.push(parseFloat(result.time));
                                    window.atob(result.value);
                                }
                                req_times_end.push(performance.now());
                            }
                        })
                    );
                }
                $.when.apply(undefined, requests).done(function() {
                    var t_r1 = performance.now();
                    $('#info').append("> Requests complete. took "+(t_r1-t_r0).toFixed(5)+"ms<br/>");
                    var sent = php_times[0];
                    var average = 0;
                    var req_average = 0;
                    for (var i = 0; i < count; i++) { //this is the average PHP handle time
                        average += php_times[i];
                        req_times.push(
                            req_times_end[i] - req_times_start[i]
                        );
                    }
                    for (var i = 0; i < req_times.length; i++) {
                        req_average += req_times[i];
                    }
                    req_average = req_average / count;
                    var total = average / count;
                    $('#info').append("> Average PHP time: " + total.toFixed(5) + "ms<br/>");
                    $('#info').append("> Average XHR time: " + req_average.toFixed(5) + "ms<br/>");
                })
            });
        </script>
    </foot>
</html>