<?php

namespace Recca0120\Rbac\Traits;

trait RoleTrait
{
    /*
     * bootRoleTrait.
     */
    public static function bootRoleTrait()
    {
        static::saved(function ($model) {
            $model->permissions()->getRelated()->fireModelEvent('saved', false);
        }, 99);
    }
}
