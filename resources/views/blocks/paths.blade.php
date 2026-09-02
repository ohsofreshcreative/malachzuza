<!--- paths --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-paths relative overflow-hidden -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main z-10">

		<img class="absolute z-10 opacity-10 -bottom-80 -left-80" src="{{ get_template_directory_uri() }}/resources/images/shape.svg" />

		@if (!empty($r_paths))
		<div class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-8">
			@foreach ($r_paths as $item)
			<div data-gsap-element="card" class="__card relative overflow-hidden p-12 aspect-square">
				@if (!empty($item['image']['url']))
				<x-picture
					:image="$item['image']"
					figureClass="absolute inset-0 z-0"
					class="h-full w-full object-cover" />
				@endif

				<div
					class="__overlay absolute inset-0 z-10"
					style="background: linear-gradient(180deg, rgba(6, 58, 110, 0.2) 0%, rgba(6, 58, 110, 1) 100%);"></div>

				<div class="__content relative z-20 flex h-full flex-col justify-end text-white">
					@if (!empty($item['title']))
					<p class="text-h4">{{ $item['title'] }}</p>
					@endif
					@if (!empty($item['text']))
					<p>{{ $item['text'] }}</p>
					@endif
					@if (!empty($item['button1']))
					<x-button
						:href="$item['button1']['url']"
						variant="secondary m-btn"
						class="">
						{{ $item['button1']['title'] }}
					</x-button>
					@endif
				</div>
			</div>
			@endforeach
		</div>
		@endif
	</div>

</section>