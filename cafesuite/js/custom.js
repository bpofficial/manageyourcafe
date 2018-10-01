var sentNotice = false;
function start(websocketServerLocation){
    window.client = new WebSocket(websocketServerLocation);
    window.client.onerror = function() {console.log('Connection Error');};
    window.client.onopen = function open () {};
    window.client.onclose = function() {setTimeout(function(){start('wss://dt.manageyour.cafe:58443/')}, 5000);};
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
function req(type, page, message, data, dataType) {
    (type == "get" || type == "g" || type == "Get") ? type = "GET" : type = type;
    (type == "post" || type == "p" || type == "Post") ? type = "POST" : type = type;
    var msg = {
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
start('wss://dt.manageyour.cafe:58443/');

