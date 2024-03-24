$(document).ready(function() {
	$(window).scroll(function() {
		var scrollTop = $(window).scrollTop();
		$('.parallax-image').css({
			'transform': 'translate3d(0,' + scrollTop * 0.3 + 'px, 0)'
		})
	})
});