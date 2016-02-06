<?php

namespace Recca0120\RBAC\Access;

use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Recca0120\RBAC\Node;

trait Authorizable
{
    /**
     * Determine if the entity has a given ability.
     *
     * @param string      $ability
     * @param array|mixed $arguments
     *
     * @return bool
     */
    public function can($ability, $arguments = [])
    {
        $app = Container::getInstance();
        if ($app !== null) {
            $gate = $app->make(GateContract::class);
            if ($gate->has($ability) === true) {
                return $gate->forUser($this)->check($ability, $arguments);
            }
        }

        $permissions = Node::cachedPermissionNodes();

        if ($permissions->contains('permission', $ability) === true) {
            return $this->hasPermission($ability);
        }

        return true;
    }

    /**
     * Determine if the entity does not have a given ability.
     *
     * @param string      $ability
     * @param array|mixed $arguments
     *
     * @return bool
     */
    public function cant($ability, $arguments = [])
    {
        return !$this->can($ability, $arguments);
    }

    /**
     * Determine if the entity does not have a given ability.
     *
     * @param string      $ability
     * @param array|mixed $arguments
     *
     * @return bool
     */
    public function cannot($ability, $arguments = [])
    {
        return $this->cant($ability, $arguments);
    }
}
