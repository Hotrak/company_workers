<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function index()
    {


    }

    public function login(Request $request)
    {
        if(!auth()->attempt($request->all(['email','password'])))
        {
            return response(['message'=>'Не верно введён логин или пароль'], 422);
        }

        $user = auth()->user();

        $token = $user->createToken('AuthToken')->accessToken;

        return ['token' => $token,'user' => $user];
    }

    public function store(RegisterRequest $request)
    {


//        $validator = Validator::make($request->all(), $rules, $messages);
//
//        if ($validator->fails()) {
//            return response(["errors" => $validator->errors()->all()],422);
//        }

        $request['password']=Hash::make($request->password);
        $user = User::create($request->all(['name','email','password']));

        $token = $user->createToken('AuthToken')->accessToken;

        return ['token' => $token,'user'=>$user];
    }

    public function logout()
    {

    }
}
