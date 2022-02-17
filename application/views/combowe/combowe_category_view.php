<link rel="stylesheet" href="/assets/css/main/combowe.css">
<main class="content">

    <section class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">方案內容</h3>
        </div>
        <div class="box-btns">
            <?php
            if (!empty($category_id)) {
            ?>
            <button id="add_btn" onclick="add_text()" class="btn btn-primary">
                <i class="fa fa-plus"></i>新增內容
            </button>
            <?php
            }
            ?>
            <?php
            if (!empty($combowe)) {
            ?>
                <button id="sort_btn" onclick="sort()" class="btn btn-info">
                    <i class="fa fa-sort"></i>排序內容
                </button>
                <button id="submit_sort_btn" onclick="save_sort(this)" class="btn btn-info" style="display:none">
                    <i class="fa fa-sort"></i>確認送出
                </button>
                <button id="cancel" class="btn btn-default" style="display:none" onclick="javascript:location.reload()">
                    <i class="fa fa-close"></i>取消
                </button>
            <?php
            }
            ?>
        </div>

        <?php
        if (!empty($combowe)) {
        ?>
            <div class="box-body">

                <ul id="sortable">
                    <?php
                    foreach ($combowe as $row) {
                    ?>
                        <li data-id="<?php echo $row['id'] ?>">
                            <div class="items">
                                <div class="items_title">
                                    <?php echo $row['id'] ?>
                                </div>
                                <div class="items_content">
                                    <textarea id="content_<?php echo $row['id'] ?>" class="content" name="content"><?php echo $row['content'] ?></textarea>
                                </div>
                                <div class="items_btns">
                                    <button class="btn btn-success" onclick="submit_edit(this)"><i class="fa fa-floppy-o"></i>確認修改</button>
                                    <button class="btn btn-danger" onclick="del(this)"><i class="fa fa-minus-square"></i>刪除</button>
                                </div>
                            </div>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </div>

        <?php
        }
        ?>
    </section>

    <?php
    if (!empty($combowe)) {
    ?>
    <section class="box box-default">
        <div class="box-header">
            <a href="/combowe/combowe_category/<?php echo $main_id?>" class="btn btn-default"><i class="fa fa-arrow-left"></i>回方案列表</a>
        </div>
    </section>
    <?php
    }
    ?>
    <!-- modal 新增 -->
    <section class="modal fade" id="add_modal" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">新增文字</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th width="100">類別ID</th>
                            <td>
                                <input id="category_id" class="form-control" type="text" value="<?php echo $category_id ?>" disabled="disabled">
                            </td>
                        </tr>
                        <tr>
                            <th>類別</th>
                            <td>
                                <input id="add_type" class="form-control" type="text" value="<?php echo $category_name ?>" disabled="disabled" />
                            </td>
                        </tr>
                        <tr>
                            <th>文字</th>
                            <td>
                                <textarea id="content" class="form-control content" name="content"></textarea>
                            </td>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i>Cancel</button>
                    <button type="button" class="btn btn-success" onclick="submit_add(this)"><i class="fa fa-floppy-o"></i>Save</button>
                </div>
            </div>
        </div>
    </section>

</main>