<!--- about -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-about relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main relative">


		@if (!empty($g_about['image']))
		<div data-gsap-element="img" class="__img h-full">
			<figure class="w-full h-full m-0">
				<picture class="w-full h-full">
					<img class="w-full img-2xl object-cover" src="{{ $g_about['image']['url'] }}" alt="{{ $g_about['image']['alt'] ?? '' }}">
				</picture>
			</figure>
		</div>
		@endif

		<div class="__col grid grid-cols-1 lg:grid-cols-2 items-center gap-8 lg:gap-10 mt-14">

			<div class="__content ">
				<h3 data-gsap-element="header" class="text-primary">{{ $g_about['header'] }}</h3>

				<div data-gsap-element="txt" class="__txt mt-4">
					{!! $g_about['text'] !!}
				</div>

				<div class="inline-buttons m-btn">
					@if (!empty($g_about['button1']))
					<x-button
						:href="$g_about['button1']['url']"
						variant="primary"
						class=""
						data-gsap-element="btn">
						{{ $g_about['button1']['title'] }}
					</x-button>
					@endif

					@if (!empty($g_about['button2']))
					<x-button
						:href="$g_about['button2']['url']"
						variant="secondary"
						class=""
						data-gsap-element="btn">
						{{ $g_about['button2']['title'] }}
					</x-button>
					@endif
				</div>

			</div>

			@if (!empty($r_about))

			<div data-gsap-element="accordion" class="grid">
				@foreach ($r_about as $item)
				<div class="accordion bg-white border border-secondary h-max">
					<input class="acc-check" type="radio" name="accordion-radio" id="check{{ $loop->index }}" {{ $loop->first ? 'checked' : '' }}>
					<label class="accordion-label flex items-center justify-between text-h7 gap-4" for="check{{ $loop->index }}">
						{{ $item['title'] }}
						<span class="accordion-toggle" aria-hidden="true">
							<span class="accordion-toggle__line"></span>
							<span class="accordion-toggle__line"></span>
						</span>
					</label>
					<div class="accordion-content [&_*]:!text-body">
						{!! $item['text'] !!}
					</div>
				</div>
				@endforeach
			</div>
			@endif

		</div>
	</div>

</section>