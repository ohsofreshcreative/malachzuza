<!--- clients -->

@php($clientsId = wp_unique_id('clients-'))

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-clients relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main flex flex-col gap-12">
		@if (!empty($g_clients['header']))
		<h2 data-gsap-element="header" class="m-header">{{ $g_clients['header'] }}</h2>
		@endif

		@if (!empty($r_clients))
		<div
			x-data="{ activeTab: 0 }"
			class="__clients grid grid-cols-1 items-start gap-8 lg:grid-cols-4 lg:gap-14">
			<div
				class="__nav flex flex-col gap-3 lg:col-span-1"
				role="tablist"
				aria-label="{{ $g_clients['header'] ?? 'Wybierz klienta' }}">
				@foreach ($r_clients as $item)
				<button
					type="button"
					id="{{ $clientsId }}-tab-{{ $loop->index }}"
					role="tab"
					@click="activeTab = {{ $loop->index }}"
					@keydown.right.prevent="activeTab = (activeTab + 1) % {{ count($r_clients) }}"
					@keydown.left.prevent="activeTab = (activeTab - 1 + {{ count($r_clients) }}) % {{ count($r_clients) }}"
					:aria-selected="activeTab === {{ $loop->index }}"
					:tabindex="activeTab === {{ $loop->index }} ? 0 : -1"
					aria-controls="{{ $clientsId }}-panel-{{ $loop->index }}"
					:class="activeTab === {{ $loop->index }} ? 'border-primary bg-primary text-white' : 'border-primary-100 bg-white text-primary hover:border-primary'"
					class="w-full cursor-pointer border px-6 py-4 text-center transition-colors">
					{{ $item['title'] }}
				</button>
				@endforeach
			</div>

			<div class="__panels lg:col-span-3">
				@foreach ($r_clients as $item)
				<div
					id="{{ $clientsId }}-panel-{{ $loop->index }}"
					role="tabpanel"
					aria-labelledby="{{ $clientsId }}-tab-{{ $loop->index }}"
					x-show="activeTab === {{ $loop->index }}"
					x-cloak
					class="__panel bg-white p-6 lg:p-10 border border-primary-100">
					<div class="flex flex-col items-start gap-8 sm:flex-row sm:items-center">
						@if (!empty($item['image']))
						<x-picture
							:image="$item['image']"
							figureClass="__logo shrink-0 w-40"
							class="w-full h-auto object-contain"
							data-gsap-element="img" />
						@endif

						@if (!empty($item['header']) || !empty($item['text']))
						<div class="__content flex flex-col gap-2">
							@if (!empty($item['header']))
							<p data-gsap-element="header" class="text-h6">{{ $item['header'] }}</p>
							@endif

							@if (!empty($item['text']))
							<div data-gsap-element="txt" class="__txt text-lg [&_strong]:text-secondary! *:[&_strong]:font-semibold!">
								{!! $item['text'] !!}
							</div>
							@endif
						</div>
						@endif
					</div>
				</div>
				@endforeach
			</div>
		</div>
		@endif
	</div>
</section>
