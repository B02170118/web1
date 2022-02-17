<link rel="stylesheet" href="/assets/css/main/workphoto.css">
<main id="workphoto" class="content">

    <section class="box box-default">
        <?php echo form_open_multipart("image/form_add_category"); ?>
        <div class="box-body">
            <table id="data-table-product" class="table table-striped">
                <tr>
                    <th>相簿名稱</th>
                    <td>
                        <input type="text" class="form-control input-lg" name="category_name" value="" required>
                    </td>
                </tr>
                <tr>
                    <th>建議比例</th>
                    <td class="text-info">橫式：W960xH640 72dpi</td>
                </tr>
                <tr>
                    <th>上傳</th>
                    <td>
                        <input type="hidden" name="type" value="add_workphoto_category">
                        <input id="file" name="userfile[]" type="file" accept="image/*" required>
                    </td>
                </tr>
                <tr>
                    <th>封面圖</th>
                    <td>
                        <div id="table-img">
                            <p>目前沒有圖片</p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i>新增相簿</button>
        </div>
    </section>
    <section class="box box-default">
        <div class="box-header">
            <a href="image/" class="btn btn-default"><i class="fa fa-arrow-left"></i>回相簿列表</a>
        </div>
    </section>

</main>