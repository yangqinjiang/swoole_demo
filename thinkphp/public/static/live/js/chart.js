$(function () {
    //聊天室的ws
    var websocket = new WebSocket("ws://singwa.swoole.com:8812");
    websocket.onopen = function (evt) {
        //发送数据
        websocket.send("chart connect...");
        console.log("conected-swoole-success");
    }
    websocket.onmessage = function (evt) {
        console.log("ws-server-return-data:");
        push(evt.data);
    }
    websocket.onclose = function (evt) {
        console.log("close");
    }

    websocket.onerror = function (evt, e) {
        console.log("error:" + evt.data);
    }
    function push(raw_data) {
        var data = JSON.parse(raw_data);
        if (!data.user) {
            return;
        }
        //填充内容
        var html = '<div class="comment">';
        html += '<span>' + data.user + '</span>';
        html += '<span>' + data.content + '</span>';
        html += '</div>';
        $('#comments').prepend(html);

    }
})