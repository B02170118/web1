<main class="content">

    <section class="box box-default">

        <div class="box-header with-border">
            <h2 class="box-title">分類列表</h2>
        </div>

        <div class="box-btns">
            <button id="add_btn" onclick="add_sort()" class="btn btn-primary">
                <i class="fa fa-plus"></i>新增分類
            </button>
            <button id="sort_btn" onclick="sort()" class="btn btn-info">
                <i class="fa fa-sort"></i>分類排序
            </button>
            <button id="submit_sort_btn" onclick="save_sort()" class="btn btn-info" style="display:none">
                <i class="fa fa-floppy-o"></i>確認排序
            </button>
            <button id="cancel" class="btn btn-default" style="display:none" onclick="javascript:location.reload()">
                <i class="fa fa-close"></i>取消
            </button>
        </div>

        <div class="box-body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>分類名稱</th>
                        <th>內容</th>
                        <th>功能</th>
                    </tr>
                </thead>
                <tbody id="sortable" class="users-list clearfix">
                    <?php
                    if (!empty($qa)) {
                        foreach ($qa as $row) {
                    ?>
                            <tr data-id="<?php echo $row['id'] ?>">
                                <td>
                                    <?php echo $row['id'] ?>
                                </td>
                                <td>
                                    <input type='text' class='form-control' value='<?php echo $row['name'] ?>' />
                                </td>
                                <td>
                                    <a class="btn btn-warning" href="./qa/view/<?php echo $row['id'] ?>">
                                        <i class="fa fa-folder"></i>分類管理
                                    </a>
                                </td>
                                <td>
                                    <button class="btn btn-success" onclick="submit_edit(this)"><i class="fa fa-floppy-o"></i>確認修改</button>
                                    <button class="btn btn-danger" onclick="del(this)"><i class="fa fa-minus-square"></i>刪除</button>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </section>

    <!-- modal 新增影片 -->
    <section class="modal fade" id="add_main" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">新增分類</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>分類名稱</th>
                            <td>
                                <input id="add_name" class='form-control' type="text" value="" />
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i>Cancel</button>
                    <button type="button" class="btn btn-success" onclick="submit_add()"><i class="fa fa-floppy-o"></i>Save</button>
                </div>
            </div>
        </div>
    </section>
</main>