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
function save_sort() {
	let sort = [];
	$("#sortable tr").each(function () {
		sort.push($(this).attr("data-id"));
	});
	$.ajax({
		url: "qa/ajax_edit_category_seq",
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
					.delay(2500);
			} else {
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
//刪除分類
function del(btn) {
	if (confirm("確定刪除? (包含分類下的內容)") === true) {
		let id = $(btn).parent().parent().attr("data-id");
		if (id > 0) {
			$.ajax({
				type: "POST",
				url: "./qa/ajax_del_category", //ajax接收的server端
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
							.addClass("success")
							.delay(2500);
					} else {
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
		} else {
			$("#alert-msg").show();
			$("#alert-content")
				.text("非法提交參數")
				.removeClass()
				.addClass("error")
				.delay(2500);
			setTimeout("location.reload()", 500);
		}
	}
}

//儲存編輯的資料
function submit_edit(btn) {
	btn.disabled = true;
	let text = $(btn).parent().parent().find(".form-control").val();
	let id = $(btn).parent().parent().attr("data-id");
	if (!id) {
		$("#alert-msg").show();
		$("#alert-content")
			.text("非法提交參數")
			.removeClass()
			.addClass("error")
			.delay(2500);
		setTimeout("location.reload()", 500);
		return;
	}
	if (!text) {
		$("#alert-msg").show();
		$("#alert-content")
			.text("名稱未填寫")
			.removeClass()
			.addClass("error")
			.delay(2500);
		return;
	}
	$.ajax({
		type: "POST",
		url: "./qa/ajax_edit_category", //ajax接收的server端
		data:
			"id=" +
			id +
			"&name=" +
			text +
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
//新增分類
function add_sort(type) {
	$("#add_main").modal("show");
}
//送出新增的分類
function submit_add() {
	let name = $("#add_name").val();
	if (!name) {
		$("#alert-msg").show();
		$("#alert-content")
			.text("非法提交參數")
			.removeClass()
			.addClass("error")
			.delay(2500);
		setTimeout("location.reload()", 500);
	} else {
		$.ajax({
			type: "POST",
			url: "./qa/ajax_add_category", //ajax接收的server端
			data: "name=" + name + "&csrf_token=" + getCookie("csrf_cookie_name"),
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
}
