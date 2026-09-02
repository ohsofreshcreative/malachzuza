<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Support\SectionClasses;

class Whyus extends Block
{
	public $name = 'Dlaczego my?';
	public $description = 'whyus';
	public $slug = 'whyus';
	public $category = 'formatting';
	public $icon = 'editor-table';
	public $keywords = ['tresc', 'porownanie', 'tabela'];
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
		$whyus = new FieldsBuilder('whyus');

		$whyus
			->setLocation('block', '==', 'acf/whyus') // ważne!
			/*--- GROUP ---*/
			->addTab('Elementy', ['placement' => 'top'])
			->addGroup('g_whyus', ['label' => ''])
			->addText('header', ['label' => 'Nagłówek'])
			->addWysiwyg('text', [
				'label' => 'Treść',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => true,
			])
			->addText('col1_title', [
				'label' => 'Tytuł kolumny #1',
				'default_value' => 'Współpraca z Bergermann',
			])
			->addText('col2_title', [
				'label' => 'Tytuł kolumny #2',
				'default_value' => 'Samodzielna rekrutacja',
			])
			->endGroup()

			/*--- TAB #2 ---*/
			->addTab('Punkty', ['placement' => 'top'])
			->addRepeater('r_whyus_col1', [
				'label' => 'Punkty - kolumna #1',
				'layout' => 'table',
				'min' => 1,
				'button_label' => 'Dodaj punkt',
			])
			->addText('text', [
				'label' => 'Tekst',
			])
			->endRepeater()
			->addRepeater('r_whyus_col2', [
				'label' => 'Punkty - kolumna #2',
				'layout' => 'table',
				'min' => 1,
				'button_label' => 'Dodaj punkt',
			])
			->addText('text', [
				'label' => 'Tekst',
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

		return $whyus;
	}

	public function with(): array
	{
		$fields = [
			'g_whyus'      => get_field('g_whyus'),
			'r_whyus_col1' => get_field('r_whyus_col1'),
			'r_whyus_col2' => get_field('r_whyus_col2'),

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
