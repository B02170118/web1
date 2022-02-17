var i = 0;

function lock_form(){
    return;
}
function getCookie(name){
    let arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
    if(arr != null) return unescape(arr[2]); return null;
}
//預覽圖片
$('#file').change(function() {
    let file = $('#file')[0].files[0];
    let reader = new FileReader;
    reader.onload = function(e) {
        $('.table-img').attr('src', e.target.result);
    };
    reader.readAsDataURL(file);
});

$("#file").change(function(){
    $("#table-img").html(""); // 清除預覽
    readURL(this);
});

function readURL(input){
    if (input.files && input.files.length >= 0) {
        for(var i = 0; i < input.files.length; i ++){
        var reader = new FileReader();
        reader.onload = function (e) {
            var img = "<img height='100' src=" + e.target.result + ">";
            $("#table-img").append(img);
        }
        reader.readAsDataURL(input.files[i]);
        }
    }else{
        var noPictures = $("<p>目前沒有圖片</p>");
        $("#table-img").append(noPictures);
    }
}