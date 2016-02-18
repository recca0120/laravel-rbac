<?php

namespace Recca0120\RBAC;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;

class GateRegistrar
{
    public function __construct(GateContract $gate)
    {
        $this->gate = $gate;
    }

    public function register()
    {
        Node::with('parent')
            ->where('level', '=', 'permission')
            ->get()
            ->each(function ($node) {
                $this->gate->define($node->permission, function ($user) use ($node) {
                    return $user->permissions->contains('permission', $node->permission);
                });
            });
        // $this->gate->before(function ($user) {
        //     if ($user->isSuperAdmin() === true) {
        //         return true;
        //     }
        // });
    }
}
