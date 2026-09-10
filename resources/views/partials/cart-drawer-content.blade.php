@if (WC()->cart->is_empty())
    <!-- Stan: Pusty koszyk -->
    <div class="flex-1 flex flex-col items-center justify-center p-8 text-center text-primary-200">
        <img src="{{ get_template_directory_uri() }}/resources/images/cart.svg" class="w-16 h-16 opacity-30 mb-4 filter invert" alt="Pusty koszyk" />
        <p class="text-lg font-bold text-primary mb-2">Twój koszyk jest pusty</p>
        <p class="text-sm mb-6 max-w-[250px] mx-auto">Dodaj produkty do koszyka, aby kontynuować zakupy.</p>
        <button @click="open = false" class="btn btn-secondary !py-3 !px-8 text-sm">
            Kontynuuj zakupy
        </button>
    </div>
@else
    <!-- Stan: Produkty w koszyku -->
    <div class="flex-1 overflow-y-auto divide-y divide-gray-100 px-6 py-4 space-y-4 custom-scrollbar">
        @foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item)
            @php
                $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                if (!$_product || !$_product->exists() || $cart_item['quantity'] <= 0) continue;
                $product_id = $cart_item['product_id'];
                $product_name = $_product->get_name();
                $product_price = WC()->cart->get_product_price($_product);
                $product_price_total = WC()->cart->get_product_subtotal($_product, $cart_item['quantity']);
                $thumbnail = $_product->get_image('thumbnail', ['class' => 'w-full h-full object-contain']);
                $permalink = $_product->is_visible() ? $_product->get_permalink($cart_item) : '';
                $remove_url = wc_get_cart_remove_url($cart_item_key);
            @endphp
            <div class="flex gap-4 py-4 first:pt-2 last:pb-2 group relative items-center">
                <div class="shrink-0 w-16 h-16 flex items-center justify-center bg-background border border-primary/25 rounded-lg p-1 overflow-hidden">
                    @if ($permalink)
                        <a class="block w-full h-full" href="{{ $permalink }}">{!! $thumbnail !!}</a>
                    @else
                        {!! $thumbnail !!}
                    @endif
                </div>
                
                <div class="flex-1 min-w-0 pr-6">
                    @if ($permalink)
                        <a href="{{ $permalink }}" class="font-bold text-sm text-primary hover:text-primary-200 transition-colors block truncate">
                            {{ $product_name }}
                        </a>
                    @else
                        <span class="font-bold text-sm text-primary block truncate">{{ $product_name }}</span>
                    @endif
                    <div class="text-xs text-primary-200 mt-1 flex items-center gap-1.5">
                        <span>Ilość: <strong class="text-primary">{{ $cart_item['quantity'] }}</strong></span>
                        <span class="text-primary-200">•</span>
                        <span>{!! $product_price !!}</span>
                    </div>
                    <div class="mt-1 font-bold text-sm text-primary">
                        {!! $product_price_total !!}
                    </div>
                </div>

                <div class="absolute right-0 top-1/2 -translate-y-1/2">
                    <a href="{{ $remove_url }}" 
                       class="remove remove_from_cart_button text-primary-200 hover:text-red-500 transition-colors text-2xl font-light p-1"
                       aria-label="Usuń produkt" 
                       data-product_id="{{ $product_id }}" 
                       data-cart_item_key="{{ $cart_item_key }}" 
                       data-product_sku="{{ $_product->get_sku() }}">&times;</a>
                </div>
            </div>
        @endforeach
    </div>

      <!-- Podsumowanie wartości i Przyciski dolne -->
    <div class="border-t border-primary/25 p-6 bg-primary-dark space-y-4">
        <div class="flex justify-between items-center text-sm font-medium">
            <span class="text-primary-200 text-base">Wartość koszyka:</span>
            <span class="font-extrabold text-xl text-primary">{!! WC()->cart->get_cart_subtotal() !!}</span>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <!-- Poprawiono ważność klasy (text-primary!) oraz tekst na 'Koszyk' -->
            <a href="{{ wc_get_cart_url() }}" class="bg-background! border-2 border-primary/25 text-primary! hover:bg-primary-dark hover:border-primary transition-colors text-center font-bold py-3 rounded-lg text-sm shadow-xs flex items-center justify-center w-full">
               Koszyk
            </a>
            <a href="{{ wc_get_checkout_url() }}" class="btn btn-secondary text-center font-bold !py-3 !rounded-lg text-sm shadow-xs flex items-center justify-center w-full">
                Zamówienia
            </a>
        </div>
    </div>
@endif	