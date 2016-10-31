<?php

namespace Recca0120\Rbac\Services;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Guard;
use Recca0120\Rbac\Permission;
use Illuminate\Support\Str;

class PermissionRegistrar
{
    private $permission;

    private $gate;

    private $guard;

    private $request;

    public function __construct(Permission $permission, Gate $gate, Guard $guard)
    {
        $this->permission = $permission;
        $this->gate = $gate;
        $this->guard = $guard;
    }

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
