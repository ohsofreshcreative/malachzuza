<!--- how --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-how relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		@if (!empty($g_how['header']))
		<h2 data-gsap-element="header" class="m-header">
			{{ $g_how['header'] }}
		</h2>
		@endif

		@if (!empty($r_how))
		<div class="__grid mt-10 grid grid-cols-1 gap-6 xl:grid-cols-4">
			@foreach (($r_how ?? []) as $item)
			@php
				$isImageCard = !empty($item['image_card']);
				$spanClass = 'xl:col-span-1';
				if ($loop->iteration === 3 || $loop->iteration === 4) {
					$spanClass = 'xl:col-span-2';
				}
			@endphp

			@if ($isImageCard)
				<div data-gsap-element="card" class="__item __item--image relative overflow-hidden bg-white {{ $spanClass }}">
					@if (!empty($item['image']['url']))
					<img class="w-full img-md object-cover" src="{{ $item['image']['url'] }}" alt="{{ $item['image']['alt'] ?? '' }}">
					@endif
				</div>
			@else
				<div data-gsap-element="card" class="__item __item--card flex flex-col justify-between border border-primary-100 bg-white p-6 xl:p-8 {{ $spanClass }}">
					@if (!empty($item['icon']['url']))
					<div class="__icon flex h-20 w-20 items-center justify-center">
						<img class="object-contain" src="{{ $item['icon']['url'] }}" alt="{{ $item['icon']['alt'] ?? '' }}">
					</div>
					@endif

					@if (!empty($item['title']))
					<h3 class="mt-6 text-h7 text-primary-dark">{{ $item['title'] }}</h3>
					@endif

					@if (!empty($item['button']['url']))
					<div class="mt-6">
						<x-button :href="$item['button']['url']" variant="underline" class="!inline-flex !items-center !gap-2">
							{{ $item['button']['title'] ?: 'Sprawdź szczegóły' }}
						</x-button>
					</div>
					@endif
				</div>
			@endif
			@endforeach
		</div>
		@endif
	</div>
</section>
