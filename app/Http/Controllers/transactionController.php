<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class transactionController extends Controller
{
    private $response = ['error' => '', 'result' => []];

    public function deposit(Request $request, $accountNumber) {
        $type = 'deposit';
        $value = $request->input('value');
        $date = now();


        $deposit = Transaction::deposit($type, $value, $accountNumber, $date);

        if($deposit) {
            $account = DB::table('accounts')->where('account_number', '=', $accountNumber)->first();

            $updateSaldo = DB::table('accounts')->where('account_number', '=', $accountNumber)->update([
                'saldo' => $account->saldo + $value,
            ]);


            return $this->response['result'] = 'Depósito concluído';
        } else {
            return $this->response['error'] = 'Algo deu de errado';
        }
    }

    public function withdraw(Request $request, $accountNumber) {
        $type = 'withdraw';
        $value = $request->input('value');
        $date = now();

        $withdraw = Transaction::withdraw($type, $value, $accountNumber, $date);

        if($withdraw) {
            $account = DB::table('accounts')->where('account_number', '=', $accountNumber)->first();

            $updateSaldo = DB::table('accounts')->where('account_number', '=', $accountNumber)->update([
                'saldo' => $account->saldo - $value,
            ]);

            return $this->response['result'] = 'Saque efetivado';
        } else {
            return $this->response['error'] = 'Algo deu de errado';
        }
    }

    public function getAllTransactions($accountNumber) {
        return Transaction::getAllTransactions($accountNumber);
    }

    public function getTransactionsBetween(Request $request) {
        $from = date($request->input('from'));
        $to = date($request->input('to'));

        if(strtotime($from) >= strtotime($to)) {
            $query = DB::table('transactions')->whereBetween('date', [$from, $to])->get();
            return $query;        
        } else {
            return 'Data inicial não pode ser maior que a data final';
        }

        
    }
}
