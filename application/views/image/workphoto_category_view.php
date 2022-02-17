<link rel="stylesheet" href="/assets/css/main/workphoto.css">
<main id="workphoto" class="content">

    <section id="workphoto_info" class="box box-default">
        <div class="box-header with-border">
            <h2 class="box-title">相簿屬性</h2>
        </div>
        <?php
        if (!empty($category)) {
        ?>
            <?php echo form_open_multipart("image/form_edit_category"); ?>

            <div class="box-body">
                <table id="data-table" class="table table-striped">
                    <tr>
                        <th>相簿ID</th>
                        <td>
                            <input class="form-control" type="text" value="<?php echo $category['id'] ?>" disabled="disabled">
                            <input id="cid" type="hidden" name="cid" value="<?php echo $category['id'] ?>">
                        </td>
                    </tr>
                    <tr>
                        <th>相簿名稱</th>
                        <td>
                            <input type="text" class="form-control input-lg" name="category_name" value="<?php echo $category['name'] ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <th>上傳</th>
                        <td>
                            <input id="file" name="userfile[]" type="file"><br>
                            <input type="hidden" name="type" value="workphoto">
                        </td>
                    </tr>
                    <tr>
                        <th>封面圖</th>
                        <td>
                            <img class="table-img" src="<?php echo WWW_test1111_COM . $category['default_img'] ?>" alt="">
                        </td>
                    </tr>
                </table>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-floppy-o"></i>確認修改
                </button>
            </div>

            </form>
        <?php
        }
        ?>
    </section>

    <section id="workphoto_view" class="box box-default">
        <div class="box-header with-border">
            <h2 class="box-title">相片管理</h2>
        </div>
        <div class="box-btns">
            <?php
            if (!empty($category)) {
            ?>
                <a id="upload_btn" href="./image/upload_img/<?php echo $category['id'] ?>" class="btn btn-primary">
                    <i class="fa fa-image"></i>上傳相片
                </a>
                <button id="sort_btn" onclick="sort()" class="btn btn-info">
                    <i class="fa fa-sort"></i>排序相片
                </button>
                <button id="submit_sort_btn" onclick="save_sort()" class="btn btn-info" style="display:none">
                    <i class="fa fa-sort"></i>確認排序
                </button>
                <button id="img_del" class="btn btn-danger">
                    <i class="fa fa-minus-square"></i>刪除相片
                </button>
                <button id="submit_del" class="btn btn-danger" style="display:none" onclick="submit_del()">
                    <i class="fa fa-floppy-o"></i>確認刪除
                </button>
                <button id="cancel" class="btn btn-default" style="display:none" onclick="javascript:location.reload()">
                    <i class="fa fa-close"></i>取消刪除
                </button>
            <?php
            }
            ?>
        </div>
        <div class="box-body">
            <ul id="sortable">
                <?php
                if (!empty($photo)) {
                    foreach ($photo as $row) {
                ?>
                        <li data-cid="<?php echo $row['id'] ?>">
                            <input class="del_img" name="del_img" type="checkbox" value="<?php echo $row['id'] ?>" style="display:none">
                            <img class="img-responsive" src="<?php echo WWW_test1111_COM . $row['img'] ?>" alt="">
                            <div class="checkbox checkbox-slider--b">
                                <label>
                                    <?php if ($row['status'] == 1) { ?>
                                        <input class="status" value="<?php echo $row['id'] ?>" type="checkbox" checked><span>開啟</span>
                                    <?php } else { ?>
                                        <input class="status" value="<?php echo $row['id'] ?>" type="checkbox"><span>關閉</span>
                                    <?php } ?>
                                </label>
                            </div>
                        </li>
                <?php
                    }
                }
                ?>
            </ul>
        </div>
    </section>

    <section class="box box-default">
        <div class="box-header">
            <a href="image/" class="btn btn-default"><i class="fa fa-arrow-left"></i>回相簿列表</a>
        </div>
    </section>

</main>