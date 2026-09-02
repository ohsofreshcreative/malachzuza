<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Support\SectionClasses;

class Support extends Block
{
	public $name = 'Wsparcie';
	public $description = 'Sekcja pakietów wsparcia i legalizacji pobytu';
	public $slug = 'support';
	public $category = 'formatting';
	public $icon = 'id-alt';
	public $keywords = ['support', 'pakiety', 'wsparcie', 'legalizacja'];
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
		$support = new FieldsBuilder('support');

		$support
			->setLocation('block', '==', 'acf/support')
			/*--- TAB #1 ---*/
			->addTab('Nagłówek sekcji', ['placement' => 'top'])
			->addGroup('g_support', ['label' => ''])
			->addText('header', ['label' => 'Nagłówek sekcji'])
			->addWysiwyg('text', [
				'label' => 'Opis sekcji',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => true,
			])
			->endGroup()

			/*--- TAB #2 ---*/
			->addTab('Pakiety', ['placement' => 'top'])
			->addRepeater('r_support', [
				'label' => 'Pakiety',
				'layout' => 'block',
				'min' => 1,
				'button_label' => 'Dodaj pakiet',
			])
			->addText('tab_name', [
				'label' => 'Nazwa zakładki (np. Pakiet standardowy)',
				'required' => 1,
			])
			->addText('header', [
				'label' => 'Nagłówek pakietu (np. Pakiet STANDARDOWY...)',
			])
			->addWysiwyg('text', [
				'label' => 'Treść pakietu (opis pod nagłówkiem)',
				'tabs' => 'all',
				'toolbar' => 'full',
			])
			->addText('right_badge', [
				'label' => 'Treść w prawej ramce (np. Firma nie musi angażować...)',
			])
			->addText('list_title', [
				'label' => 'Tytuł listy lewej (np. Zakres pakietu:)',
			])
			->addWysiwyg('left_list', [
				'label' => 'Zakres pakietu (lewa strona - punkty 1-5 itd.)',
				'tabs' => 'all',
				'toolbar' => 'full',
			])
			->addText('right_list_title', [
				'label' => 'Tytuł listy prawej (np. Korzyści dla pracodawcy:)',
			])
			->addWysiwyg('right_list', [
				'label' => 'Korzyści dla pracodawcy (prawa ramka)',
				'tabs' => 'all',
				'toolbar' => 'full',
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

		return $support;
	}

	public function with(): array
	{
		$gSupport = get_field('g_support');

		$fields = [
			'g_support' => $gSupport,
			'r_support' => get_field('r_support') ?: [],
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
