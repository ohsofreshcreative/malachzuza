<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class ProductFaq extends Field
{
	/**
	 * Definiuje FAQ na stronie produktu (nad sekcją powiązanych produktów).
	 *
	 * @return array
	 */
	public function fields(): array
	{
		$productFaq = new FieldsBuilder('product_faq_fields', [
			'title' => 'FAQ produktu',
			'style' => 'default',
			'position' => 'normal',
		]);

		$productFaq
			->setLocation('post_type', '==', 'product');

		$productFaq
			->addRepeater('product_faq', [
				'label' => 'FAQ',
				'layout' => 'table',
				'button_label' => 'Dodaj pytanie',
			])
			->addText('question', ['label' => 'Pytanie', 'required' => 1])
			->addWysiwyg('answer', [
				'label' => 'Odpowiedź',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => true,
			])
			->endRepeater();

		return [$productFaq];
	}
}
