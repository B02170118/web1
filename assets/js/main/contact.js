$("#data-table-icon").DataTable({
	ordering: false,
});
function getCookie(name) {
	var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
	if (arr != null) return unescape(arr[2]);
	return null;
}
function get_view(id) {
	var contact_id = parseInt(id);
	if (contact_id > 0) {
		$.ajax({
			url: "contact/ajax_get_data",
			dataType: "JSON",
			data:
				"contact_id=" +
				contact_id +
				"&csrf_token=" +
				getCookie("csrf_cookie_name"),
			type: "POST",
			beforeSend: function () {
				$("#loading").show();
			},
			success: function (res) {
				if (res.code == 0) {
					$("#contact_view").modal("show");
					$("#alert_id").text(res.data.id);
					$("#alert_name").text(res.data.name);
					$("#alert_phone").text(res.data.phone);
					$("#alert_email").text(res.data.email);
					$("#alert_type").text(res.data.type);
					$("#alert_contact_time").text(res.data.contact_time);
					$("#alert_engagement_date").text(res.data.engagement_date);
					$("#alert_marriage_date").text(res.data.marriage_date);
					$("#alert_reserved_time").text(res.data.reserved_time);
					$("#alert_remark").text(res.data.remark);
					$("#alert_ip").text(res.data.ip);
					$("#alert_question_time").text(res.data.question_time);
				} else {
					$("#alert-msg").show();
					$("#alert-content")
						.text(res.msg)
						.removeClass()
						.addClass("error")
						.delay(2500);
					setTimeout("location.reload()", 500);
				}
			},
			error: function (errorThrown) {
				$("#alert-msg").show();
				$("#alert-content")
					.text("Error, 錯誤碼 : " + errorThrown.statusText)
					.removeClass()
					.addClass("error")
					.delay(2500);
				setTimeout("location.reload()", 500);
			},
			complete: function () {
				$("#loading").hide();
			},
		});
	} else {
		$("#alert-msg").show();
		$("#alert-content")
			.text("參數錯誤")
			.removeClass()
			.addClass("error")
			.delay(2500);
		setTimeout("location.reload()", 500);
	}
}
function is_status(id,status,btn) {
	var contact_id = parseInt(id);
	var status = parseInt(status);
	var tr = $(btn).parent().parent();
	if ( (status == 0 || status == 1) && contact_id) {
		$.ajax({
			url: "contact/ajax_change_status",
			dataType: "JSON",
			data:
				"contact_id=" +
				contact_id +
				"&status=" +
				status +
				"&csrf_token=" +
				getCookie("csrf_cookie_name"),
			type: "POST",
			beforeSend: function () {
				$("#loading").show();
			},
			success: function (res) {
				if (res.code == 0) {
					$("#alert-msg").show();
					$("#alert-content")
						.text(res.msg)
						.removeClass()
						.addClass("success")
						.delay(500);
				}else {
					$("#alert-msg").show();
					$("#alert-content")
						.text(res.msg)
						.removeClass()
						.addClass("error")
						.delay(2500);
				}
				setTimeout("location.reload()");
			},
			error: function (errorThrown) {
				$("#alert-msg").show();
				$("#alert-content")
					.text("Error, 錯誤碼 : " + errorThrown.statusText)
					.removeClass()
					.addClass("error")
					.delay(2500);
				setTimeout("location.reload()", 500);
			},
			complete: function () {
				$("#loading").hide();
			},
		});
	} else {
		$("#alert-msg").show();
		$("#alert-content")
			.text("參數錯誤")
			.removeClass()
			.addClass("error")
			.delay(2500);
		setTimeout("location.reload()", 500);
	}
}