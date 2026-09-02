@if (!empty($row['tab_text']))
<div class="__tab-text mb-6">
	{!! $row['tab_text'] !!}
</div>
@endif

@if (!empty($row['tab_files']))
<ul class="__tab-files flex flex-col">
	@foreach ($row['tab_files'] as $file)
	@if (!empty($file['file']['url']))
	<li class="flex items-center gap-4 py-4 border-b border-primary-lighter px-0 md:px-10">
		<img src="{{ get_template_directory_uri() }}/resources/images/file.svg" alt="" class="w-6 h-6 shrink-0" />
		<span class="">{{ $file['file_title'] }}</span>
		<a href="{{ $file['file']['url'] }}" target="_blank" class="btn btn-underline ml-0 md:ml-40">Pobierz</a>
	</li>
	@endif
	@endforeach
</ul>
@endif
