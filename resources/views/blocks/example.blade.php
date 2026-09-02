<!--- example -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-example relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		@if (!empty($g_example['header']))
		<h3 data-gsap-element="header" class="">{{ $g_example['header'] }}</h3>
		@endif

		@if (!empty($g_example) || !empty($r_example))
		<div class="__grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">

			@if (!empty($g_example['logo']['url']) || !empty($g_example['text']))
			<div data-gsap-element="card" class="__item __item--logo bg-white border border-secondary-100 p-8 sm:col-span-2 flex flex-col justify-center">
				@if (!empty($g_example['logo']['url']))
				<img class="h-10 w-auto object-contain" src="{{ $g_example['logo']['url'] }}" alt="{{ $g_example['logo']['alt'] ?? '' }}">
				@endif
				@if (!empty($g_example['text']))
				<p class="mt-4">{{ $g_example['text'] }}</p>
				@endif
			</div>
			@endif

			@foreach (($r_example ?? []) as $item)
			<div data-gsap-element="card" @class([ '__item __item--stat p-8 flex flex-col justify-center text-white' , 'bg-secondary-hover'=> ($item['color'] ?? 'dark') === 'dark' , 'bg-secondary' => ($item['color'] ?? 'dark') === 'pink' , ])>
				@if (!empty($item['stat']))
				<p class="text-h2">{{ $item['stat'] }}</p>
				@endif
				@if (!empty($item['label']))
				<p class="mt-2">{{ $item['label'] }}</p>
				@endif
			</div>
			@endforeach
		</div>
		@endif
	</div>

</section>
