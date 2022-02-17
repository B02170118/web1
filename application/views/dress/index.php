<link rel="stylesheet" href="/assets/css/main/dress.css">
<main id="dress" class="content">

    <section id="dress_list" class="box box-default">

        <div class="box-header with-border">
            <h2 class="box-title">分類列表</h2>
        </div>

        <div class="box-btns">
            <button id="add_btn" onclick="add_sort()" class="btn btn-primary">
                <i class="fa fa-fw fa-plus"></i>新增分類
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

        <div class="box-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>名稱</th>
                        <th>狀態</th>
                        <th>內容</th>
                        <th>功能</th>
                    </tr>
                </thead>
                <tbody id="sortable" class="users-list clearfix">
                    <?php
                    if (!empty($dress)) {
                        foreach ($dress as $row) {
                    ?>
                            <tr data-id="<?php echo $row['id'] ?>">
                                <td>
                                    <?php echo $row['id'] ?>
                                </td>
                                <td>
                                    <input type='text' class='form-control' value='<?php echo $row['name'] ?>' />
                                </td>
                                <td>
                                    <div class="checkbox checkbox-slider--b">
                                        <label>
                                            <?php if ($row['status'] == 1) { ?>
                                                <input class="status" value="<?php echo $row['id'] ?>" type="checkbox" checked><span>開啟</span>
                                            <?php } else { ?>
                                                <input class="status" value="<?php echo $row['id'] ?>" type="checkbox"><span>關閉</span>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <a class="btn btn-warning" href="./dress/dress_main_view/<?php echo $row['id'] ?>">
                                        <i class="fa fa-fw fa-folder"></i>相簿管理
                                    </a>
                                </td>
                                <td>
                                    <button class="btn btn-success" onclick="submit_edit(this)"><i class="fa fa-fw fa-check-square"></i>確認修改</button>
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

    <section id="add_main" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">新增分類</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>名稱</th>
                            <td>
                                <input id="add_name" type="text" class="form-control input-lg" value="" />
                            </td>
                        </tr>
                        <tr>
                            <th>狀態</th>
                            <td>
                                <div class="checkbox checkbox-slider--b">
                                    <label>
                                        <input id="add_status" value="" type="checkbox" checked><span>開啟狀態</span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="submit_add()"><i class="fa fa-floppy-o"></i>新增</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i>取消</button>
                </div>
            </div>
        </div>
    </section>

</main>