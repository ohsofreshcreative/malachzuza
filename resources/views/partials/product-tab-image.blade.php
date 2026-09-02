@if (!empty($row['tab_image']))
<x-picture :image="$row['tab_image']" class="w-full h-auto object-contain" />
@endif
