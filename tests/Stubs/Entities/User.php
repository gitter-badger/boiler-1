<?php

namespace Yakuzan\Boiler\Tests\Stubs\Entities;

use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;

class User extends \Yakuzan\Boiler\Entities\User
{
    use Notifiable;

    protected $connection = 'testbench';

    protected $table = 'users';

    protected $fillable = [ 'name', 'email', 'password' ];

    protected $hidden = [ 'password', 'remember_token' ];

    protected $access_rules = [
        'name'     => 'required|string|max:255',
        'email'    => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
    ];

    public function modify_rules(Request $request): array
    {
        return [
            'name'     => 'required|max:255',
            'email'    => 'required|email|unique:users,email,'.$request->user.'|max:255',
            'password' => 'required|max:255',
        ];
    }
}
