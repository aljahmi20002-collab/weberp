!(function (t) {
	"use strict";
	function e() {}
	(e.prototype.init = function () {
		t("#sweet-basic").on("click", function () {
			Swal.fire({
				title: "Any fool can use a computer",
				confirmButtonColor: "#7367f0",
			});
		}),
			t("#sweet-title").click(function () {
				Swal.fire({
					title: "The Internet?",
					text: "That thing is still around?",
					icon: "question",
					confirmButtonColor: "#7367f0",
				});
			}),
			t("#sweet-success").click(function () {
				Swal.fire({
					title: "Good job!",
					text: "You clicked the button!",
					icon: "success",
					showCancelButton: !0,
					confirmButtonColor: "#7367f0",
					cancelButtonColor: "#dc3545",
				});
			}),
			t("#sweet-warning").click(function () {
				Swal.fire({
					title: "Are you sure?",
					text: "You won't be able to revert this!",
					icon: "warning",
					showCancelButton: !0,
					confirmButtonColor: "#28a745",
					cancelButtonColor: "#dc3545",
					confirmButtonText: "Yes, delete it!",
				}).then(function (t) {
					t.value &&
						Swal.fire(
							"Deleted!",
							"Your file has been deleted.",
							"success"
						);
				});
			}),
			t("#sweet-params").click(function () {
				Swal.fire({
					title: "Are you sure?",
					text: "You won't be able to revert this!",
					icon: "warning",
					showCancelButton: !0,
					confirmButtonText: "Yes, delete it!",
					cancelButtonText: "No, cancel!",
					confirmButtonClass: "btn btn-success mt-2",
					cancelButtonClass: "btn btn-danger ms-2 mt-2",
					buttonsStyling: !1,
				}).then(function (t) {
					t.value
						? Swal.fire({
								title: "Deleted!",
								text: "Your file has been deleted.",
								icon: "success",
						  })
						: t.dismiss === Swal.DismissReason.cancel &&
						  Swal.fire({
								title: "Cancelled",
								text: "Your imaginary file is safe :)",
								icon: "error",
						  });
				});
			}),
			t("#sweet-image").click(function () {
				Swal.fire({
					title: "Sweet!",
					text: "Modal with a custom image.",
					imageUrl: "assets/images/logo-dark.png",
					imageHeight: 20,
					confirmButtonColor: "#7367f0",
					animation: !1,
				});
			}),
			t("#sweet-close").click(function () {
				var t;
				Swal.fire({
					title: "Auto close alert!",
					html: "I will close in <strong></strong> seconds.",
					timer: 2e3,
					confirmButtonColor: "#7367f0",
					onBeforeOpen: function () {
						Swal.showLoading(),
							(t = setInterval(function () {
								Swal.getContent().querySelector("strong").textContent =
									Swal.getTimerLeft();
							}, 100));
					},
					onClose: function () {
						clearInterval(t);
					},
				}).then(function (t) {
					t.dismiss === Swal.DismissReason.timer &&
						console.log("I was closed by the timer");
				});
			}),
			t("#custom-html-alert").click(function () {
				Swal.fire({
					title: "<i>HTML</i> <u>example</u>",
					icon: "info",
					html: 'You can use <b>bold text</b>, <a href="//Themesdesign.in/">links</a> and other HTML tags',
					showCloseButton: !0,
					showCancelButton: !0,
					confirmButtonClass: "btn btn-success",
					cancelButtonClass: "btn btn-danger ml-1",
					confirmButtonColor: "#47bd9a",
					cancelButtonColor: "#dc3545",
					confirmButtonText:
						'<i class="fas fa-thumbs-up me-1"></i> Great!',
					cancelButtonText: '<i class="fas fa-thumbs-down"></i>',
				});
			}),
			t("#sweet-position").click(function () {
				Swal.fire({
					position: "top-end",
					icon: "success",
					title: "Your work has been saved",
					showConfirmButton: !1,
					timer: 1500,
				});
			}),
			t("#custom-padding-width-alert").click(function () {
				Swal.fire({
					title: "Custom width, padding, background.",
					width: 600,
					padding: 100,
					confirmButtonColor: "#7367f0",
					background:
						"#fff url(//subtlepatterns2015.subtlepatterns.netdna-cdn.com/patterns/geometry.png)",
				});
			}),
			t("#chaining-alert").click(function () {
				Swal.mixin({
					input: "text",
					confirmButtonText: "Next &rarr;",
					showCancelButton: !0,
					confirmButtonColor: "#7367f0",
					cancelButtonColor: "#74788d",
					progressSteps: ["1", "2", "3"],
				})
					.queue([
						{
							title: "Question 1",
							text: "Chaining swal2 modals is easy",
						},
						"Question 2",
						"Question 3",
					])
					.then(function (t) {
						t.value &&
							Swal.fire({
								title: "All done!",
								html:
									"Your answers: <pre><code>" +
									JSON.stringify(t.value) +
									"</code></pre>",
								confirmButtonText: "Lovely!",
								confirmButtonColor: "#7367f0",
							});
					});
			}),
			t("#dynamic-alert").click(function () {
				swal
					.queue([
						{
							title: "Your public IP",
							confirmButtonColor: "#7367f0",
							confirmButtonText: "Show my public IP",
							text: "Your public IP will be received via AJAX request",
							showLoaderOnConfirm: !0,
							preConfirm: function () {
								return new Promise(function (e) {
									t.get("https://api.ipify.org?format=json").done(
										function (t) {
											swal.insertQueueStep(t.ip), e();
										}
									);
								});
							},
						},
					])
					.catch(swal.noop);
			});
	}),
		(t.SweetAlert = new e()),
		(t.SweetAlert.Constructor = e);
})(window.jQuery),
	(function () {
		"use strict";
		window.jQuery.SweetAlert.init();
	})();
