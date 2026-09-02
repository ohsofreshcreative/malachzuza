@if (!empty($colors))
<div class="__tab-ral-palette">
	<div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-10 xl:grid-cols-12 gap-1.5">
		@foreach ($colors as $color)
		<div class="relative aspect-4/3 rounded-md overflow-hidden shadow-sm ring-1 ring-black/5" style="background-color: {{ $color['hex'] }};">
			<span class="absolute inset-x-0 bottom-0 bg-linear-to-t from-black/60 to-transparent px-1.5 pt-3 pb-1 text-[10px] leading-tight font-semibold text-white">
				{{ $color['label'] }}
			</span>
		</div>
		@endforeach
	</div>
</div>
@endif
