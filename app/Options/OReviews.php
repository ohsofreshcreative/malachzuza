<?php

namespace App\Options;

use Log1x\AcfComposer\Options;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Oreviews extends Options
{
	public $name = 'Opinie';
	public $slug = 'oreviews';
	public $title = 'Opinie';
	public $position = 101;
	public $capability = 'edit_posts';
	public $redirect = false;

	public function fields(): FieldsBuilder
	{
		$oreviews = new FieldsBuilder('oreviews');

		$oreviews
			->addText('header', ['label' => 'Nagłówek'])
			->addWysiwyg('info', [
				'label' => 'Informacje dodatkowe',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => true,
			])
			->addRepeater('r_reviews', [
				'label'        => 'Opinie',
				'layout'       => 'table',
				'min'          => 1,
				'max'          => 50,
				'button_label' => 'Dodaj opinię',
			])
			->addText('name', [
				'label' => 'Imię i nazwisko',
			])
			->addNumber('rating', [
				'label' => 'Ocena w gwiazdkach',
				'min' => 1,
				'max' => 5,
				'step' => 1,
				'default_value' => 5,
			])
			->addTextarea('txt', [
				'label'     => 'Treść opinii',
				'rows'      => 4,
				'new_lines' => 'br',
			])
			->addImage('image', [
				'label' => 'Obraz',
				'return_format' => 'array',
				'preview_size' => 'thumbnail',
			])
			->endRepeater();

		return $oreviews;
	}
}
