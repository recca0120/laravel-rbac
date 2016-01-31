<?php

namespace Recca0120\Rbac;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;

class GateRegister
{
    public function __construct(GateContract $gate)
    {
        $this->gate = $gate;
    }

    public function sync()
    {
        $this->gate->before(function ($user) {
            if ($user->isSuperAdmin() === true) {
                return true;
            }
        });
        Node::with('parent')
            ->where('level', '=', 3)
            ->get()
            ->each(function ($node) {
                $ability = $node->ability;
                $this->gate->define($ability, function ($user) use ($ability) {
                    return $user->abilities->contains('ability', $ability);
                });
            });
    }
}
