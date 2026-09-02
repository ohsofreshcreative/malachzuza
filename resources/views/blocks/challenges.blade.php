<!--- challenges -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-challenges relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		@if (!empty($g_challenges['header']))
		<h2 data-gsap-element="header" class="">{{ $g_challenges['header'] }}</h2>
		@endif

		<div class="__grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-10">

			@if (!empty($g_challenges['image']['url']))
			<div data-gsap-element="img" class="__item __item--image relative overflow-hidden sm:col-span-2">
				<img class="absolute inset-0 h-full w-full object-cover" src="{{ $g_challenges['image']['url'] }}" alt="{{ $g_challenges['image']['alt'] ?? '' }}">
			</div>
			@endif

			@foreach (($r_challenges ?? []) as $item)
			<div data-gsap-element="card" class="__item __item--card bg-white p-8 flex flex-col justify-center">
				@if (!empty($item['icon']['url']))
				<div class="__icon h-16 w-16">
					<img class="h-full w-full object-contain" src="{{ $item['icon']['url'] }}" alt="{{ $item['icon']['alt'] ?? '' }}">
				</div>
				@endif
				@if (!empty($item['title']))
				<h3 class="mt-4 text-h7">{{ $item['title'] }}</h3>
				@endif
			</div>
			@endforeach
		</div>
	</div>
</section>
