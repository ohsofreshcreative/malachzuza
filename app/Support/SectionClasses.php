<?php

namespace App\Support;

class SectionClasses
{
	public static function backgroundChoices(): array
	{
		return [
			'none' => 'Brak (domyślne)',
			'section-white' => 'Białe',
			'section-light' => 'Jasne',
			'section-primary' => 'Niebieskie',
			'section-secondary' => 'Różowe',
			'section-gradient' => 'Gradient',
			'section-dark' => 'Ciemne',
		];
	}

	/**
	 * Buduje string klas na podstawie mapy pól boolean -> klasy oraz pola tła.
	 *
	 * @param array<string, mixed> $fields
	 * @param array<string, string> $booleanMap np. ['flip' => 'order-flip']
	 */
	public static function fromMap(array $fields, array $booleanMap, ?string $backgroundField = 'background'): string
	{
		$classes = [];

		foreach ($booleanMap as $fieldName => $className) {
			if (!empty($fields[$fieldName])) {
				$classes[] = $className;
			}
		}

		if ($backgroundField !== null) {
			$bg = $fields[$backgroundField] ?? null;

			if (is_string($bg)) {
				$bg = trim($bg);
			}

			if (!empty($bg) && $bg !== 'none') {
				$classes[] = (string) $bg;
			}
		}

		// usuń puste, zrób unikalne, sklej
		$classes = array_values(array_unique(array_filter($classes)));

		return implode(' ', $classes);
	}
}
