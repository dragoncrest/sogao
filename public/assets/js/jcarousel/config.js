(function($) {
    $(function() {
		/*
		Carousel initialization
		*/
		$('.jcarousel')
			.jcarousel({
				center: true,
				wrap: 'circular',
				scroll: 1,
				animation: {
					duration: 1000,
					easing:   'swing',
					complete: function() {
					}
				}
			})
			.jcarouselAutoscroll({
				interval: 3000,
				target: '+=1',
				autostart: true
			})
		;
		/*
		 Prev control initialization
		 */
		$('.jcarousel-control-prev')
			.on('jcarouselcontrol:active', function() {
				$(this).removeClass('inactive');
			})
			.on('jcarouselcontrol:inactive', function() {
				$(this).addClass('inactive');
			})
			.jcarouselControl({
				// Options go here
				target: '-=1'
			});

		/*
		 Next control initialization
		 */
		$('.jcarousel-control-next')
			.on('jcarouselcontrol:active', function() {
				$(this).removeClass('inactive');
			})
			.on('jcarouselcontrol:inactive', function() {
				$(this).addClass('inactive');
			})
			.jcarouselControl({
				// Options go here
				target: '+=1'
			});
			
		$('.jcarousel-pagination')
            .on('jcarouselpagination:active', 'a', function() {
                $(this).removeClass('slide-inactive');
                $(this).addClass('slide-active');
            })
            .on('jcarouselpagination:inactive', 'a', function() {
                $(this).removeClass('slide-active');
                $(this).addClass('slide-inactive');
            })
            .jcarouselPagination({
				'carousel': $('.jcarousel'),
				'perPage': 1,
				'item': function(page, carouselItems) {
					return '<a class="slide-inactive" href="#' + page + '"></a>';
				}
			});	
    });
})(jQuery);