<link rel="stylesheet" href="/assets/css/main/blog.css">
<main class="content">
    <p id="next_id" style="display:none"><?php echo !empty($next_id) ? $next_id : "" ?></p>
    <?php echo form_open_multipart("blog/form_add_blog"); ?>

    <section class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">新增文章</h3>
        </div>

        <div class="box-body">
            <table id="data-table" class="table table-bordered table-striped">
                <tr>
                    <th>標題</th>
                    <td>
                        <input type="text" class="form-control" name="title" value="" required>
                    </td>
                </tr>
                <tr>
                    <th>建議比例</th>
                    <td class="text-info">橫式：W960xH640 72dpi</td>
                </tr>
                <tr>
                    <th>縮圖</th>
                    <td>
                        <input id="file" name="userfile[]" type="file" required><br>
                        <input type="hidden" name="type" value="blog">
                        <img class="table-img" src="" alt="">
                    </td>
                </tr>
                <tr>
                    <th>日期</th>
                    <td>
                        <label>Date:</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right" id="datepicker" name="date" readonly="readonly" required>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>狀態</th>
                    <td>
                        <div class="radio">
                            <label><input type="radio" name="status" value="1" checked>開</label>
                            <label><input type="radio" name="status" value="0">關</label>
                        </div>
                    </td>
                </tr>
            </table>
            <div>
                <textarea name="content"></textarea>
            </div>
        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-success">
                <i class="fa fa-floppy-o"></i>確認新增
            </button>
        </div>

    </section>

    <section class="box box-default">
        <div class="box-header">
            <a href="/blog" class="btn btn-default"><i class="fa fa-arrow-left"></i>回列表</a>
        </div>
    </section>
</main>