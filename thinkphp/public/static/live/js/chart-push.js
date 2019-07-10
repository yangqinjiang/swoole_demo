$(function () {//必须在页面加载完成
    $('#discuss-box').keydown(function (event) {
        if (event.keyCode == 13) {//回车键被按下
            var text = $(this).val();
            // 发送地址
            var url = "http://singwa.swoole.com:8811/?s=index/chart/index";
            var data = { 'content': text, 'game_id': 1 };//game_id从页面上获取
            $.post(url, data, function (result) {
                $(this).val('');//清空
            }, 'json');
        }
    });
});