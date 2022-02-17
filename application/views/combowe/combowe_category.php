<main class="content">

    <?php
    if (!empty($main)) {
    ?>
        <section class="box box-default">

            <div class="box-header with-border">
                <h2 class="box-title">編輯類別</h2>
            </div>

            <form action="./combowe/form_edit_main" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                <input type="hidden" name="csrf_token" value="<?php echo $this->security->get_csrf_hash() ?>">

                <div class="box-body">
                    <table id="data-table" class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th>類別ID</th>
                                <td>
                                    <input type="text" class="form-control" value="<?php echo $main['id'] ?>" disabled="disabled">
                                    <input id="main_id" type="hidden" name="main_id" value="<?php echo $main['id'] ?>">
                                </td>
                            </tr>
                            <tr>
                                <th>類別標題</th>
                                <td>
                                    <input class="form-control input-lg" type="text" name="main_title" value="<?php echo $main['title'] ?>">
                                </td>
                            </tr>
                            <tr>
                                <th>建議比例</th>
                                <td class="text-info">橫式：W1110xH240 72dpi</td>
                            </tr>
                            <tr>
                                <th>上傳</th>
                                <td>
                                    <input id="file" name="userfile[]" type="file"><br>
                                    <input type="hidden" name="type" value="combowe">
                                </td>
                            </tr>
                            <tr>
                                <th>背景圖</th>
                                <td>
                                    <img class="table-img" src="<?php echo WWW_test1111_COM . $main['img'] ?>" alt="">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-floppy-o"></i>確認修改
                    </button>
                </div>

            </form>
        </section>
    <?php
    }
    ?>

        <section class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title">方案列表</h3>
            </div>

            <?php
            if (!empty($main)) {
            ?>
                <div class="box-btns">
                    <button id="add_btn" onclick="add_category()" class="btn btn-primary">
                        <i class="fa fa-plus"></i>新增方案
                    </button>
                    <button id="sort_btn" onclick="sort()" class="btn btn-info">
                        <i class="fa fa-sort"></i>方案排序
                    </button>
                    <button id="submit_sort_btn" onclick="save_sort(this)" class="btn btn-info" style="display:none">
                        <i class="fa fa-sort"></i>確認送出
                    </button>
                    <button id="cancel" class="btn btn-default" style="display:none" onclick="javascript:location.reload()">
                        <i class="fa fa-close"></i>取消
                    </button>
                </div>
            <?php
            }
            ?>
    <?php
    if (!empty($category)) {
    ?>
            <div class="box-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="15">ID</th>
                            <th>方案名稱</th>
                            <th width="120">原價</th>
                            <th width="120">特價</th>
                            <th>內容</th>
                            <th>功能</th>
                        </tr>
                    </thead>
                    <tbody id="sortable" class="users-list clearfix">
                        <?php
                        if (!empty($category)) {
                            foreach ($category as $row) {
                        ?>
                                <tr data-id="<?php echo $row['id'] ?>">
                                    <td>
                                        <?php echo $row['id'] ?>
                                    </td>
                                    <td>
                                        <input class="category_title form-control" type="text" name="category_title" value="<?php echo $row['title'] ?>">
                                    </td>
                                    <td>
                                        <input class="old_price form-control" type="text" name="old_price" value="<?php echo $row['old_price'] ?>">
                                    </td>
                                    <td>
                                        <input class="price form-control" type="text" name="price" value="<?php echo $row['price'] ?>">
                                    </td>
                                    <td>
                                        <a class="btn btn-warning" href="/combowe/combowe_category_view/<?php echo $row['id'] ?>">
                                            <i class="fa fa-edit"></i>編輯
                                        </a>
                                    </td>
                                    <td>
                                        <button class="btn btn-success" onclick="submit_edit(this)"><i class="fa fa-floppy-o"></i>修改</button>
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

    <?php
    }
    ?>

    <section class="box box-default">
        <div class="box-header">
            <a href="/combowe" class="btn btn-default"><i class="fa fa-arrow-left"></i>回類別列表</a>
        </div>
    </section>

    <!-- modal 新增 -->
    <section class="modal fade" id="add_category" tabindex="-1">
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
                            <th>方案名稱</th>
                            <td>
                                <input id="add_title" class="form-control" type="text" value="" />
                            </td>
                        </tr>
                        <tr>
                            <th>原價</th>
                            <td>
                                <input id="add_old_price" class="form-control" type="text" value="" />
                            </td>
                        </tr>
                        <tr>
                            <th>特價</th>
                            <td>
                                <input id="add_price" class="form-control" type="text" value="" />
                            </td>
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