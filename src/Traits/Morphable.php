<?php

namespace Recca0120\RBAC\Traits;

use Recca0120\RBAC\Morph;

trait Morphable
{
    /**
     * The morphed users that belong to the role.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function morphedByUser($key)
    {
        return $this->morphedByMany(
            Morph::get($key),
            'user',
            'user_roles',
            'role_id',
            'user_id'
        );
    }

    /**
     * Get a relationship.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getRelationValue($key)
    {
        if (Morph::get($key) !== null) {
            return $this->getRelationshipFromMethod($key);
        }

        return parent::getRelationValue($key);
    }

    /**
     * add morpph by user.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (Morph::get($method) !== null) {
            return $this->morphedByUser($method);
        }

        return parent::__call($method, $parameters);
    }
}