<!--- proces --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-proces relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		@if (!empty($g_proces['header']))
		<h3 data-gsap-element="header" class="">{{ $g_proces['header'] }}</h3>
		@endif

		<div class="__col grid grid-cols-1 lg:grid-cols-2 items-stretch gap-8 lg:gap-16 mt-10">

			@if (!empty($r_proces))
			<div class="__repeater grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">
				@foreach ($r_proces as $item)
				<div data-gsap-element="stagger" class="__item bg-white p-6">
					@if (!empty($item['number']))
					<div class="text-h4 !text-primary">{{ $item['number'] }}</div>
					@endif

					@if (!empty($item['title']))
					<p class="text-h6 !text-body mt-4">{{ $item['title'] }}</p>
					@endif

					@if (!empty($item['txt']))
					<div class="[&_p]:text-body! mt-2">{!! $item['txt'] !!}</div>
					@endif
				</div>
				@endforeach
			</div>
			@endif

			@if (!empty($g_proces['image']))
			<div class="__img-wrap relative">
				
				<figure data-gsap-element="img" class="__img relative z-10 h-full">
					<picture>
						<img class="__img--stroke w-full h-full aspect-square object-cover" src="{{ $g_proces['image']['url'] }}" alt="{{ $g_proces['image']['alt'] ?? '' }}">
					</picture>
				</figure>
			</div>
			@endif

		</div>
	</div>

</section>