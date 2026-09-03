<!--- video -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-video relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		@if (!empty($g_video['header']))
		<h2 data-gsap-element="header" class="mb-10 text-center">{{ $g_video['header'] }}</h2>
		@endif

		@if ($source_type === 'youtube' && !empty($youtube_embed_url))
		<div class="__video">
			<iframe
				class="aspect-video w-full"
				src="{{ $youtube_embed_url }}"
				title="{{ !empty($g_video['header']) ? $g_video['header'] : 'Film YouTube' }}"
				loading="lazy"
				allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
				referrerpolicy="strict-origin-when-cross-origin"
				allowfullscreen></iframe>
		</div>
		@elseif ($source_type === 'file' && !empty($g_video['video']))
		<div class="__video">
			<video
				class="w-full"
				controls
				preload="metadata"
				playsinline>
				<source src="{{ $g_video['video'] }}">
				Twoja przeglądarka nie obsługuje odtwarzania wideo.
			</video>
		</div>
		@endif
	</div>

</section>
