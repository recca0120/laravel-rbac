<?php

namespace Recca0120\Rbac\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Recca0120\Rbac\Services\PermissionRegistrar;
use Illuminate\Auth\Access\AuthorizationException;

class PermissionRequired
{
    /**
     * $permissionRegistrar.
     *
     * @var \Recca0120\Rbac\Services\PermissionRegistrar
     */
    private $permissionRegistrar;

    /**
     * $guard.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    private $guard;

    /**
     * __construct.
     *
     * @param \Recca0120\Rbac\Services\PermissionRegistrar $permissionRegistrar
     * @param \Illuminate\Contracts\Auth\Guard $guard
     */
    public function __construct(PermissionRegistrar $permissionRegistrar, Guard $guard)
    {
        $this->permissionRegistrar = $permissionRegistrar;
        $this->guard = $guard;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->isAllowed($request) === false) {
            throw new AuthorizationException('This action is unauthorized.');
        }

        return $next($request);
    }

    /**
     * isAllowed.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function isAllowed($request)
    {
        $user = $this->guard->user();
        $actionName = $request->route()->getActionName();
        $ability = $this->permissionRegistrar->getAbility($actionName);

        return $user->can($ability);
    }
}
