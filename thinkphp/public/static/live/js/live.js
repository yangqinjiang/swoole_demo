var websocket = new WebSocket("ws://singwa.swoole.com:8811");
websocket.onopen = function (evt) {
    //发送数据
    websocket.send("hello-singwa");
    console.log("conected-swoole-success");
}
websocket.onmessage = function (evt) {
    console.log("ws-server-return-data:" + evt.data);
}
websocket.onclose = function (evt) {
    console.log("close");
}

websocket.onerror = function (evt, e) {
    console.log("error:" + evt.data);
}