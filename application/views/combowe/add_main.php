<section class="content">
    <!-- 相簿 -->
    <div class="row">
        <!-- 詳細內容 -->
        <div class="box box-default">
            <?php echo form_open_multipart("combowe/form_add_main");?>
            <div class="box-body">
                <table id="data-table-product" class="table table-bordered table-striped">
                    <tr>
                        <th>項目名稱</th>
                        <td><input type="text" name="title" value="" required></td>
                        <input type="hidden" name="type" value="combowe">
                    </tr>
                    <tr>
                        <th>預設圖片</th>
                        <td>
                            <input type="hidden" name="type" value="combowe">
                            <div class="row upload_img">
                                <div class="col-md-12">
                                    <label>選擇圖片檔案</label>
                                    <input id="file" name="userfile[]" type="file" required>
                                </div>
                                    <div id="table-img" class="col-md-12">
                                        <p>目前沒有圖片</p>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i>送出</button>
            </div>
        </div>
    </div>
</section>