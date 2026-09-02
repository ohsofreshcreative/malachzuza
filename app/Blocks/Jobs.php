<?php

namespace App\Blocks;

use App\Support\SectionClasses;
use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Jobs extends Block
{
	public $name = 'Oferty pracy';
	public $description = 'Oferty pracy';
	public $slug = 'jobs';
	public $category = 'formatting';
	public $icon = 'businessperson';
	public $keywords = ['praca', 'oferty pracy', 'rekrutacja'];
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
		$jobs = new FieldsBuilder('jobs');

		$jobs
			->setLocation('block', '==', 'acf/jobs')
			->addTab('Elementy', ['placement' => 'top'])
			->addGroup('g_jobs', ['label' => ''])
			->addText('header', [
				'label' => 'Nagłówek',
				'default_value' => 'Aktualne oferty pracy',
			])
			->endGroup()

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

		return $jobs;
	}

	public function with(): array
	{
		$jobs_query = new \WP_Query([
			'post_type' => 'praca',
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'post_status' => 'publish',
		]);

		$job_items = [];
		foreach ($jobs_query->posts as $post) {
			$job_items[] = [
				'title' => get_the_title($post->ID),
				'location' => get_field('job_location', $post->ID),
				'contract' => get_field('job_contract', $post->ID),
				'url' => get_permalink($post->ID),
			];
		}
		wp_reset_postdata();

		$fields = [
			'g_jobs' => get_field('g_jobs'),
			'job_items' => $job_items,
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