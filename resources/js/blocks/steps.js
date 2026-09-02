import Swiper from 'swiper';

import 'swiper/css';

const initSteps = () => {
	const sliders = document.querySelectorAll(
		'.steps-swiper:not(.swiper-initialized)'
	);
	if (!sliders.length) return;

	sliders.forEach((el) => {
		const section = el.closest('section');

		if (!section) return;

		const slides = [...el.querySelectorAll('.step-slide')];
		const wrapper = el.querySelector('.swiper-wrapper');
		const prevButton = section.querySelector('.__prev');
		const nextButton = section.querySelector('.__next');
		let activeIndex = 0;
		let swiper;

		el.style.setProperty('--steps-inactive-count', slides.length - 1);

		const updateSliderHeight = () => {
			let maxHeight = 0;

			el.classList.add('is-measuring');
			wrapper.style.height = 'auto';

			slides.forEach((slide) => {
				slide.classList.add('is-active', 'is-content-visible');
				maxHeight = Math.max(maxHeight, slide.scrollHeight);
				slide.classList.remove('is-active', 'is-content-visible');
			});

			slides[activeIndex].classList.add('is-active', 'is-content-visible');
			wrapper.style.height = `${maxHeight}px`;
			el.classList.remove('is-measuring');
		};

		const updateNavigation = () => {
			prevButton.disabled = activeIndex === 0;
			nextButton.disabled = activeIndex === slides.length - 1;
		};

		const setActiveStep = (index, moveSlider = true, immediate = false) => {
			const nextIndex = Math.max(0, Math.min(index, slides.length - 1));
			const isMobile = window.matchMedia('(max-width: 767px)').matches;

			if (nextIndex === activeIndex && !immediate) return;

			activeIndex = nextIndex;

			slides.forEach((slide, slideIndex) => {
				slide.classList.toggle('is-active', slideIndex === activeIndex);
				slide.classList.toggle(
					'is-content-visible',
					(immediate || isMobile) && slideIndex === activeIndex
				);
			});

			updateNavigation();

			requestAnimationFrame(() => {
				swiper.update();

				if (moveSlider) swiper.slideTo(activeIndex);
			});
		};

		swiper = new Swiper(el, {
			initialSlide: 0,
			slidesPerView: 'auto',
			allowTouchMove: false,
			watchOverflow: false,
			observer: true,
			observeParents: true,
			speed: 500,
			on: {
				slideChange(swiperInstance) {
					setActiveStep(swiperInstance.activeIndex, false);
				},
			},
		});

		slides.forEach((slide, index) => {
			slide.addEventListener('click', () => setActiveStep(index));
			slide.addEventListener('transitionend', (event) => {
				if (event.propertyName !== 'width') return;

				if (slide.classList.contains('is-active')) {
					slide.classList.add('is-content-visible');
				}

				swiper.update();
				swiper.slideTo(activeIndex, 0);
			});
		});

		prevButton.addEventListener('click', () => setActiveStep(activeIndex - 1));
		nextButton.addEventListener('click', () => setActiveStep(activeIndex + 1));

		setActiveStep(0, false, true);
		updateSliderHeight();
		window.addEventListener('resize', updateSliderHeight);
	});
};

initSteps();
