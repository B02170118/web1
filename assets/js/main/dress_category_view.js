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
			.text("參數錯誤變更失敗")
			.removeClass()
			.addClass("error")
			.delay(2500);
		setTimeout("location.reload()", 500);
	} else {
		$.ajax({
			type: "POST",
			url: "./dress/ajax_change_status_img", //ajax接收的server端
			data:
				"status=" +
				status +
				"&id=" +
				id +
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

//預覽圖片
$("#file").change(function () {
	var file = $("#file")[0].files[0];
	var reader = new FileReader();
	reader.onload = function (e) {
		$(".table-img").attr("src", e.target.result);
	};
	reader.readAsDataURL(file);
});

//順序按鈕
function sort() {
	$("#sort_btn").hide();
	$("#upload_btn").hide();
	$("#img_del").hide();
	$("#submit_sort_btn").show();
	$("#cancel").show();
	$("#sortable").sortable();
	$("#sortable").disableSelection();
	$("#sortable li a").css("pointer-events", "none");
}
//送出修改順序
function save_sort(btn) {
	btn.disabled = true;
	let sort = [];
	$("#sortable li").each(function () {
		sort.push($(this).attr("data-id"));
	});
	$.ajax({
		url: "dress/ajax_edit_img_seq",
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

//刪除相片
$("#img_del").click(function () {
	$(this).hide();
	$("#upload_btn").hide();
	$("#sort_btn").hide();
	$("#submit_del").show();
	$("#cancel").show();
	$("#sortable li").addClass("remove");
	$("#sortable li a").css("pointer-events", "none");
	$("#sortable li").click(function () {
		$(this).toggleClass("on");
		$(this)
			.children(".del_img")
			.each(function () {
				this.checked = !this.checked;
			});
	});
});
//送出刪除
function submit_del(btn) {
	if (confirm("確定刪除?")) {
		btn.disabled = true;
		let id = [];
		$("input[name='del_img']:checked").each(function (i) {
			id[i] = $(this).val();
		});
		if (id.length > 0) {
			$.ajax({
				type: "POST",
				url: "./dress/ajax_del_img", //ajax接收的server端
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
				.text("沒有選取圖片")
				.removeClass()
				.addClass("error")
				.delay(2500);
			setTimeout("location.reload()", 500);
		}
	}
}
