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
	let sort = [];
	$("#sortable tr").each(function () {
		sort.push($(this).attr("data-id"));
	});
	$.ajax({
		url: "combowe/ajax_edit_category_seq",
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
	if (confirm("確定刪除? (包含分類下的所有相簿和相片)") === true) {
		btn.disabled = true;
		let id = $(btn).parent().parent().attr("data-id");
		if (id > 0) {
			$.ajax({
				type: "POST",
				url: "./combowe/ajax_del_category", //ajax接收的server端
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
	let category_title = $(btn).parent().parent().find(".category_title").val();
	let old_price = $(btn).parent().parent().find(".old_price").val();
	let price = $(btn).parent().parent().find(".price").val();
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
	if (!category_title || !old_price || !price) {
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
		url: "./combowe/ajax_edit_category", //ajax接收的server端
		data:
			"id=" +
			id +
			"&category_title=" +
			category_title +
			"&old_price=" +
			old_price +
			"&price=" +
			price +
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
function add_category() {
	$("#add_category").modal("show");
}
//送出新增的分類
function submit_add(btn) {
	btn.disabled = true;
	let main_id = $("#main_id").val();
	let category_title = $("#add_title").val();
	let old_price = $("#add_old_price").val();
	let price = $("#add_price").val();
	if (!submit_add || !category_title || !old_price || !price) {
		$("#alert-msg").show();
		$("#alert-content")
			.text("有未填寫的欄位")
			.removeClass()
			.addClass("error")
			.delay(2500);
		btn.disabled = false;
		setTimeout("location.reload()", 2000);
	} else {
		$.ajax({
			type: "POST",
			url: "./combowe/ajax_add_category", //ajax接收的server端
			data:
				"main_id=" +
				main_id +
				"&category_title=" +
				category_title +
				"&old_price=" +
				old_price +
				"&price=" +
				price +
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
					setTimeout("location.reload()", 500);
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
}

//預覽圖片
$("#file").change(function () {
	var file = $("#file")[0].files[0];
	var reader = new FileReader();
	reader.onload = function (e) {
		$(".table-img").attr("src", e.target.result);
	};
	reader.readAsDataURL(file);
});
