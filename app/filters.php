<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});


add_action('pre_get_posts', function ($q) {
  if (is_admin() || !$q->is_main_query()) {
    return;
  }
  if ($q->is_search()) {
    $allowed_post_types = ['produkty', 'post'];
    if (!empty($_GET['post_type']) && in_array($_GET['post_type'], $allowed_post_types, true)) {
      $q->set('post_type', $_GET['post_type']);
    }
  }
});


/*--- BREACRUMB SEPARATOR ---*/
add_filter( 'woocommerce_breadcrumb_defaults', function ( $defaults ) {
    // Opakowujemy separator w element <span> z własną klasą CSS.
    $defaults['delimiter'] = '<span class="__separator">•</span>';
    return $defaults;
} );


/*--- UKRYCIE PRZEDZIAŁU CEN NA STRONIE PRODUKTU WARIANTOWEGO / USUNIĘCIE PRODUCT_META / RELATED PRODUCTS ---*/
// Cena konkretnego wariantu i tak pokazuje się nad przyciskiem "Dodaj do koszyka" (single-product/add-to-cart/variation.php).
// SKU pokazuje się teraz nad nazwą produktu (single-product/title.php), więc product_meta (SKU/kategorie/tagi) nie jest potrzebne.
// Related products zastąpione sekcją FAQ produktu (content-single-product.blade.php).
// Hook 'woocommerce_before_single_product' nie jest wywoływany w tym motywie, dlatego sprawdzamy produkt na 'template_redirect'.
add_action('template_redirect', function () {
    if (!is_product()) {
        return;
    }

    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

    $product = wc_get_product(get_queried_object_id());

    if ($product instanceof \WC_Product && $product->is_type('variable')) {
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
    }
});


/*--- DODATKOWE ZAKŁADKI PRODUKTU (ACF: product_tabs) ---*/
add_filter('woocommerce_product_tabs', function ($tabs) {
    global $product;

    if (!$product instanceof \WC_Product) {
        return $tabs;
    }

    // "Opis produktu" zawsze jako pierwsza zakładka (WooCommerce sam ją ukrywa, gdy opis jest pusty).
    if (isset($tabs['description'])) {
        $tabs['description']['title'] = 'Opis produktu';
        $tabs['description']['priority'] = 5;
    }

    $rows = get_field('product_tabs', $product->get_id()) ?: [];
    $priority = 12;

    foreach ($rows as $index => $row) {
        if (empty($row['tab_title'])) {
            continue;
        }

        $tabs['product_tab_' . $index] = [
            'title' => $row['tab_title'],
            'priority' => $priority,
            'callback' => function () use ($row) {
                echo \Roots\view('partials.product-tab-' . $row['acf_fc_layout'], ['row' => $row])->render();
            },
        ];

        $priority++;
    }

    // Dla produktów z przypisanymi kolorami RAL zakładka "Informacje dodatkowe" (domyślna z WooCommerce)
    // i tak pokazywała tylko surową, przecinkową listę ~100-200 kodów RAL - zastępujemy ją siatką
    // kolorowych kafelków w tym samym miejscu (ta sama pozycja co domyślna zakładka - priority 20).
    $colorTaxonomy = wc_attribute_taxonomy_name(\App\Woo\RalAttributes::ATTR_COLOR);
    $colorTerms = wc_get_product_terms($product->get_id(), $colorTaxonomy, ['fields' => 'all']);

    if (! empty($colorTerms)) {
        unset($tabs['additional_information']);

        $colors = array_map(function ($term) {
            $code = \App\Support\RalColors::codeFromTermName($term->name);

            return [
                'label' => $term->name,
                'hex' => $code ? \App\Support\RalColors::hex($code) : '#CCCCCC',
            ];
        }, $colorTerms);

        $tabs['ral_palette'] = [
            'title' => 'Podgląd palety RAL',
            'priority' => 20,
            'callback' => function () use ($colors) {
                echo \Roots\view('partials.product-tab-ral-palette', ['colors' => $colors])->render();
            },
        ];
    }

    return $tabs;
});


/*--- USUNIĘCIE ELEMENTÓW Z ARCHIWUM SKLEPU / KATEGORII ---*/
add_action('wp', function () {
    if (!is_shop() && !is_product_category() && !is_product_tag()) {
        return;
    }

    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
    remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
    remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
});



/**
 * Override WooCommerce Coming Soon template
 */
add_filter('woocommerce_coming_soon_template', function ($template) {
    $custom_template = get_theme_file_path('resources/views/patterns/coming-soon.php');
    
    if (file_exists($custom_template)) {
        return $custom_template;
    }
    
    return $template;
});


/*--- CHANGE EDIT SECTION ---*/


add_filter('gettext', function ($translated, $text, $domain) {
    if (
        is_admin() &&
        $text === 'Open expanded editor'
    ) {
        return 'Edytuj sekcję';
    }

    return $translated;
}, 10, 3);


add_filter('acf/blocks/default_expanded_editor_button_text', function () {
    return 'Edytuj sekcję';
});


/*--- DYNAMICZNE FRAGMENTY DLA KOSZYKA (SLAJD DRAWER) ---*/

add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
    // 1. Renderujemy ikonę na pulpit z pliku Blade (jeśli chcemy, lub trzymamy prosty kod w filters)
    ob_start();
    ?>
    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" @click.prevent="window.dispatchEvent(new CustomEvent('cart-open'))" class="relative hover:opacity-80 transition-opacity cart-custom-location-desktop">
        <img src="<?php echo get_template_directory_uri(); ?>/resources/images/cart.svg" alt="Koszyk" />
        <?php if (WC()->cart && WC()->cart->get_cart_contents_count() > 0) : ?>
            <span class="absolute -top-2 -right-2 bg-primary text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full cart-count">
                <?php echo WC()->cart->get_cart_contents_count(); ?>
            </span>
        <?php endif; ?>
    </a>
    <?php
    $fragments['a.cart-custom-location-desktop'] = ob_get_clean();

    // 2. Renderujemy ikonę na komórkę
    ob_start();
    ?>
    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" @click.prevent="window.dispatchEvent(new CustomEvent('cart-open'))" class="relative p-2 text-white hover:opacity-80 transition-opacity cart-custom-location-mobile">
        <img src="<?php echo get_template_directory_uri(); ?>/resources/images/cart.svg" class="w-6 h-6" alt="Koszyk" />
        <?php if (WC()->cart && WC()->cart->get_cart_contents_count() > 0) : ?>
            <span class="absolute top-1 right-1 bg-secondary text-primary text-[9px] font-bold w-4.5 h-4.5 flex items-center justify-center rounded-full cart-count">
                <?php echo WC()->cart->get_cart_contents_count(); ?>
            </span>
        <?php endif; ?>
    </a>
    <?php
    $fragments['a.cart-custom-location-mobile'] = ob_get_clean();

    // 3. RENDER ZAWARTOSCI SZUFLADY PROSTO Z BLADE! (BEZ ODPALANIA HTML)
    $fragments['div.cart-drawer-ajax-content'] = '<div class="flex-1 flex flex-col overflow-hidden cart-drawer-ajax-content">' . \Roots\view('partials.cart-drawer-content')->render() . '</div>';

    // 4. Cyferka przy nagłówku Drawera
    $fragments['span.cart-count-badge'] = '<span class="bg-secondary/15 text-secondary text-xs px-2.5 py-0.5 rounded-full cart-count-badge">' . WC()->cart->get_cart_contents_count() . '</span>';

    return $fragments;
});


/*--- WYKRYWANIE DODANIA DO KOSZYKA (DLA EMBEDDED REFRESH / POST) ---*/

add_action('woocommerce_add_to_cart', function () {
    if (!defined('JUST_ADDED_TO_CART')) {
        define('JUST_ADDED_TO_CART', true);
    }
}, 10);


add_action('wp_enqueue_scripts', function () {
    if (function_exists('is_woocommerce')) {
        wp_enqueue_script('wc-cart-fragments');
    }
}, 99);
