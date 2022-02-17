<!-- daterange picker -->
<link rel="stylesheet" href="assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="assets/plugins/iCheck/all.css">
<!-- Bootstrap Color Picker -->
<link rel="stylesheet" href="assets/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="assets/plugins/timepicker/bootstrap-timepicker.min.css">
<link rel="stylesheet" href="/assets/css/main/blog.css">

<main class="content">
    <?php
    if (!empty($blog)) {
    ?>
        <?php echo form_open_multipart("blog/form_edit_blog"); ?>

        <section class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">編輯內容</h3>
            </div>
            <div class="box-body">
                <table id="data-table" class="table table-bordered table-striped">
                    <input id="url" type="hidden" name="url" value="<?php echo $url ?>">
                    <tr>
                        <th>ID</th>
                        <td>
                            <input id="id" type="hidden" name="id" value="<?php echo $blog['id'] ?>">
                            <input type="text" class="form-control" value="<?php echo $blog['id'] ?>" disabled="disabled">
                        </td>
                    </tr>
                    <tr>
                        <th>標題</th>
                        <td>
                            <input type="text" class="form-control input-lg" name="title" value="<?php echo $blog['title'] ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <th>建議比例</th>
                        <td class="text-info">橫式：W960xH640 72dpi</td>
                    </tr>
                    <tr>
                        <th>上傳封面</th>
                        <td>
                            <input id="file" name="userfile[]" type="file"><br>
                            <input type="hidden" name="type" value="blog">
                        </td>
                    </tr>                    
                    <tr>
                        <th>封面圖</th>
                        <td>
                            <img class="table-img" src="<?php echo WWW_test1111_COM . $blog['img'] ?>" alt="">
                        </td>
                    </tr>
                    <tr>
                        <th>日期</th>
                        <td>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="datepicker" name="date" value="<?php echo $blog['date'] ?>" readonly="readonly" required>
                                <!-- /.input group -->
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>狀態</th>
                        <td>
                            <div class="radio">
                                <?php
                                if ($blog['status'] == 1) {
                                ?>
                                    <label><input type="radio" name="status" value="1" checked>開</label>
                                    <label><input type="radio" name="status" value="0">關</label>
                                <?php
                                } else {
                                ?>
                                    <label><input type="radio" name="status" value="1">開</label>
                                    <label><input type="radio" name="status" value="0" checked>關</label>
                                <?php
                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                </table>
                <!-- 內文 -->
                <div>
                    <textarea name="content"><?php echo $blog['content'] ?></textarea>
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-floppy-o"></i>確認修改
                </button>
            </div>
        </section>
    <?php
    }
    ?>
    <section class="box box-default">
        <div class="box-header">
            <a href="/blog" class="btn btn-default"><i class="fa fa-arrow-left"></i>回列表</a>
        </div>
    </section>
</main>