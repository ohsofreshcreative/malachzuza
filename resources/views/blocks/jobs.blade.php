<!--- jobs --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-jobs relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main flex flex-col gap-10">
		@if (!empty($g_jobs['header']))
		<h2 data-gsap-element="header">{{ $g_jobs['header'] }}</h2>
		@endif

		@if (!empty($job_items))
		<div class="flex flex-col gap-6">
			@foreach ($job_items as $item)
			<div data-gsap-element="item" class="__item flex flex-col items-start justify-between gap-6 border border-primary-light bg-white p-8 lg:flex-row lg:items-center">
				<div>
					<p data-gsap-element="header" class="text-h7">{{ $item['title'] }}</p>
					@if (!empty($item['location']) || !empty($item['contract']))
					<p data-gsap-element="txt" class="text-gray-400">
						{{ $item['location'] }}@if (!empty($item['location']) && !empty($item['contract'])) • @endif{{ $item['contract'] }}
					</p>
					@endif
				</div>

				<x-button :href="$item['url']" variant="underline" data-gsap-element="btn">Sprawdź ofertę</x-button>
			</div>
			@endforeach
		</div>
		@endif
	</div>

</section>