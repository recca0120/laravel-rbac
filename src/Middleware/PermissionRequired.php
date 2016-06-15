<?php

namespace Recca0120\Rbac\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Recca0120\Rbac\Services\PermissionRegistrar;

class PermissionRequired
{
    private $permissionRegistrar;

    public function __construct(PermissionRegistrar $permissionRegistrar)
    {
        $this->permissionRegistrar = $permissionRegistrar;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->permissionRegistrar->checkPermission($request->route()->getActionName()) === false) {
            throw new AuthorizationException('This action is unauthorized.');
        }

        return $next($request);
    }
}
