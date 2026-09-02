@php
/**
 * Product quantity input overridden with Blade & Alpine.js
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 */

defined('ABSPATH') || exit;

$min = is_numeric($min_value) ? $min_value : 1;
$max = is_numeric($max_value) && $max_value > 0 ? $max_value : 9999;
$step = is_numeric($step) ? $step : 1;
$is_hidden = ($max_value && $min_value === $max_value);
@endphp

@if ($is_hidden)
    <div class="quantity hidden">
        <input type="hidden" id="{{ esc_attr($input_id) }}" class="qty" name="{{ esc_attr($input_name) }}" value="{{ esc_attr($min_value) }}" />
    </div>
@else
    <div class="quantity" x-data="{ 
        value: {{ (int) $input_value ?: 1 }}, 
        min: {{ $min }}, 
        max: {{ $max }}, 
        step: {{ $step }},
        decrement() { if (this.value > this.min) this.value = Math.max(this.min, this.value - this.step) },
        increment() { if (this.value < this.max) this.value = Math.min(this.max, this.value + this.step) }
    }">
        <div class="flex items-center border-1 border-secondary overflow-hidden radius w-fit h-[66px] select-none bg-white">
            
            {{-- Przycisk Minus --}}
            <button type="button" 
                @click="decrement()" 
                class="px-4 py-2 hover:bg-secondary-50 transition-colors text-primary font-bold text-lg leading-none cursor-pointer h-full flex items-center justify-center border-0 bg-transparent min-w-[40px]">
                −
            </button>
            
            {{-- Standardowy Input WooCommerce (schowane strzałki) --}}
            <input
                type="number"
                id="{{ esc_attr($input_id) }}"
                class="qty !m-0 !p-0 !border-0 text-center w-12 h-10 border-x border-secondary focus:outline-none focus:ring-0 text-primary-500 font-semibold [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                step="{{ esc_attr($step) }}"
                min="{{ esc_attr($min_value) }}"
                max="{{ esc_attr($max_value) }}"
                name="{{ esc_attr($input_name) }}"
                x-model.number="value"
                @input="value = Math.min(max, Math.max(min, parseInt($event.target.value) || min))"
            />
            
            {{-- Przycisk Plus --}}
            <button type="button" 
                @click="increment()" 
                class="px-4 py-2 hover:bg-secondary-50 transition-colors text-primary font-bold text-lg leading-none cursor-pointer h-full flex items-center justify-center border-0 bg-transparent min-w-[40px]">
                +
            </button>
        </div>
    </div>
@endif