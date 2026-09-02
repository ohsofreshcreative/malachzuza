<!--- steps -->

@php
$stepShades = $background === 'section-secondary'
	? ['#760040', '#B00060', '#C4006A', '#EB007F', '#F62093']
	: ['bg-primary-dark', 'bg-primary-900', 'bg-primary-800', 'bg-primary-700', 'bg-primary-600', 'bg-primary-500'];
$stepNavColor = $background === 'section-secondary' ? 'bg-secondary-900' : 'bg-primary-900';
@endphp

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-steps relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">

		@if (!empty($g_steps['header']) || !empty($g_steps['text']))
		<div class="__top grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 items-start pb-10 border-b border-primary-100">
			@if (!empty($g_steps['header']))
			<h4 data-gsap-element="header" class="">{{ $g_steps['header'] }}</h4>
			@endif
			@if (!empty($g_steps['text']))
			<div data-gsap-element="txt" class="__txt">
				{!! $g_steps['text'] !!}
			</div>
			@endif
		</div>
		@endif

		@if (!empty($r_steps))
		<div class="steps-swiper swiper relative  overflow-hidden mt-10">
			<div class="swiper-wrapper">
				@foreach ($r_steps as $item)
				@php
				$stepColor = $stepShades[$loop->index % count($stepShades)];
				$isCustomStepColor = str_starts_with($stepColor, '#');
				@endphp
				<div
					@class(['swiper-slide step-slide', $stepColor => !$isCustomStepColor])
					@if ($isCustomStepColor) style="background-color: {{ $stepColor }};" @endif>
					<div class="__step-inner h-full flex flex-col py-8 px-6 md:px-10">
						<span class="__number shrink-0 w-14 h-14 border border-white/50 flex items-center justify-center text-white text-h6">{{ $item['number'] }}</span>

						<div class="__content mt-6">
							@if (!empty($item['title']))
							<p class="__title text-primary-300 text-h6 mb-3">{{ $item['title'] }}</p>
							@endif
							@if (!empty($item['text']))
							<div class="__txt text-white">{!! $item['text'] !!}</div>
							@endif
						</div>
					</div>
				</div>
				@endforeach
			</div>
		</div>

		<div class="__nav flex gap-3 mt-6">
			<button type="button" class="__prev {{ $stepNavColor }} h-12 w-12 flex items-center justify-center cursor-pointer transition-all duration-300 shrink-0" aria-label="Poprzedni krok">
				<svg xmlns="http://www.w3.org/2000/svg" width="13" height="12" viewBox="0 0 13 12" fill="none">
					<path d="M0.270429 5.31498C0.270706 5.31469 0.270937 5.31435 0.27126 5.31406L5.08882 0.281803C5.44973 -0.0951806 6.03348 -0.0937777 6.39273 0.285093C6.75194 0.663916 6.75055 1.27664 6.38964 1.65367L3.15514 5.03226L12.078 5.03226C12.5872 5.03226 13 5.46552 13 6C13 6.53448 12.5872 6.96774 12.078 6.96774L3.15518 6.96774L6.3896 10.3463C6.75051 10.7234 6.75189 11.3361 6.39269 11.7149C6.03344 12.0938 5.44963 12.0951 5.08877 11.7182L0.271213 6.68594C0.270936 6.68565 0.270706 6.68531 0.270383 6.68502C-0.0907122 6.30673 -0.08956 5.69202 0.270429 5.31498Z" fill="#FFF" />
				</svg>
			</button>
			<button type="button" class="__next {{ $stepNavColor }} h-12 w-12 flex items-center justify-center cursor-pointer transition-all duration-300 shrink-0" aria-label="Następny krok">
				<svg xmlns="http://www.w3.org/2000/svg" width="13" height="12" viewBox="0 0 13 12" fill="none">
					<path d="M12.7296 5.31498C12.7293 5.31469 12.7291 5.31435 12.7287 5.31406L7.91118 0.281803C7.55027 -0.0951806 6.96652 -0.0937777 6.60727 0.285093C6.24806 0.663916 6.24945 1.27664 6.61036 1.65367L9.84486 5.03226L0.921985 5.03226C0.412773 5.03226 0 5.46552 0 6C0 6.53448 0.412773 6.96774 0.921985 6.96774L9.84482 6.96774L6.6104 10.3463C6.24949 10.7234 6.24811 11.3361 6.60731 11.7149C6.96657 12.0938 7.55037 12.0951 7.91123 11.7182L12.7288 6.68594C12.7291 6.68565 12.7293 6.68531 12.7296 6.68502C13.0907 6.30673 13.0896 5.69202 12.7296 5.31498Z" fill="#FFF" />
				</svg>
			</button>
		</div>
		@endif

		@if (!empty($g_steps['summary']))
		<div class="__summary bg-bright border border-primary-100  p-8 mt-8">
			<p class="mb-0!">{{ $g_steps['summary'] }}</p>
		</div>
		@endif

	</div>

</section>
