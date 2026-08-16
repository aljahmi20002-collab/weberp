(function ($) {
	"user strict";
	$(window).on("load", function () {
		$(".loader").fadeOut(100);

		var current = location.pathname;
		var $path = current.substring(current.lastIndexOf("/") + 1);
		$(".nav-menu li a").each(function (e) {
			var $this = $(this);
			if ($path == $this.attr("href")) {
				$this.parent("li").addClass("active open");
				$this
					.parent("li")
					.parent("ul")
					.parent("li")
					.addClass("active open");
			} else if ($path == "") {
				$(".nav-menu li:first-child").addClass("active open");
			}
		});
	});
	$(document).ready(function () {
		$(".accordion-title").on("click", function (e) {
			var element = $(this).parent(".accordion-item");
			if (element.hasClass("open")) {
				element.removeClass("open");
				element.find(".accordion-content").removeClass("open");
				element.find(".accordion-content").slideUp(200, "swing");
			} else {
				element.addClass("open");
				element.children(".accordion-content").slideDown(200, "swing");
				element
					.siblings(".accordion-item")
					.children(".accordion-content")
					.slideUp(200, "swing");
				element.siblings(".accordion-item").removeClass("open");
				element
					.siblings(".accordion-item")
					.find(".accordion-title")
					.removeClass("open");
				element
					.siblings(".accordion-item")
					.find(".accordion-content")
					.slideUp(200, "swing");
			}
		});
		$(".nav-menu>li>ul").parent("li").addClass("parent-menu");
		$(".nav-menu li a").on("click", function (e) {
			var element = $(this).parent("li");
			if (element.hasClass("open")) {
				element.removeClass("open");
				element.find("li").removeClass("open");
				element.find("ul").slideUp(300, "swing");
			} else {
				element.addClass("open");
				element.children("ul").slideDown(300, "swing");
				element.siblings("li").children("ul").slideUp(300, "swing");
				element.siblings("li").removeClass("open");
				element.siblings("li").find("li").removeClass("open");
				element.siblings("li").find("ul").slideUp(300, "swing");
			}
		});

		const headerHeight = () => {
			var header = $("header").height();
			$(".header-top-wrapper").css("padding-top", () => header);
		};
		headerHeight();
		$(window).on("resize", headerHeight);
		$(window).on("scroll", headerHeight);

		var fixed_top = $("header");
		$(window).on("scroll", function () {
			if ($(this).scrollTop() > 60) {
				fixed_top.addClass("active");
			} else {
				fixed_top.removeClass("active");
			}
		});

		if ($(".wow").length) {
			var wow = new WOW({
				boxClass: "wow",
				animateClass: "animated",
				offset: 0,
				mobile: true,
				live: true,
			});
			wow.init();
		}

		var tooltipTriggerList = [].slice.call(
			document.querySelectorAll('[data-bs-toggle="tooltip"]')
		);
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
			return new bootstrap.Tooltip(tooltipTriggerEl);
		});

		$(".nav-toggle").on("click", function () {
			$(this).toggleClass("active");
			$(".overlayer").toggleClass("active");
			$(".user-panel-sidebar").toggleClass("active");
			$("header").toggleClass("fixed-width");
		});
		$(".cog-btn").on("click", function () {
			$(".theme-settings, .overlayer-2").addClass("active");
		});
		$(".overlayer, .sidebar-close, .overlayer-2, .close-theme-setttings").on(
			"click",
			function () {
				$(
					".user-panel-sidebar, .nav-toggle, .overlayer, .theme-settings, .overlayer-2"
				).removeClass("active");
				$("header").removeClass("fixed-width");
			}
		);

		$(".header-right-icons .user > a, .header-right-icons .language > a").on(
			"click",
			function () {
				if ($(this).hasClass("active")) {
					$(this).removeClass("active");
				} else {
					$(this).addClass("active");
				}
			}
		);

		// $(".toggle-mode, .toggle-mode-btn").on("click", function () {
		// 	$(this).parent().siblings().removeClass("active");
		// 	$(this).parent().addClass("active");
		// 	if ($("html").hasClass("check-theme")) {
		// 		localStorage.setItem("color-theme", true);
		// 		localStorage.setItem("default", false);
		// 		$(".toggle-mode").html('<i class="fas fa-sun"></i>');
		// 	} else {
		// 		localStorage.removeItem("color-theme");
		// 		$(".toggle-mode").html('<i class="fas fa-moon"></i>');
		// 	}
		// 	setVersion();
		// });

		// setVersion();

		// function setVersion() {
		// 	if (localStorage.getItem("color-theme")) {
		// 		$("html").removeClass("check-theme");
		// 		$("html").removeClass("dark-theme");
		// 		$(".toggle-mode").html('<i class="fas fa-moon"></i>');
		// 	} else {
		// 		$("html").addClass("check-theme");
		// 		$("html").addClass("dark-theme");
		// 		$(".toggle-mode").html('<i class="fas fa-sun"></i>');
		// 	}
		// }

		// Theme Toggle Color
		$("#set-primary-clr span").each(function () {
			$(this).on("click", function () {
				var dataColor = $(this).attr("data-color");
				$(this).siblings().removeClass("active");
				$(this).addClass("active");
				localStorage.setItem("base-clr", dataColor);
				setColor();
			});
		});
		setColor();
		function setColor() {
			if (localStorage.getItem("base-clr")) {
				$("body").attr(
					"style",
					`--base-clr: ${localStorage.getItem("base-clr")}`
				);
			}
		}

		// Sidebar Toggle Color
		$("#sidebar-color-mode span").each(function () {
			$(this).on("click", function () {
				var dataColor = $(this).attr("data-color");
				$(this).siblings().removeClass("active");
				$(this).addClass("active");
				localStorage.setItem("sidebar-clr", dataColor);
				setSidebarColor();
			});
		});
		setSidebarColor();
		function setSidebarColor() {
			if (localStorage.getItem("sidebar-clr")) {
				$(".user-panel-sidebar").attr(
					"style",
					`background-color: ${localStorage.getItem("sidebar-clr")}`
				);
			}
		}

		//reset color
		$(".reset-button").on("click", function () {
			if (localStorage.getItem("base-clr")) {
				localStorage.removeItem("base-clr");
				setColor();
			}
			if (localStorage.getItem("sidebar-clr")) {
				localStorage.removeItem("sidebar-clr");
				setSidebarColor();
			}
			if (localStorage.getItem("nav-theme")) {
				localStorage.removeItem("nav-theme");
				setNavbarColor();
			}
			location.reload();
		});

		$(".search-form")
			.find("input")
			.on("focus", function () {
				$(this).closest(".search-form").addClass("active");
			});
		$(".search-form")
			.find("input")
			.on("focusout", function () {
				$(this).closest(".search-form").removeClass("active");
			});
	});
})(jQuery);
