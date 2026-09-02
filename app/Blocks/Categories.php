<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Support\SectionClasses;

class Categories extends Block
{
	public $name = 'Kategorie';
	public $description = 'categories';
	public $slug = 'categories';
	public $category = 'formatting';
	public $icon = 'grid-view';
	public $keywords = ['kategorie', 'produkty', 'woocommerce'];
	public $mode = 'edit';
	public $supports = [
		'align' => false,
		'mode' => true,
		'jsx' => true,
		'anchor' => true,
		'customClassName' => true,
	];

	public function fields()
	{
		$categories = new FieldsBuilder('categories');

		$categories
			->setLocation('block', '==', 'acf/categories') // ważne!
			/*--- TAB #1 ---*/
			->addTab('Elementy', ['placement' => 'top'])
			->addGroup('g_categories', ['label' => ''])
			->addText('header', ['label' => 'Nagłówek'])
			->endGroup()

			/*--- TAB #2 ---*/
			->addTab('Kategorie', ['placement' => 'top'])
			->addRepeater('r_categories', [
				'label' => 'Kategorie',
				'layout' => 'table',
				'button_label' => 'Dodaj kategorię',
				'instructions' => 'Wybierz i ułóż kategorie produktów w dowolnej kolejności. Jeśli lista jest pusta, wyświetlą się wszystkie kategorie produktów.',
			])
			->addTaxonomy('category', [
				'label' => 'Kategoria',
				'taxonomy' => 'product_cat',
				'field_type' => 'select',
				'allow_null' => 0,
				'multiple' => 0,
				'return_format' => 'object',
				'required' => 1,
			])
			->endRepeater()

			/*--- USTAWIENIA BLOKU ---*/

			->addTab('Ustawienia bloku', ['placement' => 'top'])
			->addText('section_id', [
				'label' => 'ID',
			])
			->addText('section_class', [
				'label' => 'Dodatkowe klasy CSS',
			])
			->addTrueFalse('flip', [
				'label' => 'Odwrotna kolejność',
				'ui' => 1,
				'ui_on_text' => 'Tak',
				'ui_off_text' => 'Nie',
			])
			->addTrueFalse('wide', [
				'label' => 'Szeroka kolumna',
				'ui' => 1,
				'ui_on_text' => 'Tak',
				'ui_off_text' => 'Nie',
			])
			->addTrueFalse('nomt', [
				'label' => 'Usunięcie marginesu górnego',
				'ui' => 1,
				'ui_on_text' => 'Tak',
				'ui_off_text' => 'Nie',
			])
			->addTrueFalse('gap', [
				'label' => 'Większy odstęp',
				'ui' => 1,
				'ui_on_text' => 'Tak',
				'ui_off_text' => 'Nie',
			])
			->addSelect('background', [
				'label' => 'Kolor tła',
				'choices' => \App\Support\SectionClasses::backgroundChoices(),
				'default_value' => 'none',
				'ui' => 0,
				'allow_null' => 0,
			]);

		return $categories;
	}

	public function with(): array
	{
		$g_categories = get_field('g_categories');
		$rows = get_field('r_categories') ?: [];

		$terms = [];
		foreach ($rows as $row) {
			if (!empty($row['category'])) {
				$terms[] = $row['category'];
			}
		}

		$fields = [
			'g_categories' => $g_categories,
			'items' => self::items($terms),

			'section_id' => get_field('section_id'),
			'section_class' => get_field('section_class'),

			'flip' => (bool) get_field('flip'),
			'wide' => (bool) get_field('wide'),
			'nomt' => (bool) get_field('nomt'),
			'gap' => (bool) get_field('gap'),

			'background' => get_field('background') ?: get_field('default_block_background', 'option') ?: 'none',
		];

		$fields['sectionClass'] = SectionClasses::fromMap($fields, [
			'flip' => 'order-flip',
			'wide' => 'wide',
			'nomt' => '!mt-0',
			'gap' => 'wider-gap',
		]);

		return $fields;
	}

	/**
	 * Zwraca listę kategorii produktów gotową do renderowania (do użycia też poza tym blokiem).
	 */
	public static function items(array $terms = []): array
	{
		if (empty($terms)) {
			$terms = get_terms([
				'taxonomy' => 'product_cat',
				'hide_empty' => true,
				'orderby' => 'menu_order',
				'order' => 'ASC',
			]);

			if (is_wp_error($terms)) {
				$terms = [];
			}
		}

		$items = [];
		foreach ($terms as $term) {
			$thumb_id = get_term_meta($term->term_id, 'thumbnail_id', true);

			$items[] = [
				'title' => $term->name,
				'url' => get_term_link($term),
				'image' => [
					'ID' => $thumb_id ?: null,
					'alt' => $thumb_id ? get_post_meta($thumb_id, '_wp_attachment_image_alt', true) : '',
					'url' => $thumb_id ? wp_get_attachment_image_url($thumb_id, 'large') : '',
				],
			];
		}

		return $items;
	}
}
