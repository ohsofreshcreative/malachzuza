<?php

namespace App\Fields;

use App\Support\RalColors;
use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class RalColorGroup extends Field
{
	/**
	 * Definiuje pole wyboru grup kolorów RAL na stronie produktu.
	 *
	 * @return array
	 */
	public function fields(): array
	{
		$ralColorGroup = new FieldsBuilder('ral_color_group_fields', [
			'title' => 'Kolory RAL',
			'style' => 'default',
			'position' => 'normal',
		]);

		$ralColorGroup
			->setLocation('post_type', '==', 'product');

		$choices = [];

		foreach (array_keys(RalColors::GROUPS) as $group) {
			$price = function_exists('get_field') ? get_field('price_' . strtolower($group), 'option') : null;
			$choices[$group] = $price ? "{$group} ({$price} PLN/kg netto)" : $group;
		}

		$ralColorGroup
			->addCheckbox('ral_color_groups', [
				'label' => 'Grupy kolorów RAL',
				'instructions' => 'Zaznacz grupy cenowe RAL dostępne w tym produkcie. Po zapisaniu produktu atrybut "Kolor RAL" (zakładka Atrybuty) zostanie automatycznie uzupełniony wszystkimi kolorami z wybranych grup i oznaczony jako używany do wariantów - nadpisując ręczny wybór kolorów tam ustawiony. Zostaw puste, żeby zarządzać kolorami ręcznie w zakładce Atrybuty. Po zapisie przejdź do zakładki Warianty i kliknij "Utwórz warianty ze wszystkich atrybutów", aby wygenerować brakujące warianty.',
				'choices' => $choices,
				'layout' => 'vertical',
			]);

		return [$ralColorGroup];
	}
}
