<!--- values -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-values relative -smt overflow-hidden' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">

		<div class="__glow __glow--blue -right-1/8 -bottom-1/4" aria-hidden="true"></div>
		<div class="__glow __glow--pink -top-50 -left-50" aria-hidden="true"></div>

		@if (!empty($g_values['header']) || !empty($g_values['text']))
		<div class="__top grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 items-start pb-10 border-b border-primary-100">
			@if (!empty($g_values['header']))
			<h2 data-gsap-element="header" class="">{{ $g_values['header'] }}</h2>
			@endif
			@if (!empty($g_values['text']))
			<div data-gsap-element="txt" class="__txt">
				{!! $g_values['text'] !!}
			</div>
			@endif
		</div>
		@endif

		@if (!empty($r_values))
		<div @class([
			'__cards grid gap-6 mt-10',
			'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4' => $normal,
			'grid-cols-1 sm:grid-cols-2' => !$normal,
			'lg:grid-cols-2' => !$normal && $columns == 2,
			'lg:grid-cols-3' => !$normal && $columns == 3,
		])>
			@foreach ($r_values as $item)
			<div @class([
				'__card bg-bright border border-primary-100 p-8 flex flex-col items-center text-center',
				'lg:flex-row lg:items-start lg:text-left' => $icon_left,
			]) data-gsap-element="card">

				@if (!empty($item['icon']['url']))
				<div @class([
					'__icon relative flex items-center justify-center w-20 h-20 mb-6',
					'lg:mb-0 lg:mr-6 lg:shrink-0' => $icon_left,
				])>
					<span class="__icon-bg absolute inset-0 bg-secondary rotate-45"></span>
					<img class="__icon-img relative w-11 h-11 object-contain" src="{{ $item['icon']['url'] }}" alt="{{ $item['icon']['alt'] ?? '' }}">
				</div>
				@endif

				<div class="__inside">
					@if (!empty($item['title']))
					<p class="text-h7">{{ $item['title'] }}</p>
					@endif

					@if (!empty($item['text']))
					<p class="__desc mt-2">{{ $item['text'] }}</p>
					@endif
				</div>

			</div>
			@endforeach
		</div>
		@endif

	</div>

</section>