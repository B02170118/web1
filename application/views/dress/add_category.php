<link rel="stylesheet" href="/assets/css/main/dress.css">
<main id="dress" class="content">

    <section class="box box-default">
        <?php
        if (!empty($main_id)) {
        ?>
            <?php echo form_open_multipart("dress/form_add_category"); ?>
            <input type="hidden" name="url" value="<?php echo urlencode($this->agent->referrer()); ?>">
            <input type="hidden" name="main_id" value="<?php echo $main_id ?>">
            <input type="hidden" name="type" value="add_dress_category">

            <div class="box-body">
                <table id="data-table-product" class="table table-striped">
                    <tr>
                        <th>禮服分類</th>
                        <td>
                            <h4><?php echo $category_name?></h4>
                        </td>
                    </tr>
                    <tr>
                        <th>相簿名稱</th>
                        <td>
                            <input type="text" class="form-control input-lg" name="category_name" value="" required>
                        </td>
                    </tr>
                    <tr>
                        <th>建議比例</th>
                        <td class="text-info">直式：W640xH860 72dpi</td>
                    </tr>
                    <tr>
                        <th>上傳相片</th>
                        <td>
                            <div class="upload_img">
                                <input id="file" name="userfile[]" type="file" required>
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
                <button type="submit" class="btn btn-primary">確認新增</button>
            </div>
        <?php
        }
        ?>
    </section>
    <section class="box box-default">
        <div class="box-header">
            <a href="<?php echo $this->agent->referrer() ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i>上一頁</a>
        </div>
    </section>

</main>