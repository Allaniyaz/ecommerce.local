<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\APIController;
use App\Models\User;
use App\Models\Client;
use App\Models\Subcategory;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class SubcategoryController extends APIController
{

    public function index()
    {
        $subcategories = Subcategory::with('category')->get();
        return $this->successResponse($subcategories);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors());
        }

        if ($subcategory = Subcategory::create($data)) {
            return $this->successResponse($subcategory, 'Record created.');
        }

        return $this->errorResponse('Error by saving data.');

    }

}
