var sentNotice = false;
function disError(data, append) {
    if(append) {
        $('#error').attr('class', 'error-enabled');
        $('#error').attr('data-content', $(this).attr('data-content') + data);
    } else {
        $('#error').attr('class', 'error-enabled');
        $('#error').attr('data-content', data);
    }
}

function start(websocketServerLocation){
    window.client = new WebSocket(websocketServerLocation);
    window.client.onerror = function() {
        console.log('Connection Error');
    };
    window.client.onopen = function open () {
        //console.log('Connected to server.');
    };
    window.client.onmessage = function (message) {
        message = JSON.parse(message.data);
        if(message.type == "NOTI") {
            if (!("Notification" in window)) {
                console.log("This browser does not support desktop notification");
            }
            else if (Notification.permission === "granted") {
                if(!sentNotice) {
                    var notification = new Notification(message.title, {
                        body: message.body
                    });
                } else {
                    sentNotice = false;
                }
            }
            else if (Notification.permission !== 'denied' || Notification.permission === "default") {
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
                        //xd
                    } else {
                        sentNotice = true;
                        $('#notices').html(window.atob(result.value));
                    }
                },
                error: function(result) {
                    disError('error', true);
                }
            });
        }
    }; 
    window.client.onclose = function(){
        setTimeout(function(){start('wss://dt.manageyour.cafe:58443/')}, 5000);
    };
}
start('wss://dt.manageyour.cafe:58443/');


