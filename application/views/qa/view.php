<link rel="stylesheet" href="/assets/css/main/combowe.css">

<main class="content">
    <section class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                <?php echo $category_name ?>
            </h3>
        </div>

        <div class="box-btns">
            <button id="add_btn" onclick="add_sort()" class="btn btn-primary">
                <i class="fa fa-image"></i>新增問題
            </button>
            <?php
            if (!empty($qa)) {
            ?>
                <button id="sort_btn" onclick="sort()" class="btn btn-info">
                    <i class="fa fa-sort"></i>排序問題
                </button>
                <button id="submit_sort_btn" onclick="save_sort()" class="btn btn-info" style="display:none">
                    <i class="fa fa-floppy-o"></i>確認排序
                </button>
                <button id="cancel" class="btn btn-default" style="display:none" onclick="javascript:location.reload()">
                    <i class="fa fa-close"></i>取消
                </button>
            <?php
            }
            ?>
        </div>

        <p id="cid" style="display:none"><?php echo $cid ?></p>

        <?php
        if (!empty($qa)) {
        ?>
            <div class="box-body">
                <ul id="sortable">
                    <?php
                    foreach ($qa as $row) {
                    ?>
                        <li data-id="<?php echo $row['id'] ?>">
                            <div class="items">
                                <div class="items_title">
                                    <?php echo $row['id'] ?>
                                </div>
                                <div class="items_content">
                                    <textarea id="qa_content_<?php echo $row['id'] ?>" class="qa_content" name="qa_content"><?php echo $row['content'] ?></textarea>
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

                <!-- <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>內文ID</th>
                            <th>內容</th>
                            <th>功能</th>
                        </tr>
                    </thead>
                    <tbody id="sortable" class="users-list clearfix">
                        <?php
                        foreach ($qa as $row) {
                        ?>
                            <tr data-id="<?php echo $row['id'] ?>">
                                <th><?php echo $row['id'] ?></th>
                                <th>
                                    <textarea id="qa_content_<?php echo $row['id'] ?>" class="qa_content" name="qa_content"><?php echo $row['content'] ?></textarea>
                                </th>
                                <th>
                                    <button class="btn btn-success" onclick="submit_edit(this)"><i class="fa fa-floppy-o"></i>送出修改</button>
                                    <button class="btn btn-danger" onclick="del(this)"><i class="fa fa-minus-square"></i>刪除</button>
                                </th>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    <tfoot>

                    </tfoot>
                </table> -->

            </div>
        <?php
        }
        ?>
    </section>

    <section class="box box-default">
        <div class="box-header">
            <a href="/qa" class="btn btn-default"><i class="fa fa-arrow-left"></i>回列表</a>
        </div>
    </section>

    <!-- modal 新增影片 -->
    <section class="modal fade" id="add_main" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">新增問題</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>
                                <textarea id="qa_content_new" class="qa_content" name="qa_content"></textarea>
                            </th>
                        </tr>
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