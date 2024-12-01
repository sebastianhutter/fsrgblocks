// ugly hack to intiialize a swiper object with or without autoplay
// couldnt really figure out how to do this in a more elegant way

if (autoPlayDelayInMs === 0) {
	const swiper = new Swiper('.swiper', {
		// Optional parameters
		direction: 'horizontal',
		loop: true,
		slidesPerView: "auto",
		centeredSlides: true,
		spaceBetween: 10,
		// If we need pagination
		pagination: {
			el: ".swiper-pagination",
			clickable: true,
		},

		mousewheel: false,
		keyboard: false,
	});
} else {
	const swiper = new Swiper('.swiper', {
		// Optional parameters
		direction: 'horizontal',
		loop: true,
		slidesPerView: "auto",
		centeredSlides: true,
		spaceBetween: 10,
		// If we need pagination
		pagination: {
			el: ".swiper-pagination",
			clickable: true,
		},
		autoplay: {
			delay: autoPlayDelayInMs,
		},
		mousewheel: false,
		keyboard: false,
	});
}
