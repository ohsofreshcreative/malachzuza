<?php

namespace App\Blocks;

use App\Support\SectionClasses;
use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Counter extends Block
{
	public $name = 'Counter';
	public $description = 'counter';
	public $slug = 'counter';
	public $category = 'formatting';
	public $icon = 'clock';
	public $keywords = ['counter', 'licznik', 'odliczanie'];
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
		$counter = new FieldsBuilder('counter');

		$counter
			->setLocation('block', '==', 'acf/counter')
			->addText('block-title', [
				'label' => 'Tytuł',
				'required' => 0,
			])
			->addAccordion('accordion1', [
				'label' => 'Ustawienia licznika',
				'open' => 1,
			])

			/*--- ELEMENTY ---*/

			->addTab('Elementy', ['placement' => 'top'])
			->addGroup('g_counter', ['label' => ''])
			->addDateTimePicker('end_date', [
				'label' => 'Data i godzina zakończenia',
				'display_format' => 'd.m.Y H:i',
				'return_format' => 'Y-m-d H:i:s',
				'first_day' => 1,
				'required' => 1,
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
				'choices' => SectionClasses::backgroundChoices(),
				'default_value' => 'none',
				'ui' => 0,
				'allow_null' => 0,
			]);

		return $counter;
	}

	public function with(): array
	{
		$gCounter = get_field('g_counter') ?: [];
		$endDate = null;
		$counterValues = [
			'days' => 0,
			'hours' => 0,
			'minutes' => 0,
			'seconds' => 0,
		];

		if (!empty($gCounter['end_date'])) {
			$date = \DateTimeImmutable::createFromFormat(
				'Y-m-d H:i:s',
				$gCounter['end_date'],
				wp_timezone()
			);

			if ($date instanceof \DateTimeImmutable) {
				$endDate = $date->format(DATE_ATOM);
				$remainingSeconds = max(0, $date->getTimestamp() - current_datetime()->getTimestamp());

				$counterValues = [
					'days' => intdiv($remainingSeconds, 86400),
					'hours' => intdiv($remainingSeconds % 86400, 3600),
					'minutes' => intdiv($remainingSeconds % 3600, 60),
					'seconds' => $remainingSeconds % 60,
				];
			}
		}

		$fields = [
			'g_counter' => $gCounter,
			'end_date' => $endDate,
			'counter_values' => $counterValues,

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
