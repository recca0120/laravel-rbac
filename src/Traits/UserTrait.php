<?php

namespace Recca0120\RBAC\Traits;

use Baum\Extensions\Eloquent\Collection;
use Illuminate\Support\Str;
use Recca0120\RBAC\Access\Authorizable;
use Recca0120\RBAC\Morph;
use Recca0120\RBAC\Role;

trait UserTrait
{
    use Authorizable;

    /**
     * initialize morph.
     *
     * @return void
     */
    public static function bootUserTrait()
    {
        Morph::push(static::class);
    }

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->morphToMany(
            Role::class,
            'user',
            'user_roles',
            'user_id',
            'role_id'
        );
    }

    /**
     * user nodes.
     *
     * @return \Baum\Extensions\Eloquent\Collection
     */
    public function getNodes()
    {
        $nodes = new Collection();
        foreach ($this->roles as $role) {
            $nodes = $nodes->merge($role->cachedNodes());
        }
        $nodes = $nodes->unique()->sortBy(function ($node) {
            return $node->getLeft();
        });

        return $nodes;
    }

    /**
     * user nodes attribute.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getNodesAttribute()
    {
        return $this->getNodes();
    }

    /**
     * user permissions.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPermissions()
    {
        return $this->nodes->filter(function ($node) {
            return is_null($node->permission) === false;
        });
    }

    /**
     * user permissions attribute.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPermissionsAttribute()
    {
        return $this->getPermissions();
    }

    /**
     * user has permission.
     *
     * @return bool
     */
    public function hasPermission($permission)
    {
        return $this->permissions->contains('permission', $permission);
    }

    /**
     * attach role.
     *
     * @param \Recca0120\RBAC\Role|array|id $role
     *
     * @return void
     */
    public function attachRole($role)
    {
        if (is_object($role) === true) {
            $role = $role->getKey();
        } elseif (is_array($role) === true) {
            $role = $role['id'];
        }

        $this->roles()->attach($role);
    }

    /**
     * detach role.
     *
     * @param \Recca0120\RBAC\Role|array|id $role
     *
     * @return void
     */
    public function detachRole($role)
    {
        if (is_object($role) === true) {
            $role = $role->getKey();
        } elseif (is_array($role) === true) {
            $role = $role['id'];
        }
        $this->roles()->detach($role);
    }

    /**
     * sync roles.
     *
     * @param  array
     *
     * @return void
     */
    public function syncRoles($roles)
    {
        if (empty($roles) === false) {
            $this->roles()->sync(array_map(function ($role) {
                if (is_object($role) === true) {
                    $role = $role->getKey();
                } elseif (is_array($role) === true) {
                    $role = $role['id'];
                }

                return $role;
            }, $roles));
        } else {
            $this->roles()->detach();
        }
    }

    /**
     * check user role.
     *
     * @param string $role
     *
     * @return bool
     */
    public function is($role)
    {
        $roles = $this->roles;

        return $roles->contains('slug', Str::slug($role));
    }

    /**
     * Handle dynamic method calls into the model.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (Str::startsWith($method, 'is') === true) {
            $role = substr($method, 2);

            return $this->is($role);
        }

        return parent::__call($method, $parameters);
    }
}
