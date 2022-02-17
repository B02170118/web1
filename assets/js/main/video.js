function getCookie(name) {
	var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
	if (arr != null) return unescape(arr[2]);
	return null;
}

//改狀態
$(".checkbox").change(function () {
	$(".status").prop("disabled", true); //關閉checkbox
	let node = $(this).find(".status");
	let id = $(node).val();
	let status = null;
	if ($(node).prop("checked") == true) {
		status = 1;
	} else {
		status = 0;
	}
	if (!id || status == null) {
		$("#alert-msg").show();
		$("#alert-content")
			.text("變更失敗")
			.removeClass()
			.addClass("error")
			.delay(2500);
		setTimeout("location.reload()", 500);
	} else {
		$.ajax({
			type: "POST",
			url: "./video/ajax_change_status_video", //ajax接收的server端
			data:
				"id=" +
				id +
				"&status=" +
				status +
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
});
//順序按鈕
function sort(type) {
	if (type == 1) {
		//hide
		$("#top_add_btn").hide();
		$("#top_sort_btn").hide();
		$("#top_del_btn").hide();
		//show
		$("#top_save_btn").show();
		$("#top_cancel_btn").show();
		//sort
		$("#workvideo_top #sortable").sortable();
		$("#workvideo_top #sortable").disableSelection();
		$("#workvideo_top #sortable li a").css("pointer-events", "none");
	} else {
		//hide
		$("#workvideo_list #add_btn").hide();
		$("#workvideo_list #sort_btn").hide();
		$("#workvideo_list #del_btn").hide();
		//show
		$("#workvideo_list #save_btn").show();
		$("#workvideo_list #cancel_btn").show();
		//sort
		$("#workvideo_list #sortable").sortable();
		$("#workvideo_list #sortable").disableSelection();
		$("#workvideo_list #sortable li a").css("pointer-events", "none");
	}
}
//送出修改順序
function save_sort(type) {
	let sort = [];
	if (type == 1) {
		$("#workvideo_top #sortable li").each(function () {
			sort.push($(this).attr("data-id"));
		});
	} else {
		$("#workvideo_list #sortable li").each(function () {
			sort.push($(this).attr("data-id"));
		});
	}
	$.ajax({
		url: "video/ajax_edit_video_seq",
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

//刪除相簿
function del_sort(type) {
	if (type == 1) {
		//hide
		$("#top_add_btn").hide();
		$("#top_sort_btn").hide();
		$("#top_del_btn").hide();
		//show
		$("#top_del_save_btn").show();
		$("#top_cancel_btn").show();
		$("#workvideo_top #sortable li").addClass("remove");
		$("#workvideo_top #sortable li a").css("pointer-events", "none");
		$("#workvideo_top #sortable li").click(function () {
			$(this).toggleClass("on");
			$(this)
				.children(".top_del_video")
				.each(function () {
					this.checked = !this.checked;
				});
		});
	} else {
		//hide
		$("#workvideo_list #add_btn").hide();
		$("#workvideo_list #sort_btn").hide();
		$("#workvideo_list #del_btn").hide();
		//show
		$("#workvideo_list #del_save_btn").show();
		$("#workvideo_list #cancel_btn").show();
		$("#workvideo_list #sortable li").addClass("remove");
		$("#workvideo_list #sortable li a").css("pointer-events", "none");
		$("#workvideo_list #sortable li").click(function () {
			$(this).toggleClass("on");
			$(this)
				.children(".del_video")
				.each(function () {
					this.checked = !this.checked;
				});
		});
	}
}
//送出刪除
function submit_del(type) {
	if (confirm("確定刪除?")) {
		let id = [];
		if (type == 1) {
			$("input[name='top_del_video']:checked").each(function (i) {
				id[i] = $(this).val();
			});
		} else {
			$("input[name='del_video']:checked").each(function (i) {
				id[i] = $(this).val();
			});
		}
		if (id.length > 0) {
			$.ajax({
				type: "POST",
				url: "./video/ajax_del_video", //ajax接收的server端
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
				.text("沒有選取影片")
				.removeClass()
				.addClass("error")
				.delay(2500);
			setTimeout("location.reload()", 500);
		}
	}
}

//新增影片
function add_sort(type) {
	$("#add_video").modal("show");
	$("#add_type").text(type);
}
//送出新增的影片
function submit_add() {
	let title = $("#add_title").val();
	let content = $("#add_content").val();
	let link = $("#add_link").val();
	let type = $("#add_type").text();
	let status = null;
	if ($("#add_status").prop("checked") == true) {
		status = 1;
	} else {
		status = 0;
	}
	if (parseFloat(type).toString() == "NaN") {
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
			url: "./video/ajax_add_video", //ajax接收的server端
			data:
				"title=" +
				title +
				"&content=" +
				content +
				"&link=" +
				link +
				"&type=" +
				type +
				"&status=" +
				status +
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
