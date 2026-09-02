<!--- banner -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-banner relative flex flex-col overflow-hidden bg-secondary-700' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div @class([
		'__media order-2 w-full lg:absolute lg:inset-y-0 lg:right-0 lg:left-1/2 lg:h-full lg:w-auto',
		'bg-secondary-700' => empty($g_banner['image']),
		'bg-page' => !empty($g_banner['image']),
	]) aria-hidden="true">
		@if (!empty($g_banner['image']))
		<x-picture
			:image="$g_banner['image']"
			figureClass="__img h-[50vh] md:h-full w-full [&>picture]:block [&>picture]:h-full [&>picture]:w-full"
			class="h-full w-full object-cover"
			data-gsap-element="img" />
		@endif
	</div>

	<div class="__decor pointer-events-none absolute inset-0 z-20 hidden lg:block" aria-hidden="true">
		<span class="absolute top-0 left-[67%] size-[64px] bg-white"></span>
		<span class="absolute top-[20%] right-[5.5%] size-[64px] bg-secondary-300"></span>
		<span class="absolute top-[46%] left-[53.5%] size-[64px] bg-primary"></span>
		<span class="absolute right-[2.5%] bottom-0 size-[64px] bg-primary-100"></span>
		<span class="absolute top-[170px] left-1/2 size-[64px] bg-secondary-700"></span>
		<span class="absolute right-1/2 bottom-[152px] size-[64px] bg-page"></span>
	</div>

	<div class="__wrapper c-main relative z-10 order-1 grid grid-cols-1 lg:grid-cols-2">
		<div class="__content flex w-full flex-col items-start justify-start py-14 lg:py-[60px] lg:pr-16">
			@if (function_exists('yoast_breadcrumb'))
			<div data-gsap-element="bread" class="__breadcrumb text-primary [&_#breadcrumbs]:flex-wrap [&_a]:text-white lg:mb-16">
				{!! yoast_breadcrumb('<p id="breadcrumbs">', '</p>', false) !!}
			</div>
			@endif

			<div class="pt-2 pb-8">
				@if (!empty($g_banner['header']))
				<h1 data-gsap-element="header" class="text-white text-h4">
					{{ $g_banner['header'] }}
				</h1>
				@endif

				@if (!empty($g_banner['text']))
				<div data-gsap-element="txt" class="__txt text-white m-header">
					{!! $g_banner['text'] !!}
				</div>
				@endif

				@if (!empty($g_banner['button1']) || !empty($g_banner['button2']))
				<div class="inline-buttons m-btn">
					@if (!empty($g_banner['button1']))
					<x-button
						:href="$g_banner['button1']['url']"
						variant="primary"
						data-gsap-element="btn">
						{{ $g_banner['button1']['title'] }}
					</x-button>
					@endif

					@if (!empty($g_banner['button2']))
					<x-button
						:href="$g_banner['button2']['url']"
						variant="white"
						data-gsap-element="btn">
						{{ $g_banner['button2']['title'] }}
					</x-button>
					@endif
				</div>
				@endif
			</div>
		</div>
	</div>

</section>