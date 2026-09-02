@php
// Na wypadek gdyby nastąpiło tradycyjne dodanie, zostawiamy fallback
$just_added = defined('JUST_ADDED_TO_CART') || isset($_REQUEST['add-to-cart']);
@endphp

<div x-data="{ open: {{ $just_added ? 'true' : 'false' }} }"
	@cart-open.window="open = true"
	@cart-close.window="open = false"
	class="relative z-[100]"
	x-cloak>

	<!-- 1. Tło (Backdrop blur) -->
	<div x-show="open"
		x-transition:enter="transition ease-out duration-300"
		x-transition:enter-start="opacity-0"
		x-transition:enter-end="opacity-100"
		x-transition:leave="transition ease-in duration-200"
		x-transition:leave-start="opacity-100"
		x-transition:leave-end="opacity-0"
		@click="open = false"
		class="fixed inset-0 bg-black/60 backdrop-blur-xs z-[99]"></div>

	<!-- 2. Kontener wysuwający się z prawej strony -->
	<div x-show="open"
		x-transition:enter="transition ease-out duration-300 transform"
		x-transition:enter-start="translate-x-full"
		x-transition:enter-end="translate-x-0"
		x-transition:leave="transition ease-in duration-200 transform"
		x-transition:leave-start="translate-x-0"
		x-transition:leave-end="translate-x-full"
		@keydown.escape.window="open = false"
		class="fixed top-0 right-0 bottom-0 w-full max-w-[440px] bg-white text-primary shadow-2xl z-[100] flex flex-col h-full overflow-hidden">

		<!-- Header koszyka -->
		<div class="flex items-center justify-between p-6 border-b border-gray-100">
			<h6 class="text-xl font-extrabold text-primary flex items-center gap-2">
				<span>Twój koszyk</span>
				<span class="bg-secondary/15 text-secondary text-xs px-2.5 py-0.5 rounded-full cart-count-badge">
					{{ WC()->cart->get_cart_contents_count() }}
				</span>
			</h6>
			<button @click="open = false" class="p-2 -mr-2 text-gray-400 hover:text-primary transition-colors">
				<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
				</svg>
			</button>
		</div>

		<!-- Dynamiczna zawartość koszyka (ten blok jest w całości podmieniany przez AJAX) -->
		<div class="flex-1 flex flex-col overflow-hidden cart-drawer-ajax-content">
			@include('partials.cart-drawer-content')
		</div>
	</div>
</div>