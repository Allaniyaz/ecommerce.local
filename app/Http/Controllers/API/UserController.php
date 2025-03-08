<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\APIController;
use App\Models\User;
use App\Http\Resources\LogResource;
use App\Http\Requests\ClientCodeRequest;
use App\Models\Client;
use App\Models\AuthCode;
use App\Http\Resources\ClientResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AuthenticationCode;

use Illuminate\Support\Facades\Validator;

class UserController extends APIController
{

    public $successStatus = 200;

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $token = $user->createToken('MyApp');
            $success['token'] = $token->plainTextToken;
            return $this->successResponse($token->plainTextToken);
        } else {
            return $this->errorResponse('Unauthorised');
        }
    }


    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // return json_encode($request->user()); die;
        if (Auth::user()) {
            $request->user()->currentAccessToken()->delete();
            return $this->successResponse('All tokens deleted');
        } else {
            return $this->errorResponse('Unauthorised');
        }
    }


}
