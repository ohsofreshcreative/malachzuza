<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class ProductCategory extends Field
{
	/**
	 * Definiuje grupę pól dla kategorii produktów (product_cat).
	 *
	 * @return array
	 */
	public function fields(): array
	{
		$productCategory = new FieldsBuilder('product_category_fields', [
			'title' => 'Ustawienia kategorii produktów',
			'style' => 'seamless',
			'position' => 'normal',
		]);

		$productCategory
			->setLocation('taxonomy', '==', 'product_cat');

		$productCategory
			->addTextarea('hero_header', [
				'label' => 'Nagłówek hero',
				'instructions' => 'Zastępuje domyślny tytuł w nagłówku strony kategorii. Dozwolone znaczniki: <strong>, <em>, <a>, <br>.',
			])
			->addImage('hero_image', [
				'label' => 'Zdjęcie hero',
				'return_format' => 'array',
				'preview_size' => 'medium',
			]);

		return [$productCategory];
	}
}
