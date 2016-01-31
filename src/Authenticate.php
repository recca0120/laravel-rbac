<?php

namespace Recca0120\Rbac;

use Illuminate\Support\Str;
use Recca0120\Rbac\Traits\Cachable;

trait Authenticate
{
    use Cachable;

    /**
     * get nodes.
     *
     * @return mixed
     */
    public function getNodes()
    {
        $nodes = Node::with('parent');
        if ($this->isSuperAdmin() === false) {
            $nodes = $nodes
                ->whereHas('roles', function ($query) {
                    return $query->whereHas('users', function ($query) {
                        return $query->where('id', '=', $this->id);
                    });
                });
        }

        return $nodes->get();
    }

    /**
     * get nodes attributes.
     *
     * @return mixed
     */
    public function getNodesAttribute()
    {
        return $this->cached('nodes', function () {
            return $this->getNodes();
        });
    }

    /**
     * get abilities.
     *
     * @return array
     */
    public function getAbility()
    {
        return $this->getNodesAttribute()
            ->filter(function ($node) {
                return $node->ability !== null;
                // return (int) $node->level === 3;
            })
            ->map(function ($node) {
                return [
                    'ability' => $node->ability,
                ];
            });
    }

    /**
     * get abilities attributes.
     *
     * @return mixed
     */
    public function getAbilitiesAttribute()
    {
        return $this->cached('abilities', function () {
            return $this->getAbility();
        });
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
     * check user role.
     *
     * @param string $role
     *
     * @return bool
     */
    public function is($role)
    {
        $userRoles = $this->cached(__FUNCTION__, function () {
            return $this->roles->pluck('slug');
        }, 'static');

        return $userRoles->contains(Str::slug($role));
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
        if (starts_with($method, 'is') === true) {
            $role = substr($method, 2);

            return $this->is($role);
        }

        return parent::__call($method, $parameters);
    }
}
