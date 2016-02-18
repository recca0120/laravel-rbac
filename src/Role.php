<?php

namespace Recca0120\RBAC;

use Illuminate\Database\Eloquent\Model;
use Recca0120\Rbac\Traits\Slugable;

class Role extends Model implements RoleContract
{
    use Slugable, RoleTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->morphToMany(
            User::class,
            'user',
            'user_roles',
            'role_id',
            'user_id'
        );
    }

    /**
     * The nodes that belongs to role.
     */
    public function nodes()
    {
        return $this->belongsToMany(
            Node::class,
            'role_nodes',
            'role_id',
            'node_id'
        );
    }
}
