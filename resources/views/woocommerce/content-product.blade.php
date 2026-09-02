@php
global $product;
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
@endphp

@if (!empty($product))
<li class="bg-white b-shadow radius p-8 h-full">

	@php
	do_action('woocommerce_before_shop_loop_item');

	// Dla produktów wariantowych (przedział cenowy) pokazujemy tylko najniższą cenę zamiast całego zakresu.
	$price_html = $product->is_type('variable')
		? wc_price($product->get_variation_price('min', true))
		: $product->get_price_html();
	@endphp

	<a href="{{ get_the_permalink() }}" class="woocommerce-LoopProduct-link group flex flex-col h-full gap-4">

		<div class="__thumb overflow-hidden aspect-square flex items-center justify-center">
			{!! get_the_post_thumbnail($product->get_id(), 'woocommerce_thumbnail', ['class' => 'w-full h-full object-cover object-center']) !!}
		</div>

		@php do_action('woocommerce_before_shop_loop_item_title') @endphp

		<div class="flex-1">
			<h6 class="woocommerce-loop-product__title !text-lg line-clamp-2 min-h-12">{!! get_the_title() !!}</h6>
			@php do_action('woocommerce_after_shop_loop_item_title') @endphp
			@if (!empty($price_html))
			<div class="price">{!! $price_html !!}</div>
			@endif
		</div>

		<p class="btn btn-underline group-hover:text-secondary!">Zobacz</p>

	</a>

	@php do_action('woocommerce_after_shop_loop_item') @endphp

</li>
@endif