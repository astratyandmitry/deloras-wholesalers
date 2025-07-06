@php /** @var \App\Models\Collection $collection */ @endphp
@php /** @var \App\Models\Wholesaler $wholesaler */ @endphp
@php /** @var \App\Models\Product[] $products */ @endphp

@extends('layouts.app', ['title' => "Оформление заказа: {$collection->name}"])

@section('content')
    <div class="container mx-auto mt-8 pb-24 p-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold uppercase">Оформление заказа</h1>
            <p class="text-xl text-2xl">Коллекция: {{ $collection->name }}</p>
        </div>

        <form method="POST" class="bg-white mt-8 divide-y divide-gray-200 space">
            <div class="fixed bottom-0 inset-x-0 bg-white/70 backdrop-blur-xl  text-center p-4">
                <button type="submit"
                        class="w-60 py-3 text-white cursor-pointer uppercase font-bold text-lg bg-pink-600">
                    Отправить заявку
                </button>
            </div>

            @csrf
            @foreach($products as $product)
                <div class="p-4 md:flex space-x-4">
                    <div class="flex-shrink-0 mb-4 md:mb-0">
                        <a href="{{ url()->to($product->image) }}" target="_blank">
                            <img src="{{ url()->to($product->image) }}" class="md:max-h-96">
                        </a>
                    </div>

                    <div class="flex-1">
                        <h2 class="text-3xl font-semibold">{{ $product->sku }} за <span
                                class="text-red-700">${{ $product->price_usd }}</span></h2>
                        <p class="text-xl">{{ $product->description }}</p>

                        <div class="bg-gray-200 mt-4 p-4 space-y-4">
                            @foreach($product->sizes as $size)
                                <div
                                    class="md:flex items-center space-x-4"
                                    x-data="{
                                        quantity: {{ (int)\Illuminate\Support\Arr::get($items, "{$product->id}-{$size->id}.quantity") }},
                                        unitPrice: {{ $product->price_usd }},
                                        get total() {
                                            return (this.quantity * this.unitPrice);
                                        },
                                        increase() {
                                            if (this.quantity < 100) this.quantity++;
                                        },
                                        decrease() {
                                            if (this.quantity > 0) this.quantity--;
                                        }
                                    }"
                                >
                                    <div class="flex items-center justify-between md:hidden">
                                        <h3 class="text-2xl font-semibold text-right">{{ $size->name }}</h3>
                                        <h3 class="text-2xl font-medium" x-text="`$${total}`"></h3>
                                    </div>

                                    <h3 class="text-2xl font-semibold text-right w-[160px] hidden md:block">{{ $size->name }}</h3>

                                    <div class="flex items-center space-x-1 justify-center">
                                        <button
                                            type="button"
                                            class="bg-amber-600 text-white px-6 py-3 text-lg font-semibold"
                                            x-on:click="decrease"
                                        >-
                                        </button>

                                        <input type="hidden" name="data[{{ $product->id . $loop->index }}][product_id]"
                                               value="{{ $product->id }}">
                                        <input type="hidden" name="data[{{ $product->id . $loop->index }}][size_id]"
                                               value="{{ $size->id }}">

                                        <input
                                            type="number"
                                            name="data[{{ $product->id . $loop->index }}][quantity]"
                                            class="bg-white p-3 text-center text-lg font-medium w-full"
                                            x-model.number="quantity"
                                            min="0"
                                            max="100"
                                            step="1"
                                        >

                                        <select
                                            name="data[{{ $product->id . $loop->index }}][quantity_type]"
                                            class="bg-white p-3 text-center text-lg font-medium border-l border-gray-300 appearance-none"
                                        >
                                            <option value="series"
                                                    @if (\Illuminate\Support\Arr::get($items, "{$product->id}-{$size->id}.quantity_type") === 'series') selected @endif>
                                                серия
                                            </option>
                                            <option value="piece"
                                                    @if (\Illuminate\Support\Arr::get($items, "{$product->id}-{$size->id}.quantity_type") === 'piece') selected @endif>
                                                штука
                                            </option>
                                        </select>

                                        <button
                                            type="button"
                                            class="bg-green-600 text-white px-6 py-3 text-lg font-semibold"
                                            x-on:click="increase"
                                        >+
                                        </button>
                                    </div>

                                    <h3 class="text-2xl hidden md:block font-medium" x-text="`$${total}`"></h3>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.9/dist/cdn.min.js"></script>
@endsection
