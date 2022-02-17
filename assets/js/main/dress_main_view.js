function getCookie(name) {
	var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
	if (arr != null) return unescape(arr[2]);
	return null;
}

//改狀態
$(".checkbox").change(function () {
	$(".status").prop("disabled", true); //關閉checkbox
	let node = $(this).find(".status");
	let category_id = $(node).val();
	let status = null;
	if ($(node).prop("checked") == true) {
		status = 1;
	} else {
		status = 0;
	}
	if (!category_id || status == null) {
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
			url: "./dress/ajax_change_status_category", //ajax接收的server端
			data:
				"category_id=" +
				category_id +
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
function sort() {
	$("#sort_btn").hide();
	$("#add_btn").hide();
	$("#category_del").hide();
	$("#submit_sort_btn").show();
	$("#cancel").show();
	$("#sortable").sortable();
	$("#sortable").disableSelection();
	$("#sortable li a").css("pointer-events", "none");
}
//送出修改順序
function save_sort() {
	let sort = [];
	$("#sortable li").each(function () {
		sort.push($(this).attr("data-cid"));
	});
	$.ajax({
		url: "dress/ajax_edit_category_seq",
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
$("#category_del").click(function () {
	$(this).hide();
	$("#add_btn").hide();
	$("#sort_btn").hide();
	$("#submit_del").show();
	$("#cancel").show();
	$("#sortable li").addClass("remove");
	$("#sortable li a").css("pointer-events", "none");
	$("#sortable li").click(function () {
		$(this).toggleClass("on");
		$(this)
			.children(".del_category")
			.each(function () {
				this.checked = !this.checked;
			});
	});
});
//送出刪除
function submit_del() {
	if (confirm("確定刪除? (包含相簿內的相片)")) {
		let id = [];
		$("input[name='del_category']:checked").each(function (i) {
			id[i] = $(this).val();
		});
		if (id.length > 0) {
			$.ajax({
				type: "POST",
				url: "./dress/ajax_del_category", //ajax接收的server端
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
				.text("沒有選取相簿")
				.removeClass()
				.addClass("error")
				.delay(2500);
			setTimeout("location.reload()", 500);
		}
	}
}
