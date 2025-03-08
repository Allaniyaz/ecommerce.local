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

class BatchController extends APIController
{

    public function index()
    {

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

        ///////
        // $data['customer_id'] = $request->user()->customer->id;
        // $data['registration_number'] = strtoupper($data['registration_number']);
        // $data['techpassport'] = strtoupper($data['techpassport_series']) . $data['techpassport_number'];


        if ($garage = Garage::create($data)) {
            $garage->name = $garage->model->name;
            $garage->save();
            return $this->successResponse(new GarageResource($garage), 'Record created.');
        }

        return $this->errorResponse('Error by saving data.');

    }

}
