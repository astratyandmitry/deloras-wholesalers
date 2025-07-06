@php /** @var \App\Models\Order $order */ @endphp

@extends('layouts.app', ['title' => "Заказ {$order->code}"])

@section('content')
    <div class="container mx-auto my-8 p-4">
        <div class="flex flex-col md:flex-row gap-8 md:items-center justify-between">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold uppercase">Заказ {{ $order->code }}</h1>
                <p class="text-xl md:text-2xl">Заказчик: {{ $order->wholesaler->label }}</p>
            </div>

            @if ($editable)
                <div>
                    <a href="{{ route('order.checkout', [$order->collection, 't' => request()->query('t'), 'edit' => true]) }}"
                       class="bg-orange-100 px-6 py-3 text-orange-700 hover:bg-orange-200 uppercase font-medium text-sm">
                        Изменить заявку
                    </a>
                </div>
            @endif
        </div>

        <table class="w-full mt-8 bg-white">
            <tr>
                <th class="p-3 uppercase text-left text-sm font-semibold text-gray-600">#</th>
                <th class="p-3 uppercase text-left text-sm font-semibold text-gray-600">Товар</th>
                <th class="p-3 uppercase text-left text-sm font-semibold text-gray-600">Размер</th>
                <th class="p-3 uppercase text-left text-sm font-semibold text-gray-600">Кол-во</th>
            </tr>
            @foreach($order->items as $item)
                <tr class="border-t border-gray-200">
                    <td class="p-3 text-xl font-medium">
                        {{ $loop->iteration }}
                    </td>
                    <td class="p-3 text-xl font-medium">
                        {{ $item->product->sku }}
                    </td>
                    <td class="p-3 text-xl font-medium">
                        {{ $item->size->name }}
                    </td>
                    <td class="p-3 text-xl font-medium">
                        {{ $item->quantity }} {{ $item->quantity_type->getShortLabel() }}
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
