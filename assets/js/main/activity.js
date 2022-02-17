function getCookie(name) {
	var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
	if (arr != null) return unescape(arr[2]);
	return null;
}
//順序按鈕
function sort() {
	$("#sort_btn").hide();
	$("#add_btn").hide();
	$("#submit_sort_btn").show();
	$("#cancel").show();
	$("#sortable").sortable();
	$("#sortable").disableSelection();
}
//送出修改順序
function save_sort(btn) {
	btn.disabled = true;
	var sort = [];
	$("#sortable tr").each(function (i) {
		sort.push($(this).attr("data-id"));
	});
	if (sort.length > 0) {
		$.ajax({
			url: "activity/ajax_edit_activity_seq",
			dataType: "JSON",
			data: "sort=" + sort + "&csrf_token=" + getCookie("csrf_cookie_name"),
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
						.delay(2500)
				} else {
					$("#alert-msg").show();
					$("#alert-content")
						.text(res.msg)
						.removeClass()
						.addClass("error")
						.delay(2500)
				}
			},
			error: function (errorThrown) {
				$("#alert-msg").show();
				$("#alert-content")
					.text(errorThrown.statusText)
					.removeClass()
					.addClass("error")
					.delay(2500)
			},
			complete: function () {
				$("#loading").hide();
				setTimeout("location.reload()", 500);
			},
		});
	} else {
		$("#alert-msg").show();
		$("#alert-content")
			.text("參數錯誤")
			.removeClass()
			.addClass("error")
			.delay(2500)
		setTimeout("location.reload()", 500);
	}
}
//刪除項目
function del(btn) {
	if (confirm("確定刪除? (包含項目底下的資料)")) {
		let id = $(btn).parent().parent().attr("data-id");
		if (id > 0) {
			$.ajax({
				type: "POST",
				url: "./activity/ajax_del_activity", //ajax接收的server端
				data: "id=" + id + "&csrf_token=" + getCookie("csrf_cookie_name"),
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
							.addClass("alert-msg-success")
							.delay(2500)
							
					} else {
						$("#alert-msg").show();
						$("#alert-content")
							.text(res.msg)
							.removeClass()
							.addClass("alert-msg-error")
							.delay(2500)
					}
				},
				error: function (errorThrown) {
					$("#alert-msg").show();
					$("#alert-content")
						.text("錯誤代碼: " + errorThrown.statusText)
						.removeClass()
						.addClass("alert-msg-error")
						.delay(2500)
				},
				complete: function () {
					$("#loading").hide();
					setTimeout("location.reload()", 500);
				},
			});
		} else {
			$("#alert-msg").show();
			$("#alert-content")
				.text("無資料")
				.removeClass()
				.addClass("alert-msg-error")
				.delay(2500)
			setTimeout("location.reload()", 500);
		}
	}
}
