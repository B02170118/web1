<link rel="stylesheet" href="/assets/css/main/makeup.css">
<main id="makeup" class="content">

    <section class="box box-default">

        <?php echo form_open_multipart("makeup/form_add_category"); ?>
        <div class="box-body">
            <table id="data-table-product" class="table table-striped">
                <tr>
                    <th>相簿名稱</th>
                    <td>
                        <input type="text" class="form-control input-lg" name="category_name" value="" required>
                    </td>
                </tr>
                <tr>
                    <th>封面規格</th>
                    <td class="text-info">建議比例 W640xH860 72dpi</td>
                </tr>
                <tr>
                    <th>封面圖</th>
                    <td>
                        <input type="hidden" name="type" value="add_make_category">
                        <input id="file" name="userfile[]" type="file" accept="image/*" required>
                    </td>
                </tr>
                <tr>
                    <th>上傳封面圖</th>
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
            <a href="makeup/" class="btn btn-default"><i class="fa fa-arrow-left"></i>回相簿列表</a>
        </div>
    </section>

</main>