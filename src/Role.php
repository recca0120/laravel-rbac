<?php

namespace Recca0120\RBAC;

use Illuminate\Database\Eloquent\Model;
use Recca0120\RBAC\Contracts\Role as RoleContract;
use Recca0120\RBAC\Traits\RoleTrait;
use Recca0120\RBAC\Traits\Slugable;

class Role extends Model implements RoleContract
{
    use Slugable, RoleTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'description'];
}
