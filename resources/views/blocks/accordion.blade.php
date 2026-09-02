<!-- accordion -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-accordion relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="c-main">
		<div class="__wrapper">
			<h3 data-gsap-element="header" class="m-header text-primary">{{ $g_accordion['title'] }}</h3>
			<div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-8 mt-10">
				<div class="__col h-full">
					@if (!empty($g_accordion['image']))
					<figure data-gsap-element="img" class="__img order1 h-full">
						<picture class="block h-full">
							<img class="object-cover h-full w-full max-h-[504px]" src="{{ $g_accordion['image']['url'] }}" alt="{{ $g_accordion['image']['alt'] ?? '' }}">
						</picture>
					</figure>
					@endif

					@if (!empty($g_accordion['button']))
					<a class="main-btn m-btn" href="{{ $g_accordion['button']['url'] }}">{{ $g_accordion['button']['title'] }}</a>
					@endif
				</div>

				<div class="__content order2">
					<div data-gsap-element="accordion" class="accordion-wrapper grid">
						@foreach ($r_accordion as $item)
						<div class="accordion bg-white border border-secondary h-max">
							<input class="acc-check" type="radio" name="accordion-radio" id="check{{ $loop->index }}" {{ $loop->first ? 'checked' : '' }}>
							<label class="accordion-label flex items-center justify-between !text-body text-h7 gap-4" for="check{{ $loop->index }}">
								{{ $item['title'] }}
								<span class="accordion-toggle" aria-hidden="true">
									<span class="accordion-toggle__line"></span>
									<span class="accordion-toggle__line"></span>
								</span>
							</label>
							<div class="accordion-content [&_p]:text-body!">
								{!! $item['text'] !!}
							</div>
						</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>
</section>