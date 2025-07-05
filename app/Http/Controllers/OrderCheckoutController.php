<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Invitation;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        $order = $invitation->wholesaler->orders()->where('collection_id', $collection->id)->first();

        if ($order) {
            return redirect()->route('order.preview', $order);
        }

        $wholesaler = $invitation->wholesaler;
        $products = $collection->products()->with('sizes')->get();

        return view('order.checkout', compact('collection', 'wholesaler', 'products'));
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

        /** @var \App\Models\Order $order */
        $order = $invitation->wholesaler->orders()->create([
            'collection_id' => $collection->id,
        ]);

        $order->items()->createMany($items);

        return redirect()->route('order.preview', $order);
    }
}
