<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Support\SectionClasses;

class Featured extends Block
{
	public $name = 'Wyróżnione produkty';
	public $description = 'featured';
	public $slug = 'featured';
	public $category = 'formatting';
	public $icon = 'star-filled';
	public $keywords = ['produkty', 'woocommerce'];
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
		$featured = new FieldsBuilder('featured');

		$featured
			->setLocation('block', '==', 'acf/featured') // ważne!
			/*--- GROUP ---*/
			->addTab('Elementy', ['placement' => 'top'])
			->addGroup('g_featured', ['label' => ''])
			->addText('header', ['label' => 'Nagłówek'])
			->addRelationship('products', [
				'label' => 'Produkty',
				'post_type' => ['product'],
				'filters' => ['search'],
				'return_format' => 'object',
				'max' => 6,
				'instructions' => 'Wybierz i ułóż produkty w dowolnej kolejności (maks. 6). Jeśli pole jest puste, wyświetlą się produkty oznaczone w WooCommerce jako wyróżnione.',
			])
			->addLink('button', [
				'label' => 'Przycisk',
				'return_format' => 'array',
				'instructions' => 'Jeśli puste, użyty zostanie link do strony sklepu z domyślnym tekstem.',
			])
			->endGroup()

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

		return $featured;
	}

	public function with(): array
	{
		$g_featured = get_field('g_featured');
		$selected = array_slice($g_featured['products'] ?? [], 0, 8);

		if (empty($selected)) {
			$featured_query = new \WP_Query([
				'post_type' => 'product',
				'posts_per_page' => 8,
				'post_status' => 'publish',
				'tax_query' => [
					[
						'taxonomy' => 'product_visibility',
						'field' => 'name',
						'terms' => 'featured',
					],
				],
			]);
			$selected = $featured_query->posts;
			wp_reset_postdata();
		}

		$items = [];
		foreach ($selected as $post) {
			$product = wc_get_product($post->ID);

			if (!$product) {
				continue;
			}

			$thumb_id = $product->get_image_id();

			// Dla produktów wariantowych (przedział cenowy) pokazujemy tylko najniższą cenę zamiast całego zakresu.
			$price_html = $product->is_type('variable')
				? wc_price($product->get_variation_price('min', true))
				: $product->get_price_html();

			$items[] = [
				'title' => $product->get_name(),
				'url' => get_permalink($product->get_id()),
				'price_html' => $price_html,
				'image' => [
					'ID' => $thumb_id,
					'alt' => $thumb_id ? get_post_meta($thumb_id, '_wp_attachment_image_alt', true) : '',
					'url' => $thumb_id ? wp_get_attachment_image_url($thumb_id, 'large') : '',
				],
			];
		}

		$fields = [
			'g_featured' => $g_featured,
			'items' => $items,
			'button' => !empty($g_featured['button']['url']) ? $g_featured['button'] : [
				'url' => function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/'),
				'title' => 'Zobacz wszystkie produkty',
			],

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
}
