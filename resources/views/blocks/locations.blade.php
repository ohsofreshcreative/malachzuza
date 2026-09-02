<!--- locations -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-locations relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		@if (!empty($g_locations['header']))
		<h2 data-gsap-element="header" class="">{{ $g_locations['header'] }}</h2>
		@endif

		@if (!empty($r_locations))
		@php
			$firstLocation = $r_locations[0];
			$otherLocations = array_slice($r_locations, 1);
		@endphp

		<div class="flex flex-col gap-6 mt-10">
			<div data-gsap-element="card" class="__card border border-primary-100 bg-white p-8 grid grid-cols-1 lg:grid-cols-2 items-center gap-8">
				<div class="__companies flex flex-col gap-8 order-2 md:order-1">
					@foreach ($firstLocation['companies'] as $company)
					<div>
						@if (!empty($company['name']))
						<h3 class="text-h6 text-primary-dark">{{ $company['name'] }}</h3>
						@endif

						@if (!empty($company['address']))
						<p class="text-primary">{{ $company['address'] }}</p>
						@endif

						@if (!empty($company['phones']))
						<div class="flex flex-col gap-1 mt-2">
							@foreach ($company['phones'] as $phone)
							<p>{{ $phone['label'] }} | <a href="tel:{{ $phone['number'] }}">{{ $phone['number'] }}</a></p>
							@endforeach
						</div>
						@endif
					</div>
					@endforeach
				</div>

				@if (!empty($firstLocation['image']))
				<x-picture
					:image="$firstLocation['image']"
					figureClass="__img order-1 md:order-2"
					class="w-full h-full object-cover"
					data-gsap-element="img" />
				@endif
			</div>

			@if (!empty($otherLocations))
			<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
				@foreach ($otherLocations as $location)
				<div data-gsap-element="card" class="__card border border-primary-100 bg-white overflow-hidden p-8">
					@if (!empty($location['image']))
					<x-picture
						:image="$location['image']"
						figureClass="__img"
						class="w-full object-cover"
						data-gsap-element="img" />
					@endif

					<div class="p-8">
						@foreach ($location['companies'] as $company)
						<div>
							@if (!empty($company['name']))
							<h3 class="text-h6 text-primary-dark">{{ $company['name'] }}</h3>
							@endif

							@if (!empty($company['address']))
							<p class="text-primary">{{ $company['address'] }}</p>
							@endif

							@if (!empty($company['phones']))
							<div class="flex flex-col gap-1 mt-2">
								@foreach ($company['phones'] as $phone)
								<p>{{ $phone['label'] }} | <a href="tel:{{ $phone['number'] }}">{{ $phone['number'] }}</a></p>
								@endforeach
							</div>
							@endif
						</div>
						@endforeach
					</div>
				</div>
				@endforeach
			</div>
			@endif
		</div>
		@endif
	</div>

</section>
