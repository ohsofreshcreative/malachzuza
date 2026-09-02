<!--- support -->

@php($supportId = wp_unique_id('support-'))

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-support relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main flex flex-col gap-12">
		{{-- Górna sekcja z opisem --}}
		@if (!empty($g_support['header']) || !empty($g_support['text']))
		<div class="__top text-center w-full md:w-3/4 mx-auto flex flex-col gap-4">
			@if (!empty($g_support['header']))
			<h2 data-gsap-element="header" class="text-h2 text-primary-dark">
				{{ $g_support['header'] }}
			</h2>
			@endif

			@if (!empty($g_support['text']))
			<div data-gsap-element="text" class="__txt">
				{!! $g_support['text'] !!}
			</div>
			@endif
		</div>
		@endif

		{{-- Sekcja z zakładkami i zawartością pakietów --}}
		@if (!empty($r_support))
		<div
			x-data="{ activeTab: 0 }"
			class="__support flex flex-col gap-10">

			{{-- Paski nawigacji (zakładki) --}}
			<div
				class="__nav flex flex-wrap items-center justify-center gap-3"
				role="tablist"
				aria-label="{{ $g_support['header'] ?? 'Wybierz pakiet wsparcia' }}">
				@foreach ($r_support as $item)
				<button
					type="button"
					id="{{ $supportId }}-tab-{{ $loop->index }}"
					role="tab"
					@click="activeTab = {{ $loop->index }}"
					@keydown.right.prevent="activeTab = (activeTab + 1) % {{ count($r_support) }}"
					@keydown.left.prevent="activeTab = (activeTab - 1 + {{ count($r_support) }}) % {{ count($r_support) }}"
					:aria-selected="activeTab === {{ $loop->index }}"
					:tabindex="activeTab === {{ $loop->index }} ? 0 : -1"
					aria-controls="{{ $supportId }}-panel-{{ $loop->index }}"
					:class="activeTab === {{ $loop->index }} ? 'border-primary bg-primary text-white' : 'border-primary-100 bg-white text-primary hover:border-primary'"
					class="cursor-pointer border px-5 py-2.5 text-center transition-colors rounded-none">
					{{ $item['tab_name'] }}
				</button>
				@endforeach
			</div>

			{{-- Kontenery z treścią paneli --}}
			<div class="__panels">
				@foreach ($r_support as $item)
				<div
					id="{{ $supportId }}-panel-{{ $loop->index }}"
					role="tabpanel"
					aria-labelledby="{{ $supportId }}-tab-{{ $loop->index }}"
					x-show="activeTab === {{ $loop->index }}"
					x-cloak
					class="__panel bg-white p-6 md:p-10 border border-primary-100/50">

					<div class="flex flex-col gap-8">
						{{-- 1. Nagłówek pakietu i prawa skrócona ramka --}}
						<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
							<div class="lg:col-span-8 flex flex-col gap-4">
								@if (!empty($item['header']))
								<h3 data-gsap-element="header" class="text-h7 text-primary-dark">
									{!! $item['header'] !!}
								</h3>
								@endif

								@if (!empty($item['text']))
								<div data-gsap-element="txt" class="__txt text-body/90">
									{!! $item['text'] !!}
								</div>
								@endif
							</div>

							@if (!empty($item['right_badge']))
							<div data-gsap-element="card" class="lg:col-span-4 bg-bright border border-primary-100 p-6 flex items-center">
								<p class="text-body">
									{{ $item['right_badge'] }}
								</p>
							</div>
							@endif
						</div>

						<hr class="border-t border-primary-100/40">

						{{-- 2. Dwukolumnowy układ: lewa (Zakres pakietu) i prawa ramy (Korzyści) --}}
						<div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
							{{-- Lewa kolumna: zakres pakietu --}}
							<div class="lg:col-span-8 flex flex-col gap-6">
								@if (!empty($item['list_title']))
								<h4 class="text-h6 text-primary-dark">
									{{ $item['list_title'] }}
								</h4>
								@endif

								@if (!empty($item['left_list']))
								<div data-gsap-element="txt" class="__left-list prose-styles">
									{!! $item['left_list'] !!}
								</div>
								@endif
							</div>

							{{-- Prawa kolumna: korzyści dla pracodawcy (w ramce) --}}
							@if (!empty($item['right_list']) || !empty($item['right_list_title']))
							<div data-gsap-element="card" class="lg:col-span-4 bg-bright border border-primary-100 p-6 md:p-8 flex flex-col gap-5">
								@if (!empty($item['right_list_title']))
								<h4 class="text-h7 text-primary-dark">
									{{ $item['right_list_title'] }}
								</h4>
								@endif

								@if (!empty($item['right_list']))
								<div class="__right-list prose-styles">
									{!! $item['right_list'] !!}
								</div>
								@endif
							</div>
							@endif
						</div>
					</div>

				</div>
				@endforeach
			</div>

		</div>
		@endif
	</div>
</section>
