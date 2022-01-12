<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class userController extends Controller
{
    public function createUser(Request $request) {
        $hasUser = User::hasUser($request->input('cpf'));

        if($hasUser == 1) {
            return $this->response['error'] = 'Usuário já cadastrado';
        } else {
            $randomNumber = rand(999999, 100000);
            $account = $randomNumber.'-'.rand(9, 1);

            $data = [
                'name' => $request->input('name'),
                'avatar' => '',
                'cpf' => $request->input('cpf'),
                'account' => $account,
                'email' => $request->input('email'),
                'password' => hash::make($request->input('password')),
            ];

            $create = DB::table('users')->insert($data);

            if($create) {
                return $this->response['result'] = 'Usuário cadastrado com sucesso';
            } else {
                return $this->response['error'] = 'Ocorreu algum erro, tente novamente';
            }
        }
    }

    public function login(Request $request) {
        $cpf = $request->input('cpf');
        $password = $request->input('password');

        $hasUser = User::hasUser($cpf);

        $credentials = [
            'cpf' => $cpf,
            'password' => $password
        ];

        if (Auth::attempt($credentials)) {
            if(Auth::check()) {
                $userInfo = User::getUser($cpf);

                return $this->response['result'] = [
                    'id' => $userInfo->id,
                    'account' => $userInfo->account,
                    'email' => $userInfo->email,
                    'password' => $userInfo->password,
                ];    
            }
            
        } else {
            return $this->response['error'] = "Credenciais incorretas";
        }
    }

    public function updateAvatar(Request $request, $userId) {
        $image = $request->file('avatar');

        if ($image) {
            if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
                $img = rand();

                $extensao = $request->file('avatar')->extension();
                $file = "$img.$extensao";
                $upload = $request->file('avatar')->storeAs('public/media/avatars/', $file);

                DB::table('users')->where('id', '=', $userId)->update([
                    'avatar' => $file,
                ]);

                $this->response['result'] = url('storage/media/avatars/' . $file);
            } else {
                $this->response['error'] = 'File not supported';
            }
        } else {
            $this->response['error'] = 'Send a file';
        }

        return $this->response;
    }
}
