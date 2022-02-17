<link rel="stylesheet" href="/assets/css/main/dress.css">
<main id="dress" class="content">

    <section class="box box-default">
        <?php
        if (!empty($category_id)) {
        ?>
            <?php echo form_open_multipart("dress/form_upload_img"); ?>
            <input type="hidden" name="cateogry" value="<?php echo $category_id ?>">
            <input type="hidden" name="type" value="dress">
            <input type="hidden" name="url" value="<?php echo $url ?>">

            <div class="box-body">
                <table id="data-table-product" class="table table-striped">
                    <tr>
                        <th>禮服分類</th>
                        <td>
                            <h4><?php echo $main_name ?></h4>
                        </td>
                    </tr>
                    <tr>
                        <th>相簿名稱</th>
                        <td>
                            <h2><?php echo $category_name ?></h2>
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