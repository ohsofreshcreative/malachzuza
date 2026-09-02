<!--- categories -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-categories relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		@if (!empty($g_categories['header']))
		<h2 data-gsap-element="header" class="m-header">
			{{ $g_categories['header'] }}
		</h2>
		@endif

		@if (!empty($items))
		<div class="__grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-10">
			@foreach ($items as $item)
			<div data-gsap-element="card">
				<div class="__card flex flex-col justify-between radius-lg bg-white b-shadow p-6">
					<x-picture
						:image="$item['image']"
						figureClass="__img"
						class="w-full img-xs radius-img object-cover" />

					@if (!empty($item['title']))
					<p class="__title text-h7 mt-6">{{ $item['title'] }}</p>
					@endif

					<x-button :href="$item['url']" variant="underline" class="mt-6">Sprawdź</x-button>
				</div>
			</div>
			@endforeach
		</div>
		@endif
	</div>
</section>