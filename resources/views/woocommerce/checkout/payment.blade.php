@php
defined('ABSPATH') || exit;

if (! wp_doing_ajax()) {
	do_action('woocommerce_review_order_before_payment');
}
@endphp

{{-- Nadpisuje woocommerce/templates/checkout/payment.php - lekko wydzielone tłem od tabeli podsumowania powyżej. --}}
<div id="payment" class="woocommerce-checkout-payment bg-slate-50! rounded-xl border-t border-slate-200 mt-5 p-5">
	@if (WC()->cart && WC()->cart->needs_payment())
		<ul class="wc_payment_methods payment_methods methods" aria-label="{{ esc_attr__('Payment methods', 'woocommerce') }}">
			@if (! empty($available_gateways))
				@foreach ($available_gateways as $gateway)
					@php wc_get_template('checkout/payment-method.php', ['gateway' => $gateway]); @endphp
				@endforeach
			@else
				<li>
					@php
						wc_print_notice(
							apply_filters(
								'woocommerce_no_available_payment_methods_message',
								WC()->customer->get_billing_country()
									? esc_html__('Sorry, it seems that there are no available payment methods. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce')
									: esc_html__('Please fill in your details above to see available payment methods.', 'woocommerce')
							),
							'notice'
						);
					@endphp
				</li>
			@endif
		</ul>
	@endif

	<div class="form-row place-order">
		<noscript>
			{!! sprintf(esc_html__('Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce'), '<em>', '</em>') !!}
			<br/>
			<button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="{{ esc_attr__('Update totals', 'woocommerce') }}">{{ esc_html__('Update totals', 'woocommerce') }}</button>
		</noscript>

		<div class="woocommerce-privacy-policy-text-wrapper">
			@php wc_get_template('checkout/terms.php'); @endphp
		</div>

		@php do_action('woocommerce_review_order_before_submit'); @endphp

		{!! apply_filters('woocommerce_order_button_html', '<button type="submit" class="button alt w-full text-center block" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr($order_button_text) . '" data-value="' . esc_attr($order_button_text) . '">' . esc_html($order_button_text) . '</button>') !!}

		@php do_action('woocommerce_review_order_after_submit'); @endphp

		@php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); @endphp
	</div>
</div>

@php
if (! wp_doing_ajax()) {
	do_action('woocommerce_review_order_after_payment');
}
@endphp
