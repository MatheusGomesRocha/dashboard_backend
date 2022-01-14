<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class userController extends Controller
{
    private $response = ['error' => '', 'result' => []];
    
    public function createUser(Request $request) {
        $hasUser = User::hasUser($request->input('cpf'));
        
        if($hasUser == 1) {
             $this->response['error'] = ['Usuário já cadastrado'];
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
                $createAccount = DB::table('accounts')->insert([
                    'account_number' => $account,
                    'saldo' => 0
                ]);

                $this->response['result'] = 'Usuário cadastrado com sucesso';
            } else {
                $this->response['error'] = 'Ocorreu algum erro, tente novamente';
            }
        }

        return $this->response;
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

                $this->response['result'] = [
                    'id' => $userInfo->id,
                    'avatar' => $userInfo->avatar,
                    'name' => $userInfo->name,
                    'account' => $userInfo->account,
                ];    
            }
            
        } else {
            $this->response['error'] = "Credenciais incorretas";
        }

        return $this->response;
    }
}
