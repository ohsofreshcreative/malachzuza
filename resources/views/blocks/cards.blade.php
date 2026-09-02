<!--- cards --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-cards relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		<div class="__top">
			<h3 data-gsap-element="header" class="m-header !text-primary">{{ strip_tags($g_cards['header']) }}</h3>
			<p data-gsap-element="text">{{ $g_cards['text'] }}</p>
		</div>

		@if (!empty($r_cards))
		@php
		$itemCount = count($r_cards);
		$gridCols = 1;
		if ($itemCount == 2) $gridCols = 2;
		if ($itemCount == 3) $gridCols = 3;
		if ($itemCount >= 4) $gridCols = 4; // Twój dotychczasowy warunek
		$gridClass = $gridCols > 1 ? 'grid-cols-1 lg:grid-cols-' . $gridCols : 'grid-cols-1';
		@endphp

		<div class="grid {{ $gridClass }} gap-8 mt-8">
			@foreach ($r_cards as $item)

			<div data-gsap-element="card" class="h-full">
				<div class="__card relative h-full bg-white b-shadow radius p-6">
					@if (!empty($item['image']['url']))
					<img class="mb-4" src="{{ $item['image']['url'] }}" alt="{{ $item['image']['alt'] ?? '' }}" />
					@else
					<div class="text-h6 text-primary mb-2">
						{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
					</div>
					@endif

					@if (!empty($item['title']))
					<p class="text-h7 text-primary">{{ $item['title'] }}</p>
					@endif

					@if (!empty($item['text']))
					<p class="mt-2">{{ $item['text'] }}</p>
					@endif
				</div>
			</div>
			@endforeach
		</div>
		@endif

	</div>

</section>