<!-- bootstrap datepicker -->
<link rel="stylesheet" href="assets/bower_components/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">

<main class="content">
    <?php echo form_open_multipart("activity/form_edit_activity"); ?>

    <section class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">編輯首頁活動</h3>
        </div>
        <div class="box-body">
            <table id="data-table" class="table table-striped">
                <?php
                if (!empty($activity)) {
                ?>
                    <input name="id" type="hidden" value="<?php echo $activity['id'] ?>">
                    <tr>
                        <th width="20%">建議比例</th>
                        <td class="text-info">橫式：W1366xH680 72dpi</td>
                    </tr>
                    <tr>
                        <th>活動圖</th>
                        <td>
                            <img class="table-img" src="<?php echo WWW_test1111_COM . $activity['img'] ?>" alt="">
                        </td>
                    </tr>
                    <tr>
                        <th>上傳</th>
                        <td>
                            <input id="file" name="userfile[]" type="file"><br>
                            <input type="hidden" name="type" value="activity">
                        </td>
                    </tr>
                    <tr>
                        <th>連結網址</th>
                        <td>
                            <input type="text" class="form-control" name="href" value="<?php echo $activity['href'] ?>">
                        </td>
                    </tr>
                    <tr>
                        <th>開始時間</th>
                        <td>
                            <div class='input-group date datetimepicker'>
                                <input type='text' class="form-control" name="start_time" value="<?php echo $activity['start_time'] ?>" readonly required>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar">
                                    </span>
                                </span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>結束時間</th>
                        <td>
                            <div class='input-group date datetimepicker'>
                                <input type='text' class="form-control" name="end_time" value="<?php echo $activity['end_time'] ?>" readonly required>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar">
                                    </span>
                                </span>
                            </div>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </table>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-success">
                <i class="fa fa-floppy-o"></i>送出
            </button>
        </div>
    </section>
    </form>
    <section class="box box-default">
        <div class="box-header">
            <a href="/activity" class="btn btn-default"><i class="fa fa-arrow-left"></i>回列表</a>
        </div>
    </section>
</main>