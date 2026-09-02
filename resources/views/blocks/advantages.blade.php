<!--- advantages --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-advantages relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		<div class="__top w-full md:w-2/3">
			@if (!empty($g_advantages['header']))
			<h2 data-gsap-element="header" class="m-header">
				{{ $g_advantages['header'] }}
			</h2>
			@endif
			@if (!empty($g_advantages['text']))
			<div data-gsap-element="txt" class="m-header __txt">
				{!! $g_advantages['text'] !!}
			</div>
			@endif
		</div>

		@if (!empty($r_advantages))
		<div class="__grid mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
			@foreach (($r_advantages ?? []) as $item)
			<div data-gsap-element="card" class="__item __item--card flex flex-col justify-between border border-primary-100 bg-primary-bright radius p-6 xl:p-8">
				@if (!empty($item['icon']['url']))
				<div class="__icon flex h-20 w-20 items-center justify-center">
					<img class="object-contain" src="{{ $item['icon']['url'] }}" alt="{{ $item['icon']['alt'] ?? '' }}">
				</div>
				@endif

				@if (!empty($item['title']))
				<h3 class="mt-6 text-h7 text-primary-dark">{{ $item['title'] }}</h3>
				@endif
				<div>{!! $item['text'] !!}</div>
			</div>
			@endforeach
		</div>
		@endif
	</div>
</section>