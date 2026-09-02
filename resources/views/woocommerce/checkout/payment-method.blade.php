@php
if (! defined('ABSPATH')) {
	exit;
}
@endphp

{{-- Nadpisuje woocommerce/templates/checkout/payment-method.php - jedna metoda płatności na liście. --}}
<li class="wc_payment_method payment_method_{{ esc_attr($gateway->id) }} py-3 border-b border-slate-200 last:border-b-0 last:pb-0">
	<div class="flex items-center gap-2">
		<input
			id="payment_method_{{ esc_attr($gateway->id) }}"
			type="radio"
			class="input-radio w-4 h-4 shrink-0 accent-primary cursor-pointer"
			name="payment_method"
			value="{{ esc_attr($gateway->id) }}"
			{!! checked($gateway->chosen, true, false) !!}
			data-order_button_text="{{ esc_attr($gateway->order_button_text) }}"
		/>

		<label for="payment_method_{{ esc_attr($gateway->id) }}" class="text-sm font-semibold text-slate-900 cursor-pointer">
			{!! $gateway->get_title() !!} {!! $gateway->get_icon() !!}
		</label>
	</div>

	@if ($gateway->has_fields() || $gateway->get_description())
		<div class="payment_box payment_method_{{ esc_attr($gateway->id) }} [&_p:last-child]:mb-0" @if (! $gateway->chosen) style="display:none;" @endif>
			@php $gateway->payment_fields(); @endphp
		</div>
	@endif
</li>
