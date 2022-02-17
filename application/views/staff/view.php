<main class="content">
    <section class="box box-default">
        <?php echo form_open_multipart("staff/form_edit_staff", array('id' => 'form1')); ?>

        <div class="box-header with-border">
            <h3 class="box-title">編輯內容</h3>
        </div>

        <div class="box-body">
            <?php
            if (!empty($staff)) {
            ?>
                <input type="hidden" name="id" value="<?php echo $staff['id'] ?>">
                <table id="data-table" class="table table-bordered table-striped">
                    <tr>
                        <th>名稱</th>
                        <td>
                            <input type="text" class="form-control" name="title" value="<?php echo $staff['title'] ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <th>職稱</th>
                        <td>
                            <input type="text" class="form-control" name="subtitle" value="<?php echo $staff['subtitle'] ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <th>建議比例</th>
                        <td class="text-info">方形：W500xH500 72dpi</td>
                    </tr>
                    <tr>
                        <th>預設圖</th>
                        <td>
                            <input id="file2" name="userfile2[]" type="file"><br>
                            <img id="table-img2" class="table-img" src="<?php echo WWW_test1111_COM . $staff['img2'] ?>" alt="">
                        </td>
                    </tr>
                    <tr>
                        <th>變化圖</th>
                        <td>
                            <input id="file" name="userfile[]" type="file"><br>
                            <input type="hidden" name="type" value="staff">
                            <img id="table-img" class="table-img" src="<?php echo WWW_test1111_COM . $staff['img'] ?>" alt="">
                        </td>
                    </tr>
                    <tr>
                        <th>介紹</th>
                        <td>
                            <textarea name="content" class="form-control" rows="8" form="form1" required><?php echo $staff['content'] ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>連結</th>
                        <td>
                            <input type="text" class="form-control" name="href" value="<?php echo $staff['href'] ?>">
                        </td>
                    </tr>
                </table>
            <?php
            }
            ?>
        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-success">
                <i class="fa fa-floppy-o"></i>確認修改
            </button>
        </div>

    </section>
    <section class="box box-default">
        <div class="box-header">
            <a href="/staff" class="btn btn-default"><i class="fa fa-arrow-left"></i>回列表</a>
        </div>
    </section>
</main>