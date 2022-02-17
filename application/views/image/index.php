<link rel="stylesheet" href="/assets/css/main/workphoto.css">
<main id="workphoto" class="content">

    <section id="workphoto_album" class="box box-default">

        <div class="box-header with-border">
            <h2 class="box-title">相簿列表</h2>
        </div>

        <div class="box-btns">
            <a id="add_btn" href="./image/add_category" class="btn btn-primary">
                <i class="fa fa-fw fa-plus"></i>新增相簿
            </a>
            <button id="sort_btn" onclick="sort()" class="btn btn-info">
                <i class="fa fa-sort"></i>排序相簿
            </button>
            <button id="submit_sort_btn" onclick="save_sort()" class="btn btn-info" style="display:none">
                <i class="fa fa-floppy-o"></i>確認排序
            </button>
            <button id="category_del" class="btn btn-danger">
                <i class="fa fa-minus-square"></i>刪除相簿
            </button>
            <button id="submit_del" class="btn btn-danger" style="display:none" onclick="submit_del()">
                <i class="fa fa-floppy-o"></i>確認刪除
            </button>
            <button id="cancel" class="btn btn-default" style="display:none" onclick="javascript:location.reload()">
                <i class="fa fa-close"></i>取消
            </button>
        </div>

        <div class="box-body">
            <ul id="sortable">
                <?php
                if (!empty($photo)) {
                    foreach ($photo as $row) {
                ?>
                        <li data-cid="<?php echo $row['id'] ?>">
                            <input class="del_category" name="del_category" type="checkbox" value="<?php echo $row['id'] ?>" style="display:none">
                            <a href="image/workphoto_category_view/<?php echo $row['id'] ?>">
                                <img class="img-responsive" src="<?php echo WWW_test1111_COM . $row['default_img'] ?>" alt="">
                            </a>
                            <h3><?php echo $row['name'] ?></h3>
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

</main>