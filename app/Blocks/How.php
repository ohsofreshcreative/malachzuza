<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Support\SectionClasses;

class How extends Block
{
	public $name = 'How';
	public $description = 'how';
	public $slug = 'how';
	public $category = 'formatting';
	public $icon = 'admin-links';
	public $keywords = ['how', 'pomoc', 'kafelki'];
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
		$how = new FieldsBuilder('how');

		$how
			->setLocation('block', '==', 'acf/how')
			->addTab('Elementy', ['placement' => 'top'])
			->addGroup('g_how', ['label' => ''])
			->addText('header', ['label' => 'Nagłówek'])
			->addRepeater('r_how', [
				'label' => 'Kafelki',
				'layout' => 'table',
				'min' => 1,
				'button_label' => 'Dodaj kafelek',
			])
			->addImage('icon', [
				'label' => 'Ikona',
				'return_format' => 'array',
				'preview_size' => 'thumbnail',
			])
			->addImage('image', [
				'label' => 'Obraz',
				'return_format' => 'array',
			])
			->addText('title', ['label' => 'Nagłówek'])
			->addLink('button', [
				'label' => 'Przycisk',
				'return_format' => 'array',
			])
			->addTrueFalse('image_card', [
				'label' => 'Duży blok z obrazem',
				'ui' => 1,
				'ui_on_text' => 'Tak',
				'ui_off_text' => 'Nie',
			])
			->endRepeater()
			->endGroup()
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

		return $how;
	}

	public function with(): array
	{
		$gHow = get_field('g_how');

		$fields = [
			'g_how' => $gHow,
			'r_how' => $gHow['r_how'] ?? [],
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
