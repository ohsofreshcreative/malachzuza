<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Support\SectionClasses;

class Contact extends Block

{
	public $name = 'Kontakt';
	public $description = 'Contact';
	public $slug = 'contact';
	public $category = 'formatting';
	public $icon = 'email';
	public $keywords = ['formularz', 'kontakt'];
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
		$contact = new FieldsBuilder('contact');

		$contact
			->setLocation('block', '==', 'acf/contact') // ważne!
			/*--- FIELDS ---*/
			/*--- TAB #1 ---*/
			->addTab('Dane', ['placement' => 'top'])
			->addGroup('g_contact_1', ['label' => ''])
			->addImage('image', [
				'label' => 'Obraz',
				'return_format' => 'array',
				'preview_size' => 'thumbnail',
			])
			->addText('header', ['label' => 'Tytuł'])
			->addWysiwyg('text', [
				'label' => 'Treść',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => false,
			])
			->addText('phone', [
				'label' => 'Numer telefonu',
			])
			->addText('mail', [
				'label' => 'Adres e-mail',
			])
			->addWysiwyg('hours', [
				'label' => 'Godziny otwarcia',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => false,
			])
			->endGroup()
			/*--- TAB #2 ---*/
			->addTab('Formularz', ['placement' => 'top'])
			->addGroup('g_contact_2', ['label' => ''])
			->addText('title', ['label' => 'Tytuł'])
			->addText('shortcode', [
				'label' => 'Kod formularza',
				'instructions' => 'Wklej kod formularza:  [contact-form-7 id="f12c470" title="Contact form 1"]',
				'default_value' => '[contact-form-7 id="f12c470" title="Contact form 1"]',
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
			])
			->addNumber('overlay_opacity', [
				'label' => 'Krycie nakładki na zdjęciu (%)',
				'instructions' => 'Krycie kolorowej nakładki na zdjęciu w tle (0-100%). Pozwala ustawić inną wartość dla tej konkretnej strony, np. gdy zdjęcie ma być bardziej widoczne.',
				'min' => 0,
				'max' => 100,
				'step' => 1,
				'default_value' => 90,
				'append' => '%',
			]);


		return $contact;
	}

	public function with(): array
	{
		$fields = [
			'g_contact_1' => get_field('g_contact_1'),
			'g_contact_2' => get_field('g_contact_2'),

			'section_id' => get_field('section_id'),
			'section_class' => get_field('section_class'),

			'flip' => (bool) get_field('flip'),
			'wide' => (bool) get_field('wide'),
			'nomt' => (bool) get_field('nomt'),
			'gap' => (bool) get_field('gap'),

			'background' => get_field('background') ?: get_field('default_block_background', 'option') ?: 'none',
			'overlayOpacity' => is_numeric(get_field('overlay_opacity')) ? (float) get_field('overlay_opacity') : 90,
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
