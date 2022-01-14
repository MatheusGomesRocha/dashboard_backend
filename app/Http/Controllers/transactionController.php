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
        $transactions = Transaction::getAllTransactions($accountNumber);

        if($transactions) {
            return $transactions;
        } else {
            return $this->response['error'] = 'Ocorreu algum erro';
        }
    }

    public function getTransactionsBetween(Request $request) {
        $from = date($request->input('from'));
        $to = date($request->input('to'));

        $formatFrom = date('Y-m-d', strtotime($from));
        $formatTo = date('Y-m-d', strtotime($to));

        if(strtotime($formatTo) >= strtotime($formatFrom)) {
            $query = DB::table('transactions')->whereBetween('date', [$formatFrom, $formatTo])->orderByRaw('date DESC')->get();
            return $query;        
        } else {
            return 'Data inicial não pode ser maior que a data final';
        }
    }

    public function getBalanceHistory(Request $request, $accountNumber) {
        $date = date($request->input('date'));
        
        $now = now();
        
        $today = date('Y-m-d', strtotime($now));
        $yesterday = date('Y-m-d',strtotime("-1 days"));
        $twoDays = date('Y-m-d',strtotime("-2 days"));
        $threeDays = date('Y-m-d',strtotime("-3 days"));
        $fourDays = date('Y-m-d',strtotime("-4 days"));
        $fiveDays = date('Y-m-d',strtotime("-5 days"));
        $sixDays = date('Y-m-d',strtotime("-6 days"));

        $query6 = DB::table('transactions')->where('account_number', $accountNumber)->where('date', $sixDays)->get();
        $query5 = DB::table('transactions')->where('account_number', $accountNumber)->where('date', $fiveDays)->get();
        $query4 = DB::table('transactions')->where('account_number', $accountNumber)->where('date', $fourDays)->get();
        $query3 = DB::table('transactions')->where('account_number', $accountNumber)->where('date', $threeDays)->get();
        $query2 = DB::table('transactions')->where('account_number', $accountNumber)->where('date', $twoDays)->get();
        $query = DB::table('transactions')->where('account_number', $accountNumber)->where('date', $yesterday)->get();
        $queryToday = DB::table('transactions')->where('account_number', $accountNumber)->where('date', $today)->get();

        $subtotalToday = 0;
        foreach ($queryToday as $sub) {
            if($sub->type === 'withdraw') {
                $subtotalToday -= $sub->value; 
            } else {
                $subtotalToday += $sub->value;
            }
        }

        $subtotalYesterday = 0;
        foreach ($query as $sub) {
            if($sub->type === 'withdraw') {
                $subtotalYesterday -= $sub->value; 
            } else {
                $subtotalYesterday += $sub->value;
            }
        }
        
        $subtotalTwoDays = 0;
        foreach ($query2 as $sub) {
            if($sub->type === 'withdraw') {
                $subtotalTwoDays -= $sub->value; 
            } else {
                $subtotalTwoDays += $sub->value;
            }
        }

        $subtotalThreeDays = 0;
        foreach ($query3 as $sub) {
            if($sub->type === 'withdraw') {
                $subtotalThree -= $sub->value; 
            } else {
                $subtotalThree += $sub->value;
            }
        }

        $subtotalFourDays = 0;
        foreach ($query4 as $sub) {
            if($sub->type === 'withdraw') {
                $subtotalFour -= $sub->value; 
            } else {
                $subtotalFour += $sub->value;
            }
        }

        $subtotalFiveDays = 0;
        foreach ($query5 as $sub) {
            if($sub->type === 'withdraw') {
                $subtotalFiveDays -= $sub->value; 
            } else {
                $subtotalFiveDays += $sub->value;
            }
        }

        $subtotalSixDays = 0;
        foreach ($query6 as $sub) {
            if($sub->type === 'withdraw') {
                $subtotalSixDays -= $sub->value; 
            } else {
                $subtotalSixDays += $sub->value;
            }
        }

        $data = array('sixDays' => $subtotalSixDays, 'fiveDays' => $subtotalFiveDays, 'fourDays' => $subtotalFourDays, 'threeDays' => $subtotalThreeDays, 'twoDays' => $subtotalTwoDays, 'yesterday' => $subtotalYesterday, 'today' => $subtotalToday);
        return $data;
    }

    public function deleteTransactionHistory($accountNumber, $transactionId) {
        return DB::table('transactions')->where('account_number', $accountNumber)->where('id', $transactionId)->delete();
    }
}
