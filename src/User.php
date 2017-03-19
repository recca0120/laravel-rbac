<?php

namespace Recca0120\Rbac;

use Recca0120\Rbac\Traits\UserTrait;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use UserTrait;

    /**
     * roles.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
