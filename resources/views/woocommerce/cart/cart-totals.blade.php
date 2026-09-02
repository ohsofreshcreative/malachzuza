@php
defined('ABSPATH') || exit;
@endphp

{{-- Nadpisuje woocommerce/templates/cart/cart-totals.php - ta sama struktura/klasy (dla AJAX i innych wtyczek), wygląd przez Tailwind zamiast SCSS. --}}
<div class="cart_totals {{ WC()->customer->has_calculated_shipping() ? 'calculated_shipping' : '' }} bg-white border border-slate-200 rounded-xl p-6">

	@php do_action('woocommerce_before_cart_totals'); @endphp

	<h2 class="text-[22px] font-bold text-slate-900 mb-5 pb-3 border-b-2 border-slate-200">{{ esc_html__('Cart totals', 'woocommerce') }}</h2>

	<table cellspacing="0" class="shop_table shop_table_responsive w-full border-collapse">

		<tr class="cart-subtotal">
			<th class="text-left font-normal text-sm text-slate-500 py-3 pr-3 border-b border-slate-200">{{ esc_html__('Subtotal', 'woocommerce') }}</th>
			<td class="text-right py-3 border-b border-slate-200 text-sm text-slate-500" data-title="{{ esc_attr__('Subtotal', 'woocommerce') }}">
				@php wc_cart_totals_subtotal_html(); @endphp
			</td>
		</tr>

		@foreach (WC()->cart->get_coupons() as $code => $coupon)
			<tr class="cart-discount coupon-{{ esc_attr(sanitize_title($code)) }}">
				<th class="text-left font-normal text-sm text-slate-500 py-3 pr-3 border-b border-slate-200">
					@php wc_cart_totals_coupon_label($coupon); @endphp
				</th>
				<td class="text-right py-3 border-b border-slate-200 text-sm text-slate-500" data-title="{{ esc_attr(wc_cart_totals_coupon_label($coupon, false)) }}">
					@php wc_cart_totals_coupon_html($coupon); @endphp
				</td>
			</tr>
		@endforeach

		@if (WC()->cart->needs_shipping() && WC()->cart->show_shipping())
			@php do_action('woocommerce_cart_totals_before_shipping'); @endphp
			@php wc_cart_totals_shipping_html(); @endphp
			@php do_action('woocommerce_cart_totals_after_shipping'); @endphp
		@elseif (WC()->cart->needs_shipping() && get_option('woocommerce_enable_shipping_calc') === 'yes')
			<tr class="shipping">
				<th class="text-left font-normal text-sm text-slate-500 py-3 pr-3 border-b border-slate-200">{{ esc_html__('Shipping', 'woocommerce') }}</th>
				<td class="text-right py-3 border-b border-slate-200 text-sm text-slate-500" data-title="{{ esc_attr__('Shipping', 'woocommerce') }}">
					@php woocommerce_shipping_calculator(); @endphp
				</td>
			</tr>
		@endif

		@foreach (WC()->cart->get_fees() as $fee)
			<tr class="fee">
				<th class="text-left font-normal text-sm text-slate-500 py-3 pr-3 border-b border-slate-200">{{ esc_html($fee->name) }}</th>
				<td class="text-right py-3 border-b border-slate-200 text-sm text-slate-500" data-title="{{ esc_attr($fee->name) }}">
					@php wc_cart_totals_fee_html($fee); @endphp
				</td>
			</tr>
		@endforeach

		@if (wc_tax_enabled() && ! WC()->cart->display_prices_including_tax())
			@php
				$taxable_address = WC()->customer->get_taxable_address();
				$estimated_text = '';
				if (WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()) {
					$estimated_text = sprintf(' <small>' . esc_html__('(estimated for %s)', 'woocommerce') . '</small>', WC()->countries->estimated_for_prefix($taxable_address[0]) . WC()->countries->countries[$taxable_address[0]]);
				}
			@endphp

			@if (get_option('woocommerce_tax_total_display') === 'itemized')
				@foreach (WC()->cart->get_tax_totals() as $code => $tax)
					<tr class="tax-rate tax-rate-{{ esc_attr(sanitize_title($code)) }}">
						<th class="text-left font-normal text-sm text-slate-500 py-3 pr-3 border-b border-slate-200">{!! esc_html($tax->label) . $estimated_text !!}</th>
						<td class="text-right py-3 border-b border-slate-200 text-sm text-slate-500" data-title="{{ esc_attr($tax->label) }}">{!! wp_kses_post($tax->formatted_amount) !!}</td>
					</tr>
				@endforeach
			@else
				<tr class="tax-total">
					<th class="text-left font-normal text-sm text-slate-500 py-3 pr-3 border-b border-slate-200">{!! esc_html(WC()->countries->tax_or_vat()) . $estimated_text !!}</th>
					<td class="text-right py-3 border-b border-slate-200 text-sm text-slate-500" data-title="{{ esc_attr(WC()->countries->tax_or_vat()) }}">
						@php wc_cart_totals_taxes_total_html(); @endphp
					</td>
				</tr>
			@endif
		@endif

		@php do_action('woocommerce_cart_totals_before_order_total'); @endphp

		<tr class="order-total">
			<th class="text-left text-base font-bold text-slate-900 py-3 pr-3">{{ esc_html__('Total', 'woocommerce') }}</th>
			<td class="text-right py-3 text-base font-bold text-slate-900" data-title="{{ esc_attr__('Total', 'woocommerce') }}">
				@php wc_cart_totals_order_total_html(); @endphp
			</td>
		</tr>

		@php do_action('woocommerce_cart_totals_after_order_total'); @endphp

	</table>

	<div class="wc-proceed-to-checkout mt-6">
		@php do_action('woocommerce_proceed_to_checkout'); @endphp
	</div>

	@php do_action('woocommerce_after_cart_totals'); @endphp

</div>
