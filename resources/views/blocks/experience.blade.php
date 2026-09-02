<!--- experience -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-experience relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
		@if (!empty($g_experience['image']))
		<div class="__img order1 relative" data-gsap-element="img">
			<x-picture
				:image="$g_experience['image']"
				figureClass=""
				class="w-full h-full object-cover" />
			<span class="__shape" aria-hidden="true"></span>
		</div>
		@endif

		<div class="__content order2">
			@if (!empty($g_experience['header']))
			<h2 data-gsap-element="header" class="">{{ $g_experience['header'] }}</h2>
			@endif

			@if (!empty($g_experience['text']))
			<div data-gsap-element="txt" class="__txt m-header">
				{!! $g_experience['text'] !!}
			</div>
			@endif

			@if (!empty($r_experience) || !empty($g_experience['cta_header']) || !empty($g_experience['button']))
			<div class="__cards grid grid-cols-1 sm:grid-cols-2 gap-6 mt-8 lg:mt-10">
				@foreach (($r_experience ?? []) as $item)
				<div data-gsap-element="card" class="__card flex flex-col justify-center">
					@if (!empty($item['number']))
					<p class="__number text-h2">{{ $item['number'] }}</p>
					@endif
					@if (!empty($item['text']))
					<div class="__card-text">{!! $item['text'] !!}</div>
					@endif
				</div>
				@endforeach

				@if (!empty($g_experience['cta_header']) || !empty($g_experience['button']))
				<div data-gsap-element="card" class="__card __card--cta flex flex-col justify-center">
					@if (!empty($g_experience['cta_header']))
					<p class="__cta-title text-secondary">{{ $g_experience['cta_header'] }}</p>
					@endif
					@if (!empty($g_experience['button']))
					<x-button
						:href="$g_experience['button']['url']"
						variant="secondary-small"
						class="m-btn"
						data-gsap-element="btn">
						{{ $g_experience['button']['title'] }}
					</x-button>
					@endif
				</div>
				@endif
			</div>
			@endif
		</div>
	</div>
</section>