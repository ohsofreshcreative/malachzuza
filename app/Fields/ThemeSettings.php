<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class ThemeSettings extends Field
{
	public function fields(): array
	{
		$theme = new FieldsBuilder('theme_settings');

		$theme
			->setLocation('options_page', '==', 'theme-settings')
			->addTab('Layout', ['placement' => 'top'])
			->addSelect('default_block_background', [
				'label' => 'Domyślne tło bloków',
				'choices' => \App\Support\SectionClasses::backgroundChoices(),
				'default_value' => 'none',
				'ui' => 0,
				'allow_null' => 0,
			])

			->addTab('Logo', ['placement' => 'top'])
			->addImage('logo', [
				'label' => 'Logo',
				'return_format' => 'array',
				'preview_size' => 'medium',
				'library' => 'all',
			])
			->addImage('logo_footer', [
				'label' => 'Logo Stopka',
				'return_format' => 'array',
				'preview_size' => 'medium',
				'library' => 'all',
			])

			->addTab('Dane kontaktowe (Stopka)', ['placement' => 'top'])
			->addGroup('footer_contact', ['label' => 'Dane w pierwszej kolumnie stopki'])

			->addWysiwyg('address', [
				'label' => 'Adres / Dane firmy',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => true,
			])
			->addWysiwyg('hours', [
				'label' => 'Godziny otwarcia',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => true,
			])
			->addText('phone', [
				'label' => 'Numer telefonu',
			])
			->addText('email', [
				'label' => 'Adres E-mail',
			])
			->addRepeater('social_links', [
				'label' => 'Social media',
				'layout' => 'table',
				'button_label' => 'Dodaj link',
			])
			->addImage('icon', [
				'label' => 'Ikona',
				'return_format' => 'array',
				'preview_size' => 'thumbnail',
			])
			->addUrl('link', [
				'label' => 'Link URL',
			])
			->endRepeater()
			->endGroup();

		return [$theme];
	}
}
