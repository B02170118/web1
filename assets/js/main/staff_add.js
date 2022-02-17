function getCookie(name){
    var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
    if(arr != null) return unescape(arr[2]); return null;
}

//預覽圖片
$('#file').change(function() {
    var file = $('#file')[0].files[0];
    var reader = new FileReader;
    reader.onload = function(e) {
        $('#table-img').attr('src', e.target.result);
    };
    reader.readAsDataURL(file);
});
$('#file2').change(function() {
    var file = $('#file2')[0].files[0];
    var reader = new FileReader;
    reader.onload = function(e) {
        $('#table-img2').attr('src', e.target.result);
    };
    reader.readAsDataURL(file);
});