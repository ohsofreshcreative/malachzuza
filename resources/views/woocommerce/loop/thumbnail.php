@php
global $product;
@endphp

@php do_action('woocommerce_product_loop_start', $product) @endphp

<div class="__thumb">
    {!! woocommerce_get_product_thumbnail() !!}
    @php do_action('woocommerce_product_loop_sale_flash') @endphp
</div>