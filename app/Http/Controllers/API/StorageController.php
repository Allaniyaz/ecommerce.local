<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\APIController;
use App\Models\User;
use App\Models\Client;
use App\Models\Product;
use App\Models\Storage;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class StorageController extends APIController
{

    public function index()
    {
        $storages = Storage::get(['id', 'name']);
        return $this->successResponse($storages);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors());
        }

        if ($storage = Storage::create($data)) {
            return $this->successResponse($storage, 'Record created.');
        }

        return $this->errorResponse('Error by saving data.');

    }

}
