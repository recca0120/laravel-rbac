<?php

namespace Recca0120\RBAC;

use Illuminate\Database\Eloquent\Model;
use Recca0120\RBAC\Contracts\User as UserContract;
use Recca0120\RBAC\Traits\UserTrait;

class User extends Model implements UserContract
{
    use UserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username', 'group_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
