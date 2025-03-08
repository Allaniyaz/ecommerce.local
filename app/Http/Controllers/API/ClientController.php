<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\APIController;
use App\Models\User;
use App\Models\Client;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ClientController extends APIController
{

    public function index()
    {
        $clients = Client::get(['id', 'name']);
        return $this->successResponse($clients);
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

        if ($client = Client::create($data)) {
            return $this->successResponse($client, 'Record created.');
        }

        return $this->errorResponse('Error by saving data.');

    }

}
