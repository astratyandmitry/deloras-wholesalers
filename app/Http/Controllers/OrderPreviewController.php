<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\View\View;

final class OrderPreviewController extends Controller
{
    public function __invoke(Order $order): View
    {
        $order->load('collection', 'wholesaler', 'items', 'items.product', 'items.size');

        return view('order.preview', compact('order'));
    }
}
