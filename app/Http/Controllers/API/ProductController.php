<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\APIController;
use App\Models\User;
use App\Models\Client;
use App\Models\Product;
use App\Models\BatchProduct;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ProductController extends APIController
{

    public function index()
    {
        $products = Product::with(['subcategory'])->get();
        return $this->successResponse($products);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'price' => 'required|integer',
            'subcategory_id' => 'required|integer|exists:subcategories,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors());
        }

        if ($product = Product::create($data)) {
            return $this->successResponse($product, 'Record created.');
        }

        return $this->errorResponse('Error by saving data.');

    }

    public function available(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors());
        }

        $date = $request->date ? $request->date : date('Y-m-d');

        $data = DB::table('batches as t1')
            ->join('batch_product as t3', 't1.id', '=', 't3.batch_id')
            ->join('products as t2', 't3.product_id', '=', 't2.id')
            ->join('subcategories as t4', 't2.subcategory_id', '=', 't4.id')
            ->join('categories as t5', 't4.category_id', '=', 't5.id')
            ->select('t2.id', 't2.name', DB::raw('SUM(t3.remain_qty) as remain_qty'), 't2.price', 't5.name as category')
            ->where('t3.updated_at', '<=', $date . ' 23:59:59')
            ->groupBy('t2.id', 't2.name')
            ->get();

        return $this->successResponse($data);
    }

}
