@extends('layouts.app')

@section('content')

<div class="hero category-header relative bg-secondary-700">
	<div class="__wrapper c-main relative z-10">
		@if (function_exists('yoast_breadcrumb'))
		<div data-gsap-element="bread" class="__breadcrumb text-primary [&_#breadcrumbs]:flex-wrap [&_a]:text-white">
			{!! yoast_breadcrumb('<p id="breadcrumbs" class="!pt-4">', '</p>', false) !!}
		</div>
		@endif
		<div class="__wrapper pt-10 pb-20">
			<div class="__content w-full md:w-2/3">
				<h2 class="text-white m-header">
					{{ sprintf(__('Wyniki wyszukiwania dla: %s', 'sage'), get_search_query()) }}
				</h2>
			</div>

			<form role="search" method="get" class="__search relative mt-6 max-w-sm" action="{{ home_url('/') }}">
				<label class="sr-only" for="blog-search">
					{{ _x('Szukaj', 'label', 'sage') }}
				</label>
				<input
					type="search"
					id="blog-search"
					name="s"
					value="{{ get_search_query() }}"
					placeholder="Szukaj wpisów…"
					class="w-full bg-white px-4 py-2 pr-12 text-sm"
				>
				<input type="hidden" name="post_type" value="post">
				<button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 p-2">
					<span class="sr-only">{{ _x('Szukaj', 'submit button', 'sage') }}</span>
					<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
						<circle cx="11" cy="11" r="7"></circle>
						<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
					</svg>
				</button>
			</form>
		</div>
	</div>

	<div class="__decor pointer-events-none absolute inset-0 z-20 hidden lg:block" aria-hidden="true">
		<span class="absolute top-0 left-[67%] size-[64px] bg-white"></span>
		<span class="absolute top-[20%] right-[5.5%] size-[64px] bg-secondary-300"></span>
		<span class="absolute top-[46%] left-[56.5%] size-[64px] bg-primary"></span>
		<span class="absolute right-[2.5%] bottom-0 size-[64px] bg-primary-100"></span>
		<span class="absolute top-[170px] left-1/2 size-[64px] bg-secondary-700"></span>
	</div>
</div>

@if (have_posts())
<div class="__posts c-main !mt-10 posts grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
	@while (have_posts())
	@php
	the_post();
	@endphp

	@includeFirst([
	'partials.content-' . get_post_type(),
	'partials.content',
	])
	@endwhile
</div>

{!! the_posts_pagination() !!}
@else
<div class="mt-20 mb-20">
	<div class="c-main">
		<h3>Brak wyników dla podanej frazy.</h3>
		<a class="main-btn m-btn" href="/wszystkie-wpisy/">
			Sprawdź wszystkie wpisy
		</a>
	</div>
</div>
@endif

<!-- cta -->
@php
$g_octa = get_field('g_octa', 'option');
$form = true;
$sectionClass = '-smt';
$section_id = '';
$section_class = '';
$background = 'none';
@endphp
@include('blocks.cta')

@endsection
