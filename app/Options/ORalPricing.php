<?php

namespace App\Options;

use Log1x\AcfComposer\Options;
use StoutLogic\AcfBuilder\FieldsBuilder;

class ORalPricing extends Options
{
	public $name = 'Ceny RAL';
	public $slug = 'oralpricing';
	public $title = 'Ceny RAL';
	public $position = 106;
	public $capability = 'edit_posts';
	public $redirect = false;

	public function fields(): FieldsBuilder
	{
		$oralpricing = new FieldsBuilder('oralpricing');

		$oralpricing
			->addGroup('g_oralpricing', ['label' => ''])
			->addNumber('price_rc1', [
				'label' => 'RC1 - cena za 1 kg (PLN netto)',
				'step' => '0.01',
				'default_value' => 16,
				'required' => 1,
			])
			->addNumber('price_rc2', [
				'label' => 'RC2 - cena za 1 kg (PLN netto)',
				'step' => '0.01',
				'default_value' => 18,
				'required' => 1,
			])
			->addNumber('price_rc3', [
				'label' => 'RC3 - cena za 1 kg (PLN netto)',
				'step' => '0.01',
				'default_value' => 20,
				'required' => 1,
			])
			->addNumber('price_rc4', [
				'label' => 'RC4 - cena za 1 kg (PLN netto)',
				'step' => '0.01',
				'default_value' => 22,
				'required' => 1,
			])
			->addNumber('price_rc5', [
				'label' => 'RC5 - cena za 1 kg (PLN netto)',
				'step' => '0.01',
				'default_value' => 24,
				'required' => 1,
			])
			->addTrueFalse('recalculate_now', [
				'label' => 'Przelicz teraz ceny wariantów RAL',
				'instructions' => 'Zaznacz i zapisz, żeby przeliczyć ceny wszystkich wariantów produktów RAL (cena = waga [kg] x cena/kg grupy) wg cen powyżej. Po przeliczeniu pole samo się odznacza.',
				'ui' => 1,
				'ui_on_text' => 'Tak',
				'ui_off_text' => 'Nie',
				'default_value' => 0,
			])
			->addTrueFalse('sync_colors_now', [
				'label' => 'Zsynchronizuj kolory RAL (jednorazowo)',
				'instructions' => 'Zaznacz i zapisz, żeby utworzyć/zaktualizować terminy atrybutu "Kolor RAL" (wraz z miniaturkami kolorów) oraz podstawowe wagi w atrybucie "Waga". Bezpieczne do ponownego uruchomienia. Po synchronizacji pole samo się odznacza.',
				'ui' => 1,
				'ui_on_text' => 'Tak',
				'ui_off_text' => 'Nie',
				'default_value' => 0,
			])
			->endGroup();

		return $oralpricing;
	}
}
