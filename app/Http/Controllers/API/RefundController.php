<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\APIController;
use App\Models\User;
use App\Models\Refund;
use App\Models\Product;
use App\Models\BatchProduct;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class RefundController extends APIController
{
    public function index()
    {
        $refunds = Refund::with([
                'client',
                'provider',
                'batch',
                'order',
                'product_id',
            ])->get();
        return $this->successResponse($refunds);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'nullable|max:500',
            'provider_id' => 'nullable|required_without:client_id,order_id|integer|exists:providers,id',
            'client_id' => 'nullable|required_without:provider_id|required_with:order_id|integer|exists:clients,id',
            'order_id' => 'nullable|required_with:client_id|integer|exists:orders,id',
            'batch_id' => 'required|integer|exists:batches,id',
            'product_id' => 'required|integer|exists:products,id',
            'qty' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors());
        }

        if ($data['client_id']) {
            $refund = new Refund;
            $refund->name = $data['name'] ?? '';
            $refund->client_id = $data['client_id'];
            $refund->order_id = $data['order_id'];
            $refund->batch_id = $data['batch_id'];
            $refund->product_id = $data['product_id'];
            $refund->qty = $data['qty'];
            $refund->save();

            $batch_product = BatchProduct::where('batch_id', $refund->batch_id)
                ->where('product_id', $refund->product_id)
                ->first();
            if ($batch_product) {
                $batch_product->is_refunded = 1;
                $batch_product->remain_qty += $refund->qty;
                $batch_product->save();
            }

            $order_product = OrderProduct::where('order_id', $refund->order_id)
                ->where('batch_id', $refund->batch_id)
                ->where('product_id', $refund->product_id)
                ->first();
            if ($order_product) {
                $order_product->is_refunded = 1;
                $order_product->qty -= $refund->qty;
                $order_product->save();
            }

        } else if ($data['provider_id']) {
            $refund = new Refund;
            $refund->provider_id = $data['provider_id'];
            $refund->batch_id = $data['batch_id'];
            $refund->product_id = $data['product_id'];
            $refund->qty = $data['qty'];
            $refund->save();

            $batch_product = BatchProduct::where('batch_id', $refund->batch_id)
                ->where('product_id', $refund->product_id)
                ->first();
            if ($batch_product) {
                $batch_product->is_refunded = 1;
                $batch_product->qty -= $refund->qty;
                $batch_product->remain_qty -= $refund->qty;
                $batch_product->save();
            }
        }

        return $this->successResponse($refund);

    }

}
