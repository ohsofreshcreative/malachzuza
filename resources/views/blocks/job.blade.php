<!--- job --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	@class([ 'b-job relative mt-10' ,
	$sectionClass=> filled($sectionClass),
	$section_class => filled($section_class),
	$background => filled($background) && $background !== 'none',
	])>

	<div class="__wrapper c-main">
		<div class="grid grid-cols-1 items-start gap-10 lg:grid-cols-2 lg:gap-20">
			@if (!empty($g_job['content']))
			<div data-gsap-element="txt" class="__content __txt">
				{!! $g_job['content'] !!}
			</div>
			@endif

			@if (!empty($g_job_form['shortcode']))
			<div data-gsap-element="form" class="__form bg-white p-8 lg:p-10">
				@if (!empty($g_job_form['title']))
				<h5 data-gsap-element="header" class="m-header">{{ $g_job_form['title'] }}</h5>
				@endif
				{!! do_shortcode($g_job_form['shortcode']) !!}
			</div>
			@endif
		</div>
	</div>

</section>
