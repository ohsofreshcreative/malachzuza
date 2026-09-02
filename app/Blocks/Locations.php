<?php

namespace App\Blocks;

use App\Support\SectionClasses;
use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Locations extends Block
{
	public $name = 'Lokalizacje';
	public $description = 'locations';
	public $slug = 'locations';
	public $category = 'formatting';
	public $icon = 'location-alt';
	public $keywords = ['lokalizacje', 'adresy', 'kontakt'];
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
		$locations = new FieldsBuilder('locations');

		$locations
			->setLocation('block', '==', 'acf/locations') // ważne!
			/*--- TAB #1 ---*/
			->addTab('Elementy', ['placement' => 'top'])
			->addGroup('g_locations', ['label' => ''])
			->addText('header', ['label' => 'Nagłówek'])
			->endGroup()

			/*--- TAB #2 ---*/
			->addTab('Lokalizacje', ['placement' => 'top'])
			->addRepeater('r_locations', [
				'label' => 'Lokalizacje',
				'layout' => 'block',
				'min' => 1,
				'button_label' => 'Dodaj lokalizację',
			])
			->addImage('image', [
				'label' => 'Obraz',
				'return_format' => 'array',
				'preview_size' => 'medium',
			])
			->addRepeater('companies', [
				'label' => 'Firmy',
				'layout' => 'block',
				'min' => 1,
				'button_label' => 'Dodaj firmę',
			])
			->addText('name', ['label' => 'Nazwa firmy'])
			->addText('address', ['label' => 'Adres'])
			->addRepeater('phones', [
				'label' => 'Telefony',
				'layout' => 'table',
				'min' => 1,
				'button_label' => 'Dodaj numer',
			])
			->addText('label', ['label' => 'Etykieta'])
			->addText('number', ['label' => 'Numer telefonu'])
			->endRepeater()
			->endRepeater()
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

		return $locations;
	}

	public function with(): array
	{
		$fields = [
			'g_locations' => get_field('g_locations'),
			'r_locations' => get_field('r_locations'),

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
