<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\APIController;
use App\Models\User;
use App\Models\ProviderCategory;
use App\Models\Category;
use App\Models\Provider;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ProviderController extends APIController
{

    public function index()
    {
        $providers = Provider::get(['id', 'name']);
        return $this->successResponse($providers);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'categories' => 'required|array',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors());
        }

        if (!empty($data['categories']) && $provider = Provider::create($data)) {

            foreach ($data['categories'] as $cat_id) {
                if (Category::where('id', $cat_id)->exists()) {
                    ProviderCategory::create([
                        'provider_id' => $provider->id,
                        'category_id' => $cat_id,
                    ]);
                }
            }

            return $this->successResponse($provider, 'Record created.');
        }

        return $this->errorResponse('Error by saving data.');

    }

}
