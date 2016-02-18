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
        if (is_null($app) === false) {
            $gate = $app->make(GateContract::class);
            $allow = $gate->forUser($this)->check($ability, $arguments);
            if ($allow === true || $gate->has($ability) === true) {
                return $allow;
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
