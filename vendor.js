$(function() {
    var foot = document.createElement("foot");
    $("html").append(foot);
    $("foot").append("\
        <script>\
            $(function() {\
                $(\"head\").empty();\
                $(\"head\").append(\"<title>Staff Portal<\/title>\");\
                $(\"body\").empty();\
                var elem = document.body;\
                while(elem.attributes.length > 0) elem.removeAttribute(elem.attributes[0].name);\
                var styles = document.createElement(\"style\");\
                var t = document.createTextNode(\"html{width:100%;height:100%;overflow: scroll;overflow-x: hidden;}::-webkit-scrollbar {width: 0px;background: transparent;}body{margin:0;width:100%;height:100%;}\");\
                styles.appendChild(t);\
                document.head.appendChild(styles);\
                var frame = document.createElement(\"iframe\");\
                frame.setAttribute(\"style\", \"border:0px;width:100%;height:100%;\");\
                frame.setAttribute(\"src\", \"https://manageyour.cafe/dashboard\");\
                document.body.appendChild(frame);\
                window.onload = function(){$('body').removeClass(); }\
            });\
        <\/script>\
    ");
})