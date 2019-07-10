//聊天室的ws
var websocket = new WebSocket("ws://singwa.swoole.com:8812");
websocket.onopen = function (evt) {
    //发送数据
    websocket.send("hello-singwa");
    console.log("conected-swoole-success");
}
websocket.onmessage = function (evt) {
    console.log("ws-server-return-data:" + evt.data);
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
    if (!data.type) {
        return;
    }
    var html = '<div class="frame">';
    html += '<h3 class="frame-header">';
    html += '<i class="icon iconfont icon-shijian"></i>第' + data.type + '节 01：30';
    html += '</h3>';
    html += '<div class="frame-item">';
    html += ' <span class="frame-dot"></span>';
    html += '<div class="frame-item-author">';
    if (data.logo) {
        html += '<img src="' + data.logo + '" width="20px" height="20px" />';
    }
    html += data.title + '</div>';
    html += '<p>' + data.content + '</p>';
    html += '</div>';
    html += '</div>';
    $('#match-result').prepend(html);

}