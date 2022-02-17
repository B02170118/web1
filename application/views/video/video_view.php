<link rel="stylesheet" href="/assets/css/main/video.css">
<main id="video" class="content">

    <section class="box box-default">
        <?php
        if (!empty($video)) {
        ?>
            <div class="box-header with-border">
                <h3 class="box-title">編輯內容</h3>
            </div>

            <div class="box-body">
                <table id="data-table-product" class="table table-bordered table-striped">
                    <tr>
                        <th>ID</th>
                        <td>
                            <input id="video_id" class="form-control" type="text" name="video_id" value="<?php echo $video['id'] ?>" disabled="disabled">
                        </td>
                    </tr>
                    <tr>
                        <th>影片標題</th>
                        <td>
                            <input id="title" class="form-control input-lg" type="text" name="title" value="<?php echo $video['title'] ?>">
                        </td>
                    </tr>
                    <tr>
                        <th>影片ID</th>
                        <td>
                            <input id="link" class="form-control" type="text" name="link" value="<?php echo $video['link'] ?>">
                            <div class="text-info">
                                於youtube網址中取得：<br />
                                https://www.youtube.com/watch?v=「<span class="text-danger">YDsrfY_rlGU</span>」&feature=emb_title
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>影片介紹</th>
                        <td>
                            <textarea id="content" name="content" class="form-control" rows="10"><?php echo $video['content'] ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>影片位置</th>
                        <td>
                            <label>
                                <?php
                                if ($video['type'] == 1) {
                                ?>
                                    <input type="radio" name="type" value="1" checked>置頂
                                    <input type="radio" name="type" value="0">一般
                                <?php
                                } else {
                                ?>
                                    <input type="radio" name="type" value="1">置頂
                                    <input type="radio" name="type" value="0" checked>一般
                                <?php
                                }
                                ?>
                            </label>
                        </td>
                    </tr>

                </table>
            </div>

            <div class="box-footer">
                <button onclick="submit_edit(this)" class="btn btn-success"><i class="fa fa-floppy-o"></i>確認修改</button>
            </div>
        <?php
        }
        ?>
    </section>
    <section class="box box-default">
        <div class="box-header">
            <a href="video/" class="btn btn-default"><i class="fa fa-arrow-left"></i>回影片列表</a>
        </div>
    </section>

</main>