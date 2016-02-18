<?php

namespace Recca0120\RBAC\Traits;

use Recca0120\RBAC\Role;

trait NodeTrait
{
    use BaumExtend;

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_nodes',
            'node_id',
            'role_id'
        );
    }

    /**
     * get permission.
     *
     * @return string
     */
    public function getPermission()
    {
        if ((int) $this->level !== 3) {
            return;
        }

        if ($this->parent === null || (int) $this->parent->level !== 2) {
            return $this->slug;
        }

        return implode('-', [$this->parent->slug, $this->slug]);
    }

    /**
     * permission attribute.
     *
     * @return string
     */
    public function getPermissionAttribute()
    {
        return $this->getPermission();
    }
}
