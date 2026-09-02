<!--- hero -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-hero relative bg-bright overflow-hidden' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__glow __glow--blue -top-50 -left-100" aria-hidden="true"></div>
	<div class="__glow __glow--pink left-1/2 -translate-x-1/2 -bottom-1/4" aria-hidden="true"></div>

	<div class="__stripes-wrap opacity-10 lg:opacity-100" aria-hidden="true">
		<div class="__stripes-spacer"></div>
		<div class="__stripes"></div>
	</div>

	<div class="__wrapper c-main relative h-full flex items-center py-40">

		<div class="__col grid grid-cols-1 lg:grid-cols-2 items-center gap-8 lg:gap-20 relative z-10">
			@if (!empty($g_hero['image']))
			<x-picture
				data-gsap-element="img"
				:image="$g_hero['image']"
				figureClass="__img h-full order-2"
				class="w-full object-contain" />
			@endif

			<div class="__content order-1">
				<h1 data-gsap-element="header" class="text-h3 text-primary-dark">{{ $g_hero['header'] }}</h1>

				<div data-gsap-element="txt" class="__txt m-header">
					{!! $g_hero['text'] !!}
				</div>

				@if (!empty($g_hero['button1']) || !empty($g_hero['button2']))
				<div class="inline-buttons m-btn">
					@if (!empty($g_hero['button1']))
					<x-button
						:href="$g_hero['button1']['url']"
						variant="primary"
						class=""
						data-gsap-element="btn">
						{{ $g_hero['button1']['title'] }}
					</x-button>
					@endif

					@if (!empty($g_hero['button2']))
					<x-button
						:href="$g_hero['button2']['url']"
						variant="white"
						class=""
						data-gsap-element="btn">
						{{ $g_hero['button2']['title'] }}
					</x-button>
					@endif
				</div>
				@endif

			</div>

		</div>
	</div>

</section>