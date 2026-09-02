<!--- featured -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-featured relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		@if (!empty($g_featured['header']))
		<h2 data-gsap-element="header" class="m-header text-h3 text-primary">{{ strip_tags($g_featured['header']) }}</h2>
		@endif

		@if (!empty($items))
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mt-10">
			@foreach ($items as $item)
			<div data-gsap-element="card" class="h-full">
				<a href="{{ $item['url'] }}" class="__card group relative flex flex-col h-full overflow-hidden radius bg-white b-shadow gap-4 p-8">
					<div class="__img flex items-center justify-center aspect-square">
						@if (!empty($item['image']['ID']) || !empty($item['image']['url']))
						<x-picture :image="$item['image']" class="w-full h-full object-contain" figureClass="w-full h-full" />
						@endif
					</div>
					<div class="__content flex flex-col flex-1">
						<div class="__title line-clamp-2 min-h-12">{{ $item['title'] }}</div>
						@if (!empty($item['price_html']))
						<div class="price">{!! $item['price_html'] !!}</div>
						@endif
					</div>
						<p class="btn btn-underline group-hover:text-secondary!">
							Zobacz
						</p>
				</a>
			</div>
			@endforeach
		</div>
		@endif

		@if (!empty($button['url']))
		<div class="__bottom text-center mt-10">
			<x-button :href="$button['url']" variant="primary" data-gsap-element="btn">
				{{ $button['title'] ?: 'Zobacz wszystkie produkty' }}
			</x-button>
		</div>
		@endif
	</div>

</section>