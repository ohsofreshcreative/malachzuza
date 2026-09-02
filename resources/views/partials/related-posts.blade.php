@php
$_related_cats = wp_get_post_categories(get_the_ID());
$_related_query = new WP_Query([
    'category__in'        => $_related_cats,
    'post__not_in'        => [get_the_ID()],
    'posts_per_page'      => 3,
    'ignore_sticky_posts' => 1,
]);
@endphp

@if($_related_query->have_posts())
<section class="related-posts bg-bright -smt pt-20 pb-26">
    <div class="c-main">
        <h3 class="__header text-2xl font-bold">Podobne wpisy</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
            @while($_related_query->have_posts())
            @php $_related_query->the_post(); @endphp
            @include('partials.content')
            @endwhile
            @php wp_reset_postdata(); @endphp
        </div>
    </div>
</section>
@endif
