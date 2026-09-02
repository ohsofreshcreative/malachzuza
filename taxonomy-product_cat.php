<?php
/**
 * Szablon dla archiwów kategorii produktów (np. /product-category/nazwa/).
 * Współdzieli widok ze stroną sklepu (obsługuje sidebar i pola ACF kategorii).
 */
echo \Roots\view('woocommerce.archive-product')->render();