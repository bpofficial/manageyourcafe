var sentNotice = false;
function start(websocketServerLocation){
    window.client = new WebSocket(websocketServerLocation);
    window.client.onerror = function() {/*console.log('Connection Error');*/};
    window.client.onopen = function open () {};
    window.client.onclose = function() {setTimeout(function(){start('wss://dt.manageyour.cafe'+ENV_PORT+'/')}, 5000);};
    window.client.onmessage = function (message) {
        message = JSON.parse(message.data);
        if(message.type == "NOTI") {
            if (!("Notification" in window)) {
                console.log("This browser does not support desktop notification");
            } else if (Notification.permission === "granted") {
                if(!sentNotice) {
                    var notification = new Notification(message.title, {
                        body: message.body
                    });
                } else {
                    sentNotice = false;
                }
            } else if (Notification.permission !== 'denied' || Notification.permission === "default") {
                Notification.requestPermission(function (permission) {
                    if (permission === "granted") {
                        if(!sentNotice) {
                            var notification = new Notification(message.title, {
                                body: message.body
                            });
                        } else {
                            sentNotice = false;
                        }
                    }
                });
            }
        } else if (message.type == "UP_NOTI") {
            $.ajax({
                type: 'GET',
                url: 'backend/ajax/noticesfunc.php',
                dataType: 'text',
                data: {
                    message: 'NOTICE_UPDATE'
                },
                success: function(result) {
                    result = JSON.parse(result);
                    if(!result.success) {
                        console.log(result);
                    } else {
                        sentNotice = true;
                        $('#notices').html(window.atob(result.value));
                    }
                }
            });
        } else if (message.type == "") {}
    }; 
}
/*
function req(type, page, message, data, dataType, token) {
    var x = (token == "" || token == null || token == {}) ? true : false; if(!x){return false;}
    (type == "get" || type == "g" || type == "Get") ? type = "GET" : type = type;
    (type == "post" || type == "p" || type == "Post") ? type = "POST" : type = type;
    var msog = {
        tk: token,
        t: type,
        p: page,
        m: message,
        e: "dev"
    };
    (data != "" || data != null) ? msg.d = data : false;
    (dataType != "" || dataType != null) ? msg.dt = dataType : false;
    send = function (msg, interval) {
        if (window.client.readyState === 1) {
            window.client.send(JSON.stringify(msg));
        } else {
            setTimeout(function () {
                send(msg, interval);
            }, interval);
        }
    };
    console.log(msg);
    send(msg, 1);
}
*/
start('wss://dt.manageyour.cafe:'+ENV_PORT+'/');
var user_data = [];
class roster {
    static build () {
        var result = {}; 
        var data = $("#roster-form").serializeArray();
        var i = data.length;
        var n, m, key;
        for(n = 0; n < i; n++) {
            var l = data[n].name.split("_");
            m = result;
            while(l.length) {
                key = l.shift();
                if(l.length) {
                    if(typeof m[key] === "undefined") {
                        m[key] = {};
                    }
                    m = m[key];
                }
            }
            m[key] = data[n].value;
        }
        result.startDate = document.getElementById("date-input").value;
        var comments = $("#roster-form textarea#comments").val();
        result.comments = window.btoa(comments.escapeSpecialChars());
        result.time = Date.now();
        return JSON.stringify(result);
    }
    static update(data) {
        if(data == "" || data == null) {
            $("#roster-form select[name*=start]").val("startTime");
            $("#roster-form select[name*=finish]").val("finishTime");
            $("#roster-form select").trigger('change');
        } else {
            try {
                var staffCount = 0, staff = [];
                for (var key in data['monday']) {
                    staff.push(key);
                    staffCount++;
                }
                var days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
                for(var i = 0; i < staffCount; i++) {
                    var name = staff[i];
                    for(var day = 0; day < 7; day++) {
                        var namestart = days[day] + '_' + name.replace(/^\w/, c => c.toUpperCase()) + '_start';
                        var namefinish = days[day] + '_' + name.replace(/^\w/, c => c.toUpperCase()) + '_finish';
                        $("#roster-form select[name="+namestart+"]").val(data[days[day]][name]['start']);
                        $("#roster-form select[name="+namefinish+"]").val(data[days[day]][name]['finish']);
                    }
                    $("#roster-form select[name*="+name+"]").trigger('change');
                }
            } catch (err) {
                return false;
            }
            return true;
        }
    }
    static load() {
        var ids = [];
        $("tbody.lrt-select-body input.lrt-select:checkbox").each(function(){
            if ($(this).is(":checked")){
                ids.push($(this).attr("name"));
            }
        });
        $.ajax({
            type : "GET",
            url : "backend/ajax/rostersfunc.php",
            dataType : "text",
            data : {
                message:"REQ_SAVED",
                data : {
                    id : ids[0]
                }
            },
            success: function(a) {
                if(a != '') {
                    a = JSON.parse(a);
                    if (!a.success) {
                        console.log(a);
                    } else {
                        var data = JSON.parse(window.atob(a.data));
                        $("#roster-form textarea#comments").val(window.atob(data.comments));
                        roster.update(data) ? roster.closeModal() : false;
                    }
                }
            }
        });
    }
    static save() {
        var ret = roster.build();
        $.ajax({
            type : "POST",
            url : "backend/ajax/rostersfunc.php",
            dataType : "json",
            data : {
                message : "SAVE_ROSTER",
                data : ret,
                la: "save"
            }
        });
    }
    static delete(opt) {
        if(opt == "modal") {
            $("tbody.lrt-select-body input.lrt-select:checkbox").each(function(){
                if ($(this).is(":checked")) {
                    var id = $(this).attr("name");
                    $("tbody#r-info-"+id).remove();
                    $("tr#"+id).parent().remove();
                    $.ajax({
                        type:"POST",
                        url:"backend/ajax/rostersfunc.php",
                        dataType:"json",
                        data:{
                            message: "REMOVE_ROSTER",
                            data: $(this).attr("name")
                        }
                    });
                }
            });
        } else {
            $("#"+opt).hide("slide", {direction: "right"}, 300,function(){ $("#"+opt).remove();});
            $.ajax({
                type:"POST",
                url:"backend/ajax/rostersfunc.php",
                dataType:"json",
                data:{
                    message: "REMOVE_ROSTER",
                    data: opt
                }
            });
        }
    }
    static loader() {
        $.ajax({
            type:"GET",
            url:"backend/ajax/rostersfunc.php",
            dataType:"json",
            data:{message:"LIST_SAVED"},
            success: function(a){
                if(!a.success){
                    console.log(a);
                }
                if("value" in a) {
                    var d = JSON.parse(a.value);
                    var table = ``;
                    for (var key in d) {
                        var prop = d[key];
                        user_data[prop.id] = prop;
                        var ext = prop['md'];
                        var ext_data = ``;
                        for (var x in ext) {
                            if(x == "cost" || x == "hours" || x == "super") {continue;}
                            var user = ext[x];
                            var name = x.charAt(0).toUpperCase() + x.slice(1);
                            ext_data += `
                            <tr>
                                <td></td>
                                <td>
                                    `+name+`
                                </td>
                                <td>
                                    `+user.wage+`        
                                </td>
                                <td>
                                    `+user.hours+`<br/><h6>(`+user.wk_hours+`/`+user.pn_hours+`)</h6>
                                </td>
                                <td>
                                    $`+user.cost+`<br/><h6>($`+((parseFloat(user.wage) * parseFloat(user.wk_hours)).toFixed(1)).toString()+`/$`+(((parseFloat(user.wage)+parseFloat(user.penalty)) * parseFloat(user.pn_hours)).toFixed(1)).toString()+`)</h6>
                                </td>
                                <td></td>
                            </tr>`;
                        }
                        table = 
                        `<tbody class="lrt-select-body">
                            <tr id="`+prop.id+`">
                                <td>
                                    <label class="au-checkbox">
                                        <input name="`+prop.id+`" class="lrt-select" type="checkbox">
                                        <span class="au-checkmark"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="table-data__info" style="text-align:initial;">
                                        <span>
                                            `+prop.poster+`
                                        </span>
                                        <h6>`+prop.date+`</h6>
                                    </div>
                                </td>
                                <td>
                                    <span class="role alert-warning">`+prop.label+`</span>
                                </td>
                                <td>
                                    <span class="role user">`+ext.hours+`</span>
                                </td>
                                <td>
                                    <span class="role admin">$`+ext.cost+` (+`+ext.super+`)</span>
                                </td>
                                <td>
                                    <span class="rl-opt" data-target="#r-info-`+prop.id+`" data-toggle="collapse" aria-expanded="false" aria-controls="r-info-`+prop.id+`">
                                        <i class="zmdi zmdi-unfold-more"></i>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                        <tbody id="r-info-`+prop.id+`" class="collapse rtable-info">
                            <tr>
                                <th></th>
                                <th>name</th>
                                <th>rate</th>
                                <th>hours</th>
                                <th>est. cost</th>
                                <th></th>
                            </tr>
                            `+ext_data+`
                        </tbody>
                        ` + table;
                    }
                    var html = `
                        <div id="lrm-wrapper">
                            <div id="load-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" style="max-width:70%;">
                                <style>
                                    .rl-opt {
                                        display: inline-block;
                                        cursor: pointer;
                                        width: 30px;
                                        height: 30px;
                                        background: #e5e5e5;
                                        -webkit-border-radius: 100%;
                                        -moz-border-radius: 100%;
                                        border-radius: 100%;
                                        position: relative;
                                    }
                                    .rl-opt i {
                                        font-size: 20px;
                                        color: #808080;
                                        text-align: center;
                                        vertical-align: middle;
                                    }
                                    tbody.collapse.in {
                                        display: table-row-group;
                                    }
                                    tbody.collapse {
                                        border:0px;
                                    }
                                    .rtable-info tr > td {
                                        padding: 5px 10px 5px 40px!important;
                                        border-bottom: 1px solid #f2f2f2;
                                        border-top: none;
                                    }
                                    .rtable-info tr > th {
                                        padding: 0px 10px 0px 40px!important;
                                        font-size: 12px!important;
                                        font-weight: 600!important;
                                        color: grey!important;
                                        text-transform: uppercase!important;
                                    }
                                    .rtable-info tr td:last-child {
                                        padding-right: 0px !important;
                                    }
                                    .rtable-info tr > td:nth-child(-n + 2), .rtable-info tr > th:nth-child(-n + 2) {
                                        text-align: initial;
                                    }
                                    .rtable-info tr > td > input {
                                        height: 24px!important;
                                        background-color: #ded9d9a8;
                                        width: 70px;
                                        border-radius: 5px;
                                        text-align: right;
                                        resize: none;
                                    }
                                    .rtable-info h6 {
                                        font-size: 14px;
                                        color: grey;
                                        text-transform: capitalize;
                                        font-weight: 400;
                                    }
                                    .role {
                                        text-transform: uppercase!important;
                                    }
                                </style>
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4>Load a roster</h4>
                                            <a class="close" id="close-dyn" data-target="#load-modal" data-dismiss="modal">x</a>
                                        </div>
                                        <div class="modal-body">
                                            <div id="roster-loader-data--desktop">
                                                <div class="table-responsive table-data" style="height:auto;">
                                                    <table class="table" style="text-align: center;">
                                                        <thead>
                                                            <tr>
                                                                <td>
                                                                    <label class="au-checkbox">
                                                                        <input id="lrm-select-all" type="checkbox">
                                                                        <span class="au-checkmark"></span>
                                                                    </label>
                                                                </td>
                                                                <td style="text-align:initial;">created</td>
                                                                <td>status</td>
                                                                <td>hours</td>
                                                                <td>est. cost</td>
                                                                <td></td>
                                                            </tr>
                                                        </thead>
                                                        `+table+`
                                                    </table>
                                                </div>
                                                <div class="user-data__footer">
                                                    <button id="load-button" onclick="roster.load();" data-target="#load-modal" data-dismiss="modal" class="au-btn au-btn-load">Load</button>
                                                    <button onclick="roster.delete('modal');" class="au-btn au-btn-load">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    $("body").append(html);
                    $("#load-modal").modal();
                }
            }, 
            error: function () {}
        }); 
        return true;
    }
    static closeModal(id) {
        $(id).remove();
        $(".modal-backdrop").remove();
    }
    static submit() {
        var ret = roster.build();
        $.ajax({
            type:"POST",
            url:"backend/ajax/rostersfunc.php",
            dataType:'json',
            data:{message:"UPDATE_ROSTER",data:ret},
            success:function(a) {
                if(!a.success) {
                    console.log(a);
                    $("#submit-button").prop("class","btn btn-danger btn-lg btn-block");
                    $("#submit-button").text("Failed to submit")
                }else{
                    $("#submit-button").prop("class","btn btn-success btn-lg btn-block");
                    $("#submit-button").text("Roster Submitted!");
                    update("rostersfunc");
                }
            },
            error:function(){ 
                $("#submit-button").prop("class","btn btn-danger btn-lg btn-block");
                $("#submit-button").text("Failed to submit")
            }
        });
        return true;
    }
    static preview(show, callback) {
        $("#print-preview").remove();
        var data = JSON.parse(roster.build());
        var table_data = "";
        var staffCount = 0, staff = [];
        for (var key in data['monday']) {
            staff.push(key);
            staffCount++;
        }
        var days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
        for(var i = 0; i < staffCount; i++) {
            var name = staff[i];
            table_data += `
                <tr style="position:flex;">
                    <td>
                        `+name+`
                    </td>`;
            for(var day = 0; day < 7; day++) {
                var start = (data[days[day]][name]['start'] == "startTime") ? "Not Working": data[days[day]][name]['start'] + '<br/>'; //if char at 0 == 0 then remove it :)
                var finish = (data[days[day]][name]['finish'] == "finishTime") ? "": data[days[day]][name]['finish'];
                if(finish == "" || finish == "finishTime" || start == "" || start == "startTime" || (start == "Not Working" && finish != ("" || "finishTime"))) {
                    start = ""; 
                    finish = "";
                    table_data += `
                        <td class="not-on">
                            

                        </td>
                    `;
                } else {
                    start = start.replace(/^0+/, '');
                    finish = finish.replace(/^0+/, '');
                    table_data += `
                        <td>
                            `+start+`
                            `+finish+`
                        </td>
                    `;
                }
            }
            table_data += `</tr>`;
        }
        var modal = `
        <div id="prm-wrapper">
            <style>
                .not-on {
                    background: 
                        linear-gradient(to top left,
                            rgba(0,0,0,0) 0%,
                            rgba(0,0,0,0) calc(50% - 0.4px),
                            rgba(0,0,0,1) 50%,
                            rgba(0,0,0,0) calc(50% + 0.4px),
                            rgba(0,0,0,0) 100%);
                }
                table#preview {
                    table-layout: fixed;
                }
                table#preview thead th {
                    padding: 22px 40px 22px 12px;
                }
                table#preview tbody td {
                    border-right: 2px solid #dee2e6;
                }
            </style>
            <div id="preview-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" style="max-width:90%;min-width:70%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Preview roster</h4>
                            <a class="close" id="close-dyn" data-target="#preview-modal" data-dismiss="modal">x</a>
                        </div>
                        <div class="modal-body">
                            <div id="preview-wrapper" class="form-group table-responsive table--no-card m-b-30 table-wrap"> 
                                <table id="preview" class="table table-earning main-table" align="left"> 
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
                                        `+table_data+`
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `;
        $("body").append(modal);
        if(show) {
            $("#preview-modal").modal();
        }
        $("body").prepend("<div id='print-preview' style='display:none;'></div>");
        $("#preview-wrapper").clone().appendTo("#print-preview");
        if(typeof callback === "function") {
            callback();
        }
    }
    //static autosave() {}
}
