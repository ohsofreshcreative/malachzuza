@php
$categories = get_the_category();
$category = !empty($categories) ? $categories[0] : null;
@endphp

<article @php(post_class('__card'))>

	<a class="group" href="{{ get_permalink() }}">
		<div class="__content relative bg-white p-6">
			@if (has_post_thumbnail())
			<div class="block overflow-hidden">
				<img src="{{ get_the_post_thumbnail_url(null, 'large') }}" alt="{{ get_the_title() }}" class="w-full img-s object-cover">
			</div>
			@endif
			<div class="flex items-center justify-between mt-4">
				@if ($category)
				<div data-gsap-element="header" href="{{ get_category_link($category->term_id) }}" class="bg-secondary w-max text-white text-xs px-4 py-3">{{ $category->name }}</div>
				@endif
				<p class="text-xs text-gray-500 !mt-0">{{ get_the_date('F j, Y') }}</p>
			</div>
			<h6 class="mt-4">
				{!! get_the_title() !!}
			</h6>
			<!--  <div class="mt-2">
            @php(the_excerpt())
        </div> -->
			<p href="{{ get_permalink() }}" class="btn btn-underline group-hover:text-secondary! mt-4">
				Przeczytaj
			</p>
		</div>
	</a>
</article>