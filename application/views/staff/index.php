<main class="content">
    <section class="box box-default">

        <div class="box-header with-border">
            <h2 class="box-title">列表</h2>
        </div>

        <div class="box-btns">
            <a id="add_btn" href="staff/add_staff" class="btn btn-primary">
                <i class="fa fa-plus"></i>新增
            </a>
            <button id="sort_btn" onclick="sort()" class="btn btn-info">
                <i class="fa fa-sort"></i>排序
            </button>
            <button id="submit_sort_btn" onclick="save_sort(this)" class="btn btn-info" style="display:none">
                <i class="fa fa-sort"></i>確認送出
            </button>
            <button id="cancel" class="btn btn-default" style="display:none" onclick="javascript:location.reload()">
                <i class="fa fa-close"></i>取消
            </button>
        </div>

        <?php
        if (!empty($staff)) {
        ?>
            <div class="box-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>名稱</th>
                            <th>職稱</th>
                            <th>預設圖</th>
                            <th>連結</th>
                            <th>功能</th>
                        </tr>
                    </thead>
                    <tbody id="sortable" class="users-list clearfix">
                        <?php
                        foreach ($staff as $row) {
                        ?>
                            <tr data-id="<?php echo $row['id'] ?>">
                                <td>
                                    <?php echo $row['id'] ?>
                                </td>
                                <td>
                                    <?php echo $row['title'] ?>
                                </td>
                                <td>
                                    <?php echo $row['subtitle'] ?>
                                </td>
                                <td>
                                    <img class="table-img" src="<?php echo WWW_test1111_COM . $row['img2'] ?>" alt="">
                                </td>
                                <td>
                                    <a href="<?php echo $row['href'] ?>" target="_blank">LINK</a>
                                </td>
                                <td>
                                    <a class="btn btn-warning" href="./staff/view/<?php echo $row['id'] ?>">
                                        <i class="fa fa-edit"></i>編輯
                                    </a>
                                    <button class="btn btn-danger" onclick="del(this)"><i class="fa fa-minus-square"></i>刪除</button>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        <?php
        }
        ?>
    </section>

</main>