<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_number',
        'type',
        'deposit',
        'withdraw',
        'date',
    ];

    public static function deposit($type, $value, $account_number, $date) {
        return DB::table('transactions')->insert([
            'type' => $type,
            'value' => $value,
            'account_number' => $account_number,
            'date' => $date,
        ]);
    }

    public static function withdraw($type, $value, $account_number, $date) {
        return DB::table('transactions')->insert([
            'type' => $type,
            'value' => $value,
            'account_number' => $account_number,
            'date' => $date,
        ]);
    }

    public static function getAllTransactions($account) {
        return DB::table('transactions')->where('account_number', '=', $account)->get();
    }
}
