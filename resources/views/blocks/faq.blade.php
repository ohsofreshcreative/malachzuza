<!--- faq --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-faq relative -smt' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main grid grid-cols-1 md:grid-cols-[1fr_2fr] gap-0 md:gap-20">

		<div class="__content">
			<p data-gsap-element="title" class="text-secondary">FAQ</p>
			<h4 data-gsap-element="header" class="">{{ $g_faq['header'] }}</h4>
			@if (!empty($g_faq['image']))
			<div data-gsap-element="img" class="__img order1 mt-10">
				<img class="__img object-cover" src="{{ $g_faq['image']['url'] }}" alt="{{ $g_faq['image']['alt'] ?? '' }}">
			</div>
			@endif
		</div>
		<div data-gsap-element="tabs" class="tabs-wrapper flex flex-col mt-4">
			@foreach ($r_faq as $item)
<div class="tabs bg-white h-max">
    <input class="tab-check" type="checkbox" name="radio-a" id="check{{ $loop->index }}">
    <label class="tabs-label flex items-center justify-between" for="check{{ $loop->index }}">
        <div class="flex items-center gap-4">
            <p class="!text-md font-header">{{ $item['title'] }}</p>
        </div>

        <span class="__icon" aria-hidden="true">
            <span class="__plus text-secondary">+</span>
            <span class="__minus text-secondary">−</span>
        </span>
    </label>

    <div class="tabs-content">
        {!! $item['txt'] !!}
    </div>
</div>
@endforeach
		</div>

	</div>

</section>