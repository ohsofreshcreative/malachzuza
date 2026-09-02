@php
/**
* Szablon pojedynczego produktu nadpisany w Blade.
* Klasy Tailwinda dodane są bezpośrednio do sekcji galerii i opisu.
*/
defined('ABSPATH') || exit;

// Pobranie wartości z opcji ACF
$g_values = get_field('g_values', 'option');
$r_values = get_field('r_values', 'option');

// FAQ produktu (nad sekcją powiązanych produktów, sama sekcja related jest usunięta)
$product_faq = get_field('product_faq') ?: [];

// Akcje wywoływane przed strukturą produktu
if (post_password_required()) {
echo get_the_password_form(); // WPCS: XSS ok.
return;
}
@endphp

<div id="product-{{ the_ID() }}" {{ wc_product_class('', $product) }}>

	<div class="__product grid grid-cols-1 lg:grid-cols-2 gap-10">

		<div class="">
			@php
			/**
			* Hook: woocommerce_before_single_product_summary.
			*
			* @hooked woocommerce_show_product_sale_flash - 10
			* @hooked woocommerce_show_product_images - 20 (Galeria)
			*/
			do_action('woocommerce_before_single_product_summary');
			@endphp
		</div>

		{{-- KOLUMNA PRAWA: Tytuł, Cena, Opis, Przycisk koszyka --}}
		<div class="">

			@php
			/**
			* Hook: woocommerce_single_product_summary.
			*
			* @hooked woocommerce_template_single_title - 5
			* @hooked woocommerce_template_single_rating - 10
			* @hooked woocommerce_template_single_price - 10
			* @hooked woocommerce_template_single_excerpt - 20
			* @hooked woocommerce_template_single_add_to_cart - 30
			* @hooked woocommerce_template_single_meta - 40
			* @hooked woocommerce_template_single_sharing - 50
			* @hooked WC_Structured_Data::generate_product_data() - 60
			*/
			do_action('woocommerce_single_product_summary');
			@endphp
		</div>

	</div>

	<div class="w-full mt-16 pt-16 border-t border-gray-100 !float-none !clear-both">
		@php
		/**
		* Hook: woocommerce_after_single_product_summary.
		*
		* @hooked woocommerce_output_product_data_tabs - 10
		* @hooked woocommerce_upsell_display - 15
		*/
		do_action('woocommerce_after_single_product_summary');
		@endphp
	</div>

	@if (!empty($product_faq))
	<section class="b-faq relative left-1/2 w-screen -translate-x-1/2 bg-white -smt section-py">
		<div class="__wrapper c-main">
			<h6>Najczęściej zadawane pytania</h6>
			<div class="tabs-wrapper flex flex-col mt-4">
				@foreach ($product_faq as $item)
				<div class="tabs bg-white border border-secondary-100 rounded-xl h-max">
					<input class="tab-check" type="checkbox" name="radio-product-faq" id="product-faq-check{{ $loop->index }}">
					<label class="tabs-label flex items-center justify-between" for="product-faq-check{{ $loop->index }}">
						<div class="flex items-center gap-4">
							<p class="!text-lg !font-semibold font-header">{{ $item['question'] }}</p>
						</div>

						<span class="__icon" aria-hidden="true">
							<span class="__plus text-secondary">+</span>
							<span class="__minus text-secondary">−</span>
						</span>
					</label>

					<div class="tabs-content">
						{!! $item['answer'] !!}
					</div>
				</div>
				@endforeach
			</div>
		</div>
	</section>
	@endif

</div>

@php do_action('woocommerce_after_single_product'); @endphp
