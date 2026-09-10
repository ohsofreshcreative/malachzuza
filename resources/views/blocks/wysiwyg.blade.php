<!--- wysiwyg -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-wysiwyg relative -smt py-60' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

  @if (!empty($g_wysiwyg['image']))
    <x-picture
      :image="$g_wysiwyg['image']"
      figureClass="__bg absolute inset-0 z-0 m-0 pointer-events-none"
      class="absolute inset-0 w-full h-full object-cover"
      loading="lazy"
      aria-hidden="true" />
    <div class="__overlay absolute inset-0 z-1 pointer-events-none bg-black/80" aria-hidden="true"></div>
  @endif

	<div @class(['__wrapper c-main relative text-center z-10', 'text-white' => !empty($g_wysiwyg['image'])])>
		@if (!empty($g_wysiwyg['header']))
		<h4 data-gsap-element="header" class=" w-full md:w-2/3 mx-auto">{{ $g_wysiwyg['header'] }}</h4>
		@endif

		<div>
			<div data-gsap-element="txt" class="__txt mt-4">
				{!! $g_wysiwyg['txt'] !!}
			</div>
			@if (!empty($g_wysiwyg['button']))
			<x-button
				:href="$g_wysiwyg['button']['url']"
				variant="primary"
				class="mt-6"
				data-gsap-element="btn">
				{{ $g_wysiwyg['button']['title'] }}
			</x-button>
			@endif
		</div>
	</div>

</section>
