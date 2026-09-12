<!--- counter -->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-counter relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		<h2 data-gsap-element="header" class="text-center">Premiera za</h2>
		@if (!empty($end_date))
		<div
			class="__grid grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mt-8"
			data-counter-date="{{ $end_date }}"
			role="timer"
			aria-label="Czas pozostały do wskazanego terminu">
			<div data-gsap-element="card" class="__card flex flex-col items-center gap-2 border border-primary-100/30 bg-background p-6 text-center radius">
				<span data-counter-days class="text-5xl !font-bold">{{ $counter_values['days'] }}</span>
				<span>dni</span>
			</div>
			<div data-gsap-element="card" class="__card flex flex-col items-center gap-2 border border-primary-100/30 bg-background p-6 text-center radius">
				<span data-counter-hours class="text-5xl !font-bold">{{ str_pad((string) $counter_values['hours'], 2, '0', STR_PAD_LEFT) }}</span>
				<span>godziny</span>
			</div>
			<div data-gsap-element="card" class="__card flex flex-col items-center gap-2 border border-primary-100/30 bg-background p-6 text-center radius">
				<span data-counter-minutes class="text-5xl !font-bold">{{ str_pad((string) $counter_values['minutes'], 2, '0', STR_PAD_LEFT) }}</span>
				<span>minuty</span>
			</div>
			<div data-gsap-element="card" class="__card flex flex-col items-center gap-2 border border-primary-100/30 bg-background p-6 text-center radius">
				<span data-counter-seconds class="text-5xl !font-bold">{{ str_pad((string) $counter_values['seconds'], 2, '0', STR_PAD_LEFT) }}</span>
				<span>sekundy</span>
			</div>
		</div>
		@endif
	</div>
</section>