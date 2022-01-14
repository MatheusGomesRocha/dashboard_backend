<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class accountController extends Controller
{
    private $response = ['error' => '', 'result' => []];
    
    public function getSaldo($accountNumber) {
        $saldo = Account::getSaldo($accountNumber);

        if($saldo) {
            return $saldo;
        } else {
            return $this->response['error'] = 'Ocorreu algum erro';
        }
    }
}
