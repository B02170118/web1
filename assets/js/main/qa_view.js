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
	$("#sortable li").each(function () {
		sort.push($(this).attr("data-id"));
	});
	$.ajax({
		url: "qa/ajax_edit_text_seq",
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
//刪除
function del(btn) {
	if (confirm("確定刪除?") === true) {
		let id = $(btn).parents("li").attr("data-id");
		if (id > 0) {
			$.ajax({
				type: "POST",
				url: "./qa/ajax_del_text", //ajax接收的server端
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
	let textarea = $(btn).parents(".items").find("textarea").attr("id");
	let editor = CKEDITOR.instances[textarea];
	let content = encodeURIComponent(editor.getData());
	let id = $(btn).parents("li").attr("data-id");
	let cid = $("#cid").text();
	if (!id || !cid) {
		$("#alert-msg").show();
		$("#alert-content")
			.text("非法提交參數")
			.removeClass()
			.addClass("error")
			.delay(2500);
		setTimeout("location.reload()", 500);
		return;
	}
	if (!content) {
		$("#alert-msg").show();
		$("#alert-content")
			.text("請輸入文字")
			.removeClass()
			.addClass("error")
			.delay(2500);
		return;
	}
	$.ajax({
		type: "POST",
		url: "./qa/ajax_edit_text", //ajax接收的server端
		data:
			"cid=" +
			cid +
			"&id=" +
			id +
			"&content=" +
			content +
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
			setTimeout("location.reload()", 500);
		},
		complete: function () {
			$("#loading").hide();
			setTimeout("location.reload()", 500);
		},
	});
}
//新增
function add_sort(type) {
	$("#add_main").modal("show");
}
//送出新增
function submit_add(btn) {
	btn.disabled = true;
	let textarea = $(btn).parents().parents().find("#qa_content_new").attr("id");
	let editor = CKEDITOR.instances[textarea];
	let content = encodeURIComponent(editor.getData());
	let cid = $("#cid").text();
	if (!cid) {
		$("#alert-msg").show();
		$("#alert-content")
			.text("非法提交參數")
			.removeClass()
			.addClass("error")
			.delay(2500);
		setTimeout("location.reload()", 500);
		return;
	}
	if (!content) {
		$("#alert-msg").show();
		$("#alert-content")
			.text("請輸入文字")
			.removeClass()
			.addClass("error")
			.delay(2500);
		btn.disabled = false;
		return;
	}
	$.ajax({
		type: "POST",
		url: "./qa/ajax_add_text", //ajax接收的server端
		data:
			"cid=" +
			cid +
			"&content=" +
			content +
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
				$("#alert-msg").show();
				$("#alert-content")
					.text(res.msg)
					.removeClass()
					.addClass("error")
					.delay(2500);
				btn.disabled = false;
			}
		},
		error: function (errorThrown) {
			$("#alert-msg").show();
			$("#alert-content")
				.text(errorThrown.statusText)
				.removeClass()
				.addClass("error")
				.delay(2500);
			setTimeout("location.reload()", 500);
		},
		complete: function () {
			$("#loading").hide();
			setTimeout("location.reload()", 500);
		},
	});
}
$(function () {
	CKEDITOR.replaceAll("qa_content", {});
});
