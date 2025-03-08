<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\APIController;
use App\Models\User;
use App\Models\Client;
use App\Models\Product;
use App\Models\Category;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class CategoryController extends APIController
{

    public function index()
    {
        $categories = Category::get(['id', 'name']);
        return $this->successResponse($categories);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors());
        }

        if ($client = Category::create($data)) {
            return $this->successResponse($client, 'Record created.');
        }

        return $this->errorResponse('Error by saving data.');

    }

}
