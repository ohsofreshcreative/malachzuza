<!--- sectors -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-sectors relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">

		@if (!empty($sector_items))
		<div class="flex flex-col gap-20">
			@foreach ($sector_items as $item)
			<div data-gsap-element="item" class="__col grid grid-cols-1 lg:grid-cols-2 items-center gap-8 lg:gap-20">

				<div class="__content w-full lg:w-5/6 z-30 order-2 {{ $loop->even ? 'lg:order-2 ml-auto' : 'lg:order-1 mr-auto' }}">
					<h2 data-gsap-element="header" class="text-h4 m-header">{{ $item['title'] }}</h2>

					@if (!empty($item['excerpt']))
					<div data-gsap-element="txt" class="__txt">
						<p>{{ $item['excerpt'] }}</p>
					</div>
					@endif

					@if (!empty($item['tags']))
					<div class="__tags flex flex-wrap gap-2 m-btn">
						@foreach ($item['tags'] as $tag)
						<span class="bg-primary-100/20 text-[14px] px-4 py-2">{{ $tag }}</span>
						@endforeach
					</div>
					@endif

					<div class="m-btn">
						<x-button :href="$item['url']" variant="primary" data-gsap-element="btn">Sprawdź szczegóły</x-button>
					</div>
				</div>

				@if (!empty($item['image_url']))
				<div class="__img-wrap relative order-1 {{ $loop->even ? 'lg:order-1' : 'lg:order-2' }}">
					<figure data-gsap-element="img" class="__img relative z-10">
						<picture>
							<img class="w-full h-full max-h-[504px] object-cover" src="{{ $item['image_url'] }}" alt="{{ $item['image_alt'] }}">
						</picture>
					</figure>
					<span class="__frame absolute top-1/2 -translate-y-1/2 {{ $loop->even ? '-right-16 lg:-right-16' : '-left-16 lg:-left-16' }} w-32 h-32 lg:w-32 lg:h-32 border-8 border-secondary outline-30 outline-background bg-background hidden lg:block z-20" aria-hidden="true"></span>
				</div>
				@endif

			</div>
			@endforeach
		</div>
		@endif
	</div>

</section>