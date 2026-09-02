<!--- switcher -->

@php($switcherId = wp_unique_id('switcher-'))

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-switcher relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main flex flex-col gap-12">
		@if (!empty($g_switcher['header']))
		<h2 data-gsap-element="header">{{ $g_switcher['header'] }}</h2>
		@endif

		@if (!empty($r_switcher))
		<div
			x-data="{ activeTab: 0 }"
			class="__switcher grid grid-cols-1 items-start gap-8 lg:grid-cols-4 lg:gap-14">
			<div
				class="__nav flex flex-col gap-3 lg:col-span-1"
				role="tablist"
				aria-label="{{ $g_switcher['header'] ?? 'Wybierz zakładkę' }}">
				@foreach ($r_switcher as $item)
				<button
					type="button"
					id="{{ $switcherId }}-tab-{{ $loop->index }}"
					role="tab"
					@click="activeTab = {{ $loop->index }}"
					@keydown.right.prevent="activeTab = (activeTab + 1) % {{ count($r_switcher) }}"
					@keydown.left.prevent="activeTab = (activeTab - 1 + {{ count($r_switcher) }}) % {{ count($r_switcher) }}"
					:aria-selected="activeTab === {{ $loop->index }}"
					:tabindex="activeTab === {{ $loop->index }} ? 0 : -1"
					aria-controls="{{ $switcherId }}-panel-{{ $loop->index }}"
					:class="activeTab === {{ $loop->index }} ? 'border-primary bg-primary text-white' : 'border-primary-100 bg-white text-primary hover:border-primary'"
					class="w-full cursor-pointer border px-6 py-4 text-center transition-colors">
					{{ $item['title'] }}
				</button>
				@endforeach
			</div>

			<div class="__panels lg:col-span-3">
				@foreach ($r_switcher as $item)
				<div
					id="{{ $switcherId }}-panel-{{ $loop->index }}"
					role="tabpanel"
					aria-labelledby="{{ $switcherId }}-tab-{{ $loop->index }}"
					x-show="activeTab === {{ $loop->index }}"
					x-cloak
					class="__panel bg-white p-6 lg:p-10">
					<div class="flex flex-col gap-8">
						@if (!empty($item['image']))
						<x-picture
							:image="$item['image']"
							figureClass="__img overflow-hidden"
							class="aspect-video w-full object-cover max-h-[400px]"
							data-gsap-element="img" />
						@endif

						@if (!empty($item['header']) || !empty($item['text']))
						<div class="__content flex flex-col gap-6">
							@if (!empty($item['header']))
							<p data-gsap-element="header" class="text-h6">{{ $item['header'] }}</p>
							@endif

							@if (!empty($item['text']))
							<div data-gsap-element="txt" class="__txt">
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
