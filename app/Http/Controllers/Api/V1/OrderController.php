<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\order\CreateOrder;
use App\Http\Requests\order\UpdateOrder;
use App\Http\Resources\orders\OrderResource;
use App\Models\Order;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function create(CreateOrder $request) {
        $data = $request->only('product_name', 'amount');
        $order = Order::create([
            'user_id' => Auth::id(),
            'product_name' => $data['product_name'],
            'amount' => $data['amount'],
        ]);
        return [
            'order_id' => $order->id,
            'msg' => 'order_success'
        ];
    }

    public function getUserOrders() {
        $orders = Order::where('user_id', Auth::id())->get();
        return OrderResource::collection($orders);
    }

    public function update(UpdateOrder $request, $id)
    {
        $order = Order::findOrFail($id);
        if ($order->user_id !== Auth::id()) {
            return response()->json(['msg' => 'order_not_found'], 403);
        }
        $oldStatus = $order->status;
        $order->update($request->only(['product_name', 'amount', 'status']));
        if ($oldStatus != $order->status) {
            $order->user->notify(new OrderStatusUpdated($order));
        }

        return response()->json([
            'msg' => 'order_updated',
            'order' => new OrderResource($order),
        ]);
    }

    public function delete($id) {
        $order = Order::findOrFail($id);
        if ($order->user_id !== Auth::id()) {
            return response()->json(['msg' => 'order_not_found'], 403);
        }
        $order->delete();

        return response()->json(['msg' => 'order_deleted'], 200);
    }
}
