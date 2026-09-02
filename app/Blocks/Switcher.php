<?php

namespace App\Blocks;

use App\Support\SectionClasses;
use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Switcher extends Block
{
	public $name = 'Switcher';
	public $description = 'switcher';
	public $slug = 'switcher';
	public $category = 'formatting';
	public $icon = 'image-flip-horizontal';
	public $keywords = ['switcher', 'przełącznik', 'zakładki'];
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
		$switcher = new FieldsBuilder('switcher');

		$switcher
			->setLocation('block', '==', 'acf/switcher')
			/*--- TAB #1 ---*/
			->addTab('Elementy', ['placement' => 'top'])
			->addGroup('g_switcher', ['label' => ''])
			->addText('header', ['label' => 'Nagłówek'])
			->endGroup()

			/*--- TAB #2 ---*/
			->addTab('Zakładki', ['placement' => 'top'])
			->addRepeater('r_switcher', [
				'label' => 'Zakładki',
				'layout' => 'table',
				'min' => 1,
				'button_label' => 'Dodaj zakładkę',
			])
			->addText('title', [
				'label' => 'Nazwa zakładki',
				'required' => 1,
				'wrapper' => [
					'width' => '20',
				],
			])
			->addImage('image', [
				'label' => 'Obraz',
				'return_format' => 'array',
				'preview_size' => 'thumbnail',
				'wrapper' => [
					'width' => '10',
				],
			])
			->addText('header', [
				'label' => 'Nagłówek',
				'wrapper' => [
					'width' => '20',
				],
			])
			->addWysiwyg('text', [
				'label' => 'Treść',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => true,
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
				'choices' => SectionClasses::backgroundChoices(),
				'default_value' => 'none',
				'ui' => 0,
				'allow_null' => 0,
			]);

		return $switcher;
	}

	public function with(): array
	{
		$fields = [
			'g_switcher' => get_field('g_switcher'),
			'r_switcher' => get_field('r_switcher'),

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
