//預覽圖片
$('#file').change(function() {
    var file = $('#file')[0].files[0];
    var reader = new FileReader;
    reader.onload = function(e) {
        $('.table-img').attr('src', e.target.result);
    };
    reader.readAsDataURL(file);
});

$(function () {
    $('.datetimepicker').datetimepicker({
        language: 'zh-TW',//顯示中文
        format: 'yyyy-mm-dd hh:ii:00',//顯示格式
        initialDate: new Date(),//初始化當前日期
        ignoreReadonly: true,  //禁止使用者輸入 啟用唯讀
        autoclose: true,//選中自動關閉
        todayBtn: true,//顯示今日按鈕
    });
});
