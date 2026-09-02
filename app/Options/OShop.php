<?php

namespace App\Options;

use Log1x\AcfComposer\Options;
use StoutLogic\AcfBuilder\FieldsBuilder;

class OShop extends Options
{
	public $name = 'Ustawienia sklepu';
	public $slug = 'oshop';
	public $title = 'Ustawienia sklepu';
	public $position = 103;
	public $capability = 'edit_posts';
	public $redirect = false;

	public function fields(): FieldsBuilder
	{
		$oshop = new FieldsBuilder('oshop');

		$oshop
			->addGroup('g_oshop', ['label' => ''])
			->addImage('hero_image', [
				'label'         => 'Zdjęcie hero',
				'return_format' => 'array',
				'preview_size'  => 'medium',
			])
			->addTextarea('hero_header', [
				'label' => 'Nagłówek hero',
				'instructions' => 'Zastępuje domyślny tytuł na głównej stronie sklepu. Dozwolone znaczniki: <strong>, <em>, <a>, <br>.',
			])
			->addWysiwyg('description', [
				'label' => 'Opis',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => true,
			])
			->endGroup();

		return $oshop;
	}
}
