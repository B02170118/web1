<link rel="stylesheet" href="/assets/css/main/blog.css">
<main class="content">
    <section class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">文章列表</h3>
        </div>

        <div class="box-btns">
            <a id="add_btn" href="blog/add_blog" class="btn btn-primary">
                <i class="fa fa-plus"></i>新增文章
            </a>
            <button id="sort_btn" onclick="sort()" class="btn btn-info">
                <i class="fa fa-sort"></i>文章排序
            </button>
            <button id="submit_sort_btn" onclick="save_sort(this)" class="btn btn-info" style="display:none">
                <i class="fa fa-sort"></i>確認送出
            </button>
            <button id="cancel" class="btn btn-default" style="display:none" onclick="javascript:location.reload()">
                <i class="fa fa-close"></i>取消
            </button>
        </div>

        <?php
        if (!empty($blog)) {
        ?>
            <div class="box-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="15">ID</th>
                            <th>縮圖</th>
                            <th>標題</th>
                            <th>日期</th>
                            <th>狀態</th>
                            <th>功能</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="sortable" class="users-list">
                        <?php
                        foreach ($blog as $row) {
                        ?>
                            <tr data-id="<?php echo $row['id'] ?>">
                                <td>
                                    <span><?php echo $row['id'] ?></span>
                                </td>
                                <td>
                                    <img class="table-img" src="<?php echo WWW_test1111_COM . $row['img'] ?>" alt="">
                                </td>
                                <td>
                                    <span><?php echo $row['title'] ?></span>
                                </td>
                                <td>
                                    <small><?php echo $row['date'] ?></small>
                                </td>
                                <td>
                                    <?php if ($row['status'] == 1) { ?>
                                        <span class="text-success">開</span>
                                    <?php } else { ?>
                                        <span class="text-danger">關</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <a class="btn btn-warning" href="./blog/view/<?php echo $row['id'] ?>">
                                        <i class="fa fa-edit"></i>編輯
                                    </a>
                                </td>
                                <td>
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