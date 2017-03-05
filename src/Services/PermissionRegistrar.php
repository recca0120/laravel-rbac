<?php

namespace Recca0120\Rbac\Services;

use Illuminate\Support\Str;
use Recca0120\Rbac\Permission;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Access\Gate;

class PermissionRegistrar
{
    /**
     * $permission.
     *
     * @var \Recca0120\Rbac\Permission
     */
    private $permission;

    /**
     * $gate.
     *
     * @var \Illuminate\Contracts\Auth\Access\Gate
     */
    private $gate;

    /**
     * $guard.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    private $guard;

    /**
     * __construct.
     *
     * @param \Recca0120\Rbac\Permission $permission
     * @param \Illuminate\Contracts\Auth\Access\Gate $gate
     * @param \Illuminate\Contracts\Auth\Guard $guard
     */
    public function __construct(Permission $permission, Gate $gate, Guard $guard)
    {
        $this->permission = $permission;
        $this->gate = $gate;
        $this->guard = $guard;
    }

    /**
     * defineGate.
     */
    public function defineGate()
    {
        $permissions = $this->permission->findAllByPermission();
        if (is_null($permissions) === false) {
            $permissions->each(function ($permission) {
                $this->gate->define($permission->as, function ($user) use ($permission) {
                    return $user->hasRole($permission->roles);
                });
            });
        }
    }

    /**
     * checkPermission.
     *
     * @param string $actionName
     * @return bool
     */
    public function checkPermission($actionName)
    {
        $ability = $this->getAbility($actionName);
        $permission = $this->permission->findOneByAs($ability);
        if (is_null($permission) === true) {
            return true;
        }
        $user = $this->guard->user();

        return $user->hasRole($permission->roles);
    }

    /**
     * getAbility.
     *
     * @param string $actionName
     * @return string
     */
    public function getAbility($actionName)
    {
        list($controller, $method) = explode('@', $actionName);
        $name = str_plural(strtolower(Str::snake(preg_replace('/Controller$/i', '', class_basename($controller)))));
        $resourceAbilityMap = $this->resourceAbilityMap();
        $method = (isset($resourceAbilityMap[$method]) === true) ? $resourceAbilityMap[$method] : $method;

        return sprintf('admin.%s.%s', $name, $method);
    }

    /**
     * Get the map of resource methods to ability names.
     *
     * @return array
     */
    protected function resourceAbilityMap()
    {
        return [
            'index' => 'show',
            'create' => 'create',
            'store' => 'create',
            'show' => 'show',
            'edit' => 'update',
            'update' => 'update',
            'destroy' => 'destroy',
            'view' => 'show',
        ];
        // return [
        //     'index'   => 'view',
        //     'create'  => 'create',
        //     'store'   => 'create',
        //     'show'    => 'view',
        //     'edit'    => 'update',
        //     'update'  => 'update',
        //     'destroy' => 'delete',
        // ];
    }
}
