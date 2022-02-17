function getCookie(name){
    var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
    if(arr != null) return unescape(arr[2]); return null;
}

//預覽圖片
$('#file').change(function() {
    var file = $('#file')[0].files[0];
    var reader = new FileReader;
    reader.onload = function(e) {
        $('.table-img').attr('src', e.target.result);
    };
    reader.readAsDataURL(file);
});

$(function(){
    var id = $("#next_id").text();
    CKEDITOR.replace( 'content', {
        filebrowserImageUploadUrl : 'blog/content_upload_img?type=blog_content&id='+id
    });
    $.datepicker.setDefaults($.datepicker.regional['zh-TW']); 
    $('#datepicker').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',//日期格式 
    });
    
});