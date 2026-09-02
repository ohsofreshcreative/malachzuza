<!--- cta -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-cta relative -smt overflow-hidden' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	@if (!empty($g_octa['image']))
	<x-picture
		:image="$g_octa['image']"
		figureClass="__bg absolute inset-0 z-0"
		class="w-full h-full object-cover" />
	@endif

	<div class="__overlay absolute inset-0 z-1 pointer-events-none bg-linear-to-r from-black to-transparent"></div>

	<div class="__wrapper c-main relative z-10 grid grid-cols-1 lg:grid-cols-2 items-center gap-12 lg:gap-20 py-30">
		<div class="__content w-full">

			@if (!empty($g_octa['header']))
			<h2 data-gsap-element="header" class="text-white">{{ $g_octa['header'] }}</h2>
			@endif

			@if (!empty($g_octa['txt']))
			<div data-gsap-element="txt" class="__txt  m-header text-white">{!! $g_octa['txt'] !!}</div>
			@endif

			@if (!$form && (!empty($g_octa['button1']) || !empty($g_octa['button2'])))
			<div class="inline-buttons m-btn">
				@if (!empty($g_octa['button1']))
				<x-button
					:href="$g_octa['button1']['url']"
					variant="primary"
					data-gsap-element="btn">
					{{ $g_octa['button1']['title'] }}
				</x-button>
				@endif

				@if (!empty($g_octa['button2']))
				<x-button
					:href="$g_octa['button2']['url']"
					variant="white"
					data-gsap-element="btn">
					{{ $g_octa['button2']['title'] }}
				</x-button>
				@endif
			</div>
			@endif
		</div>

		@if ($form && !empty($g_octa['shortcode']))
		<div data-gsap-element="form" class="__form bg-white">
			@if (!empty($g_octa['title']))
			<h5 class="__form-title m-header">{{ $g_octa['title'] }}</h5>
			@endif
			{!! do_shortcode($g_octa['shortcode']) !!}
		</div>
		@endif
	</div>

</section>