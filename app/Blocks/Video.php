<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Support\SectionClasses;

class Video extends Block
{
	public $name = 'Wideo';
	public $description = 'video';
	public $slug = 'video';
	public $category = 'formatting';
	public $icon = 'video-alt3';
	public $keywords = ['video', 'wideo'];
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
		$video = new FieldsBuilder('video');

		$video
			->setLocation('block', '==', 'acf/video') // ważne!
			/*--- GROUP ---*/
			->addTab('Elementy', ['placement' => 'top'])
			->addGroup('g_video', ['label' => ''])

			->addText('header', ['label' => 'Tytuł'])
			->addSelect('source_type', [
				'label' => 'Źródło wideo',
				'choices' => [
					'file' => 'Plik z biblioteki mediów',
					'youtube' => 'Link YouTube',
				],
				'default_value' => 'file',
				'ui' => 1,
			])
			->addFile('video', [
				'label' => 'Plik wideo',
				'return_format' => 'url',
				'mime_types' => 'mp4,webm,ogv',
				'conditional_logic' => [[[
					'field' => 'source_type',
					'operator' => '==',
					'value' => 'file',
				]]],
			])
			->addUrl('youtube_url', [
				'label' => 'Link YouTube',
				'instructions' => 'Wklej pełny adres filmu z youtube.com lub youtu.be.',
				'conditional_logic' => [[[
					'field' => 'source_type',
					'operator' => '==',
					'value' => 'youtube',
				]]],
			])

			->endGroup()

			/*--- USTAWIENIA BLOKU ---*/

			->addTab('Ustawienia bloku', ['placement' => 'top'])
			->addText('section_id', [
				'label' => 'ID',
			])
			->addText('section_class', [
				'label' => 'Dodatkowe klasy CSS',
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
			->addSelect('background', [
				'label' => 'Kolor tła',
				'choices' => \App\Support\SectionClasses::backgroundChoices(),
				'default_value' => 'none',
				'ui' => 0,
				'allow_null' => 0,
			]);

		return $video;
	}

	public function with(): array
	{
		$gVideo = get_field('g_video') ?: [];
		$sourceType = ($gVideo['source_type'] ?? 'file') === 'youtube' ? 'youtube' : 'file';

		$fields = [
			'g_video' => $gVideo,
			'source_type' => $sourceType,
			'youtube_embed_url' => $sourceType === 'youtube'
				? $this->youtubeEmbedUrl($gVideo['youtube_url'] ?? null)
				: null,

			'section_id' => get_field('section_id'),
			'section_class' => get_field('section_class'),

			'wide' => (bool) get_field('wide'),
			'nomt' => (bool) get_field('nomt'),

			'background' => get_field('background') ?: get_field('default_block_background', 'option') ?: 'none',
		];

		$fields['sectionClass'] = SectionClasses::fromMap($fields, [
			'wide' => 'wide',
			'nomt' => '!mt-0',
		]);

		return $fields;
	}

	private function youtubeEmbedUrl(?string $url): ?string
	{
		if (empty($url)) {
			return null;
		}

		$parts = wp_parse_url(trim($url));

		if (!is_array($parts) || empty($parts['host'])) {
			return null;
		}

		$host = strtolower(preg_replace('/^www\./', '', $parts['host']));
		$path = $parts['path'] ?? '';
		$videoId = null;

		if ($host === 'youtu.be') {
			$videoId = strtok(ltrim($path, '/'), '/');
		} elseif (in_array($host, ['youtube.com', 'm.youtube.com', 'music.youtube.com', 'youtube-nocookie.com'], true)) {
			if ($path === '/watch') {
				parse_str($parts['query'] ?? '', $query);
				$videoId = $query['v'] ?? null;
			} elseif (preg_match('~^/(?:embed|shorts|live)/([^/?]+)~', $path, $matches)) {
				$videoId = $matches[1];
			}
		}

		if (!is_string($videoId) || !preg_match('/^[a-zA-Z0-9_-]{11}$/', $videoId)) {
			return null;
		}

		return 'https://www.youtube-nocookie.com/embed/' . rawurlencode($videoId);
	}
}
