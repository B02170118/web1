<main class="content">

    <section class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">線上預約/諮詢</h3>
        </div>

        <div class="box-body table-responsive">
            <table id="data-table-icon" class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>聯絡人</th>
                        <th>電話</th>
                        <th>郵件</th>
                        <th>諮詢</th>
                        <th>方便聯絡</th>
                        <th>留言時間</th>
                        <th>狀態</th>
                        <th>內容</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($contact)) {
                        foreach ($contact as $row) {
                    ?>
                            <tr class="table-row <?php if($row['status'] == 1):?> read-msg <?php endif;?>">
                                <td>
                                    <?php echo $row['id'] ?>
                                </td>
                                <td>
                                    <?php echo $row['name'] ?>
                                </td>
                                <td>
                                    <?php echo $row['phone'] ?>
                                </td>
                                <td>
                                    <?php echo $row['email'] ?>
                                </td>
                                <td>
                                    <?php echo $row['type'] ?>
                                </td>
                                <td>
                                    <?php echo $row['contact_time'] ?>
                                </td>
                                <td>
                                    <?php echo $row['question_time'] ?>
                                </td>
                                <td>
                                <?php if($row['status'] == 1):?>
                                    <button type="button" class="btn btn-success btn-status" onclick="is_status(<?php echo $row['id'] ?>,0,this)">
                                        <i class="fa fa-check"></i>已讀
                                    </button>
                                <?php else:?>
                                    <button type="button" class="btn btn-default btn-status" onclick="is_status(<?php echo $row['id'] ?>,1,this)">
                                        <i class="fa fa-commenting-o"></i>未讀
                                    </button>
                                <?php endif;?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-warning" onclick="get_view(<?php echo $row['id'] ?>)">
                                        <i class="fa fa-eye"></i>查看
                                    </button>
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



    <section class="modal fade" id="contact_view" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">線上預約/諮詢</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th width="15%">ID</th>
                            <td id="alert_id">
                            </td>
                        </tr>
                        <tr>
                            <th>聯絡人</th>
                            <td id="alert_name">
                            </td>
                        </tr>
                        <tr>
                            <th>電話</th>
                            <td id="alert_phone">
                            </td>
                        </tr>
                        <tr>
                            <th>郵件</th>
                            <td id="alert_email">
                            </td>
                        </tr>
                        <tr>
                            <th>諮詢</th>
                            <td id="alert_type">
                            </td>
                        </tr>

                        <tr>
                            <th>方便聯絡</th>
                            <td id="alert_contact_time">
                            </td>
                        </tr>
                        <tr>
                            <th>訂婚日期</th>
                            <td id="alert_engagement_date">
                            </td>
                        </tr>

                        <tr>
                            <th>結婚日期</th>
                            <td id="alert_marriage_date">
                            </td>
                        </tr>
                        <tr>
                            <th>預約到店</th>
                            <td id="alert_reserved_time">
                            </td>
                        </tr>
                        <tr>
                            <th>備註</th>
                            <td id="alert_remark">
                            </td>
                        </tr>
                        <tr>
                            <th>IP</th>
                            <td id="alert_ip">
                            </td>
                        </tr>
                        <tr>
                            <th>留言時間</th>
                            <td id="alert_question_time">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </section>

</main>