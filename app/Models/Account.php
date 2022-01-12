<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_number',
        'saldo',
    ];

    public static function getSaldo($account_number) {
        return DB::table('accounts')->where('account_number', '=', $account_number)->first();
    }
}
