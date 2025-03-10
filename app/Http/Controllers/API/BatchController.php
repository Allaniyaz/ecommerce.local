<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\APIController;
use App\Models\OrderProduct;
use App\Models\Batch;
use App\Models\BatchProduct;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class BatchController extends APIController
{

    public function index()
    {
        $batches = Batch::with(['products'])->get();
        return $this->successResponse($batches);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'nullable',
            'provider_id' => 'required|integer|exists:providers,id',
            'storage_id' => 'required|integer|exists:storages,id',
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
                'price' => 'required|integer',
            ]);

            if ($validator2->fails()) {
                $err[] = $validator2->errors();
            }
        }

        if (!empty($err)) {
            return $this->errorResponse($err);
        }
        //


        $batch = new Batch;
        $batch->provider_id = $data['provider_id'];
        $batch->storage_id = $data['storage_id'];
        $batch->save();

        foreach ($data['products'] as $prod) {
            $batch->products()->attach($prod['id'], [
                    'qty' => $prod['qty'],
                    'price' => $prod['price'],
                    'remain_qty' => $prod['qty'],
                    'storage_id' => $data['storage_id']
            ]);
        }

        return $this->successResponse($batch);
    }

    public function profit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:batches,id'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors());
        }

        $batch_products = BatchProduct::where('batch_id', $request->id)->get();
        $order_products = OrderProduct::where('batch_id', $request->id)->get();

        $payed_total = $batch_products->pluck('payed')->sum();
        $sold_total = $order_products->pluck('sold')->sum();

        return $this->successResponse($sold_total - $payed_total);
    }

}
