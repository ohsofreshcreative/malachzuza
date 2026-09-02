{{--
The Template for displaying product archives, including the main shop page which is a post type archive

This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.

HOWEVER, on occasion WooCommerce will need to update template files and you
(the theme developer) will need to copy the new files to your theme to
maintain compatibility. We try to do this as little as possible, but it does
happen. When this occurs the version of the template file will be bumped and
the readme will list any important changes.

@see https://docs.woocommerce.com/document/template-structure/
@package WooCommerce/Templates
@version 3.4.0
--}}

@extends('layouts.app')

@section('content')
@php
$term = get_queried_object();
$is_shop_main = !($term instanceof WP_Term);
// Na głównej stronie sklepu (bez terminu kategorii) używamy ustawień z opcji "Ustawienia sklepu"
$g_oshop = $is_shop_main ? get_field('g_oshop', 'option') : null;

$hero_image = ($term instanceof WP_Term) ? get_field('hero_image', $term) : ($g_oshop['hero_image'] ?? null);
$hero_header_custom = ($term instanceof WP_Term) ? get_field('hero_header', $term) : ($g_oshop['hero_header'] ?? null);
$display_header = !empty($hero_header_custom) ? $hero_header_custom : woocommerce_page_title(false);
do_action('get_header', 'shop');
do_action('woocommerce_before_main_content');

// Pobranie wartości z opcji ACF
$g_values = get_field('g_values', 'option');
$r_values = get_field('r_values', 'option');

// Pobranie opisu kategorii / archiwum / sklepu
$description = ($term instanceof WP_Term) ? term_description($term->term_id, $term->taxonomy) : ($g_oshop['description'] ?? '');

// Przyciski kategorii (na sklepie i na podstronach kategorii). Domyślna kategoria zastępowana przyciskiem "Wszystkie",
// podświetlonym na /sklep. Na podstronie kategorii podświetlona jest wybrana kategoria.
$current_term_id = ($term instanceof WP_Term) ? $term->term_id : 0;
$default_cat_id = (int) get_option('default_product_cat');
$category_terms = get_terms([
'taxonomy' => 'product_cat',
'hide_empty' => true,
'orderby' => 'menu_order',
'order' => 'ASC',
'exclude' => $default_cat_id ? [$default_cat_id] : [],
]);

$category_buttons = [];
if (!is_wp_error($category_terms)) {
$category_buttons[] = [
'title' => 'Wszystkie',
'url' => function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/'),
'active' => $is_shop_main,
];

foreach ($category_terms as $category_term) {
$category_buttons[] = [
'title' => $category_term->name,
'url' => get_term_link($category_term),
'active' => $category_term->term_id === $current_term_id,
];
}
}
@endphp

<header class="b-herosub relative overflow-hidden">
	<div class="__wrapper relative">

		<div class="__inside c-main relative z-20 pt-50">
			<div class="__content w-full md:w-2/3">
				@if(function_exists('yoast_breadcrumb'))
				<div class="__breadcrumb mb-4">
					{!! yoast_breadcrumb('<p id="breadcrumbs">','</p>') !!}
				</div>
				@endif

				<h1 class=""> {!! strip_tags($display_header, '<strong><em><a><br>') !!}</h1>

				@if (!empty($description))
				<div id="category-desc-content" class="mt-4">
					{!! $description !!}
				</div>
				@endif
			</div>

			@if (!empty($category_buttons))
			<div class="inline-buttons m-btn flex-wrap">
				@foreach ($category_buttons as $item)
				<x-button :href="$item['url']" variant="{{ $item['active'] ? 'primary-small' : 'outline-primary-small' }}">{{ $item['title'] }}</x-button>
				@endforeach
			</div>
			@endif
		</div>

	</div>
</header>
<div class="c-main flex flex-col lg:flex-row gap-10 pt-5 pb-16">

	{{-- Sidebar z filtrami --}}
	@if (is_active_sidebar('shop-sidebar'))
	<aside class="__shop-sidebar w-full lg:w-1/4">
		@php dynamic_sidebar('shop-sidebar') @endphp
	</aside>
	@endif

	{{-- Produkty --}}
	<div class="__products min-w-0">
		@if (woocommerce_product_loop())
		@php
		do_action('woocommerce_before_shop_loop');
		woocommerce_product_loop_start();
		@endphp

		@if (wc_get_loop_prop('total'))
		@while (have_posts())
		@php
		the_post();
		do_action('woocommerce_shop_loop');
		wc_get_template_part('content', 'product');
		@endphp
		@endwhile
		@endif

		@php
		woocommerce_product_loop_end();
		do_action('woocommerce_after_shop_loop');
		@endphp
		@else
		@php do_action('woocommerce_no_products_found') @endphp
		@endif
	</div>

</div>


<!-- cta -->
@php
$g_octa = get_field('g_octa', 'option');
$form = false;
$sectionClass = '!mt-0';
$section_id = '';
$section_class = '';
$background = 'none';
@endphp
@include('blocks.cta')