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
    @if (!empty($end_date))
    <div
      class="__grid grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4"
      data-counter-date="{{ $end_date }}"
      role="timer"
      aria-label="Czas pozostały do wskazanego terminu">
      <div data-gsap-element="card" class="__card flex flex-col items-center gap-2 border border-primary-100 bg-white p-6 text-center radius">
        <span data-counter-days>{{ $counter_values['days'] }}</span>
        <span>DNI</span>
      </div>
      <div data-gsap-element="card" class="__card flex flex-col items-center gap-2 border border-primary-100 bg-white p-6 text-center radius">
        <span data-counter-hours>{{ str_pad((string) $counter_values['hours'], 2, '0', STR_PAD_LEFT) }}</span>
        <span>GODZINY</span>
      </div>
      <div data-gsap-element="card" class="__card flex flex-col items-center gap-2 border border-primary-100 bg-white p-6 text-center radius">
        <span data-counter-minutes>{{ str_pad((string) $counter_values['minutes'], 2, '0', STR_PAD_LEFT) }}</span>
        <span>MINUTY</span>
      </div>
      <div data-gsap-element="card" class="__card flex flex-col items-center gap-2 border border-primary-100 bg-white p-6 text-center radius">
        <span data-counter-seconds>{{ str_pad((string) $counter_values['seconds'], 2, '0', STR_PAD_LEFT) }}</span>
        <span>SEKUNDY</span>
      </div>
    </div>
    @endif
  </div>
</section>
