@php
defined('ABSPATH') || exit;
@endphp

{{-- Nadpisuje woocommerce/templates/checkout/review-order.php - stoi wewnątrz karty #order_review (patrz form-checkout.blade.php). --}}
<table class="shop_table woocommerce-checkout-review-order-table w-full border-collapse">
	<thead>
		<tr>
			<th class="product-name text-left text-[11px] font-bold uppercase tracking-wide text-slate-500 pb-3 border-b border-slate-200">{{ esc_html__('Product', 'woocommerce') }}</th>
			<th class="product-total text-right text-[11px] font-bold uppercase tracking-wide text-slate-500 pb-3 border-b border-slate-200">{{ esc_html__('Subtotal', 'woocommerce') }}</th>
		</tr>
	</thead>
	<tbody>
		@php do_action('woocommerce_review_order_before_cart_contents'); @endphp

		@foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item)
			@php
				$_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
				$visible = apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key);
			@endphp

			@if ($_product instanceof WC_Product && $_product->exists() && $cart_item['quantity'] > 0 && $visible)
				<tr class="{{ esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)) }}">
					<td class="product-name text-left text-sm text-slate-700 py-3 pr-3 border-b border-slate-200">
						{!! wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key)) !!}&nbsp;
						{!! apply_filters('woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity text-primary font-bold">' . sprintf('&times;&nbsp;%s', $cart_item['quantity']) . '</strong>', $cart_item, $cart_item_key) !!}
						{!! wc_get_formatted_cart_item_data($cart_item) !!}
					</td>
					<td class="product-total text-right text-sm text-slate-500 py-3 border-b border-slate-200">
						{!! apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key) !!}
					</td>
				</tr>
			@endif
		@endforeach

		@php do_action('woocommerce_review_order_after_cart_contents'); @endphp
	</tbody>
	<tfoot>

		<tr class="cart-subtotal">
			<th class="text-left text-sm text-slate-500 font-normal py-3 pr-3 border-b border-slate-200">{{ esc_html__('Subtotal', 'woocommerce') }}</th>
			<td class="text-right text-sm text-slate-500 py-3 border-b border-slate-200">
				@php wc_cart_totals_subtotal_html(); @endphp
			</td>
		</tr>

		@foreach (WC()->cart->get_coupons() as $code => $coupon)
			<tr class="cart-discount coupon-{{ esc_attr(sanitize_title($code)) }}">
				<th class="text-left text-sm text-slate-500 font-normal py-3 pr-3 border-b border-slate-200">
					@php wc_cart_totals_coupon_label($coupon); @endphp
				</th>
				<td class="text-right text-sm text-slate-500 py-3 border-b border-slate-200">
					@php wc_cart_totals_coupon_html($coupon); @endphp
				</td>
			</tr>
		@endforeach

		@if (WC()->cart->needs_shipping() && WC()->cart->show_shipping())
			@php do_action('woocommerce_review_order_before_shipping'); @endphp
			@php wc_cart_totals_shipping_html(); @endphp
			@php do_action('woocommerce_review_order_after_shipping'); @endphp
		@endif

		@foreach (WC()->cart->get_fees() as $fee)
			<tr class="fee">
				<th class="text-left text-sm text-slate-500 font-normal py-3 pr-3 border-b border-slate-200">{{ esc_html($fee->name) }}</th>
				<td class="text-right text-sm text-slate-500 py-3 border-b border-slate-200">
					@php wc_cart_totals_fee_html($fee); @endphp
				</td>
			</tr>
		@endforeach

		@if (wc_tax_enabled() && ! WC()->cart->display_prices_including_tax())
			@if (get_option('woocommerce_tax_total_display') === 'itemized')
				@foreach (WC()->cart->get_tax_totals() as $code => $tax)
					<tr class="tax-rate tax-rate-{{ esc_attr(sanitize_title($code)) }}">
						<th class="text-left text-sm text-slate-500 font-normal py-3 pr-3 border-b border-slate-200">{{ esc_html($tax->label) }}</th>
						<td class="text-right text-sm text-slate-500 py-3 border-b border-slate-200">{!! wp_kses_post($tax->formatted_amount) !!}</td>
					</tr>
				@endforeach
			@else
				<tr class="tax-total">
					<th class="text-left text-sm text-slate-500 font-normal py-3 pr-3 border-b border-slate-200">{{ esc_html(WC()->countries->tax_or_vat()) }}</th>
					<td class="text-right text-sm text-slate-500 py-3 border-b border-slate-200">
						@php wc_cart_totals_taxes_total_html(); @endphp
					</td>
				</tr>
			@endif
		@endif

		@php do_action('woocommerce_review_order_before_order_total'); @endphp

		<tr class="order-total">
			<th class="text-left text-base font-bold text-slate-900 py-3 pr-3">{{ esc_html__('Total', 'woocommerce') }}</th>
			<td class="text-right text-base font-bold text-slate-900 py-3">
				@php wc_cart_totals_order_total_html(); @endphp
			</td>
		</tr>

		@php do_action('woocommerce_review_order_after_order_total'); @endphp

	</tfoot>
</table>
