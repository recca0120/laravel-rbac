<?php

namespace Recca0120\Rbac;

use Illuminate\Database\Eloquent\Model;
use Recca0120\Rbac\Traits\PermissionTrait;

class Permission extends Model
{
    use PermissionTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type', 'icon', 'as', 'uses',
    ];

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
