<?php

namespace Recca0120\Rbac;

use Recca0120\Rbac\Traits\RoleTrait;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use RoleTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'sort',
    ];

    /**
     * users.
     *
     * @return \Illuminte\Database\Eloquent\Collection
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * permissions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}
