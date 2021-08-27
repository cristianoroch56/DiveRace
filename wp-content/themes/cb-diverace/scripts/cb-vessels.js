(function($) {
	$('.cabin-hotspots a').hover(function() {
		var popup = $(this);
		var href = $(this).attr('href');

		popup.magnificPopup({
			type: 'inline',
			preloader: false,
			callbacks: {
				open: function() {
					var strip = href.replace('#', '');
					const slider = $('.gallery-' + strip);

					slider.slick({
						dots: false,
						arrows: true,
						infinite: true,
						speed: 1000,
						fade: true,
						cssEase: 'linear',
					});
				},
				close: function() {
					var strip = href.replace('#', '');
					const slider = $('.gallery-' + strip);
					slider.slick('unslick');
				},
			},
		});
	});
})(jQuery);
