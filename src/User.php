<?php

namespace Recca0120\Rbac;

use Illuminate\Database\Eloquent\Model;
use Recca0120\Rbac\Traits\UserTrait;

class User extends Model
{
    use UserTrait;

    /**
     * roles.
     *
     * @method roles
     *
     * @return \Illuminte\Database\Eloquent\Collection
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function providers() {
        return $this->hasMany(UserProvider::class);
    }
}
