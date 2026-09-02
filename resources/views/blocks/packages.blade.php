<!--- packages --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-packages relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		<div class="__top text-center w-full md:w-3/4 mx-auto">
			<h3 data-gsap-element="header" class="">{{ strip_tags($g_packages['header']) }}</h3>
			<div data-gsap-element="text" class="m-header text-xl">{!! $g_packages['text'] !!}</div>
		</div>

		<div class="__tables grid grid-cols-1 md:grid-cols-3 gap-8 mt-10">
			@if (!empty($r_packages1))
			<div class="grid gap-8 mt-10">
				@foreach ($r_packages1 as $item)
				<div data-gsap-element="card" class="__card relative bg-white">
					<div class="__header bg-primary-dark p-8">
						@if (!empty($item['title']))
						<p class="text-h5 text-white">{{ $item['header'] }}</p>
						@endif
						@if (!empty($item['text']))
						<p class="text-primary-lighter">{{ $item['text'] }}</p>
						@endif
					</div>
					@if (!empty($item['list']))
					<p class="">{!! $item['list'] !!}</p>
					@endif
					@if (!empty($item['title']))
					<p class="p-8 bg-primary-lighter">Zaangażowanie firmy: <b>{{ $item['title'] }}</b></p>
					@endif
				</div>
				@endforeach
			</div>
			@endif
			@if (!empty($r_packages2))
			<div class="grid gap-8 mt-10">
				@foreach ($r_packages2 as $item)
				<div data-gsap-element="card" class="__card relative bg-white">
					<div class="__header bg-primary-dark p-8">
						@if (!empty($item['title']))
						<p class="text-h5 text-white">{{ $item['header'] }}</p>
						@endif
						@if (!empty($item['text']))
						<p class="text-primary-lighter">{{ $item['text'] }}</p>
						@endif
					</div>
					@if (!empty($item['list']))
					<p class="">{!! $item['list'] !!}</p>
					@endif
					@if (!empty($item['title']))
					<p class="p-8 bg-primary-lighter">Zaangażowanie firmy: <b>{{ $item['title'] }}</b></p>
					@endif
				</div>
				@endforeach
			</div>
			@endif
			@if (!empty($r_packages3))
			<div class="grid gap-8 mt-10">
				@foreach ($r_packages3 as $item)
				<div data-gsap-element="card" class="__card relative bg-white">
					<div class="__header bg-primary-dark p-8">
						@if (!empty($item['title']))
						<p class="text-h5 text-white">{{ $item['header'] }}</p>
						@endif
						@if (!empty($item['text']))
						<p class="text-primary-lighter">{{ $item['text'] }}</p>
						@endif
					</div>
					@if (!empty($item['list']))
					<p class="">{!! $item['list'] !!}</p>
					@endif
					@if (!empty($item['title']))
					<p class="p-8 bg-primary-lighter">Zaangażowanie firmy: <b>{{ $item['title'] }}</b></p>
					@endif
				</div>
				@endforeach
			</div>
			@endif
		</div>

		<div class="__bottom text-center w-full md:w-2/3 mx-auto mt-20">
			@if (!empty($g_packages2['header']))
			<h2 data-gsap-element="header" class="m-header">{{ strip_tags($g_packages2['header']) }}</h2>
			@endif
			@if (!empty($g_packages2['text']))
			<div data-gsap-element="text" class="text-h7">{!! $g_packages2['text'] !!}</div>
			@endif
			@if (!empty($g_packages2['button']))
			<x-button
				:href="$g_packages2['button']['url']"
				variant="primary"
				class="m-btn"
				data-gsap-element="btn">
				{{ $g_packages2['button']['title'] }}
			</x-button>
			@endif
		</div>

	</div>

</section>