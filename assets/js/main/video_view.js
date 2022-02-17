function getCookie(name) {
	var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
	if (arr != null) return unescape(arr[2]);
	return null;
}
//儲存編輯的資料
function submit_edit(btn) {
	btn.disabled = true;
	let type = $("input[name=type]:checked").val();
	let content = $("#content").val();
	let link = $("#link").val();
	let title = $("#title").val();
	let video_id = $("#video_id").val();
	if (!video_id) {
		$("#alert-msg").show();
		$("#alert-content")
			.text("非法提交參數")
			.removeClass()
			.addClass("error")
			.delay(2500);
		setTimeout("location.reload()", 500);
		return;
	}
	if (!type || !link || !title) {
		$("#alert-msg").show();
		$("#alert-content")
			.text("有未填寫的欄位")
			.removeClass()
			.addClass("error")
			.delay(2500);
		setTimeout("location.reload()", 2000);
		return;
	}
	$.ajax({
		type: "POST",
		url: "./video/ajax_edit_video", //ajax接收的server端
		data:
			"type=" +
			type +
			"&content=" +
			content +
			"&link=" +
			link +
			"&title=" +
			title +
			"&video_id=" +
			video_id +
			"&csrf_token=" +
			getCookie("csrf_cookie_name"),
		dataType: "json",
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
					.delay(2500);
			} else {
				btn.disabled = false;
				$("#alert-msg").show();
				$("#alert-content")
					.text(res.msg)
					.removeClass()
					.addClass("error")
					.delay(2500);
			}
		},
		error: function (errorThrown) {
			$("#alert-msg").show();
			$("#alert-content")
				.text(errorThrown.statusText)
				.removeClass()
				.addClass("error")
				.delay(2500);
		},
		complete: function () {
			$("#loading").hide();
			setTimeout("location.reload()", 500);
		},
	});
}
