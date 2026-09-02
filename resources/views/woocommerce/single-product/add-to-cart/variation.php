<?php
/**
 * Single variation display
 *
 * This is a javascript-based template for single variations (see https://codex.wordpress.org/Javascript_Reference/wp.template).
 * The values will be dynamically replaced after selecting attributes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

defined( 'ABSPATH' ) || exit;

?>
<script type="text/template" id="tmpl-variation-template">
    <div class="woocommerce-variation-description">{{{ data.variation.variation_description }}}</div>
    <div class="woocommerce-variation-price"> Cena: {{{ data.variation.price_html }}}</div>
    
  
        <div class="woocommerce-variation-price-net price-net text-lg text-gray-500 mt-1 !font-medium [&_bdi]:!text-lg [&_bdi]:!text-gray-500 [&_bdi]:!font-medium [&_bdi_span]:!text-lg [&_bdi_span]:!text-gray-500 [&_bdi_span]:!font-medium">
           {{{ data.variation.price_net_html }}} 
        </div>


    <div class="woocommerce-variation-availability">{{{ data.variation.availability_html }}}</div>
</script>
<script type="text/template" id="tmpl-unavailable-variation-template">
	<p role="alert"><?php esc_html_e( 'Sorry, this product is unavailable. Please choose a different combination.', 'woocommerce' ); ?></p>
</script>
