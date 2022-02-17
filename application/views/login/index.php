<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <base href="<?php echo base_url(); ?>" />
    <title>官網管理後台</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="assets/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="assets/plugins/iCheck/square/blue.css">
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Sans+TC:400,500&display=swap">
</head>

<body class="hold-transition login-page">

    <div class="login-box">
        <div class="login-logo">
            <b>官網管理後台</b>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <form id="form1" method="post" onsubmit="return check_form();">
                <input type="hidden" name="<?php echo $csrfname ?>" value="<?php echo $csrfhash ?>" />
                <div class="form-group has-feedback">
                    <input type="text" name="account" class="form-control" placeholder="帳號" pattern="[A-Za-z0-9]{4,20}" title="請輸入4-20個英數字" required>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" name="password" class="form-control" placeholder="密碼" pattern="{8,20}" title="請輸入8-20個以內英數字" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="form-group">
                    <div class="checkbox icheck">
                        <input type="text" value="" name="captcha" class="form-control" pattern="[A-Z2-9]{4}" placeholder="驗證碼" title="請輸入正確驗證碼" required>
                        <div id="captcha">
                            <a href="javascript:get_captcha()" title="換一張" style="display: block; margin-top: 1rem;">
                                <?php echo $img ?>
                            </a>
                        </div>
                    </div>
                </div>
                <p id="errormsg" style="color:red"></p>
                <div class="form-group">
                    <button id="submitbtn" type="submit" class="btn btn-lg btn-primary btn-block">登入</button>
                </div>

            </form>

        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery 3 -->
    <script src="assets/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="assets/bower_components/jquery-ui/jquery-ui.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script>
        function getCookie(name) {
            var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
            if (arr != null) return unescape(arr[2]);
            return null;
        }

        function get_captcha() {
            $.ajax({
                type: 'GET',
                url: './login/ajax_reload_img' //ajax接收的server端
                    ,
                success: function(res) {
                    $("#captcha a").html(res);
                },
                error: function(errorThrown) {
                    $("#captcha a").html(res);
                }
            });
        }

        function check_form() {
            $("#submitbtn").attr('disabled', true);
            $.ajax({
                type: 'POST',
                url: './login/ajax_login' //ajax接收的server端
                    ,
                data: $("#form1").serialize() + '&csrf_token=' + getCookie('csrf_cookie_name'),
                dataType: 'json',
                success: function(res) {
                    $("#errormsg").text(res.msg);
                    if (res.code == 0) {
                        setTimeout("location.reload()", 500);
                    } else {
                        get_captcha();
                        $("#submitbtn").attr('disabled', false);
                    }
                },
                error: function(errorThrown) {
                    $("#errormsg").text("error");
                    $("#submitbtn").attr('disabled', false);
                    get_captcha();
                }
            });
            return false;
        }
    </script>
</body>

</html>