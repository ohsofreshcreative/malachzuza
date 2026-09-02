<!--- whyus -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-whyus relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">

		<div class="__top grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
			@if (!empty($g_whyus['header']))
			<h4 data-gsap-element="header" class="">{{ $g_whyus['header'] }}</h4>
			@endif
			@if (!empty($g_whyus['text']))
			<div data-gsap-element="txt" class="__txt">
				{!! $g_whyus['text'] !!}
			</div>
			@endif
		</div>

		<div class="__table grid grid-cols-1 lg:grid-cols-2 gap-6 mt-10">

			<div data-gsap-element="card" class="__col">
				<div class="__col-header bg-primary-dark p-6">
					<h3 class="text-h7 text-white">{{ $g_whyus['col1_title'] ?? '' }}</h3>
				</div>
				@if (!empty($r_whyus_col1))
				<div class="__col-body bg-white">
					@foreach ($r_whyus_col1 as $item)
					<div class="__row flex items-center gap-4 p-6 border-b border-primary-100 last:border-b-0">
						<span class="__icon w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center shrink-0">
							<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
								<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
							</svg>
						</span>
						<p>{{ $item['text'] }}</p>
					</div>
					@endforeach
				</div>
				@endif
			</div>

			<div data-gsap-element="card" class="__col">
				<div class="__col-header bg-primary-100 p-6">
					<h3 class="text-h7">{{ $g_whyus['col2_title'] ?? '' }}</h3>
				</div>
				@if (!empty($r_whyus_col2))
				<div class="__col-body bg-white">
					@foreach ($r_whyus_col2 as $item)
					<div class="__row flex items-center gap-4 p-6 border-b border-primary-100 last:border-b-0">
						<span class="__icon w-6 h-6 rounded-full bg-red-500 text-white flex items-center justify-center shrink-0">
							<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
								<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
							</svg>
						</span>
						<p>{{ $item['text'] }}</p>
					</div>
					@endforeach
				</div>
				@endif
			</div>

		</div>
	</div>

</section>
