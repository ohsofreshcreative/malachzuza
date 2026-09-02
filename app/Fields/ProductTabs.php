<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class ProductTabs extends Field
{
	/**
	 * Definiuje dodatkowe zakładki na stronie produktu (obok "Opis produktu").
	 *
	 * @return array
	 */
	public function fields(): array
	{
		$productTabs = new FieldsBuilder('product_tabs_fields', [
			'title' => 'Dodatkowe zakładki produktu',
			'style' => 'default',
			'position' => 'normal',
		]);

		$productTabs
			->setLocation('post_type', '==', 'product');

		$productTabs
			->addFlexibleContent('product_tabs', [
				'label' => 'Zakładki produktu',
				'button_label' => 'Dodaj zakładkę',
				'instructions' => 'Kolejność zakładek na stronie produktu odpowiada kolejności tutaj. Zakładka "Opis produktu" pojawia się zawsze jako pierwsza (i tylko wtedy, gdy produkt ma wypełniony długi opis).',
			])
			->addLayout('text', ['label' => 'Tekst'])
			->addText('tab_title', ['label' => 'Nazwa zakładki', 'required' => 1])
			->addWysiwyg('tab_content', [
				'label' => 'Treść',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => true,
			])
			->addLayout('files', ['label' => 'Pliki do pobrania'])
			->addText('tab_title', ['label' => 'Nazwa zakładki', 'required' => 1])
			->addWysiwyg('tab_text', [
				'label' => 'Opis (opcjonalnie)',
				'tabs' => 'visual',
				'toolbar' => 'basic',
				'media_upload' => false,
			])
			->addRepeater('tab_files', [
				'label' => 'Pliki',
				'layout' => 'table',
				'button_label' => 'Dodaj plik',
				'min' => 1,
			])
			->addText('file_title', ['label' => 'Nazwa pliku', 'required' => 1])
			->addFile('file', ['label' => 'Plik', 'return_format' => 'array', 'required' => 1])
			->endRepeater()
			->addLayout('image', ['label' => 'Obraz'])
			->addText('tab_title', ['label' => 'Nazwa zakładki', 'required' => 1])
			->addImage('tab_image', [
				'label' => 'Obraz',
				'return_format' => 'array',
				'preview_size' => 'medium',
			])
			->endFlexibleContent();

		return [$productTabs];
	}
}
