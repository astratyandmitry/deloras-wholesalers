<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Enums\OrderStatus;
use App\Models\Invitation;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

final class OrderCheckoutController extends Controller
{
    public function form(Collection $collection, Request $request): View|RedirectResponse
    {
        /** @var \App\Models\Invitation $invitation */
        $invitation = Invitation::query()
            ->where('collection_id', $collection->id)
            ->where('token', $request->query('t'))
            ->first();

        abort_if(! $invitation, 404);

        /** @var \App\Models\Order $order */
        $order = $invitation->wholesaler->orders()
            ->where('collection_id', $collection->id)
            ->where('status', OrderStatus::PENDING)
            ->first();

        if (! $request->query('edit') && $order) {
            return redirect()->route('order.preview', [$order, 't' => $invitation->token]);
        }

        $items = [];
        if ($request->query('edit')) {
            $items = $order->load('items')->items
                ->keyBy(fn($item) => $item->product_id.'-'.$item->size_id)
                ->map(fn($item) => [
                    'quantity' => $item->quantity,
                    'quantity_type' => $item->quantity_type->value,
                ])->toArray();

            $items = Arr::dot($items);
        }

        $wholesaler = $invitation->wholesaler;
        $products = $collection->products()->with('sizes')->get();

        return view('order.checkout', compact('collection', 'wholesaler', 'products', 'items'));
    }

    public function store(Collection $collection, Request $request): RedirectResponse
    {
        /** @var \App\Models\Invitation $invitation */
        $invitation = Invitation::query()
            ->where('collection_id', $collection->id)
            ->where('token', $request->query('t'))
            ->first();

        abort_if(! $invitation, 404);

        $validated = $request->validate([
            'data' => 'required|array',
            'data.*.product_id' => 'integer|required|exists:products,id',
            'data.*.size_id' => 'integer|required|exists:product_sizes,id',
            'data.*.quantity' => 'numeric|nullable',
            'data.*.quantity_type' => 'required|in:series,piece',
        ]);

        $items = collect($validated['data'])->filter(fn(array $item) => (int) $item['quantity'] > 0);

        if ($items->isEmpty()) {
            return redirect()->back();
        }

        $order = DB::transaction(function () use ($invitation, $collection, $items) {
            $invitation->wholesaler->orders()
                ->where('collection_id', $collection->id)
                ->where('status', OrderStatus::PENDING)
                ->update(['status' => OrderStatus::UPDATED]);

            /** @var \App\Models\Order $order */
            $order = $invitation->wholesaler->orders()->create([
                'collection_id' => $collection->id,
            ]);

            $order->items()->createMany($items);

            return $order;
        });

        return redirect()->route('order.preview', [$order, 't' => $invitation->token]);
    }
}
