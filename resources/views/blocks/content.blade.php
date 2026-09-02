<!--- content -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-content relative -smt overflow-hidden' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main relative">

		<div class="__col grid grid-cols-1 lg:grid-cols-2 items-center gap-8 lg:gap-20">
			<div class="__img order1 relative" data-gsap-element="img">
				@if (!empty($g_content['image']))
				<x-picture
					data-gsap-element="img"
					:image="$g_content['image']"
					:figureClass="'__img h-full'"
					class="w-full object-cover radius max-h-[600px]" />
				@if ($bgshape)
				<span class="__shape" aria-hidden="true"></span>
				@endif
				@endif
			</div>

			<div class="__content order2">
				<p data-gsap-element="title" class="__title">{{ $g_content['title'] }}</p>
				<h2 data-gsap-element="header" class="text-h4">{{ $g_content['header'] }}</h2>

				<div data-gsap-element="txt" class="m-header __txt">
					{!! $g_content['text'] !!}
				</div>


				@if (!empty($g_content['hint']))
				<div data-gsap-element="box" class="__hint flex items-center radius bg-primary-lighter border border-dashed border-primary p-6 gap-4 mt-6">
					@if (!empty($g_content['image_hint']['url']))
					<img
						class="max-w-10 aspect-square"
						src="{{ $g_content['image_hint']['url'] }}"
						alt="{{ $g_content['image_hint']['alt'] ?? '' }}">
					@endif

					<div>
						@if (!empty($g_content['header_hint']))
						<b class="!text-primary !font-[700]">
							{{ $g_content['header_hint'] }}
						</b>
						@endif
						@if (!empty($g_content['text_hint']))
						<p class="">
							{{ $g_content['text_hint'] }}
						</p>
						@endif
					</div>
				</div>
				@endif

				@if (!empty($g_content['button1']) || !empty($g_content['button2']))
				<div class="inline-buttons m-btn">
					@if (!empty($g_content['button1']))
					<x-button
						:href="$g_content['button1']['url']"
						variant="primary"
						class=""
						data-gsap-element="btn">
						{{ $g_content['button1']['title'] }}
					</x-button>
					@endif

					@if (!empty($g_content['button2']))
					<x-button
						:href="$g_content['button2']['url']"
						variant="secondary"
						class=""
						data-gsap-element="btn">
						{{ $g_content['button2']['title'] }}
					</x-button>
					@endif
				</div>
				@endif

			</div>

		</div>
	</div>
</section>