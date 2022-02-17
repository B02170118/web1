<main class="content">

    <section class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">類別列表</h3>
        </div>

        <div class="box-btns">
            <a id="add_btn" href="./combowe/add_main" class="btn btn-primary">
                <i class="fa fa-plus"></i>新增類別
            </a>
            <button id="sort_btn" onclick="sort()" class="btn btn-info">
                <i class="fa fa-sort"></i>調整順序
            </button>
            <button id="submit_sort_btn" onclick="save_sort(this)" style="display:none" class="btn btn-info">
                <i class="fa fa-sort"></i>確認送出
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
                        <th>類別名稱</th>
                        <th>背景圖</th>
                        <th>內容</th>
                        <th>功能</th>
                    </tr>
                </thead>
                <tbody id="sortable" class="users-list clearfix">
                    <?php
                    if (!empty($combowe)) {
                        foreach ($combowe as $row) {
                    ?>
                            <tr data-id="<?php echo $row['id'] ?>">
                                <td>
                                    <?php echo $row['id'] ?>
                                </td>
                                <td>
                                    <?php echo $row['title'] ?>
                                </td>
                                <td>
                                    <img class="table-img" src="<?php echo WWW_test1111_COM . $row['img'] ?>" alt="">
                                </td>
                                <td>
                                    <a class="btn btn-warning" href="combowe/combowe_category/<?php echo $row['id'] ?>">
                                        <i class="fa fa-folder"></i>類別管理
                                    </a>
                                </td>
                                <td>
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

</main>