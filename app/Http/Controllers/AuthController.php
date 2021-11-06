<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['create', 'login', 'unauthorized']]);
    }

    public function unauthorized()
    {
        return response()->json(['error' => 'Não autorizado'], 401);
    }

    public function login(Request $request)
    {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            $array['error'] = 'Dados incorretos';
            return $array;
        }

        $email = $request->email;
        $password = $request->password;

        $token = Auth::attempt([
            'email' => $email,
            'password' => $password
        ]);

        if (!$token) {
            $array['error'] = 'Ocorreu um erro';
            return $array;
        }

        $info = Auth::user();
        $info->avatar = url('media/avatars/' . $info->avatar);
        $array['data'] = $info;
        $array['token'] = $token;

        return $array;
    }

    public function logout()
    {
        Auth::logout();

        return ['error' => ''];
    }

    public function refresh()
    {
        $array = ['error' => ''];
        $token = Auth::refresh();

        $info = Auth::user();
        $info->avatar = url('media/avatars/' . $info->avatar);
        $array['data'] = $info;
        $array['token'] = $token;

        return $array;
    }

    public function create(Request $request)
    {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'password_confirm' => 'required',
        ]);

        if ($validator->fails()) {
            $array['error'] = 'Dados incorretos';
            return $array;
        }

        $name = $request->name;
        $email = $request->email;
        $password = $request->password;
        $passwordConfirm = $request->password_confirm;

        if ($password != $passwordConfirm) {
            $array['error'] = 'As senhas não conferem';
            return $array;
        }

        $emailExistis = User::where('email', $email)->count();

        if ($emailExistis > 0) {
            $array['error'] = 'Email já cadastrado';
            return $array;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $newUser = new User();
        $newUser->name = $name;
        $newUser->email = $email;
        $newUser->password = $hash;
        $newUser->save();

        $token = Auth::attempt([
            'email' => $email,
            'password' => $password
        ]);

        if (!$token) {
            $array['error'] = 'Ocorreu um erro';
            return $array;
        }

        $info = Auth::user();
        $info->avatar = url('media/avatars/' . $info->avatar);
        $array['data'] = $info;
        $array['token'] = $token;

        return $array;
    }
}
