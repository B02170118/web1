<link rel="stylesheet" href="/assets/css/main/workphoto.css">
<main id="workphoto" class="content">

    <section class="box box-default">
        <?php
        if (!empty($category_id)) {
        ?>
            <?php echo form_open_multipart("image/form_upload_img"); ?>
            <input type="hidden" name="cateogry" value="<?php echo $category_id ?>">
            <input type="hidden" name="type" value="workphoto">
            <input type="hidden" name="url" value="<?php echo $url ?>">
            <div class="box-body">
                <table id="data-table-product" class="table table-striped">
                    <tr>
                        <th width="150">相簿名稱</th>
                        <td>
                            <input type="text" class="form-control input-lg" name="category_name" value="" placeholder="<?php echo $category_name ?>" disabled required>
                        </td>
                    </tr>
                    <tr>
                        <th>建議比例</th>
                        <td class="text-info">橫式：W960xH640 72dpi、直式：W640xH960 72dpi</td>
                    </tr>
                    <tr>
                        <th>上傳相片</th>
                        <td>
                            <div class="upload_img">
                                <input id="file" name="userfile[]" type="file" accept="image/*" multiple required>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>預覽上傳</th>
                        <td>
                            <div id="table-img">
                                <p>目前沒有圖片</p>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">確認上傳</button>
            </div>
        <?php
        } else {
            echo "參數錯誤";
        }
        ?>
    </section>

    <section class="box box-default">
        <div class="box-header">
            <a href="<?php echo $this->agent->referrer() ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i>上一頁</a>
        </div>
    </section>

</main>