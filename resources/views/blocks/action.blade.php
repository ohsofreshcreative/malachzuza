<!--- action -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-action relative overflow-hidden -smt ' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main relative bg-primary-dark overflow-hidden">
		@if (!empty($g_action['image']))
		<x-picture
			:image="$g_action['image']"
			figureClass="__img absolute inset-0 z-0 h-full w-1/2 ml-auto"
			class="h-full w-full object-cover"
			data-gsap-element="img" />
		@endif

		<div class="__overlay absolute inset-0 z-10 bg-linear-to-r from-primary-dark via-primary-dark/95 to-transparent" aria-hidden="true"></div>

		<div class="__content relative z-20 flex h-full w-full flex-col items-start justify-center p-8 lg:w-2/3 lg:p-14">
			@if (!empty($g_action['header']))
			<h5 data-gsap-element="header" class="m-header text-white">{{ $g_action['header'] }}</h5>
			@endif

			@if (!empty($g_action['text']))
			<div data-gsap-element="txt" class="__txt text-white">
				{!! $g_action['text'] !!}
			</div>
			@endif

			@if (!empty($g_action['button1']))
			<div class="inline-buttons m-btn">
				<x-button
					:href="$g_action['button1']['url']"
					variant="secondary"
					data-gsap-element="btn">
					{{ $g_action['button1']['title'] }}
				</x-button>
			</div>
			@endif
		</div>
	</div>
</section>