<?php

namespace App\Woo;

use App\Support\RalColors;

defined('ABSPATH') || exit;

/**
 * Atrybuty i wycena kolorów RAL na produktach zmiennych WooCommerce.
 *
 * Konfiguracja produktu (jednorazowo, ręcznie w adminie):
 * 1. WP Admin -> Ustawienia -> Ceny RAL: ustaw ceny/kg RC1-RC5 i zaznacz "Zsynchronizuj kolory RAL".
 * 2. Produkty -> Atrybuty: powstaną "Kolor RAL" i "Waga" - w razie potrzeby dodaj więcej wag. Klikając
 *    "Konfiguruj terminy" przy "Kolor RAL" można ręcznie zmienić grupę cenową (RC1-RC5) pojedynczego
 *    koloru w jego edycji (pole "Grupa cenowa RAL") albo zaznaczyć checkboxami wiele kolorów naraz i
 *    z listy "Akcje zbiorcze" wybrać "Ustaw grupę: RCx" / "Wyczyść przypisanie grupy" - nadpisuje to
 *    domyślne przypisanie z RalColors::GROUPS zarówno przy wycenie wariantów, jak i przy zaznaczaniu
 *    grup na produkcie (patrz colorGroupForTerm()).
 * 3. Na produkcie (typ "Produkt zmienny"): w polu "Grupy kolorów RAL" (zakładka Ustawienia produktu)
 *    zaznacz potrzebne grupy RC1-RC5 - po zapisaniu atrybut "Kolor RAL" uzupełni się automatycznie
 *    wszystkimi kolorami z tych grup i zostanie oznaczony jako używany do wariantów (patrz
 *    App\Fields\RalColorGroup i RalAttributes::assignColorGroups). W zakładce Atrybuty wybierz też
 *    potrzebne wagi i zaznacz przy nich "Użyj do wariantów", zapisz.
 * 4. W zakładce Warianty -> "Utwórz warianty ze wszystkich atrybutów" - cena każdego wariantu (waga x cena/kg
 *    grupy RAL) liczy się automatycznie od razu przy tworzeniu (hook `product_variation_linked`) oraz przy
 *    każdym późniejszym zapisie wariantu (hook `woocommerce_save_product_variation`).
 * 5. Jeśli zmienią się ceny RC1-RC5, zaznacz "Przelicz teraz ceny wariantów RAL" w Ustawienia -> Ceny RAL,
 *    żeby przeliczyć ceny już istniejących wariantów.
 */
class RalAttributes
{
	public const ATTR_COLOR = 'kolor-ral';
	public const ATTR_WEIGHT = 'waga';
	public const DEFAULT_WEIGHTS = [5, 10, 30];

	/**
	 * Klucz meta terminu (na "Kolor RAL"), w którym trzyma się grupę cenową RC1-RC5 przypisaną
	 * ręcznie w adminie - nadpisuje domyślne przypisanie z RalColors::GROUPS dla danego koloru.
	 */
	public const META_GROUP = 'ral_price_group';

	public function __construct()
	{
		add_action('init', [$this, 'registerAttributes'], 20);
		add_action('acf/save_post', [$this, 'handleOptionsSave'], 20);
		add_action('acf/save_post', [$this, 'assignColorGroups'], 20);
		add_action('woocommerce_save_product_variation', [$this, 'autoPriceVariation'], 10, 2);
		// "Utwórz warianty ze wszystkich atrybutów" tworzy warianty przez create_all_product_variations(),
		// które NIE odpala woocommerce_save_product_variation (tylko product_variation_linked) - bez tego
		// hooka nowo utworzone warianty zostawałyby z ceną 0, dopóki ktoś ręcznie nie zapisałby każdego z osobna.
		add_action('product_variation_linked', [$this, 'autoPriceVariation'], 10, 1);

		// Pole "Grupa cenowa RAL" na ekranie dodawania/edycji terminu atrybutu "Kolor RAL" (Produkty ->
		// Atrybuty -> Kolor RAL -> Konfiguruj terminy) - pozwala ręcznie przypisać/przenieść dany kolor
		// do innej grupy RC1-RC5 niż domyślna z RalColors::GROUPS. Nazwa taksonomii jest zawsze "pa_" +
		// slug atrybutu (konwencja WooCommerce), więc można ją tu bezpiecznie zbudować bez czekania na init.
		$colorTaxonomy = 'pa_' . self::ATTR_COLOR;
		add_action("{$colorTaxonomy}_add_form_fields", [$this, 'renderColorGroupFieldAdd']);
		add_action("{$colorTaxonomy}_edit_form_fields", [$this, 'renderColorGroupFieldEdit']);
		add_action("created_{$colorTaxonomy}", [$this, 'saveColorGroupField']);
		add_action("edited_{$colorTaxonomy}", [$this, 'saveColorGroupField']);

		// Masowe przypisanie grupy na liście terminów "Kolor RAL" (zaznacz kolory checkboxami -> wybierz
		// akcję "Ustaw grupę: RCx" z listy Akcje zbiorcze) - żeby nie trzeba było wchodzić w edycję
		// każdego koloru z osobna. Ekran listy terminów ma zawsze screen id "edit-{taksonomia}".
		add_filter("bulk_actions-edit-{$colorTaxonomy}", [$this, 'addColorGroupBulkActions']);
		add_filter("handle_bulk_actions-edit-{$colorTaxonomy}", [$this, 'handleColorGroupBulkAction'], 10, 3);
		add_action('admin_notices', [$this, 'renderColorGroupBulkActionNotice']);

		// Kolumna "Grupa cenowa RAL" na liście terminów koloru RAL - żeby od razu było widać przypisanie
		// bez wchodzenia w edycję każdego koloru.
		add_filter("manage_edit-{$colorTaxonomy}_columns", [$this, 'addColorGroupColumn'], 20);
		add_filter("manage_{$colorTaxonomy}_custom_column", [$this, 'renderColorGroupColumn'], 10, 3);
	}

	/**
	 * Zakłada globalne atrybuty WooCommerce "Kolor RAL" i "Waga", jeśli jeszcze nie istnieją.
	 */
	public function registerAttributes(): void
	{
		if (! function_exists('wc_get_attribute_taxonomies') || ! function_exists('wc_create_attribute')) {
			return;
		}

		$existing = wp_list_pluck(wc_get_attribute_taxonomies(), 'attribute_name');
		$created = false;

		if (! in_array(self::ATTR_COLOR, $existing, true)) {
			wc_create_attribute([
				'name' => 'Kolor RAL',
				'slug' => self::ATTR_COLOR,
				'type' => 'color',
				'order_by' => 'name_num',
				'has_archives' => false,
			]);
			$created = true;
		}

		if (! in_array(self::ATTR_WEIGHT, $existing, true)) {
			wc_create_attribute([
				'name' => 'Waga',
				'slug' => self::ATTR_WEIGHT,
				'type' => 'select',
				'order_by' => 'menu_order',
				'has_archives' => false,
			]);
			$created = true;
		}

		if (! $created) {
			return;
		}

		// wc_get_attribute_taxonomies() (użyte wyżej) cache'uje wynik w wp_cache na czas requestu -
		// samo delete_transient() tego nie czyści, więc trzeba unieważnić grupę cache "woocommerce-attributes",
		// inaczej WC_Post_Types::register_taxonomies() niżej użyje starej (sprzed dodania) listy atrybutów.
		if (class_exists('WC_Cache_Helper')) {
			\WC_Cache_Helper::invalidate_cache_group('woocommerce-attributes');
		}

		// WC rejestruje taksonomie pa_* na init@5 na podstawie stanu bazy sprzed powyższego wc_create_attribute()
		// (my odpalamy się na init@20) - bez tego nowa taksonomia nie istniałaby aż do kolejnego requestu.
		if (class_exists('WC_Post_Types')) {
			\WC_Post_Types::register_taxonomies();
		}
	}

	/**
	 * Obsługuje przełączniki "Zsynchronizuj kolory RAL" / "Przelicz teraz ceny" na stronie opcji.
	 */
	public function handleOptionsSave($postId): void
	{
		if ($postId !== 'options' || ! function_exists('get_field')) {
			return;
		}

		if (get_field('sync_colors_now', 'option')) {
			$this->syncColorTerms();
			update_field('sync_colors_now', 0, 'option');
		}

		if (get_field('recalculate_now', 'option')) {
			$this->recalculateAllVariations();
			update_field('recalculate_now', 0, 'option');
		}
	}

	/**
	 * Hook `acf/save_post` - gdy na produkcie zaznaczono grupy kolorów RAL (pole "ral_color_groups"),
	 * uzupełnia atrybut "Kolor RAL" wszystkimi kolorami z tych grup i oznacza go jako używany do wariantów.
	 * Nadpisuje ręczny wybór kolorów w zakładce Atrybuty - puste pole grup zostawia go bez zmian.
	 */
	public function assignColorGroups($postId): void
	{
		if (! is_numeric($postId) || get_post_type((int) $postId) !== 'product' || ! function_exists('get_field')) {
			return;
		}

		$postId = (int) $postId;
		$groups = array_intersect((array) get_field('ral_color_groups', $postId), array_keys(RalColors::GROUPS));

		if (empty($groups)) {
			return;
		}

		$colorTaxonomy = wc_attribute_taxonomy_name(self::ATTR_COLOR);

		if (! taxonomy_exists($colorTaxonomy)) {
			return;
		}

		// Przeszukuje realne przypisanie grupy per termin (colorGroupForTerm - respektuje ręczne
		// nadpisanie z edycji terminu), a nie tylko domyślną mapę RalColors::GROUPS - dzięki temu
		// przeniesienie koloru do innej grupy w Produkty -> Atrybuty od razu działa też tutaj.
		$terms = get_terms(['taxonomy' => $colorTaxonomy, 'hide_empty' => false]);
		$termIds = [];

		foreach ($terms as $term) {
			$code = RalColors::codeFromTermName($term->name);
			$group = $this->colorGroupForTerm($term->term_id, $code);

			if ($group && in_array($group, $groups, true)) {
				$termIds[] = $term->term_id;
			}
		}

		if (empty($termIds)) {
			return;
		}

		wp_set_object_terms($postId, $termIds, $colorTaxonomy);

		$product = wc_get_product($postId);

		if (! $product) {
			return;
		}

		$attributes = $product->get_attributes();
		$existing = $attributes[$colorTaxonomy] ?? null;

		$attribute = new \WC_Product_Attribute();
		$attribute->set_id(wc_attribute_taxonomy_id_by_name(self::ATTR_COLOR));
		$attribute->set_name($colorTaxonomy);
		$attribute->set_options($termIds);
		$attribute->set_position($existing ? $existing->get_position() : 0);
		$attribute->set_visible(true);
		$attribute->set_variation(true);

		$attributes[$colorTaxonomy] = $attribute;
		$product->set_attributes($attributes);
		$product->save();
	}

	/**
	 * Pole "Grupa cenowa RAL" na ekranie dodawania nowego terminu.
	 */
	public function renderColorGroupFieldAdd(): void
	{
		echo '<div class="form-field">';
		$this->renderColorGroupSelect(null);
		echo '</div>';
	}

	/**
	 * Pole "Grupa cenowa RAL" na ekranie edycji istniejącego terminu.
	 */
	public function renderColorGroupFieldEdit(\WP_Term $term): void
	{
		echo '<tr class="form-field"><th scope="row"><label for="ral_price_group">' . esc_html__('Grupa cenowa RAL', 'woocommerce') . '</label></th><td>';
		$this->renderColorGroupSelect($term);
		echo '</td></tr>';
	}

	/**
	 * Wspólny select RC1-RC5 dla ekranów dodawania/edycji terminu koloru RAL.
	 */
	private function renderColorGroupSelect(?\WP_Term $term): void
	{
		$current = $term ? get_term_meta($term->term_id, self::META_GROUP, true) : '';

		if ($term && ! $current) {
			echo '<label for="ral_price_group">' . esc_html__('Grupa cenowa RAL', 'woocommerce') . '</label>';
		}

		echo '<select name="' . esc_attr(self::META_GROUP) . '" id="ral_price_group">';
		echo '<option value="">' . esc_html__('- brak przypisania -', 'woocommerce') . '</option>';

		foreach (array_keys(RalColors::GROUPS) as $group) {
			$price = function_exists('get_field') ? get_field('price_' . strtolower($group), 'option') : null;
			$label = $price ? "{$group} ({$price} PLN/kg netto)" : $group;

			echo '<option value="' . esc_attr($group) . '" ' . selected($current, $group, false) . '>' . esc_html($label) . '</option>';
		}

		echo '</select>';
		echo '<p class="description">' . esc_html__('Decyduje, wg jakiej ceny za 1 kg (Ustawienia -> Ceny RAL) liczy się cena wariantów w tym kolorze oraz do której grupy trafia ten kolor po zaznaczeniu grupy na stronie produktu.', 'woocommerce') . '</p>';
	}

	/**
	 * Zapisuje wybraną grupę cenową jako meta terminu (hook `created_{tax}` / `edited_{tax}`).
	 */
	public function saveColorGroupField(int $termId): void
	{
		if (! isset($_POST[self::META_GROUP]) || ! current_user_can('manage_product_terms')) {
			return;
		}

		$group = sanitize_text_field(wp_unslash($_POST[self::META_GROUP]));

		if ($group === '') {
			delete_term_meta($termId, self::META_GROUP);

			return;
		}

		if (! array_key_exists($group, RalColors::GROUPS)) {
			return;
		}

		update_term_meta($termId, self::META_GROUP, $group);
	}

	/**
	 * Dokłada do listy "Akcje zbiorcze" na ekranie terminów koloru RAL opcje "Ustaw grupę: RCx"
	 * (po jednej na każdą grupę) oraz "Wyczyść przypisanie grupy" (powrót do domyślnej z RalColors::GROUPS).
	 */
	public function addColorGroupBulkActions(array $actions): array
	{
		foreach (array_keys(RalColors::GROUPS) as $group) {
			$price = function_exists('get_field') ? get_field('price_' . strtolower($group), 'option') : null;
			$actions["ral_set_group_{$group}"] = $price
				? "Ustaw grupę: {$group} ({$price} PLN/kg netto)"
				: "Ustaw grupę: {$group}";
		}

		$actions['ral_clear_group'] = 'Wyczyść przypisanie grupy (domyślna z arkusza)';

		return $actions;
	}

	/**
	 * Hook `handle_bulk_actions-edit-{tax}` - wykonuje zaznaczoną akcję zbiorczą ("Ustaw grupę: RCx" /
	 * "Wyczyść przypisanie grupy") na zaznaczonych terminach koloru RAL.
	 */
	public function handleColorGroupBulkAction($location, $action, $termIds)
	{
		if (! current_user_can('manage_product_terms')) {
			return $location;
		}

		if ($action === 'ral_clear_group') {
			foreach ((array) $termIds as $termId) {
				delete_term_meta((int) $termId, self::META_GROUP);
			}

			return add_query_arg('ral_group_bulk', 'clear:' . count((array) $termIds), $location);
		}

		if (! preg_match('/^ral_set_group_(RC[1-5])$/', (string) $action, $matches)) {
			return $location;
		}

		$group = $matches[1];

		foreach ((array) $termIds as $termId) {
			update_term_meta((int) $termId, self::META_GROUP, $group);
		}

		return add_query_arg('ral_group_bulk', $group . ':' . count((array) $termIds), $location);
	}

	/**
	 * Komunikat po wykonaniu akcji zbiorczej zmiany grupy koloru RAL.
	 */
	public function renderColorGroupBulkActionNotice(): void
	{
		if (empty($_GET['ral_group_bulk'])) {
			return;
		}

		[$group, $count] = array_pad(explode(':', sanitize_text_field(wp_unslash($_GET['ral_group_bulk']))), 2, '');
		$count = (int) $count;

		$message = $group === 'clear'
			? "Wyczyszczono przypisanie grupy dla {$count} kolorów RAL (wracają do domyślnej z arkusza)."
			: "Ustawiono grupę cenową {$group} dla {$count} kolorów RAL.";

		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($message) . '</p></div>';
	}

	/**
	 * Dokłada kolumnę "Grupa cenowa RAL" do listy terminów koloru RAL.
	 */
	public function addColorGroupColumn(array $columns): array
	{
		$columns['ral_price_group'] = 'Grupa cenowa RAL';

		return $columns;
	}

	/**
	 * Wypełnia kolumnę "Grupa cenowa RAL" - pokazuje aktualną grupę (RC1-RC5) i, jeśli to ręczne
	 * przypisanie (nie domyślne z RalColors::GROUPS), dopisuje o tym adnotację.
	 */
	public function renderColorGroupColumn($content, $column, $termId)
	{
		if ($column !== 'ral_price_group') {
			return $content;
		}

		$term = get_term($termId);
		$code = $term instanceof \WP_Term ? RalColors::codeFromTermName($term->name) : null;
		$group = $this->colorGroupForTerm((int) $termId, $code);

		if (! $group) {
			return '<span style="color:#a00;">- brak -</span>';
		}

		$isOverride = (bool) get_term_meta((int) $termId, self::META_GROUP, true);

		return esc_html($group) . ($isOverride ? ' <span style="color:#787c82;">(ręcznie)</span>' : '');
	}

	/**
	 * Zwraca grupę cenową RC1-RC5 dla terminu koloru RAL: ręczne przypisanie z meta terminu (jeśli
	 * ustawione), w przeciwnym razie domyślne przypisanie z RalColors::GROUPS dla podanego kodu.
	 */
	public function colorGroupForTerm(int $termId, ?int $code): ?string
	{
		$override = get_term_meta($termId, self::META_GROUP, true);

		if ($override && array_key_exists($override, RalColors::GROUPS)) {
			return $override;
		}

		return $code ? RalColors::group($code) : null;
	}

	/**
	 * Tworzy/aktualizuje terminy atrybutu "Kolor RAL" (z miniaturkami) i podstawowe wagi.
	 */
	public function syncColorTerms(): void
	{
		$colorTaxonomy = wc_attribute_taxonomy_name(self::ATTR_COLOR);
		$weightTaxonomy = wc_attribute_taxonomy_name(self::ATTR_WEIGHT);

		if (! taxonomy_exists($colorTaxonomy) || ! taxonomy_exists($weightTaxonomy)) {
			return;
		}

		foreach (RalColors::all() as $code) {
			$name = "RAL {$code}";
			$term = get_term_by('name', $name, $colorTaxonomy);

			if (! $term) {
				$inserted = wp_insert_term($name, $colorTaxonomy);

				if (is_wp_error($inserted)) {
					continue;
				}

				$termId = $inserted['term_id'];
			} else {
				$termId = $term->term_id;
			}

			update_term_meta($termId, 'product_attribute_color', RalColors::hex($code));

			// Grupę cenową NIE zapisujemy tu jako meta domyślnie - colorGroupForTerm() i tak dolicza
			// domyślną z RalColors::GROUPS, gdy meta nie jest ustawiona. Meta pojawia się dopiero po
			// ręcznej zmianie (edycja terminu / akcja zbiorcza), co jednocześnie służy jako znacznik
			// "(ręcznie)" w kolumnie listy terminów - zapisywanie tu wartości domyślnej zafałszowałoby ten znacznik.
		}

		foreach (self::DEFAULT_WEIGHTS as $kg) {
			$name = "{$kg}kg";

			if (! get_term_by('name', $name, $weightTaxonomy)) {
				wp_insert_term($name, $weightTaxonomy);
			}
		}
	}

	/**
	 * Przelicza ceny wszystkich wariantów wszystkich produktów korzystających z atrybutu "Kolor RAL".
	 */
	public function recalculateAllVariations(): void
	{
		$productIds = wc_get_products([
			'type' => 'variable',
			'limit' => -1,
			'return' => 'ids',
		]);

		foreach ($productIds as $productId) {
			$product = wc_get_product($productId);

			if (! $product || ! in_array(wc_attribute_taxonomy_name(self::ATTR_COLOR), array_keys($product->get_attributes()), true)) {
				continue;
			}

			foreach ($product->get_children() as $variationId) {
				$this->autoPriceVariation($variationId, 0);
			}
		}
	}

	/**
	 * Hook `woocommerce_save_product_variation` / `product_variation_linked` - automatycznie liczy cenę
	 * wariantu (waga w kg x cena/kg grupy RAL), gdy wariant ma ustawiony kolor RAL i wagę. $i (numer wiersza
	 * w zakładce Warianty) przychodzi tylko z woocommerce_save_product_variation i nie jest tu używany.
	 */
	public function autoPriceVariation(int $variationId, $i = null): void
	{
		$variation = wc_get_product($variationId);

		if (! $variation instanceof \WC_Product_Variation) {
			return;
		}

		$attributes = $variation->get_attributes();
		$colorAttribute = wc_attribute_taxonomy_name(self::ATTR_COLOR);
		$weightAttribute = wc_attribute_taxonomy_name(self::ATTR_WEIGHT);

		// get_attributes() zwraca klucze jako pełne nazwy taksonomii ("pa_kolor-ral", "pa_waga"), nie
		// same slugi atrybutów (self::ATTR_COLOR/ATTR_WEIGHT) - stąd te dwie zmienne wyżej.
		$colorSlug = $attributes[$colorAttribute] ?? null;
		$weightSlug = $attributes[$weightAttribute] ?? null;

		if (! $colorSlug || ! $weightSlug) {
			return;
		}

		$colorTerm = get_term_by('slug', $colorSlug, $colorAttribute);
		$weightTerm = get_term_by('slug', $weightSlug, $weightAttribute);

		if (! $colorTerm || ! $weightTerm) {
			return;
		}

		$code = RalColors::codeFromTermName($colorTerm->name);
		$weightKg = RalColors::weightFromTermName($weightTerm->name);

		if (! $code || ! $weightKg) {
			return;
		}

		$group = $this->colorGroupForTerm($colorTerm->term_id, $code);

		if (! $group) {
			return;
		}

		$pricePerKg = (float) get_field('price_' . strtolower($group), 'option');

		if ($pricePerKg <= 0) {
			return;
		}

		$price = round($weightKg * $pricePerKg, 2);

		update_post_meta($variationId, '_regular_price', $price);

		if (! $variation->get_sale_price('edit')) {
			update_post_meta($variationId, '_price', $price);
		}
	}
}

new RalAttributes();
