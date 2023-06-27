<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $this->validate($request, [
            'username' => 'required|min:6|max:20',
            'password' => 'required|min:8|max:30',
        ]);

        $loginType = filter_var($data['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            'password' => $data['password'],
            $loginType => $data['username'],
        ];

        if (!$token = auth('api')->attempt($credentials)) {
            return $this->customResponse('Credenciais incorretas.', null, 401);
        }

        return $this->tokenResponse($token);
    }

    public function update(Request $request)
    {
        $id = auth('api')->id();

        $data = $this->validate($request, [
            'name' => 'nullable|min:3|max:50',
            'username' => 'nullable|regex:/^[A-Za-z0-9_]+$/|min:6|max:20|unique:users,username,NULL,id,deleted_at,NULL',
            'email' => 'nullable|email|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => 'nullable|min:8|max:30'
        ], [
            'username.regex' => 'O nome de usuário pode conter apenas letras, números ou _'
        ]);

        $data['password'] = Hash::make($data['password']);

        try {

            DB::beginTransaction();

            User::where('id', $id)->update($data);
            $model = User::where('id', $id)->first();

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();

            return $this->customResponse($exception->getMessage(), null, 500);
        }

        return $this->customResponse("Usuário atualizado.", $model);
    }

    public function changeUserType(Request $request)
    {
        if(!auth('api')->user()->isAdmin()) {
            return $this->customResponse("Não autorizado.", null, 403);
        }

        $data = $this->validate($request, [
            'type' => 'required|min:3|max:15',
            'user_id' => 'required'
        ]);

        $id = $data['user_id'];
        unset($data['user_id']);

        try {

            DB::beginTransaction();

            User::where('id', $id)->update($data);
            $model = User::where('id', $id)->first();

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();

            return $this->customResponse($exception->getMessage(), null, 500);
        }

        return $this->customResponse("Usuário atualizado.", $model);
    }

    public function register(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required|min:3|max:50',
            'username' => 'required|regex:/^[A-Za-z0-9_]+$/|min:6|max:20|unique:users,username,NULL,id,deleted_at,NULL',
            'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => 'required|min:8|max:30'
        ], [
            'username.regex' => 'O nome de usuário pode conter apenas letras, números ou _'
        ]);

        try {
            DB::beginTransaction();

            $model = User::create($data);

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            return $this->customResponse($exception->getMessage(), null, 500);
        }

        return $this->customResponse('Usuário cadastrado com sucesso.', $model, 201);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return $this->customResponse('Deslogado com sucesso.');
    }

    protected function tokenResponse($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
