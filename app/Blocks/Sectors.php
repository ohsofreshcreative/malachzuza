<?php

namespace App\Blocks;

use App\Support\SectionClasses;
use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Sectors extends Block
{
	public $name = 'Sektory';
	public $description = 'sectors';
	public $slug = 'sectors';
	public $category = 'formatting';
	public $icon = 'networking';
	public $keywords = ['sektory', 'branze', 'kafelki'];
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
		$sectors = new FieldsBuilder('sectors');

		$sectors
			->setLocation('block', '==', 'acf/sectors') // ważne!
			->addTab('Elementy', ['placement' => 'top'])
			->addGroup('g_sectors', ['label' => ''])
			->addMessage('Informacja', 'Ten blok automatycznie wyświetla wpisy typu „Sektor". Aby zarządzać elementami, przejdź do sekcji „Sektory" w panelu administratora.')
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
				'choices' => SectionClasses::backgroundChoices(),
				'default_value' => 'none',
				'ui' => 0,
				'allow_null' => 0,
			]);

		return $sectors;
	}

	public function with(): array
	{
		$sectors_query = new \WP_Query([
			'post_type'      => 'sektor',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'post_status'    => 'publish',
		]);

		$sector_items = [];
		foreach ($sectors_query->posts as $post) {
			$thumb_id = get_post_thumbnail_id($post->ID);
			$tags     = get_field('r_sector_tags', $post->ID);

			$sector_items[] = [
				'title'     => $post->post_title,
				'excerpt'   => get_the_excerpt($post),
				'url'       => get_permalink($post->ID),
				'image_url' => $thumb_id ? wp_get_attachment_image_url($thumb_id, 'large') : null,
				'image_alt' => $thumb_id ? get_post_meta($thumb_id, '_wp_attachment_image_alt', true) : '',
				'tags'      => !empty($tags) ? array_column($tags, 'tag') : [],
			];
		}
		wp_reset_postdata();

		$fields = [
			'g_sectors'    => get_field('g_sectors'),
			'sector_items' => $sector_items,

			'section_id'    => get_field('section_id'),
			'section_class' => get_field('section_class'),

			'flip' => (bool) get_field('flip'),
			'wide' => (bool) get_field('wide'),
			'nomt' => (bool) get_field('nomt'),
			'gap'  => (bool) get_field('gap'),

			'background' => get_field('background') ?: get_field('default_block_background', 'option') ?: 'none',
		];

		$fields['sectionClass'] = SectionClasses::fromMap($fields, [
			'flip' => 'order-flip',
			'wide' => 'wide',
			'nomt' => '!mt-0',
			'gap'  => 'wider-gap',
		]);

		return $fields;
	}
}
