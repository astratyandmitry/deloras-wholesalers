<?php

namespace App\Http\Controllers;

use App\Models\Enums\OrderStatus;
use App\Models\Invitation;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class OrderPreviewController extends Controller
{
    public function __invoke(Order $order, Request $request): View
    {
        /** @var \App\Models\Invitation $invitation */
        $invitation = Invitation::query()
            ->where('token', $request->get('t'))
            ->first();

        $order->load('collection', 'wholesaler', 'items', 'items.product', 'items.size');

        $editable = $invitation
            && $invitation->wholesaler_id === $order->wholesaler_id
            && $order->status === OrderStatus::PENDING;

        return view('order.preview', compact('order', 'editable'));
    }
}
