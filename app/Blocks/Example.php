<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Support\SectionClasses;

class Example extends Block
{
	public $name = 'Przykład współpracy';
	public $description = 'example';
	public $slug = 'example';
	public $category = 'formatting';
	public $icon = 'megaphone';
	public $keywords = ['przyklad', 'wspolpraca', 'kafelki'];
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
		$example = new FieldsBuilder('example');

		$example
			->setLocation('block', '==', 'acf/example') // ważne!
			/*--- GROUP ---*/
			->addTab('Elementy', ['placement' => 'top'])
			->addGroup('g_example', ['label' => ''])
			->addText('header', ['label' => 'Nagłówek'])
			->addImage('logo', [
				'label' => 'Logo',
				'return_format' => 'array',
				'preview_size' => 'thumbnail',
			])
			->addTextarea('text', [
				'label' => 'Opis (kafelek z logo)',
				'rows' => 3,
			])
			->endGroup()

			/*--- TAB #2 ---*/
			->addTab('Kafelki', ['placement' => 'top'])
			->addRepeater('r_example', [
				'label' => 'Liczby',
				'layout' => 'table',
				'min' => 1,
				'button_label' => 'Dodaj liczbę',
			])
			->addText('stat', [
				'label' => 'Liczba (np. 500+)',
			])
			->addText('label', [
				'label' => 'Opis liczby',
			])
			->addSelect('color', [
				'label' => 'Kolor kafelka',
				'choices' => [
					'dark' => 'Ciemny (bordowy)',
					'pink' => 'Różowy',
				],
				'default_value' => 'dark',
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

		return $example;
	}

	public function with(): array
	{
		$fields = [
			'g_example' => get_field('g_example'),
			'r_example' => get_field('r_example'),

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
