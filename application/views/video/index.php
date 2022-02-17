<link rel="stylesheet" href="/assets/css/main/video.css">
<main id="video" class="content">

    <section id="workvideo_top" class="box box-default">
        <div class="box-header with-border">
            <h2 class="box-title">置頂影片</h2>
        </div>
        <div class="box-btns">
            <button id="top_add_btn" onclick="add_sort(1)" class="btn btn-primary">
                <i class="fa fa-youtube-play"></i>新增影片
            </button>
            <button id="top_sort_btn" onclick="sort(1)" class="btn btn-info">
                <i class="fa fa-sort"></i>影片排序
            </button>
            <button id="top_save_btn" onclick="save_sort(1)" class="btn btn-info" style="display:none">
                <i class="fa fa-sort"></i>確認排序
            </button>
            <button id="top_del_btn" onclick="del_sort(1)" class="btn btn-danger">
                <i class="fa fa-minus-square"></i>刪除影片
            </button>
            <button id="top_del_save_btn" onclick="submit_del(1)" class="btn btn-danger" style="display:none">
                <i class="fa fa-floppy-o"></i>確認刪除
            </button>
            <button id="top_cancel_btn" onclick="javascript:location.reload()" class="btn btn-default" style="display:none">
                <i class="fa fa-close"></i>取消
            </button>
        </div>
        <div class="box-body">
            <ul id="sortable" class="users-list clearfix">
                <?php
                if (!empty($top_video)) {
                    foreach ($top_video as $row) {
                ?>
                        <li data-id="<?php echo $row['id'] ?>">
                            <input class="top_del_video" name="top_del_video" type="checkbox" value="<?php echo $row['id'] ?>" style="display:none">
                            <div class="items">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <a href="video/video_view/<?php echo $row['id'] ?>">
                                        <img src="https://img.youtube.com/vi/<?php echo $row['link'] ?>/0.jpg">
                                    </a>
                                </div>
                                <h4>
                                    <?php echo $row['title'] ?>
                                </h4>
                                <div class="checkbox checkbox-slider--b">
                                    <label>
                                        <?php if ($row['status'] == 1) { ?>
                                            <input class="status" value="<?php echo $row['id'] ?>" type="checkbox" checked><span>開啟</span>
                                        <?php } else { ?>
                                            <input class="status" value="<?php echo $row['id'] ?>" type="checkbox"><span>關閉</span>
                                        <?php } ?>
                                    </label>
                                </div>
                            </div>
                        </li>
                <?php
                    }
                }
                ?>
            </ul>
        </div>

    </section>

    <section id="workvideo_list" class="box box-default">
        <div class="box-header with-border">
            <h2 class="box-title">一般影片</h2>
        </div>
        <div class="box-btns">
            <button id="add_btn" onclick="add_sort(0)" class="btn btn-primary">
                <i class="fa fa-youtube-play"></i>新增影片
            </button>
            <button id="sort_btn" onclick="sort(0)" class="btn btn-info">
                <i class="fa fa-sort"></i>影片排序
            </button>
            <button id="save_btn" onclick="save_sort(0)" class="btn btn-info" style="display:none">
                <i class="fa fa-sort"></i>確認排序
            </button>
            <button id="del_btn" onclick="del_sort(0)" class="btn btn-danger">
                <i class="fa fa-minus-square"></i>刪除影片
            </button>
            <button id="del_save_btn" onclick="submit_del(0)" class="btn btn-danger" style="display:none">
                <i class="fa fa-floppy-o"></i>確認刪除
            </button>
            <button id="cancel_btn" onclick="javascript:location.reload()" class="btn btn-default" style="display:none">
                <i class="fa fa-close"></i>取消
            </button>
        </div>
        <div class="box-body">
            <ul id="sortable" class="users-list clearfix">
                <?php
                if (!empty($video)) {
                    foreach ($video as $row) {
                ?>
                        <li data-id="<?php echo $row['id'] ?>">
                            <input class="del_video" name="del_video" type="checkbox" value="<?php echo $row['id'] ?>" style="display:none">
                            <div class="items">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <a href="video/video_view/<?php echo $row['id'] ?>">
                                        <img src="https://img.youtube.com/vi/<?php echo $row['link'] ?>/0.jpg">
                                    </a>
                                </div>
                                <h4>
                                    <?php echo $row['title'] ?>
                                </h4>
                                <div class="checkbox checkbox-slider--b">
                                    <label>
                                        <?php if ($row['status'] == 1) { ?>
                                            <input class="status" value="<?php echo $row['id'] ?>" type="checkbox" checked><span>開啟</span>
                                        <?php } else { ?>
                                            <input class="status" value="<?php echo $row['id'] ?>" type="checkbox"><span>關閉</span>
                                        <?php } ?>
                                    </label>
                                </div>
                            </div>
                        </li>
                <?php
                    }
                }
                ?>
            </ul>
        </div>
    </section>

    <!-- modal 新增影片 -->
    <section class="modal fade" id="add_video" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">新增影片</h4>
                </div>
                <div class="modal-body">
                    <table id="add_video" class="table table-bordered table-striped">
                        <tr>
                            <th>標題</th>
                            <td>
                                <input id="add_title" class="form-control" type="text" value="" />
                            </td>
                        </tr>
                        <tr>
                            <th>內文</th>
                            <td>
                                <textarea id="add_content" class="form-control" rows="10"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>影片ID</th>
                            <td>
                                <input id="add_link" class="form-control" type="text" value="" />
                            </td>
                        </tr>
                        <tr>
                            <th>類型</th>
                            <td>
                                <p id="add_type"></p>
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
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i>Cancel</button>
                    <button type="button" class="btn btn-success" onclick="submit_add()"><i class="fa fa-floppy-o"></i>Save</button>
                </div>
            </div>
        </div>
    </section>

</main>