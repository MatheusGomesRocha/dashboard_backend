<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'avatar',
        'account',
        'cpf',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function hasUser($cpf) {
        return DB::table('users')->where('cpf', '=', $cpf)->count();
    }

    public static function getUser($cpf) {
        return DB::table('users')->where('cpf', '=', $cpf)->first();
    }

    public static function createUser($data) {
        return DB::table('users')->insert([
            'name' => $data.name,
            'avatar' => '',
            'cpf' => $data.cpf,
            'account' => $data.account,
            'email' => $data.email,
            'password' => $data.password,
        ]);
    }

}
