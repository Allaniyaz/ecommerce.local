<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\APIController;
use App\Models\User;
use App\Models\Client;
use App\Models\Product;
use App\Models\BatchProduct;
use App\Models\Order;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class OrderController extends APIController
{
    public function index()
    {

    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'nullable',
            'client_id' => 'required|integer|exists:clients,id',
            'products' => 'required|array',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors());
        }

        // validating products
        $err = [];
        foreach($data['products'] as $product) {

            $validator2 = Validator::make($product, [
                'id' => 'required|integer|exists:products,id',
                'name' => 'nullable',
                'qty' => 'required|integer',
            ]);

            if ($validator2->fails()) {
                $err[] = $validator2->errors();
            }
        }

        if (!empty($err)) {
            return $this->errorResponse($err);
        }


        $available = [];
        for ($i = 0; $i < count($data['products']); $i++) {
            $available[$i] = BatchProduct::where('product_id', $data['products'][$i]['id'])
                ->where('remain_qty', '>=', $data['products'][$i]['qty'])
                ->orderBy('created_at')
                ->first();
            // if ($available[$i]) {
            //     return $this->errorResponse('Unfortunately, there is no product with the specified quantity in stock.');
            // }
        }


        $order = new Order;
        $order->client_id = $data['client_id'];
        $order->save();

        for ($i = 0; $i < count($available); $i++) {
            $product = Product::find($data['products'][$i]['id']);
            $order->products()->attach($product->id, [
                'qty' => $data['products'][$i]['qty'],
                'price' => $product->price,
                'batch_id' => $available[$i]->id,
            ]);

            $available[$i]->remain_qty -= $data['products'][$i]['qty'];
            $available[$i]->save();
        }

        return $this->successResponse($order);
    }

}
